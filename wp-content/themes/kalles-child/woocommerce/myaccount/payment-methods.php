    <?php 
    /** 
     * Payment methods 
     * 
     * Shows customer payment methods on the account page. 
     * 
     * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/payment-methods.php. 
     * 
     * HOWEVER, on occasion WooCommerce will need to update template files and you 
     * (the theme developer) will need to copy the new files to your theme to 
     * maintain compatibility. We try to do this as little as possible, but it does 
     * happen. When this occurs the version of the template file will be bumped and 
     * the readme will list any important changes. 
     * 
     * @see     https://docs.woocommerce.com/document/template-structure/ 
     * @author  WooThemes 
     * @package WooCommerce/Templates 
     * @version 2.6.0 
     */ 

    global $wpdb;
    global $current_user;
    $cardsprefix = $wpdb->prefix . 'wc_partnerapi_cards';
    $user_id = $current_user->ID;
    $cards = $wpdb->get_results( "SELECT `ID`, `card_num`, `card_type` FROM {$cardsprefix} WHERE `user_id` = {$user_id} AND `status` = 'save'", ARRAY_A);
    if ( $cards ) : ?>
        <table class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table">
            <thead>
                <tr>
                    <th class="woocommerce-orders-table__header">ID</th>
                    <th class="woocommerce-orders-table__header"><?php esc_html_e( 'Type card', 'partnerapi' ); ?></th>
                    <th class="woocommerce-orders-table__header"><?php esc_html_e( 'Number card', 'partnerapi' ); ?></th>
                    <th class="woocommerce-orders-table__header"><?php esc_html_e( 'Action', 'partnerapi' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $cards as $card ){ ?>    
                    <tr class="woocommerce-orders-table__row">
                        <td class="woocommerce-orders-table__cell"><strong id="partner-card-id"><?php echo $card['ID']; ?></strong></td>
                        <td class="woocommerce-orders-table__cell"><strong><?php echo $card['card_type']; ?></strong></td>
                        <td class="woocommerce-orders-table__cell"><strong><?php echo $card['card_num']; ?></strong></td>
                        <td class="woocommerce-orders-table__cell"><button id="delete-partner-card" class="button"><?php esc_html_e( 'Delete', 'partnerapi' ); ?></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e( 'No cards', 'partnerapi' ); ?></p>
    <?php endif; ?>
    <div style="margin-top: 20px;">
        <a class="button" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php esc_html_e( 'Add payment method', 'woocommerce' ); ?></a>
    </div>