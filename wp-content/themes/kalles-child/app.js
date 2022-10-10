(function($) {
    $(window).on('load', function(){
//         function hideClickbox(){
//             $('.selectBox').hide();
//             $('#create-order').hide();
//         }
//         function showClickbox(){
//             $('.selectBox').show();
//             $('#create-order').show();
//         }
//         let regionLoad;
//         $( "#billing_state option:selected").each(function() {
//             regionLoad = $(this).val();
//         });
//         if(regionLoad === '01') {
//             showClickbox();
//         } else {
//             hideClickbox();
//         }

//         $(document.body).on('change', 'select[name=billing_state]', function(){
//             shipMethodLoading();
//         });

//         function shipMethodLoading(){
//             let regionLoadChange;
//             $( "#billing_state option:selected").each(function() {
//                 regionLoadChange = $(this).val();
//             });
//             if(regionLoadChange === '01') {
//                 showClickbox();
//             } else {
//                 hideClickbox();
//             }
//         }
        let allFilter = $('.the4-filter-wrap').find('.wrap_filter>.col-12>.widget-title');
        $.each(allFilter, function(index, value){
            if(value.outerText == 'по Цвет для фильтров'){
                $(this).text("По цвету")
            }
            if(value.outerText == 'по Размер'){
                $(this).text('По размеру')
            }
            if(value.outerText == 'по Цена'){
                $(this).text('По цене')
            }
			if(value.outerText == "bo'yicha Narxi"){
                $(this).text("Narx bo'yicha");
            }
			if(value.outerText == "bo'yicha Размер"){
                $(this).text("O'lcham bo'yicha");
            }
			if(value.outerText == "bo'yicha Цвет для фильтров"){
                $(this).text("Rang bo'yicha");
            }
        });
        $('.the4-banner a').removeAttr('target');
//         $( 'form.checkout' ).on( 'change', 'input[name^="shipping_method"]', function () {
//             var val = jQuery( this ).val();
//             if ( val.match( "^clickbox" ) ) {
//                 $('.selectBox').show();
//                 $('#create-order').show();
//             } else {
//                 $('.selectBox').hide();
//                 $('#create-order').hide();
//             }
//         } );
		var window_w = $(window).width();
		var cat_menu = $('#the4-mobile-menu__cat')
		var initDropdownMenuCat = function() {
        	$( '#the4-mobile-menu__cat ul li.has-sub' ).append( '<span class="holder"></span>' );
			$( cat_menu ).on('click','.holder', function() {
				var el = $( cat_menu ).closest( 'li' );
				if ( el.hasClass( 'open' ) ) {
					el.removeClass( 'open' );
					el.find( 'li' ).removeClass( 'open' );
					el.find( 'ul' ).slideUp();
				} else {
					el.addClass( 'open' );
					el.children( 'ul' ).slideDown();
					el.siblings( 'li' ).children( 'ul' ).slideUp();
					el.siblings( 'li' ).removeClass( 'open' );
					el.siblings( 'li' ).find( 'li' ).removeClass( 'open' );
					el.siblings( 'li' ).find( 'ul' ).slideUp();
				}
			});
    	}
		if ( window_w < 768 ) {
            initDropdownMenuCat();
        }
		
		$('#cardformadd_cc').inputmask('9999 9999 9999 9999');
		$('#cardformadd_ex').inputmask('99/99');
		$('#cardformadd_phone').inputmask('\\9\\9\\8 (99) 999-99-99');
		$('#cardformadd_code').inputmask('99999');
		
		var currentLang = jQuery('#currentLang').data('lang');
		let partnerapi1 = currentLang == 'uz' ? 'Xato' : 'Ошибка';
		let partnerapi2 = currentLang == 'uz' ? 'Kartangiz raqamini kiriting' : 'Введите номер своей карты';
		let partnerapi3 = currentLang == 'uz' ? 'Noma\'lum turdagi karta' : 'Неизвестный тип карты';
		let partnerapi4 = currentLang == 'uz' ? 'Karta muddatini kiriting' : 'Введите срок истечения карты';
		let partnerapi5 = currentLang == 'uz' ? 'Telefon raqamingizni kiriting' : 'Введите номер телефона';
		let partnerapi6 = currentLang == 'uz' ? 'Telefon raqamingiz notog\'ri' : 'Номер телефона не верный';
		let partnerapi7 = currentLang == 'uz' ? 'Tasdiqlash kodini kiriting' : 'Введите код подтверждения';
		let partnerapi8 = currentLang == 'uz' ? 'Tasdiqlash kodi notog\'ri' : 'Код подтверждения не верный';
		let partnerapi9 = currentLang == 'uz' ? 'Kod' : 'Код';
		let partnerapi10 = currentLang == 'uz' ? 'Muvvafiqiyatli' : 'Успешно';
		let partnerapi11 = currentLang == 'uz' ? 'Karta ulandi' : 'Карта привязана';
		let partnerapi12 = currentLang == 'uz' ? 'Karta o\'chirildi' : 'Карта удалена';
		
		jQuery(document).on('click', '#card-form-add-getcode', function() {
            var card_number = jQuery('#cardformadd_cc').val();
            var card_num = card_number.replace(/\D/g,'');
            var card_date_num = jQuery('#cardformadd_ex').val();
            var card_date = card_date_num.replace(/\D/g,'');
            var card_phone_num = jQuery('#cardformadd_phone').val();
            var card_phone = card_phone_num.replace(/\D/g,'');
            var regex_phone = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");
            if(card_num.toString().trim() === ''){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi2,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(!card_num.length === 12){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi3,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(card_date.toString().trim() === ''){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi4,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(card_phone.toString().trim() === ''){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi5,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(!regex_phone.test(card_phone.toString().trim())){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi6,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else {
                tokenRequestCabinet(card_num, card_date, card_phone);
            }
        });
        var tokenRequestCabinet = function(card_num, card_date, card_phone) {
            jQuery('#cardformadd_code-confirmcode').addClass('the4-loading');
            jQuery('#cardformadd_code-confirmcode').prop('disabled', true);
            jQuery.post('/wp-admin/admin-ajax.php', {
                'action': 'token_request_auth_create',
                'params': {
                    'cardNumber': card_num,
                    'expireDate': card_date,
                    'phoneNumber': card_phone
                }
            }, function (response) {
                jQuery('#cardformadd_code-confirmcode').removeClass('the4-loading');
                jQuery('#cardformadd_code-confirmcode').prop('disabled', false);
                jQuery('#cardformadd_cc').val('');
                jQuery('#cardformadd_ex').val('');
                if(response.error){
                    jQuery.toast({
                        heading: partnerapi1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                    jQuery('.card-form-add').attr('data-step', 'create');
                    jQuery('.card-form-add').attr('data-cards', '');
                    jQuery('.card-form-add').attr('data-token', '');
                    jQuery('.card-form-add').attr('data-cardnumber', '');
                } else {
                    var token_id = response.result.token_id;
                    var if_save = response.result.if_save;
                    var check_save = response.result.check_save;
                    jQuery('.card-form-add').attr('data-token', token_id);
                    jQuery('.card-form-add').attr('data-usersaved', if_save);
                    jQuery('.card-form-add').attr('data-step', 'verify');
                    jQuery('.card-form-add').attr('data-check', check_save);
                    console.log(check_save);
                    if(response.result.card_token_id){
                        var cards = response.result.card_token_id;
                        var cardsLists = [];
                        jQuery.each(cards, function( key, value ) {
                            cardsLists.push(value.token_id);
                        });
                        jQuery('.card-form-add').attr('data-cards', cardsLists);
                    }
                    jQuery.toast({
                        heading: partnerapi9,
                        text: partnerapi7,
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    jQuery('#cardformadd_code').focus();
                }
            }, 'json');
            return false; 
        };
        jQuery(document).on('click', '#cardformadd_code-confirmcode', function() {
            var token_id = jQuery('.card-form-add').data('token');
            var verify_code = jQuery('#cardformadd_code').val();
            var cards = jQuery('.card-form-add').data('cards');
            var check_save = jQuery('.card-form-add').data('check');
            if(verify_code.toString().trim() === ''){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi7,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(!verify_code.length === 5){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi8,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else {
                tokenRequestVerifyCabinet(token_id, verify_code, cards, 1, check_save);
            }
        });
        var tokenRequestVerifyCabinet = function(token_id, verify_code, cards, saved, check_save) {
            jQuery('#cardformadd_code-confirmcode').addClass('the4-loading');
            jQuery('#cardformadd_code-confirmcode').prop('disabled', true);
            jQuery.post('/wp-admin/admin-ajax.php', {
                'action': 'token_request_auth_verify',
                'params': {
                    'tokenId': token_id,
                    'verifyCode': verify_code,
                    'cards': cards,
                    'saved': saved,
                    'check': check_save
                }
            }, function (response) {
                jQuery('#cardformadd_code-confirmcode').removeClass('the4-loading');
                jQuery('#cardformadd_code-confirmcode').prop('disabled', false);
                if(response.error){
                    jQuery.toast({
                        heading: partnerapi1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                    jQuery('.card-form-add').attr('data-step', 'create');
                    jQuery('.card-form-add').attr('data-cards', '');
                    jQuery('.card-form-add').attr('data-token', '');
                    jQuery('.card-form-add').attr('data-cardnumber', '');
                } else {
                    jQuery('body').trigger('update_checkout');
                    jQuery('.card-form-add').attr('data-step', 'confirm');
                    jQuery.toast({
                        heading: partnerapi10,
                        text: partnerapi11,
                        showHideTransition: 'slide',
                        bgColor: '#0c9043',
                        textColor: '#fff',
                        loaderBg: '#2ecc71',
                        icon: 'success'
                    });
                }
            }, 'json');
            return false; 
        };
		$(document).on('click', '#delete-partner-card', function() {
			var partner_card_id = $(this).parent().parent().find('#partner-card-id').text();
			if(partner_card_id.length >= 1){
				tokenRequestDeleteCabinet(partner_card_id);
			}
		});
		var tokenRequestDeleteCabinet = function(partner_card_id) {
			$('#delete-partner-card').addClass('the4-loading');
			$('#delete-partner-card').prop('disabled', true);
			$.post('/wp-admin/admin-ajax.php', {
				'action': 'token_request_delete_cabinet',
				'params': {
					'card_id': partner_card_id,
				}
			}, function (response) {
				$('#delete-partner-card').removeClass('the4-loading');
				$('#delete-partner-card').prop('disabled', false);
				if(response.error){
					$.toast({
						heading: partnerapi1,
						text: response.error.message,
						showHideTransition: 'slide',
						bgColor: '#E74C3C',
						textColor: '#fff',
						loaderBg: '#9A3328',
						icon: 'error'
					});
				} else {
					$.toast({
						heading: partnerapi10,
						text: partnerapi12,
						showHideTransition: 'slide',
						bgColor: '#0c9043',
						textColor: '#fff',
						loaderBg: '#2ecc71',
						icon: 'success'
					});
					location.reload();
				}
			}, 'json');
			return false; 
		};
    });
})(jQuery);