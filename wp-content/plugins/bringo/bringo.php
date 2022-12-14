<?php
 
/**
 * Plugin Name: Bringo
 * Plugin URI: https://market.click.uz/
 * Description: Bringo integration Woocomerce
 * Version: 1.0.0
 * Author: Asadbek Ibragimov
 * Author URI: http://click.uz/
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: bringo
 * Domain Path: /i18n/languages/
 **/
 
if ( ! defined( 'WPINC' ) ) {
    die;
}
$bringoorderid;
function log_me($message) {
    if ( WP_DEBUG === true ) {
        if ( is_array($message) || is_object($message) ) {
            error_log( print_r($message, true) );
        } else {
            error_log( $message );
        }
    }
}
define( 'WC_BRINGO_PLUGIN_URL', plugin_dir_url(__FILE__) );
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    function bringo_shipping_method(){
        if (!class_exists('Bringo_Shipping_Method')) {
            class Bringo_Shipping_Method extends WC_Shipping_Method{
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct($instance_id = 0){
                    $this->id = 'bringo';
                    $this->instance_id = absint($instance_id);
                    $this->method_title = __('Bringo Shipping', 'bringo');
                    $this->method_description = __('Custom Shipping Method for Bringo', 'bringo');
                    $this->supports = array(
                        'shipping-zones',
                        'settings'
                    );
                    $this->init();
                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = pll__( 'bringo2' );
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init(){
                    $this->init_form_fields();
                    $this->init_settings();
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields(){
                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'bringo'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'bringo'),
                            'default' => 'yes'
                        )
                    );

                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array()){
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => 0
                    );
                    $this->add_rate($rate);
                }
            }
        }
    }
    add_action('woocommerce_shipping_init', 'bringo_shipping_method');

    function add_bringo_shipping_method($methods){
        $methods['bringo'] = 'Bringo_Shipping_Method';
        return $methods;
    }
    add_filter('woocommerce_shipping_methods', 'add_bringo_shipping_method');
    
	add_action( 'woocommerce_review_order_before_payment', function() {   
        echo '<input type="hidden" id="bringo-price">';
        if ( WC()->session->get('bringo_address' )){
            echo '<div class="selectBox" id="selectBringo" style="display: none"><h5 id="bringo-edit">' . esc_html__( WC()->session->get('bringo_address')  ) . '</h5>' . '<button class="selectClickbox" type="button" id="bringo-btn">' . esc_html__( WC()->session->get('change_bringo') ) . '</button></div>';
        }
        echo '<div class="selectBox" id="selectBringo" style="display: none"><h5 id="bringo-edit">' . esc_html__( '???????????????? ?????????? ????????????????', 'bringo'  ) . '</h5>' . '<button class="selectClickbox" type="button" id="bringo-btn">' . esc_html__( '??????????????', 'clickbox' ) . '</button></div>';
    });
}

