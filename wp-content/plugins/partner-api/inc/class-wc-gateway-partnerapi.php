<?php
/**
 * Partner API Payment Gateway.
 *
 * Provides a Partner API Payment Gateway.
 *
 * @class          WC_Gateway_PartnerAPI
 * @extends        WC_Payment_Gateway
 * @version        1.0.0
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}

define( 'UZCARD_LOGO', plugin_dir_url( __FILE__ ) . 'uzcard_logo.png' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use GuzzleHttp\Client;

class WC_Gateway_PartnerAPI extends WC_Payment_Gateway{

    private $table;
    protected $client; 
    protected $url = 'https://api.click.uz/partner'; 
    protected $partnerID = ''; 
    protected $partnerKey = ''; 
    protected $data = [];

    public function __construct(){
        global $wpdb;
		$this->table = $wpdb->prefix . 'wc_partnerapi_cards';
        $this->id = 'partnerapi';
        $this->has_fields = true;
        $this->order_button_text = __('Pay', 'partnerapi');
        $this->method_title = 'Partner API';
        $this->method_description = 'Оплата через Uzcard либо Humo';
        $this->supports = array('products');
        $this->plugin = plugin_basename( __FILE__ );
        $this->init_form_fields();
        $this->title = __('Bank card', 'partnerapi');
        $this->description = 'Оплата через Uzcard либо Humo';
        $this->client = new Client(); 
        $this->partnerID = '13065336'; 
        $this->partnerKey = '8f53cf29606d97125b7aa90bd51ed9aa66df0aa1218e3cd4b85d9aac4ab95092'; 
        $this->data = [ 
            "jsonrpc" => "2.0", 
            "method" => "", 
            "params" => [], 
            "id" => "", 
        ];  
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
        add_action('wp_ajax_token_request_create', array($this, 'token_request_create'));
        add_action('wp_ajax_nopriv_token_request_create', array($this, 'token_request_create'));
        add_action('wp_ajax_token_request_verify', array($this, 'token_request_verify'));
        add_action('wp_ajax_nopriv_token_request_verify', array($this, 'token_request_verify'));
        add_action('wp_ajax_use_other_card', array($this, 'use_other_card'));
        add_action('wp_ajax_nopriv_use_other_card', array($this, 'use_other_card'));
        add_action('wp_ajax_token_request_auth_create', array($this, 'token_request_auth_create'));
        add_action('wp_ajax_nopriv_token_request_auth_create', array($this, 'token_request_auth_create'));
        add_action('wp_ajax_token_request_auth_verify', array($this, 'token_request_auth_verify'));
        add_action('wp_ajax_nopriv_token_request_auth_verify', array($this, 'token_request_auth_verify'));
        add_action('wp_ajax_papi_auth_use', array($this, 'papi_auth_use'));
        add_action('wp_ajax_nopriv_papi_auth_use', array($this, 'papi_auth_use'));
		add_action('wp_ajax_token_request_delete_cabinet', array($this, 'token_request_delete_cabinet'));
        add_action('wp_ajax_nopriv_token_request_delete_cabinet', array($this, 'token_request_delete_cabinet'));
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'papi_save_fields' )  );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'form' ) );
    }

    public function get_icon(){
        $icon_html = '<img src="' . UZCARD_LOGO . '" alt="CLICK" />';
        return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
    }

    public function payment_scripts() { 
        if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
            return;
        }
        wp_register_script( 'woocommerce_tingle', WC_PartnerAPI_PLUGIN_URL . 'assets/tingle.js', array() );
		wp_register_script( 'woocommerce_partnerapi', WC_PartnerAPI_PLUGIN_URL . 'assets/partnerapi.js?ver1', array( 'jquery' ) );
        wp_enqueue_script( 'woocommerce_tingle' );
        wp_enqueue_script( 'woocommerce_partnerapi' );
    }

    public function payment_fields() {
		global $woocommerce;
        $session_step = WC()->session->get( 'step' );
        $session_token = WC()->session->get( 'token_id' );
        $session_card = WC()->session->get( 'card_number' ); 
		$ordID = $woocommerce->session->order_awaiting_payment;
		echo '<input type="hidden" id="merchant_user_id" value="' . $ordID . '">';
        if ( !is_user_logged_in()) {
            if(!$session_step){
                $session_step = 'papi-create';
            }
            woocommerce_form_field( 'karta_id', array(        
                'type' => 'hidden',        
                'class' => array( 'karta_id' ),        
                'required' => true,        
            ), $session_token );
            echo '<div class="papi" data-step="' . $session_step .'" data-login="papi-noauth" data-token="'. $session_token .'" data-cardnumber="'. $session_card .'" data-cards=""><div class="papi-one create"><div class="papi-title"><h4>' . __('Pay card', 'partnerapi') . '</h4><p>' . __('Pay card descr', 'partnerapi') . '</p></div><div class="papi-one-form"><div class="papi-one-form-top"><div class="papi-one-form-top-cc"><input id="partnerapi_ccNo" type="tel" placeholder="0000 0000 0000 0000"></div><div class="papi-one-form-top-ex"><input id="partnerapi_expdate" type="tel" placeholder="00/00"></div></div><div class="papi-one-form-center"><div class="papi-one-form-center-phone"><input type="tel" id="partnerapi_phone" class="partner-phone" placeholder="'. __('Phone number card', 'partnerapi') .'"></div><div class="papi-one-form-center-button"><button id="papi-noauth-givecode" type="button">'. __('Continue', 'partnerapi') .'</button></div></div></div></div><div class="papi-two verify"><div class="papi-title"><h4>' . __('Confirm title', 'partnerapi') . '</h4><p>' . __('Confirm descr', 'partnerapi') . '</p></div><div class="papi-two-form"><div class="papi-two-form-phone"><input type="text" id="partnerapi_code" class="partner-code" placeholder="' . __('Code sms', 'partnerapi') . '"></div><div class="papi-two-form-button"><button id="papi-noauth-confirmcode" type="button">' . __('Confirm', 'partnerapi') . '</button></div></div></div><div class="papi-three confirm"><div class="papi-three-icon"><img src="/wp-content/themes/kalles/assets/images/good.svg" alt=""></div><div class="papi-three-text"><p><span id="papi-three-card">'. $session_card .'</span>' . __('connect', 'partnerapi') . '</p></div><div class="papi-three-btn"><button type="button" id="papi-noauth-otheradd">' . __('Use other', 'partnerapi') . '</button></div></div></div>';
            ?>
        <?php } else {
            global $wpdb;
            global $current_user;
            $cardsArray = [];
            $user_id = $current_user->ID;
            $cards = $wpdb->get_results( "SELECT `token_id`, `card_num` FROM {$this->table} WHERE `user_id` = {$user_id} AND `status` = 'save'", ARRAY_A);
            $session_save = WC()->session->get( 'card_save' ); 
            if($cards){
				$session_step = 'papi-saveds';
            } else {
                if(!$session_step){
                    $session_step = 'papi-create';
                } else if(!$session_card){
					$session_step = 'papi-create';
				}
            }
            echo '<div class="papi" data-step="' . $session_step .'" data-login="papi-auth" data-token="'. $session_token .'" data-cardnumber="'. $session_card .'" data-cards="" data-usersaved="'. $session_save .'">';
			if($cards){
                foreach($cards as $card){  
                    $cardsArray[$card['token_id']] = $card['card_num'];
                }
                woocommerce_form_field( 'karta_id', array(
                    'type'        => 'select',
                    'class'       => array('karta_id saveds'),
                    'options'     => $cardsArray,
                    'required'    => false
                ), '');
            } else {
                woocommerce_form_field( 'karta_id', array(        
                    'type' => 'hidden',        
                    'class' => array( 'karta_id' ),        
                    'required' => true,        
                ), $session_token);
            }
            echo '<div class="papi-one create"><div class="papi-title"><h4>' . __('Pay card', 'partnerapi') . '</h4><p>' . __('Pay card descr', 'partnerapi') . '</p></div><div class="papi-one-form"><div class="papi-one-form-top"><div class="papi-one-form-top-cc"><input id="partnerapi_ccNo" type="tel" placeholder="0000 0000 0000 0000"></div><div class="papi-one-form-top-ex"><input id="partnerapi_expdate" type="tel" placeholder="00/00"></div></div><div class="papi-one-form-center"><div class="papi-one-form-center-phone"><input type="tel" id="partnerapi_phone" class="partner-phone" placeholder="'. __('Phone number card', 'partnerapi') .'"></div><div class="papi-one-form-center-button"><button id="papi-auth-givecode" type="button">'. __('Continue', 'partnerapi') .'</button></div></div></div></div><div class="papi-two verify"><div class="papi-title"><h4>' . __('Confirm title', 'partnerapi') . '</h4><p>' . __('Confirm descr', 'partnerapi') . '</p></div><div class="papi-two-form"><div class="papi-two-form-phone"><input type="text" id="partnerapi_code" class="partner-code" placeholder="' . __('Code sms', 'partnerapi') . '"></div><div class="papi-two-form-button"><button id="papi-auth-confirmcode" type="button">'. __('Confirm', 'partnerapi') .'</button></div></div><div class="papi-two-saved"><input type="checkbox" id="papi-form-saved">'. __('Save card', 'partnerapi') .'</label></div></div><div class="papi-three confirm"><div class="papi-three-icon"><img src="/wp-content/themes/kalles/assets/images/good.svg" alt=""></div><div class="papi-three-text"><p><span id="papi-three-card">'. $session_card .'</span>'. __('connect', 'partnerapi') .'</p></div><div class="papi-three-btn"><button type="button" id="papi-auth-use">'. __('Use saves', 'partnerapi') .'</button></div></div><div class="papi-three saveds"><div class="papi-three-btn"><button type="button" id="papi-auth-newadd">'. __('Add new', 'partnerapi') .'</button></div></div></div>';
        } ?>
            <link rel="stylesheet" href="<?php echo get_home_url(); ?>/wp-content/plugins/partner-api/assets/partnerapi.css">
            <script>
                jQuery('#partnerapi_ccNo').inputmask('9999 9999 9999 9999');
                jQuery('#partnerapi_expdate').inputmask('99/99');
                jQuery('#partnerapi_phone').inputmask('\\9\\9\\8 (99) 999-99-99');
                jQuery('#partnerapi_code').inputmask('99999');
            </script>
    <?php }

    public function papi_save_fields( $order_id ) { 
        if ( $_POST['karta_id'] ) update_post_meta( $order_id, '_karta_id', esc_attr( $_POST['karta_id'] ) );
        if ( $_POST['merchant_order_id'] ) update_post_meta( $order_id, '_merchant_order_id', esc_attr( $order_id ) );
    }

    public function validate_fields(){
        if( empty( $_POST[ 'karta_id' ]) ) {
            wc_add_notice( __('Add card please', 'partnerapi'), 'error' );
            return false;
        }
        return true;
    }

    protected function authHash() { 
        return $this->partnerID . '; ' . sha1(time() . $this->partnerKey) . '; ' . time(); 
    } 

    protected function makeRequest() { 
        return $this->client->post($this->url, [ 
            'headers' => [ 
                'Service' => $this->authHash(), 
            ], 
            'json' => $this->data, 
        ]); 
    } 

    protected function setParam($key, $value) { 
         $this->data['params'][$key] = $value; 
    } 

    protected function request() { 
        try {
            $response = $this->makeRequest(); 
            if($response->getStatusCode() == 200) { 
                return json_decode($response->getBody(), true); 
            } else {
                return [ 
                    "error" => [ 
                        "code" => json_decode($response->getStatusCode()), 
                        "message" => json_decode($response->getBody(), true), 
                    ], 
                    "jsonrpc" => "2.0", 
                    "id" => "500", 
                ];
             } 
        } catch (\Exception $exception) { 
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return [ 
                "error" => [ 
                    "code" => json_decode($response->getStatusCode()), 
                    "message" => json_decode($response->getBody(), true),
                ], 
                "jsonrpc" => "2.0", 
                "id" => "500", 
            ]; 
        } 
    }

    protected function hashCardNumber($cardNumber) { 
        $pk  = openssl_get_publickey(file_get_contents(__DIR__ . '/public.pem')); 
        openssl_public_encrypt($cardNumber, $encrypted, $pk); 
        return base64_encode($encrypted); 
    }

    public function tokenCreateRequest($cardNumber, $expireDate, $phoneNumber, $newToken) { 
        $this->data['method'] = "token.create"; 
        $this->data['id'] = 123;
        $this->setParam('card_number', $this->hashCardNumber($cardNumber)); 
        $this->setParam('expire_date', $expireDate); 
        $this->setParam('phone_number', $phoneNumber); 
        $this->setParam('new_token', $newToken); 
        return $this->request();
    }

    public function tokenVerifyRequest($tokenID, $verificationCode) { 
        $this->data['method'] = "token.verify"; 
        $this->data['id'] = 124; 
        $this->setParam('token_id', $tokenID); 
        $this->setParam('verification_code', $verificationCode); 
        return $this->request(); 
    } 

    public function tokenPayment($cardToken, $serviceId, $parameters, $externalid){ 
        $this->data['method'] = "payment.token"; 
        $this->data['id'] = 130; 
        $this->setParam('card_token', $cardToken); 
        $this->setParam('service_id', $serviceId);
        $this->setParam('parameters', $parameters);
        $this->setParam('external_id', $externalid);
        return $this->request(); 
    }
	
	public function tokenDeleteRequest($card_token) { 
        $this->data['method'] = "token.remove"; 
        $this->data['id'] = 125; 
        $this->setParam('card_token', $card_token); 
        return $this->request(); 
    } 

    public function token_request_create(){
        global $wpdb;
        $cardNumber = $_POST['params']['cardNumber'];
        $expireDate = $_POST['params']['expireDate'];
        $phoneNumber = $_POST['params']['phoneNumber'];
        $response = $this->tokenCreateRequest($cardNumber, $expireDate, $phoneNumber, false);
        if($response["result"]){
            $card_token = $response["result"]["token"];
            if (array_key_exists('card_number', $response["result"])) {
                $responseDouble = $this->tokenCreateRequest($cardNumber, $expireDate, $phoneNumber, true);
                if($responseDouble["result"]){
                    $phone_number = $responseDouble["result"]["phone_number"];
                    $token_id = $responseDouble["result"]["token_id"];
                    $save_cards_ids = $wpdb->get_results( 
                        $wpdb->prepare( 
                            "
                                SELECT token_id 
                                FROM {$this->table}
                                WHERE card_token = %s
                            ", 
                            $card_token
                        )
                    );
                    $wpdb->insert($this->table, array(
                        'token_id'      => $token_id,
                        'phone_number'  => $phone_number,
                        'type_order'    => '01',
                        'status'        => 'not-save'
                    ));
                    if(!$save_cards_ids){
                        $save_cards_ids = '';
                    }
                    $responseSecond = [ 
                        "result" => [ 
                            "token_id"      => $token_id, 
                            "phone_number"  => $phone_number,
                            'card_token_id' => $save_cards_ids
                        ]
                    ];
                    WC()->session->set( 'token_id' , $token_id );
                    WC()->session->set( 'step' , 'papi-verify' );
                    echo json_encode($responseSecond);
                } else {
                    echo json_encode($response);
                    WC()->session->set( 'step' , 'papi-create' );
                    WC()->session->set( 'token_id' , '' );
                    WC()->session->set( 'card_number' , '' );
                }
            } else {
                $phone_number = $response["result"]["phone_number"];
                $token_id = $response["result"]["token_id"];
                $wpdb->insert($this->table, array(
                    'token_id'      => $token_id,
                    'phone_number'  => $phone_number,
                    'type_order'    => '02',
                    'status'        => 'not-save'
                ));
                $responseSecond = [ 
                    "result" => [ 
                        "token_id"      => $token_id, 
                        "phone_number"  => $phone_number,
                    ]
                ];
                WC()->session->set( 'token_id' , $token_id );
                WC()->session->set( 'step' , 'papi-verify' );
                echo json_encode($responseSecond);
            }
        } else {
            echo json_encode($response);
            WC()->session->set( 'step' , 'papi-create' );
            WC()->session->set( 'token_id' , '' );
            WC()->session->set( 'card_number' , '' );
            WC()->session->set( 'card_save' , '' );
        }
        wp_die();
    }

    public function token_request_verify(){
        global $wpdb;
        $token_id = $_POST['params']['tokenId'];
        $verify_code = $_POST['params']['verifyCode'];
        $cards = ($_POST['params']['cards']);
        $response = $this->tokenVerifyRequest($token_id, $verify_code);
        if($response["result"]){
            $card_token = $response["result"]["token"];
            $card_number = $response["result"]["card_number"];
            $card_type = $response["result"]["card_type"];
            $wpdb->update( $this->table, array(
                'card_token'    => $card_token,
                'card_num'      => $card_number,
                'card_type'     => $card_type,
                'type_order'    => '03'
            ), array('token_id' => $token_id ) );
            if($cards){
                $cardsList = explode(",", $cards);
                foreach($cardsList as $key => $value){
                    $wpdb->update( $this->table, array(
                        'card_token'    => $card_token
                    ), array('token_id' => $value ) );
                }
            }
            $responseSecond = [
                "result" => [ 
                    "token_id" => $token_id, 
                    "card_number" => $card_number
                ]
            ];
            WC()->session->set( 'token_id' , $token_id );
            WC()->session->set( 'card_number' , $card_number );
            WC()->session->set( 'step' , 'papi-confirm' );
            echo json_encode($responseSecond);
        } else {
            echo json_encode($response);
            WC()->session->set( 'step' , 'papi-create' );
            WC()->session->set( 'token_id' , '' );
            WC()->session->set( 'card_number' , '' );
            WC()->session->set( 'card_save' , '' );
        }
        wp_die();
    }

    public function use_other_card(){
        $response = [ 
            "result" => [ 
                "status" => 'success', 
            ]
        ];
        echo json_encode($response);
        WC()->session->set( 'step' , 'papi-create' );
        WC()->session->set( 'token_id' , '' );
        WC()->session->set( 'card_number' , '' );
        WC()->session->set( 'card_save' , '' );
        wp_die();
    }

    public function token_request_auth_create(){
        global $wpdb;
        global $current_user;
        $get_user_id = $current_user->ID;
        $user_login = $current_user->user_login;
        $cardNumber = $_POST['params']['cardNumber'];
        $expireDate = $_POST['params']['expireDate'];
        $phoneNumber = $_POST['params']['phoneNumber'];
        $ifsave = 'not';
        if($user_login == $phoneNumber){
            $ifsave = 'yes';
        }
        WC()->session->set( 'card_save' , $ifsave );
        $response = $this->tokenCreateRequest($cardNumber, $expireDate, $phoneNumber, false);
        if($response["result"]){
            if (array_key_exists('card_number', $response["result"])) {
                $card_token = $response["result"]["token"];
                $responseDouble = $this->tokenCreateRequest($cardNumber, $expireDate, $phoneNumber, true);
                if($responseDouble["result"]){
                    $phone_number = $responseDouble["result"]["phone_number"];
                    $token_id = $responseDouble["result"]["token_id"];
                    $save_cards_ids = $wpdb->get_results( 
                        $wpdb->prepare( 
                            "
                                SELECT token_id 
                                FROM {$this->table}
                                WHERE card_token = %s
                            ", 
                            $card_token
                        )
                    );
					$check_save = $wpdb->get_results( 
                        $wpdb->prepare( 
                            "
                                SELECT token_id 
                                FROM {$this->table}
                                WHERE card_token = %s AND status = %s
                            ", 
                            $card_token, 'save'
                        )
                    );
                    $wpdb->insert($this->table, array(
                        'token_id'      => $token_id,
                        'phone_number'  => $phone_number,
                        'type_order'    => '05',
                        'user_id'       => $get_user_id
                    ));
                    if(!$save_cards_ids){
                        $save_cards_ids = '';
                    }
					if($check_save){
                        $check_save = 1;
                    } else {
                        $check_save = 0;
                    }
                    $responseSecond = [ 
                        "result" => [ 
                            "token_id"      => $token_id, 
                            "phone_number"  => $phone_number,
                            'card_token_id' => $save_cards_ids,
                            'if_save' => $ifsave,
							'check_save' => $check_save
                        ]
                    ];
                    WC()->session->set( 'step' , 'papi-verify' );
                    echo json_encode($responseSecond);
                }
            } else {
                $phone_number = $response["result"]["phone_number"];
                $token_id = $response["result"]["token_id"];
                $wpdb->insert($this->table, array(
                    'token_id'      => $token_id,
                    'phone_number'  => $phone_number,
                    'type_order'    => '04',
                    'user_id'       => $get_user_id
                ));
                $responseSecond = [ 
                    "result" => [ 
                        "token_id"      => $token_id, 
                        "phone_number"  => $phone_number,
                        'if_save' => $ifsave
                    ]
                ];
                WC()->session->set( 'step' , 'papi-verify' );
                echo json_encode($responseSecond);
            }
        } else {
            echo json_encode($response);
            WC()->session->set( 'step' , 'papi-create' );
            WC()->session->set( 'token_id' , '' );
            WC()->session->set( 'card_number' , '' );
            WC()->session->set( 'card_save' , '' );
        }
        wp_die();
    }

    public function token_request_auth_verify(){
        global $wpdb;
        global $current_user;
        $token_id = $_POST['params']['tokenId'];
        $verify_code = $_POST['params']['verifyCode'];
        $cards = $_POST['params']['cards'];
        $saved = $_POST['params']['saved'];
		$check_save = $_POST['params']['check'];
        $response = $this->tokenVerifyRequest($token_id, $verify_code);
        if($response["result"]){
            $card_token = $response["result"]["token"];
            $card_number = $response["result"]["card_number"];
            $card_type = $response["result"]["card_type"];
			$wpdb->update( $this->table, array(
				'card_token'    => $card_token,
				'card_num'      => $card_number,
				'card_type'     => $card_type,
				'type_order'    => '06'
			), array('token_id' => $token_id ) );
			if($cards){
				$cardsList = explode(",", $cards);
				foreach($cardsList as $key => $value){
					$wpdb->update( $this->table, array(
						'card_token'    => $card_token
					), array('token_id' => $value ) );
				}
			}
			if($check_save == 1 && $saved == 1){
				$responseSecond = [
                    "error" => [ 
                        "message" => 'Карта уже ранее была добавлена!'
                    ]
                ];
                WC()->session->set( 'step' , 'papi-create' );
                WC()->session->set( 'token_id' , '' );
                WC()->session->set( 'card_number' , '' );
                WC()->session->set( 'card_save' , '' );
			} else {
				if($saved == 1){
                    $wpdb->update( $this->table, array(
                        'status'    => 'save'
                    ), array('token_id' => $token_id ) );
                } else {
                    $wpdb->update( $this->table, array(
                        'status'    => 'not-save'
                    ), array('token_id' => $token_id ) );
                }
                $responseSecond = [
                    "result" => [ 
                        "token_id" => $token_id, 
                        "card_number" => $card_number
                    ]
                ];
				WC()->session->set( 'token_id' , $token_id );
                WC()->session->set( 'card_number' , $card_number );
                WC()->session->set( 'step' , 'papi-confirm' );
			}
            echo json_encode($responseSecond);
        } else {
            echo json_encode($response);
            WC()->session->set( 'step' , 'papi-create' );
            WC()->session->set( 'token_id' , '' );
            WC()->session->set( 'card_number' , '' );
            WC()->session->set( 'card_save' , '' );
        }
        wp_die();
    }

    public function papi_auth_use(){
        WC()->session->set( 'token_id' , '' );
        WC()->session->set( 'card_number' , '' );
        WC()->session->set( 'card_save' , '' );
        WC()->session->set( 'step' , 'papi-saveds' );
        global $wpdb;
        global $current_user;
        $user_id = $current_user->ID;
        $cards = $wpdb->get_results( "SELECT `token_id`, `card_num` FROM {$this->table} WHERE `user_id` = {$user_id} AND `status` = 'save'", ARRAY_A);
        if($cards){
            $response = [ 
                "result" => [ 
                    "status" => 'success'
                ]
            ];
        } else {
            $response = [ 
                "result" => [ 
                    "status"    => 'not',
                    "message"   => __('Add new card please', 'partnerapi')
                ]
            ];
        }
        echo json_encode($response);
        wp_die();
    }
	
	public function token_request_delete_cabinet(){
        global $wpdb;
        $card_id = $_POST['params']['card_id'];
        $card_token = $wpdb->get_var( 
            $wpdb->prepare( 
                "
                    SELECT card_token 
                    FROM {$this->table}
                    WHERE ID = %s
                ", 
                $card_id
            )
        );
        $wpdb->delete( $this->table, array( 'ID' => $card_id ) );
        $response = [ 
            "result" => [ 
                "message" => 'Карта успешно удалена'
            ]
        ];
        echo json_encode($response);
        WC()->session->set( 'token_id' , '' );
        WC()->session->set( 'card_number' , '' );
        WC()->session->set( 'card_save' , '' );
        wp_die();
    }

    public function process_payment( $order_id ) {
		global $wpdb;
		global $woocommerce;
		$order = wc_get_order( $order_id );
        $token_id = get_post_meta( $order_id, '_karta_id', true );
		$card_token = $wpdb->get_var( 
			$wpdb->prepare( 
				"
					SELECT card_token 
					FROM {$this->table}
					WHERE token_id = %s
				", 
				$token_id
			)
		);
		$order_data = $order->get_data();
		$amount = $order->get_subtotal();
		$params = array(
			"cntrg_param" => $order_data['id'],
			"amount" => $amount
		);
		$amountDel = $order->get_shipping_total();
		$paramsDel = array(
			"cntrg_param" => $order_data['id'],
			"amount" => $amountDel
		);
		$idMain = uniqid();
		$idDel = uniqid();
		$phoneUser = mb_substr( $order->get_billing_phone(), 3);
		$query_args = array(
			'order_id' => $order_data['id'],
			'amount' => number_format($order->get_shipping_total(), 0, '.', ''),
			'user_phone' =>  str_replace(array('(', ')', ' ', '-'), '', $phoneUser )
		);
		$response = $this->tokenPayment($card_token, 19252, $params, $idMain);
		if($response['result']){
			$status = $response['result']['payment_status'];
			if($status == 1 || $status == 2){
				$responseDel = $this->tokenPayment($card_token, 19252, $paramsDel, $idDel);
				if($responseDel['result']){
					$statusDel = $responseDel['result']['payment_status'];
					if($statusDel == 1 || $statusDel == 2){
						$order->payment_complete();
						return array(
							'result'   => 'success',
							'redirect' => $this->get_return_url( $order ),
						);
					} else {
						$order_number = $order->get_order_number();
						$phoneUser = mb_substr( $order->get_billing_phone(), 3);
						$query_args = array(
							'transaction_param' => $order_id != $order_number ? $order_number . CLICK_DELIMITER . $order_id : $order_id,
							'amount' =>  number_format( $order->get_shipping_total(), 0, '.', '' ),
							'user_phone' =>  str_replace(array('(', ')', ' ', '-'), '', $phoneUser ),
							'return_url' => apply_filters( 'click_return_url', add_query_arg( array( 'click-return' => WC()->customer->get_id() ), $order->get_view_order_url() ) )
						);
						return array(
							'result'   => 'success',
							'redirect' => add_query_arg(
								'order_pay',
								$order->get_id(),
								add_query_arg( $query_args, 'https://market.click.uz/payDilevery' )
							)
						);
					}
				} else {
					$order_number = $order->get_order_number();
					$phoneUser = mb_substr( $order->get_billing_phone(), 3);
					$query_args = array(
						'transaction_param' => $order_id != $order_number ? $order_number . CLICK_DELIMITER . $order_id : $order_id,
						'amount' =>  number_format( $order->get_shipping_total(), 0, '.', '' ),
						'user_phone' =>  str_replace(array('(', ')', ' ', '-'), '', $phoneUser ),
						'return_url' => apply_filters( 'click_return_url', add_query_arg( array( 'click-return' => WC()->customer->get_id() ), $order->get_view_order_url() ) )
					);
					return array(
						'result'   => 'success',
						'redirect' => add_query_arg(
							'order_pay',
							$order->get_id(),
							add_query_arg( $query_args, 'https://market.click.uz/payDilevery' )
						)
					);
				}
			} else {
				wc_add_notice( $response['result']['status_description'] , 'error' );
				return false;
			}
		} else{
			wc_add_notice( 'Произошла ошибка' , 'error' );
			return false;
		}	
	}
}
