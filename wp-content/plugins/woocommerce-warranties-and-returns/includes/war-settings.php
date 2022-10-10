<?php

class WC_War_Settings {

	public static function init() {

		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::wcwar_add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_wcwar', __CLASS__ . '::wcwar_settings_tab' );
		add_action( 'woocommerce_update_options_wcwar', __CLASS__ . '::wcwar_save_settings' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::wcwar_settings_scripts' );

	}

	public static function wcwar_settings_scripts( $settings_tabs ) {
		if ( isset( $_GET['page'], $_GET['tab'] ) && ( $_GET['page'] == 'wc-settings' || $_GET['page'] == 'woocommerce_settings' ) && $_GET['tab'] == 'wcwar' ) {
			wp_enqueue_style( 'wcwar-vendor-css', plugins_url( 'lib/formbuilder/vendor/css/vendor.css', dirname(__FILE__) ) );
			wp_enqueue_style( 'wcwar-formbuilder-css', plugins_url( 'lib/formbuilder/formbuilder.css', dirname(__FILE__) ) );
			wp_enqueue_style( 'wcwar-settings-css', plugins_url( 'assets/css/settings.css', dirname(__FILE__) ) );
			wp_enqueue_script( 'wcwar-vendor', plugins_url( 'lib/formbuilder/vendor/js/vendor.js', dirname(__FILE__) ), array( 'jquery' ), WC_Warranties_And_Returns::$version, true );
			wp_enqueue_script( 'wcwar-formbuilder', plugins_url( 'lib/formbuilder/formbuilder.js', dirname(__FILE__) ), array( 'jquery' ), WC_Warranties_And_Returns::$version, true );
			wp_enqueue_script( 'wcwar-settings', plugins_url( 'assets/js/formbuilder.js', dirname(__FILE__) ), array( 'jquery' ), WC_Warranties_And_Returns::$version, true );
		}
	}

	public static function wcwar_add_settings_tab( $settings_tabs ) {
		$settings_tabs['wcwar'] = __( 'Warranties and Returns', 'wcwar' );
		return $settings_tabs;
	}

	public static function wcwar_settings_tab() {
		woocommerce_admin_fields( self::wcwar_get_settings() );
	}

	public static function wcwar_save_settings() {
		woocommerce_update_options( self::wcwar_get_settings() );
	}

	public static function wcwar_get_settings() {

		$presets = get_terms( 'wcwar_warranty_pre', array('hide_empty' => false) );

		$ready_presets = array(
			'' => __( 'None', 'wcwar' )
		);

		foreach ( $presets as $preset ) {
			$ready_presets[$preset->term_id] = $preset->name;
		}

		$settings = array(
			'section_install_title' => array(
				'name'     => __( 'Installation Settings', 'wcwar' ),
				'type'     => 'title',
				'desc'     => __( 'Warranties and Returns for WooCommerce! This plugin was made by Mihajlovicnenad.com! Thank you for using!', 'wcwar' ) . ' <a href="http://mihajlovicnenad.com/" target="_blank">' . 'Get more awesome plugins for WooCommerce here!' . '</a>'
			),
			'wcwar_single_action' => array(
				'name'    => __( 'Override Single Product Page Action', 'wcwar' ),
				'type'    => 'text',
				'desc'    => __( 'Change default init action on single product pages. Use actions initiated in your content-single-product.php file. Please enter action in the following format action_name:priority. E.G. woocommerce_before_add_to_cart_form:10', 'wcwar' ) . ' (default: woocommerce_before_add_to_cart_form )',
				'id'      => 'wcwar_single_action',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
			),
			'wcwar_single_titles' => array(
				'name'    => __( 'Single Product Page Heading', 'wcwar' ),
				'type'    => 'select',
				'desc'    => __( 'Select heading level for Single Product Pages warranty titles.', 'wcwar' ),
				'id'      => 'wcwar_single_titles',
				'default' => 'h4',
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6'
				),
				'css' => 'width:300px;margin-right:12px;'
			),
			'wcwar_single_mode' => array(
				'name'    => __( 'Single Warranty Page Display Mode', 'wcwar' ),
				'type'    => 'select',
				'desc'    => __( 'Select display mode for the Single Warranty Page.', 'wcwar' ),
				'id'      => 'wcwar_single_mode',
				'default' => 'new',
				'options' => array(
					'old' => 'Old - WooThemes, Basic Themes',
					'new' => 'New - Most Supported in Premium Themes'
				),
				'css' => 'width:300px;margin-right:12px;'
			),
			'war_settings_page' => array(
				'name'    => __( 'Request Warranty Page', 'wcwar' ),
				'type'    => 'single_select_page',
				'desc'    => __( 'Please select the page for requesting warranties. This page should have been automatically created, if this is not the case please view documentation for more information on how to create a warranty requests page.', 'wcwar' ),
				'id'      => 'war_settings_page',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
			),
			'section_install_end' => array(
				'type' => 'sectionend'
			),
			'section_settings_title' => array(
				'name'     => __( 'Basic Settings', 'wcwar' ),
				'type'     => 'title',
				'desc'     => __( 'Setup basic Warranties and Returns for WooCommerce settings.', 'wcwar' )
			),
			'wcwar_enable_admin_requests' => array(
				'name'    => __( 'Enable Admin Warranties', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'If checked admins will have the ability to create warranty requests for items that do not have any warranties.', 'wcwar' ),
				'id'      => 'wcwar_enable_admin_requests',
				'default' => 'yes',
			),
			'wcwar_enable_multi_requests' => array(
				'name'    => __( 'Enable Multi Warranty Requests', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'Check this option to enable multi requests in the defined warranty period. New requests will available upon completing the previous requests.', 'wcwar' ),
				'id'      => 'wcwar_enable_multi_requests',
				'default' => 'no',
			),
			'wcwar_enable_guest_requests' => array(
				'name'    => __( 'Enable Guest Warranty Requests', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'Guests can also access warranties using their order id and their email addres to confirm their identity. Check this option if you want to allow not logged in users to request warranties and returns.', 'wcwar' ),
				'id'      => 'wcwar_enable_guest_requests',
				'default' => 'no',
			),
			'wcwar_default_warranty' => array(
				'name'    => __( 'Select Default Warranty', 'wcwar' ),
				'type'    => 'select',
				'desc'    => __( 'Products without warranties can have a default warranty. Please select warranty preset.', 'wcwar' ),
				'id'      => 'wcwar_default_warranty',
				'default' => '',
				'options' => $ready_presets,
				'css' => 'width:300px;margin-right:12px;'
			),
			'wcwar_default_post' => array(
				'name'    => __( 'Select Warranty Status', 'wcwar' ),
				'type'    => 'select',
				'desc'    => __( 'Select status for the newly submitted warranties.', 'wcwar' ),
				'id'      => 'wcwar_default_post',
				'default' => 'pending',
				'options' => array(
					'publish' => __( 'Published', 'wcwar' ),
					'pending' => __( 'Pending', 'wcwar' )
				),
				'css' => 'width:300px;margin-right:12px;'
			),
			'wcwar_registration_key' => array(
				'name'    => __( 'Register Warranties and Returns for WooCommerce', 'wcwar' ),
				'type'    => 'text',
				'desc'    => __( 'Enter your purchase code to get instant updated even before the codecanyon.net releases!', 'wcwar' ),
				'id'      => 'wcwar_registration_key',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
			),
			'section_settings_end' => array(
				'type' => 'sectionend'
			),
			'section_email_title' => array(
				'name'     => __( 'Email Settings', 'wcwar' ),
				'type'     => 'title',
				'desc'     => __( 'Email and Admin Quick Email settings.', 'wcwar' )
			),
			'wcwar_email_disable' => array(
				'name'    => __( 'Disable Warranty Information in Emails', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'Check this option to disable warranty information in order emails sent by WooCommerce.', 'wcwar' ),
				'id'      => 'wcwar_email_disable',
				'default' => 'no',
			),
			'wcwar_email_name' => array(
				'name'    => __( 'Quick Email From Name', 'wcwar' ),
				'type'    => 'text',
				'desc'    => __( 'Enter quick email from name which you would like to appear in the sent emails.', 'wcwar' ),
				'id'      => 'wcwar_email_name',
				'default' => get_bloginfo( 'name' ),
			),
			'wcwar_email_address' => array(
				'name'    => __( 'Quick Email Reply To', 'wcwar' ),
				'type'    => 'text',
				'desc'    => __( 'Enter email address that will appear as reply to address.', 'wcwar' ),
				'id'      => 'wcwar_email_address',
				'default' => get_bloginfo( 'admin_email' ),
			),
			'wcwar_email_bcc' => array(
				'name'    => __( 'Quick Email BCC', 'wcwar' ),
				'type'    => 'text',
				'desc'    => __( 'Enter email addresses separated by comma to send BCC copies of the emails sent using the quick email feature.', 'wcwar' ),
				'id'      => 'wcwar_email_bcc',
				'default' => '',
			),
			'section_email_end' => array(
				'type' => 'sectionend'
			),
			'section_returns_title' => array(
				'name'     => __( 'Return Requests Settings', 'wcwar' ),
				'type'     => 'title',
				'desc'     => __( 'Setup in store returns.', 'wcwar' )
			),
			'wcwar_enable_returns' => array(
				'name'    => __( 'Enable Item Returns', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'This option will enable the in store returns. Set your return period in which the items can be sent back by customers with a refund.', 'wcwar' ),
				'id'      => 'wcwar_enable_returns',
				'default' => 'no',
			),
			'wcwar_returns_period' => array(
				'name' => __( 'Return Period Limit', 'wcwar' ),
				'type' => 'number',
				'desc' => __( 'Number of days for returning items upon order completition. If 0 is set, items will have a lifetime return period.', 'wcwar' ),
				'id'   => 'wcwar_returns_period',
				'default' => 0,
				'custom_attributes' => array(
					'min' 	=> 0,
					'max' 	=> 1826,
					'step' 	=> 1
				)
			),
			'wcwar_returns_no_warranty' => array(
				'name'    => __( 'Enable Returns Without Warranty', 'wcwar' ),
				'type'    => 'checkbox',
				'desc'    => __( 'If checked, returns will be available for items that have no warranty.', 'wcwar' ),
				'id'      => 'wcwar_returns_no_warranty',
				'default' => 'no',
			),
			'section_returns_end' => array(
				'type' => 'sectionend'
			),
			'section_form_title' => array(
				'name'     => __( 'Warranties Request Form', 'wcwar' ),
				'type'     => 'title',
				'desc'     => __( 'Make a warranty request form.', 'wcwar' ),
				'id'       => 'wcwar_form_title'
			),
			'wcwar_form' => array(
				'name'    => __( 'Request Form', 'wcwar' ),
				'type'    => 'textarea',
				'desc'    => __( 'HTML Warranty Request Form', 'wcwar' ),
				'id'      => 'wcwar_form',
				'default' => ''
			),
			'section_form_end' => array(
				'type' => 'sectionend'
			),
		);

		return apply_filters( 'wc_wcwar_settings', $settings );
	}

}

WC_War_Settings::init();

?>