function bringo_script() { ?>
    <script type="text/javascript">
        jQuery( function($){
            localStorage.setItem('bringo-map', 'false');
            var itemsSize = $('#clickbox-box').val();
            var itemsD = (itemsSize == 1) ? '23' : '40';
            var itemsS = (itemsSize == 1) ? '14' : (itemsSize == 2) ? '10' : (itemsSize == 3) ? '20' : '30';
            var itemsV = (itemsSize == 1) ? '28' : '35';
            var currentLang = $('#currentLang').data('lang');
            let text1 = currentLang == 'uz' ? 'Manzilni kiriting' : '?????????????? ??????????';
            let text2 = currentLang == 'uz' ? 'Topish' : '??????????';
            let text3 = currentLang == 'uz' ? 'Manzil topilmadi' : '?????????? ???? ????????????';
            let text4 = currentLang == 'uz' ? 'Nuqtani tanlash' : '?????????????? ??????????';
            let text5 = currentLang == 'uz' ? 'Qidiruv tafsilotlarini o\'chirish' : '???????????????? ??????????';
            let text6 = currentLang == 'uz' ? 'Tasdiqlash' : '??????????????????????';
            let text7 = currentLang == 'uz' ? 'Ortga qaytish' : '?????????????????? ??????????';
            let text8 = currentLang == 'uz' ? 'Yopish' : '??????????????';
            let text9 = currentLang == 'uz' ? 'Yetkazib berish faqat Toshkent shahri bo\'ylab!' : '???????????????? ???????????? ???? ????????????????!';
            let text10 = currentLang == 'uz' ? 'Qo\'shimcha ma\'lumotlar' : '???????????????????????????? ????????????';
            let text11 = currentLang == 'uz' ? 'Uy raqami' : '?????????? ????????';
            let text12 = currentLang == 'uz' ? 'Xonadon raqami' : '?????????? ????????????????';
            let text13 = currentLang == 'uz' ? 'Izoh' : '??????????????????????';
            let text14 = currentLang == 'uz' ? 'Davom ettirish' : '????????????????????';
            let text15 = currentLang == 'uz' ? 'Manzil to\'liq emas, aniqlashtirish uchun ko\'cha nomi va uy raqamini kiriting' : '???????????????? ??????????, ?????????????????? ??????????????????, ?????????????? ???????????????? ?????????? ?? ?????????? ????????';
            let text16 = currentLang == 'uz' ? 'Uy raqamini belgilang' : '???????????????? ?????????? ????????';
            let text17 = currentLang == 'uz' ? 'Manzil to\'liq emas, aniqlashtirish uchun uy raqamini kiriting' : '???????????????? ??????????, ?????????????????? ??????????????????, ?????????????? ?????????? ????????';
            let text18 = currentLang == 'uz' ? 'Manzilni aniqlashtiring' : '???????????????? ??????????';
            let text19 = currentLang == 'uz' ? 'Manzil topilmadi' : '?????????? ???? ????????????';
            let text20 = currentLang == 'uz' ? 'Tanlash' : '??????????????';
            let text21 = currentLang == 'uz' ? 'Yetkazib berish manzilini tanlang' : '???????????????? ?????????? ????????????????';
            let text22 = currentLang == 'uz' ? 'Manzilni o\'zgartirish' : '???????????????? ??????????';
            let text23 = currentLang == 'uz' ? 'Sizga ma\'qul bo\'lgan yetkazib berish vaqti' : '???????????????????????????????? ?????????? ????????????????';
            function loadingBtn(id){
                $(id).addClass('the-loading');
                $(id).prop('disabled', true);
            }
            function loadingBtnFalse(id){
                $(id).removeClass('the-loading');
                $(id).prop('disabled', false);
            }
            var bringoModal = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-map'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModal2 = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-map'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModalAddress = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-tashkent'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModalConfirm = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-tashkent'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            bringoModal.setContent('<div class="bringo-header"><input type="text" id="suggest" class="input" placeholder="' + text1 + '" required><button type="submit" id="bringoBtn">'    + text2 + '</button></div><div id="bringo-error"><p id="notice">' + text3 + '</p><button id="changeMap" style="display: none;">' + text4 + '</button></div><div id="map"></div><div class="bringo-footer"><button id="bringo-clear">' + text5 + '</button><button id="bringo-button-select">' + text6 + '</button></div>');
            bringoModal2.setContent('<div id="bringo-map"></div><div class="bringo-map-footer"><button id="back-map">' + text7 +'</button><button id="bringo-button-select2" data-active="0">' + text8 + '</button></div>');
            bringoModalAddress.setContent('<div id="bringo-text">' + text9 + '</div><button id="bringo-close-btn">' + text8 + '</button>');
            bringoModalConfirm.setContent('<div id="bringo-text">' + text10 + '</div><div class="bringo-conf-box"><input type="text" placeholder="' + text23 +'" id="bringo-time"></div><div class="bringo-conf-box" required><input type="text" class="bringo-confirm-input" id="bringo-home" placeholder="' + text11 +'" required><input type="text" class="bringo-confirm-input" id="bringo-appartment" placeholder="' + text12 + '" required></div><div class="bringo-conf-box"></div><div class="bringo-conf-box"><button id="bringo-conform-btn">' + text14 + '</button></div>');
            function initBringo() {
                var suggestView = new ymaps.SuggestView('suggest'),map,placemark;
                $('#bringoBtn').bind('click', function (e) {
                    geocode();
                });
                function geocode() {
                    var request = $('#suggest').val();
                    ymaps.geocode(request).then(function (res) {
                        var obj = res.geoObjects.get(0),
                            error, hint;
                        if (obj) {
                            switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                                case 'exact':
                                    break;
                                case 'number':
                                case 'near':
                                case 'range':
                                    error = text15;
                                    hint = text16;
                                    break;
                                case 'street':
                                    error = text17;
                                    hint = text16;
                                    break;
                                case 'other':
                                default:
                                    error = text15;
                                    hint = text16;
                            }
                        } else {
                            error = text19;
                            hint = text18;
                        }
                        if (error) {
                            showError(error);
                            showMessage(hint);
                        } else {
                            showResult(obj);
                        }
                    }, function (e) {
                        console.log(e)
                    })
                }
                function showResult(obj) {
                    $('#map').css('height', '400px');
                    $('#map').css('margin', '20px 0');
                    $('#suggest').removeClass('input_error');
                    $('#bringo-error').css('display', 'none');
                    var mapContainer = $('#map'),
                        bounds = obj.properties.get('boundedBy'),
                        mapState = ymaps.util.bounds.getCenterAndZoom(
                            bounds,
                            [mapContainer.width(), mapContainer.height()]
                        ),
                        address = obj.getAddressLine(),
                        shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                    mapState.controls = [];
                    createMap(mapState, shortAddress);
                    showMessage(address);
                    $('.bringo-footer').css('display', 'flex');
                    var cord = obj.geometry._coordinates
                    $('#bringo-button-select').attr('data-active', 1);
                    $('#bringo-button-select').attr('data-lat', cord[0]);
                    $('#bringo-button-select').attr('data-lng', cord[1]);
                    $('#bringo-button-select').attr('data-address', address);
                }
                function showError(message) {
                    localStorage.setItem('bringo-map', 'false');
                    $('#map').css('height', '0');
                    $('#map').css('margin', '0');
                    $('#suggest').addClass('input_error');
                    $('#bringo-error').css('display', 'flex');
                    $('#changeMap').css('display', 'block');
                    $('#notice').html(message);
                    $('.bringo-footer').css('display', 'none');
                    $('#bringo-button-select').attr('data-active', 0);
                    $('#bringo-button-select').attr('data-lat', '');
                    $('#bringo-button-select').attr('data-lng', '');
                    $('#billing_address_1').val('');
                    if (map) {
                        map.destroy();
                        map = null;
                    }
                }
                function createMap(state, caption) {
                    var checkMap = localStorage.getItem('bringo-map');
                    if (!map && checkMap === 'false') {
                        map = new ymaps.Map('map', state, {
                            suppressMapOpenBlock: true
                        });
                        placemark = new ymaps.Placemark(
                            map.getCenter(), {
                                preset: 'islands#redDotIconWithCaption'
                            });
                        map.geoObjects.add(placemark);
                        localStorage.setItem('bringo-map', 'true');
                    } else {
                        map.setCenter(state.center, state.zoom);
                        placemark.geometry.setCoordinates(state.center);
                        placemark.properties.set({iconCaption: caption, balloonContent: caption});
                    }
                }
                function showMessage(message) {
                    $('#message').text(message);
                }
                $('#bringo-clear').click(function(){
                    localStorage.setItem('bringo-map', 'false');
                    $('#map').css('height', '0');
                    $('#map').css('margin', '0');
                    $('.bringo-footer').css('display', 'none');
                    $('#bringo-button-select').attr('data-active', 0);
                    $('#bringo-button-select').attr('data-lat', '');
                    $('#bringo-button-select').attr('data-lng', '');
                    $('#billing_address_1').val('');
                    $('#suggest').val('');
                    if (map) {
                        map.destroy();
                        map = null;
                    }
                });
            }
            function initMap() {
                $('#bringo-map').html('');
                var myPlacemark,
                    myMap = new ymaps.Map("bringo-map", {
                    center: [41.31688073, 69.24690049],
                    zoom: 12,
                    controls: ['geolocationControl']
                    }, {suppressMapOpenBlock: true}
				);
                myMap.events.add('click', function (e) {
                    var coords = e.get('coords');
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                        myPlacemark.events.add('dragend', function () {
                            getAddress(myPlacemark.geometry.getCoordinates());
                        });
                    }
                    getAddress(coords);
                    $('#bringo-button-select2').text(text6);
                    $('#bringo-button-select2').addClass('active');
                    $('#bringo-button-select2').attr('data-active', 1);
                    $('#bringo-button-select2').attr('data-lat', coords[0].toPrecision(8));
                    $('#bringo-button-select2').attr('data-lng', coords[1].toPrecision(8));
                });
                function createPlacemark(coords) {
                    return new ymaps.Placemark(coords, {
                        iconCaption: '??????????...',
                    }, {
                        preset: 'islands#violetDotIconWithCaption',
                        draggable: false
                    });
                }
                function getAddress(coords) {
                    myPlacemark.properties.set('iconCaption', '??????????...');
                    ymaps.geocode(coords).then(function (res) {
                        var firstGeoObject = res.geoObjects.get(0);
                        var addressGeo = firstGeoObject.getAddressLine();
                        myPlacemark.properties
                            .set({
                                iconCaption: firstGeoObject.getAddressLine()
                            });
                        $('#bringo-button-select2').attr('data-address', addressGeo)
                    });
                }
            }
            let bringoBtn = document.getElementById('bringo-btn');
            if(bringoBtn){
                bringoBtn.addEventListener('click', function(){
                    $('#bringo-map').html('');
                    bringoModal.open();
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                    $('#bringo-map').show();
                    ymaps.ready(initBringo);
                    focusSuggest();
                });
            }
            $(document).on('click', '#bringo-button-select', function() {
                bringoModal.close();
                $('#bringo-btn').text(text20);
                $('#bringo-edit').text(text21);
                var activeBtn = $(this).attr('data-active');
                var data = {
                    bringo_merchant_id: 395,
                    merchant: {
                        lat: "41.34021",
                        lng: "69.31330"
                    },
                    client: {
                        lat: $(this).attr('data-lat'),
                        lng: $(this).attr('data-lng')
                    },
                    items: [
                        {
                            name: '?????????? ???'+itemsSize,
                            weight: '0.37',
                            height: itemsD,
                            width: itemsS,
                            length: itemsV
                        },
                    ]
                };
                if(activeBtn == 1){
                    $.ajax({
                        type: 'POST',
                        url: "https://dev.bringo.uz/api/v1/calculateDeliveryPrice",
                        data: JSON.stringify(data),
                        dataType: "json",
                        success: function(result) {
                            if(result.success == true){
                                $('#bringo-btn').text(text22);
                                $('#bringo-edit').text($('#bringo-button-select').attr('data-address'));
                                $('#bringo_user_address').val($('#bringo-button-select').attr('data-address'));
                                $('#billing_address_1').val($('#bringo-button-select').attr('data-address'));
                                $('#bringo_user_lat').val($('#bringo-button-select').attr('data-lat'));
                                $('#bringo_user_lng').val($('#bringo-button-select').attr('data-lng'));
                                bringoModalConfirm.open();
                                $.ajax({
                                    type: 'POST',
                                    url: wc_checkout_params.ajax_url,
                                    data: {
                                        'action': 'woo_get_ajax_data',
                                        'billing_ups': result.data.delivery_price,
                                        'change_bringo': '???????????????? ??????????',
                                        'bringo_lat': data.client.lat,
                                        'bringo_lng': data.client.lng,
                                        'bringo_address': $('#bringo-button-select').attr('data-address'),
                                    },
                                    success: function (result) {
                                        $('body').trigger('update_checkout');
                                    },
                                    error: function(error){
                                        console.log(error);
                                    }
                                });
                                
                            } else if(result.success == false) {
                                $('#bringo-btn').text(text20);
                                $('#bringo-edit').text(text21);
                                $('#bringo_user_address').val();
                                $('#billing_address_1').val('');
                                $('#bringo-button-select').attr('data-active', 0);
                                bringoModalAddress.open();
                            }
                        }
                    });
                } else {
                    $('#billing_address_1').val('');
                }
            });
            $(document).on('click', '#bringo-button-select2', function() {
                bringoModal2.close();
                $('#bringo-btn').text(text20);
                $('#bringo-edit').text(text21);
                var activeBtn = $(this).attr('data-active');
                var data = {
                    bringo_merchant_id: 395,
                    merchant: {
                        lat: "41.32666",
                        lng: "69.31330"
                    },
                    client: {
                        lat: $(this).attr('data-lat'),
                        lng: $(this).attr('data-lng')
                    },
                    items: [
                        {
                            name: '?????????? ???'+itemsSize,
                            weight: '0.34',
                            height: itemsD,
                            width: itemsS,
                            length: itemsV
                        },
                    ]
                };
                if(activeBtn == 1){
                    $.ajax({
                        type: 'POST',
                        url: "https://dev.bringo.uz/api/v1/calculateDeliveryPrice",
                        data: JSON.stringify(data),
                        dataType: "json",
                        success: function(result) {
                            if(result.success == true){
                                $('#bringo-btn').text(text22);
                                $('#bringo-edit').text($('#bringo-button-select2').attr('data-address'));
                                $('#bringo_user_address').val($('#bringo-button-select2').attr('data-address'));
                                $('#billing_address_1').val($('#bringo-button-select2').attr('data-address'));
                                $('#bringo_user_lat').val($('#bringo-button-select').attr('data-lat'));
                                $('#bringo_user_lng').val($('#bringo-button-select').attr('data-lng'));
                                $('#selectBringo').attr('data-price', result.data.delivery_price);
                                $('#selectBringo').attr('data-distance', result.data._km);
                                bringoModalConfirm.open();
                            } else if(result.success == false) {
                                $('#bringo-btn').text(text20);
                                $('#bringo-edit').text(text21);
                                $('#bringo_user_address').val();
                                $('#billing_address_1').val('');
                                $('#bringo-button-select2').attr('data-active', 0);
                                bringoModalAddress.open();
                            }
                        }
                    });
                } else {
                    $('#billing_address_1').val('');
                }
            });
            $('#bringo-close-btn').click(function(){
                bringoModalAddress.close();
                $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    data: {
                        'action': 'woo_get_ajax_data',
                        'billing_ups': 0,
                        'change_bringo': '??????????????',
                        'bringo_address': '???????????????? ?????????? ???????? ?????????? ?????????????????? ??????????????',
                    },
                    success: function (result) {
                        $('body').trigger('update_checkout');
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            });
            $('#bringo-conform-btn').click(function(){
                bringoModalConfirm.close();
                var comment1 = $('#bringo-home').val();
                var comment2 = $('#bringo-appartment').val();
                var comment3 = $('#bringo-time').val();
                var comment = '?????????? ????????: ' + comment1 + ', ?????????? ????????????????: ' + comment2 + ', ???????????????????????????????? ?????????? ????????????????: ' + comment3;
                $('#bringo-button-select').attr('data-comment', comment);
                $('#bringo-button-select2').attr('data-comment', comment);
                $('#bringo_user_comment').val(comment);
            });
            $('#changeMap').click(function(){
                $('#bringo-map').html('');
                bringoModal.close();
                bringoModal2.open();
                $('#the4-header').css('z-index', '-1');
                $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                $('#bringo-map').show();
                ymaps.ready(initMap);
            });
            $('#back-map').click(function(){
                bringoModal2.close();
                bringoModal.open();
            });
            function focusSuggest(){
                setTimeout(() => {
                    $('#suggest').focus();
                }, 100);
            }
        });
    </script>
   <?php
}
add_action( 'woocommerce_review_order_before_payment', 'bringo_script', 10, 0 );

