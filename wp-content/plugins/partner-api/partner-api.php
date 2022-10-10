<?php
/**
 * @package  PartnerAPI
 */
/*
Plugin Name: Partner API
Plugin URI: https://click.uz/
Description: CLICK Partner API Payment Method Plugin
Version: 1.0.0
Author: OOO "Click"
Author URI: https://click.uz/
License: GPLv2 or later
 * Text Domain: partnerapi
 * Domain Path: /i18n/languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use Inc\Activate;
use Inc\Deactivate;

define( 'SERVICE_URL', 'https://api.click.uz/partner' );
define( 'WC_PartnerAPI_PLUGIN_URL', plugin_dir_url(__FILE__) );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	class WC_PartnerAPI {
        public $plugin;
        public function __construct() {
            $this->plugin = plugin_basename( __FILE__ );
            load_plugin_textdomain( 'partnerapi', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
            register_activation_hook( __FILE__, array( $this, 'activate' ) );
            register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
            add_action( 'plugins_loaded', array( $this, 'init' ) );
            add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );
        }

        public function init() {
            if ( class_exists( 'WC_Payment_Gateway' ) ) {
                require_once 'inc/class-wc-gateway-partnerapi.php';
                new WC_PartnerAPI();
            }
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
                CREATE TABLE `{$wpdb->prefix}wc_partnerapi_cards` (
                    `ID` BIGINT(20)	UNSIGNED NOT NULL AUTO_INCREMENT,
                    `token_id` BIGINT(20) UNSIGNED NOT NULL,
                    `phone_number` NVARCHAR(120),
                    `type_order` NVARCHAR(120),
                    `card_num` NVARCHAR(120),
                    `card_type` NVARCHAR(120),
                    `card_token` NVARCHAR(120),
                    `order_id` BIGINT(20) UNSIGNED NOT NULL,
                    `user_id` BIGINT(20) UNSIGNED NOT NULL,
                    `status` NVARCHAR(120),
                    PRIMARY KEY (`ID`)
                ) $collate; " );
        }

        public function add_gateway( $methods ) {
            $methods[] = 'WC_Gateway_PartnerAPI';
            return $methods;
        }

    }

    new WC_PartnerAPI();
}
