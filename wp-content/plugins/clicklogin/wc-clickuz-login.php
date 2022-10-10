<?php
/*
 * Plugin Name: CLICK Login
 * Plugin URI: https://click.uz
 * Description: Login using CLICK partner API
 * Version: 1.0.0
 * Author: OOO "Click"
 * Author URI: https://click.uz

 * Text Domain: clickuz
 * Domain Path: /i18n/languages/

 */

if (!defined('ABSPATH')) {
    exit;
}
define('CLICK_LOGIN_VERSION', '1.0.0');
define('CLICK_LOGIN_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
use Inc\Activate;
use Inc\Deactivate;
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    class WC_ClickuzLogin
    {
        public $plugin;
        private $table;
        public function __construct()
        {
            global $wpdb;
		    $this->table = $wpdb->prefix . 'wc_click_login';
            $this->plugin = plugin_basename(__FILE__);
            load_plugin_textdomain('clickuz_login', false, dirname(plugin_basename(__FILE__)) . '/i18n/languages/');
            register_activation_hook( __FILE__, array( $this, 'activate' ) );
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
            add_action('wp_ajax_check_phone', array($this, 'check_phone'));
            add_action('wp_ajax_nopriv_check_phone', array($this, 'check_phone'));
            add_action('wp_ajax_check_reset_phone', array($this, 'check_reset_phone'));
            add_action('wp_ajax_nopriv_check_reset_phone', array($this, 'check_reset_phone'));
            add_action('wp_ajax_check_otp', array($this, 'check_otp'));
            add_action('wp_ajax_nopriv_check_otp', array($this, 'check_otp'));
            add_action('wp_ajax_nopriv_click_login_auth', array($this, 'authorize'));
            add_action('wp_ajax_nopriv_click_login_register', array($this, 'register'));
            add_action('wp_ajax_nopriv_click_login_reset', array($this, 'reset'));
			add_action( 'woocommerce_order_status_clickbox-send', array($this, 'send_sms'), 20, 2 );  
            add_action( 'woocommerce_order_status_dpd-send', array($this, 'send_sms'), 20, 2 );  
            add_action( 'woocommerce_order_status_bringo-send', array($this, 'send_sms'), 20, 2 );  
        }

        public function activate() {
            if ( ! function_exists( 'curl_exec' ) ) {
                wp_die( '<pre>This plugin requires PHP CURL library installled in order to be activated </pre>' );
            }

            if ( ! function_exists( 'openssl_verify' ) ) {
                wp_die( '<pre>This plugin requires PHP OpenSSL library installled in order to be activated </pre>' );
            }
            $this->install();
            flush_rewrite_rules();
        }

        public function deactivate() {
            flush_rewrite_rules();
        }

        public function install() {
            global $wpdb;

            $wpdb->hide_errors();

            $collate = '';

            if ( $wpdb->has_cap( 'collation' ) ) {
                if ( ! empty( $wpdb->charset ) ) {
                    $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                }
                if ( ! empty( $wpdb->collate ) ) {
                    $collate .= " COLLATE $wpdb->collate";
                }
            }

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( "
                CREATE TABLE `{$wpdb->prefix}wc_click_login` (
                    `ID` BIGINT(20)	UNSIGNED NOT NULL AUTO_INCREMENT,
                    `phone_number` NVARCHAR(120),
                    `code` INT(120),
                    `status` INT(120),
                    `timeout` INT(120),
                    `type` INT(120),
                    PRIMARY KEY (`ID`)
                ) $collate; " );
        }

        public function check_phone(){
            global $wpdb;
            $phone = $_POST['params']['phone_number'];
			$lang = $_POST['params']['lang'];
            $user_id = $wpdb->get_var("Select ID From {$wpdb->users} Where user_login='{$phone}'");
            if (!$user_id) {
                $new_code = rand(1000, 9999);
                $new_timeout = time() + 60;
                $wpdb->insert($this->table, array(
                    'phone_number'  => $phone,
                    'code'          => $new_code,
                    'status'        => 1,
                    'timeout'       => $new_timeout,
                    'type'          => 1
                ));
                $ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.infozone.uz/send-message/');
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				$date = date('Y-m-d H:i:s');
				$md5 = hash('sha256', '0x444EBBC511F68CCD9A59AD55' . '810738460' . $date);
				$headers = array(
					'Accept: application/json',
					'Content-Type: application/json',
					'Auth: 810738460:'.$md5.':'.time(),
				);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                if ($lang == 'uz') {
                    $message = "market.click.uz saytiga kirish uchun kodingiz: {$new_code}. Ushbu kodni hech kimga bermang. Firibgarlardan ehtiyot bo'ling.";
                } else {
                    $message = "Vash kod dlya dostupa na sayt market.click.uz: {$new_code}. Ne peredavayte etot kod nikomu. Osteregaytes moshennikov.";
                }
				$post = [
					'msisdn' => $phone,
					'message' => $message,
					'message_id' => rand(100000, 9999999),
					'original_datetime' => $date
				];
				$post = json_encode($post);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				$response = curl_exec($ch);
                $responseShow = json_decode($response, true);
				curl_close($ch);
                $data = array(
                    'status' => 'not-registered',
                    'result' => $responseShow,
                    'key'    => $new_timeout,
					'lang'	 => $lang
                );
            } else {
                $data['status'] = 'registered';
            }
            echo json_encode($data);
            wp_die();
        }

        public function check_otp(){
            global $wpdb;
            $sms_code = $_POST['params']['sms_code'];
            $sms_key = $_POST['params']['key'];
            $phone = $_POST['params']['phone'];
            $code = $wpdb->get_var( 
                $wpdb->prepare( 
                    "
                        SELECT code 
                        FROM {$this->table}
                        WHERE timeout = %s AND phone_number = %s
                    ", 
                    $sms_key, $phone
                )
            );
            if ( $sms_code == $code ) {
                $currentTime = time();
                if($currentTime < $sms_key){
                    $response = array(
                        'result' => 'success'
                    );
                    $wpdb->update( $this->table, array(
                        'status' => 2
                    ), array('timeout' => $sms_key, 'phone_number' => $phone ) );
                } else {
                    $response = array(
                        'error' => __( 'The waiting time (60 seconds) has expired', 'clickuz_login' ),
                        'sms'   => $sms_code,
                        'code'  => $code
                    );
                    $wpdb->update( $this->table, array(
                        'status' => 3
                    ), array('timeout' => $sms_key, 'phone_number' => $phone  ) );
                }
            } else {
                $response = array(
                    'error' => sprintf(
                            __( '%s The password you entered is incorrect.', 'clickuz_login' ), '<strong>' . $phone . '</strong>'
                        ) .
                        ' <a href="' . wp_lostpassword_url() . '">' .
                        __( 'Lost your password?', 'clickuz_login' ) .
                        '</a>',
                    'sms'   => $sms_code,
                    'code'  => $code
                );
                $wpdb->update( $this->table, array(
                    'status' => 4
                ), array('timeout' => $sms_key, 'phone_number' => $phone  ) );
            }
            echo json_encode($response);
            wp_die();
        }

        public function check_reset_phone(){
            global $wpdb;
            $phone = $_POST['params']['phone_number'];
			$lang = $_POST['params']['lang'];
            $user_id = $wpdb->get_var("Select ID From {$wpdb->users} Where user_login='{$phone}'");
            if (!$user_id) {
                $data['status'] = 'not-registered';  
            } else {
                $new_code = rand(1000, 9999);
                $new_timeout = time() + 60;
                $wpdb->insert($this->table, array(
                    'phone_number'  => $phone,
                    'code'          => $new_code,
                    'status'        => 11,
                    'timeout'       => $new_timeout,
                    'type'          => 2
                ));
                $ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://api.infozone.uz/send-message/');
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				$date = date('Y-m-d H:i:s');
				$md5 = hash('sha256', '0x444EBBC511F68CCD9A59AD55' . '810738460' . $date);
				$headers = array(
					'Accept: application/json',
					'Content-Type: application/json',
					'Auth: 810738460:'.$md5.':'.time(),
				);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                if ($lang == 'uz') {
                    $message = "Siz market.click.uz ga kirish uchun takroran kod so'radingiz. Kodingiz: {$new_code}";
                } else {
                    $message = "Vy povtorno zaprosili kod dlya dostupa na sayt market.click.uz. Vash kod: {$new_code}";
                }
				$post = [
					'msisdn' => $phone,
					'message' => $message,
					'message_id' => rand(100000, 9999999),
					'original_datetime' => $date
				];
				$post = json_encode($post);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				$response = curl_exec($ch);
                $response = json_decode($response, true);
				curl_close($ch);
                $data = array(
                    'status' => 'registered',
                    'result' => $response,
                    'key'    => $new_timeout
                );
            }
            echo json_encode($data);
            wp_die();
        }

        public function authorize() {
            $creds = array(
                'user_login'    => trim( wp_unslash( $_POST['params']['phone_number'] ) ),
                'user_password' => $_POST['params']['password'],
            );
            $user = wp_signon( $creds, is_ssl() );
            if ( is_wp_error( $user ) ) {
                $response = array('error' => array(
                    'code' => $user->get_error_code(),
                    'message' => $user->get_error_message()
                ));
                if($user->get_error_code() == 'incorrect_password') {
                    $response['message'] =
                        sprintf(
                            __( '%s The password you entered is incorrect.', 'clickuz_login' ), '<strong>' . $creds['user_login'] . '</strong>'
                        ) .
                        ' <a href="' . wp_lostpassword_url() . '">' .
                        __( 'Lost your password?', 'clickuz_login' ) .
                        '</a>';
                }
            } else {
                $response = array('user_id' => $user->ID);
            }
            echo json_encode($response);
            wp_die();
        }

        public function register() {
            $user_data = array(
                'user_login' => $_POST['params']['phone_number'],
                'user_pass' => $_POST['params']['password'],
                'display_name' => $_POST['params']['display_name']
            );
            $ID = wp_insert_user( $user_data);
            
            if( is_wp_error($ID) ) {
                $response = array('error' => array(
                    'code' => $ID->get_error_code(),
                    'message' => $ID->get_error_message()
                ));
            } else {
                wc_set_customer_auth_cookie( $ID );
                $response = array('user_id' => $ID);
            }
            echo json_encode($response);
            wp_die();
        }

        public function reset() {
            global $wpdb;
            $phone = $_POST['params']['phone_number'];
            $user_id = $wpdb->get_var("Select ID From {$wpdb->users} Where user_login='{$phone}'");
            $password = trim( wp_unslash( $_POST['params']['password'] ) );;
            $setPassword = wp_set_password( $password, $user_id );
            if( is_wp_error($setPassword) ) {
                $response = array('error' => array(
                    'code' => $setPassword->get_error_code(),
                    'message' => $setPassword->get_error_message()
                ));
            } else {
                wc_set_customer_auth_cookie( $user_id );
                $response = array('user_id' => $user_id);
            }
            echo json_encode($response);
            wp_die();
        }
		
		public function send_sms($order_id, $order){
            global $wpdb;
            $ordermeta = new WC_Order( $order_id );
            $order_data = $ordermeta->get_data();
            $get_phone = $order_data['billing']['phone'];
            $user_phone = preg_replace("/[^,.0-9]/", '', $get_phone);
            $wpdb->insert($this->table, array(
                'phone_number'  => $user_phone,
                'status'        => 20,
                'type'          => 3
            ));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.infozone.uz/send-message/');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $date = date('Y-m-d H:i:s');
            $md5 = hash('sha256', '0x444EBBC511F68CCD9A59AD55' . '810738460' . $date);
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Auth: 810738460:'.$md5.':'.time(),
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $message = "Ваша посылка в пути!

Sizning jo'natmangiz yo'lda!";
            $post = [
                'msisdn' => $user_phone,
                'message' => $message,
                'message_id' => rand(100000, 9999999),
                'original_datetime' => $date
            ];
            $post = json_encode($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            $response = json_decode($response, true);
            curl_close($ch);
        }
    }
    new WC_ClickuzLogin();
}