function bringo_register_status( $order_statuses ){
    $order_statuses['wc-bringo-send'] = array(                                 
        'label' => _x( '?????????? ?? ???????????????? Bringo', 'Order status', 'woocommerce' ),
        'public' => false,                                 
        'exclude_from_search' => false,                                 
        'show_in_admin_all_list' => true,                                 
        'show_in_admin_status_list' => true,                                 
        'label_count' => _n_noop( '?????????? ?? ???????????????? Bringo <span class="count">(%s)</span>', '?????????? ?? ???????????????? Bringo <span class="count">(%s)</span>', 'woocommerce' ),                              
    );      
    return $order_statuses;
}
add_filter( 'woocommerce_register_shop_order_post_statuses', 'bringo_register_status' );

function bringo_show_status( $order_statuses ) {      
    $order_statuses['wc-bringo-send'] = _x( '?????????? ?? ???????????????? Bringo', 'Order status', 'woocommerce' );       
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'bringo_show_status' );

function bringo_getshow_status( $bulk_actions ) {
    $bulk_actions['mark_bringo-send'] = '???????????????? ???????????? ???? ?????????? ?? ???????????????? Bringo';
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-shop_order', 'bringo_getshow_status' );

function bringo_checkout_add( $checkout) {
    woocommerce_form_field( 'bringo_user_lng', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_lng'),
        ), $checkout->get_value( 'bringo_user_lng' ));
    
    woocommerce_form_field( 'bringo_user_lat', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_lat'),
        ), $checkout->get_value( 'bringo_user_lat' ));
    
    woocommerce_form_field( 'bringo_user_comment', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_comment'),
        ), $checkout->get_value( 'bringo_user_comment' ));
}
add_action( 'woocommerce_after_order_notes', 'bringo_checkout_add' );

