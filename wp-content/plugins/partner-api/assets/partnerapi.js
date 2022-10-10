jQuery( document ).ready( function(jQuery) {
    var phoneDefault = jQuery('#billing_phone').val();
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
    let partnerapi12 = currentLang == 'uz' ? 'Sahifani yangilab boshidan urinib ko\'ring' : 'Обновите страницу и попробуйте еще';
    let partnerapi13 = currentLang == 'uz' ? 'Karta qo\'shing' : 'Добавьте карту';

    jQuery('#partnerapi_phone').val(phoneDefault);
    jQuery(document).on('click', '#papi-noauth-givecode', function() {
        var card_number = jQuery('#partnerapi_ccNo').val();
        var card_num = card_number.replace(/\D/g,'');
        var card_date_num = jQuery('#partnerapi_expdate').val();
        var card_date = card_date_num.replace(/\D/g,'');
        var card_phone_num = jQuery('#partnerapi_phone').val();
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
            tokenRequest(card_num, card_date, card_phone);
        }
    });
    jQuery(document).on('click', '#papi-noauth-confirmcode', function() {
        var token_id = jQuery('.papi').data('token');
        var verify_code = jQuery('#partnerapi_code').val();
        var cards = jQuery('.papi').data('cards');
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
            tokenRequestVerify(token_id, verify_code, cards);
        }
    });
    jQuery(document).on('click', '#papi-noauth-otheradd', function() {
        useOtherCard();
    });
    jQuery(document).on('click', '#papi-auth-givecode', function() {
        var card_number = jQuery('#partnerapi_ccNo').val();
        var card_num = card_number.replace(/\D/g,'');
        var card_date_num = jQuery('#partnerapi_expdate').val();
        var card_date = card_date_num.replace(/\D/g,'');
        var card_phone_num = jQuery('#partnerapi_phone').val();
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
            tokenRequestAuth(card_num, card_date, card_phone);
        }
    });
    jQuery(document).on('click', '#papi-auth-confirmcode', function() {
        var token_id = jQuery('.papi').data('token');
        var verify_code = jQuery('#partnerapi_code').val();
        var cards = jQuery('.papi').data('cards');
        var saved = 0;
        if (jQuery('#papi-form-saved').is(':checked')){
            saved = 1;
        }
		var check_save = jQuery('.papi').data('check');
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
                text: partnerapi6,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
        } else {
            tokenRequestVerifyAuth(token_id, verify_code, cards, saved, check_save);
        }
    });
    jQuery(document).on('click', '#papi-auth-use', function() {
        useCards();
    });
    jQuery(document).on('click', '#papi-auth-newadd', function() {
        jQuery('.papi').attr('data-step', 'papi-create');
    });
    var tokenRequest = function(card_num, card_date, card_phone) {
        jQuery('#papi-noauth-givecode').addClass('the4-loading');
        jQuery('#papi-noauth-givecode').prop('disabled', true);
        jQuery('#karta_id').val('');
        jQuery.post('/wp-admin/admin-ajax.php', {
            'action': 'token_request_create',
            'params': {
                'cardNumber': card_num,
                'expireDate': card_date,
                'phoneNumber': card_phone
            }
        }, function (response) {
            jQuery('#papi-noauth-givecode').removeClass('the4-loading');
            jQuery('#papi-noauth-givecode').prop('disabled', false);
            jQuery('#partnerapi_ccNo').val('');
            jQuery('#partnerapi_expdate').val('');
            jQuery('#partnerapi_phone').val('');
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
                jQuery('.papi').attr('data-step', 'papi-create');
                jQuery('.papi').attr('data-cards', '');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
                jQuery('#karta_id').val('');
            } else {
                var token_id = response.result.token_id;
                jQuery('.papi').attr('data-token', token_id);
                jQuery('.papi').attr('data-step', 'papi-verify');
                if(response.result.card_token_id){
                    var cards = response.result.card_token_id;
                    var cardsLists = [];
                    jQuery.each(cards, function( key, value ) {
                        cardsLists.push(value.token_id);
                    });
                    jQuery('.papi').attr('data-cards', cardsLists);
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
                jQuery('#partnerapi_code').focus();
            }
        }, 'json');
        return false; 
    };
    var tokenRequestVerify = function(token_id, verify_code, cards) {
        jQuery('#papi-noauth-confirmcode').addClass('the4-loading');
        jQuery('#papi-noauth-confirmcode').prop('disabled', true);
        jQuery.post('/wp-admin/admin-ajax.php', {
            'action': 'token_request_verify',
            'params': {
                'tokenId': token_id,
                'verifyCode': verify_code,
                'cards': cards
            }
        }, function (response) {
            jQuery('#papi-noauth-confirmcode').removeClass('the4-loading');
            jQuery('#papi-noauth-confirmcode').prop('disabled', false);
            jQuery('#partnerapi_code').val('');
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
                jQuery('.papi').attr('data-step', 'papi-create');
                jQuery('.papi').attr('data-cards', '');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
                jQuery('#karta_id').val('');
            } else {
                jQuery('body').trigger('update_checkout');
                jQuery('.papi').attr('data-step', 'papi-confirm');
                jQuery('#karta_id').val(response.result.token_id);
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
    var useOtherCard = function() {
        jQuery('#papi-noauth-otheradd').addClass('the4-loading');
        jQuery('#papi-noauth-otheradd').prop('disabled', true);
        jQuery.post('/wp-admin/admin-ajax.php', {
            'action': 'use_other_card'
        }, function (response) {
            jQuery('#papi-noauth-otheradd').removeClass('the4-loading');
            jQuery('#papi-noauth-otheradd').prop('disabled', false);
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
                jQuery('.papi').attr('data-step', 'papi-create');
            } else {
                jQuery('body').trigger('update_checkout');
                jQuery('.papi').attr('data-step', 'papi-create');
                jQuery('.papi').attr('data-cards', '');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
                jQuery('#karta_id').val('');
            }
        }, 'json');
        return false; 
    };
    var tokenRequestAuth = function(card_num, card_date, card_phone) {
        jQuery('#papi-auth-givecode').addClass('the4-loading');
        jQuery('#papi-auth-givecode').prop('disabled', true);
        jQuery('#karta_id').val('');
        jQuery.post('/wp-admin/admin-ajax.php', {
            'action': 'token_request_auth_create',
            'params': {
                'cardNumber': card_num,
                'expireDate': card_date,
                'phoneNumber': card_phone
            }
        }, function (response) {
            jQuery('#papi-auth-givecode').removeClass('the4-loading');
            jQuery('#papi-auth-givecode').prop('disabled', false);
            jQuery('#partnerapi_ccNo').val('');
            jQuery('#partnerapi_expdate').val('');
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
                jQuery('.papi').attr('data-step', 'papi-create');
                jQuery('.papi').attr('data-cards', '');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
                jQuery('#karta_id').val('');
            } else {
                var token_id = response.result.token_id;
                var if_save = response.result.if_save;
                var check_save = response.result.check_save;
                jQuery('.papi').attr('data-token', token_id);
                jQuery('.papi').attr('data-usersaved', if_save);
                jQuery('.papi').attr('data-step', 'papi-verify');
                jQuery('.papi').attr('data-check', check_save);
                if(response.result.card_token_id){
                    var cards = response.result.card_token_id;
                    var cardsLists = [];
                    jQuery.each(cards, function( key, value ) {
                        cardsLists.push(value.token_id);
                    });
                    jQuery('.papi').attr('data-cards', cardsLists);
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
                jQuery('#partnerapi_code').focus();
            }
        }, 'json');
        return false; 
    };
    var tokenRequestVerifyAuth = function(token_id, verify_code, cards, saved, check_save) {
        jQuery('#papi-auth-confirmcode').addClass('the4-loading');
        jQuery('#papi-auth-confirmcode').prop('disabled', true);
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
            jQuery('#papi-auth-confirmcode').removeClass('the4-loading');
            jQuery('#papi-auth-confirmcode').prop('disabled', false);
            jQuery('#partnerapi_code').val('');
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
                jQuery('.papi').attr('data-step', 'papi-create');
                jQuery('.papi').attr('data-cards', '');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
                jQuery('#karta_id').val('');
            } else {
                jQuery('body').trigger('update_checkout');
                jQuery('.papi').attr('data-step', 'papi-confirm');
                jQuery('#karta_id').val(response.result.token_id);
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
    var useCards = function() {
        jQuery('#papi-auth-use').addClass('the4-loading');
        jQuery('#papi-auth-use').prop('disabled', true);
        jQuery.post('/wp-admin/admin-ajax.php', {
            'action': 'papi_auth_use'
        }, function (response) {
            jQuery('#papi-auth-use').removeClass('the4-loading');
            jQuery('#papi-auth-use').prop('disabled', false);
            jQuery('body').trigger('update_checkout');
            if(response.error){
                jQuery.toast({
                    heading: partnerapi1,
                    text: partnerapi12,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            } else if(response.result.status == 'not') {
                jQuery.toast({
                    heading: partnerapi13,
                    text: response.result.message,
                    showHideTransition: 'slide',
                    bgColor: '#7f8c8d',
                    textColor: '#fff',
                    loaderBg: '#3C4242',
                    icon: 'info'
                });
                jQuery('.papi').attr('data-step', 'papi-saveds');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
            } else {
                jQuery('.papi').attr('data-step', 'papi-saveds');
                jQuery('.papi').attr('data-token', '');
                jQuery('.papi').attr('data-cardnumber', '');
            }
        }, 'json');
        return false; 
    }
});