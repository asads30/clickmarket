<?php
/**
 * Add payment method form form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-add-payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

	$current_user = wp_get_current_user();

?>
<div class="card-form">
	<div class="card-form-add" data-step="create" data-cards>
		<div class="card-form-add-head create">
			<div class="card-form-add-head-title">
				<span><?php esc_html_e( 'Add new', 'partnerapi' ); ?></span>
			</div>
		</div>
		<div class="card-form-add-top create">
			<div class="card-form-add-form-cc">
				<label for="cardformadd_cc"><?php esc_html_e( 'Number card', 'partnerapi' ); ?></label>
				<input id="cardformadd_cc" type="text" placeholder="0000 0000 0000 0000" class="card-form-add-form-input">
			</div>
		</div>
		<div class="card-form-add-center create">
			<div class="card-form-add-form-ex">
				<label for="cardformadd_ex"><?php esc_html_e( 'Date card', 'partnerapi' ); ?></label>
				<input id="cardformadd_ex" type="text" placeholder="00/00" class="card-form-add-form-input">
			</div>
			<div class="card-form-add-center-phone">
				<label for="cardformadd_phone"><?php esc_html_e( 'Phone number card', 'partnerapi' ); ?></label>
				<input type="hidden" id="cardformadd_phone" value="<?php echo esc_attr( $current_user->user_login ); ?>">
				<div class="cardformadd_phone_val"><?php echo esc_attr( $current_user->user_login ); ?></div>
			</div>
		</div>
		<div class="card-form-add-bottom create">
			<button id="card-form-add-getcode" type="button"><?php esc_html_e( 'Continue', 'partnerapi' ); ?></button>
		</div>
		<div class="card-form-add-head verify">
			<div class="card-form-add-head-title">
				<span><?php esc_html_e( 'Confirm descr', 'partnerapi' ); ?></span>
			</div>
			<div class="card-form-add-head-back">
				<button id="card-form-add-head-back-verify" type="button"><?php esc_html_e( 'Back', 'partnerapi' ); ?></button>
			</div>
		</div>
		<div class="card-form-add-conf verify">
			<div class="card-form-add-conf-code">
				<input type="text" id="cardformadd_code" placeholder="00000" class="card-form-add-form-input">
			</div>
			<div class="card-form-add-conf-button">
				<button id="cardformadd_code-confirmcode" type="button"><?php esc_html_e( 'Confirm', 'partnerapi' ); ?></button>
			</div>
		</div>
		<div class="card-form-add-confirm confirm">
			<div class="card-form-add-confirm-icon">
				<img src="/wp-content/themes/kalles/assets/images/good.svg" alt="">
			</div>
			<p><?php pll_e('cardAddSuccess2'); ?></p>
			<div class="card-form-add-confirm-buttons">
				<a href="<?php echo wc_get_endpoint_url('payment-methods'); ?>"><?php esc_html_e( 'All cards', 'partnerapi' ); ?></a>
				<a href="<?php echo wc_get_endpoint_url('add-payment-method'); ?>"><?php esc_html_e( 'Add new', 'partnerapi' ); ?></a>
			</div>
		</div>
	</div>
</div>