function bringo_checkout_update( $order_id ) {
    if ( ! empty( $_POST['bringo_user_lng'] ) ) {
        update_post_meta( $order_id, 'bringo_client_lng', sanitize_text_field( $_POST['bringo_user_lng'] ) );
    }
    if ( ! empty( $_POST['bringo_user_lat'] ) ) {
        update_post_meta( $order_id, 'bringo_client_lat', sanitize_text_field( $_POST['bringo_user_lat'] ) );
    }
    if ( ! empty( $_POST['bringo_user_comment'] ) ) {
        update_post_meta( $order_id, 'bringo_user_comment', sanitize_text_field( $_POST['bringo_user_comment'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'bringo_checkout_update' );

add_action( 'woocommerce_after_shipping_rate', 'bringo_custom_fields', 20, 2 );
function bringo_custom_fields( $method, $index ) {
    if( ! is_checkout()) return;

    $clickbox_method_shipping = 'bringo';

    if( $method->id != $clickbox_method_shipping ) return;

    $chosen_method_id = WC()->session->chosen_shipping_methods[ $index ];

    if($chosen_method_id == $clickbox_method_shipping ):

        echo '<div class="clickbox-fields">';

        woocommerce_form_field( 'bringo_user_address' , array(
            'type'          => 'hidden',
            'class'         => array(),
            'required'      => true,
        ), WC()->checkout->get_value( 'bringo_user_address' ));

        echo '</div>';
    endif;
}

add_action('woocommerce_checkout_process', 'bringo_checkout_process');
function bringo_checkout_process() {
    if( isset( $_POST['bringo_user_address'] ) && empty( $_POST['bringo_user_address'] ) )
        wc_add_notice( esc_html__( '???????????????????? ???????????????? ?????????? ????????????????', 'clickbox' ), "error" );
}

add_action( 'woocommerce_checkout_update_order_meta', 'bringo_update_order_meta', 30, 1 );
function bringo_update_order_meta( $order_id ) {
    if( isset( $_POST['bringo_user_address'] ))
        update_post_meta( $order_id, 'bringo_client_address', sanitize_text_field( $_POST['bringo_user_address'] ) );
}

function bringo_order_sendpay( $order_id, $order ) {
    $order = new WC_Order( $order_id );
    $order_data = $order->get_data();
    $get_phone = $order_data['billing']['phone'];
    $user_phone = preg_replace("/[^,.0-9]/", '', $get_phone);
    $shipping_method = @array_shift($order->get_shipping_methods());
    $shipping_method_name = $shipping_method['method_id'];
    $shipping_method_price = $shipping_method['total'];
    $bringo_client_lng = $order->get_meta('bringo_client_lng');
    $bringo_client_lat = $order->get_meta('bringo_client_lat');
    $bringo_client_address = $order->get_meta('bringo_client_address');
    $bringo_user_comment = (string)$order->get_meta('bringo_user_comment');
    $products = array(
        "name" => '?????????? ???1',
        "weight" => '0.34',
        "height" => '10',
        "width" => '15',
        "length" => '20'
    );
    try {
        $url = "https://dev.bringo.uz/api/v1/merchant/createOrder";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $dataSend = array(
            "bringo_merchant_id" => 395,
            "payment_id" => 27,
            "merchant" => array(
                "lng" => "69.31330",
                "lat" => "41.32666",
                "merchant_phone_number" => "998712310881",
                "merchant_address" =>  "??????????????, ???????????? ?????????? ??????????",
                "workdays" => array(
                    "mon" => "09:00-18:00",
                    "tue" => "09:00-18:00",
                    "wed" => "09:00-18:00",
                    "thu" => "09:00-18:00",
                    "fri" => "09:00-18:00"
                ),
            ),
            "client" => array(
                "lng" => $bringo_client_lng,
                "lat" => $bringo_client_lat,
                "client_phone_number" => $user_phone,
                "user_address" => $bringo_client_address
            ),
            "items" => array(
                $products
            ),
            "total_price" => $order->get_subtotal(),
            "comment" => $bringo_user_comment
        );
        $data_string = json_encode($dataSend);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        $bringoRes = json_decode($resp);
        log_me($dataSend);
        log_me($bringoRes);
        update_post_meta($order_id, 'bringo_order_id', esc_attr($bringoRes->data->id));
        curl_close($curl);
    } catch (\Exception $exception) {
    }
}
add_action( 'woocommerce_order_status_bringo-send', 'bringo_order_sendpay', 20, 2 );


function bringo_get_status($order){
    $bringoorderid = $order->get_meta('bringo_order_id');
	$bringo_client_address = $order->get_meta('bringo_client_address');
    $bringo_user_comment = $order->get_meta('bringo_user_comment');
    if (!$bringoorderid) {
        $status_bringo = '?????? ???????????? ?? BRINGO';
    } else {
        echo '<strong>ID ???????????? BRINGO: </strong>' . $bringoorderid . '<br />';
        $url = file_get_contents("https://dev.bringo.uz/api/merchant/getOrder?order_id=" . $bringoorderid . "&merchant_id=395");
        $data = json_decode($url);
        echo '<strong>???????????? ???????????? BRINGO: </strong>' . $data->data->status_name;
    }
    echo '<br /><strong>?????????????? ?? ???????????? BRINGO: </strong>' . $bringo_user_comment;
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'bringo_get_status', 10, 1 );

add_filter( 'woocommerce_package_rates','conditional_custom_shipping_cost', 90, 2 );
function conditional_custom_shipping_cost( $rates, $package ) {
    if ( WC()->session->get('billing_ups' )){
        foreach ( $rates as $rate_key => $rate_values ) {
            if ( 'bringo' == $rate_values->method_id ) {
                $rates[$rate_key]->cost = WC()->session->get('billing_ups' );
            }
        }
    }
    return $rates;
}

add_action( 'woocommerce_checkout_update_order_review', 'refresh_shipping_methods', 10, 1 );
function refresh_shipping_methods( $post_data ){
    $bool = true;
    if ( WC()->session->get('billing_ups' )) $bool = false;
    foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
        WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
    }
    WC()->cart->calculate_shipping();
}

add_action( 'wp_ajax_woo_get_ajax_data', 'woo_get_ajax_data' );
add_action( 'wp_ajax_nopriv_woo_get_ajax_data', 'woo_get_ajax_data' );
function woo_get_ajax_data() {
    if ( $_POST['billing_ups']){
        WC()->session->set('billing_ups', $_POST['billing_ups'] );
    } else {
        WC()->session->set('billing_ups', '0' );
    }
    echo json_encode( WC()->session->get('billing_ups' ) );
    if ( $_POST['change_bringo']){
        WC()->session->set('change_bringo', $_POST['change_bringo'] );
    } else {
        WC()->session->set('change_bringo', '??????????????' );
    }
    if ( $_POST['bringo_lat']){
        WC()->session->set('bringo_lat', $_POST['bringo_lat'] );
    } else {
        WC()->session->set('bringo_lat', '' );
    }
    echo json_encode( WC()->session->get('bringo_lat' ) );
    if ( $_POST['bringo_lng']){
        WC()->session->set('bringo_lng', $_POST['bringo_lng'] );
    } else {
        WC()->session->set('bringo_lng', '' );
    }
    echo json_encode( WC()->session->get('bringo_lng' ) );
    if ( $_POST['bringo_address']){
        WC()->session->set('bringo_address', $_POST['bringo_address'] );
    } else {
        WC()->session->set('bringo_address', '' );
    }
    echo json_encode( WC()->session->get('bringo_address' ) );
    die();
}