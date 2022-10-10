jQuery(function ($) {
    var isLoading = false;    
    $('.click-login-btn').removeClass('the4-loading');
    $('.auth-sidebar-btn').removeClass('the4-loading');
    $('.click-login-btn').prop('disabled', false);
    $('.auth-sidebar-btn').prop('disabled', false);
	$('.the4-login-register').click(function(){
		$('.click-login').find('#phone-number').focus();
	});
    var currentLang = $('#currentLang').data('lang');
    let text1 = currentLang == 'uz' ? 'Xato' : 'Ошибка';
    let text2 = currentLang == 'uz' ? 'Telefon raqamingizni kiriting' : 'Заполните ваш номер телефона';
    let text3 = currentLang == 'uz' ? 'Noto\'g\'ri telefon raqam' : 'Неверный номер телефона';
    let text4 = currentLang == 'uz' ? 'Parol' : 'Пароль';
    let text5 = currentLang == 'uz' ? 'Akkaunt parolini kiriting' : 'Введите пароль от аккаунта';
    let text6 = currentLang == 'uz' ? 'Kod' : 'Код';
    let text7 = currentLang == 'uz' ? 'Tasdiqlash kodni kiriting' : 'Введите код подтверждения';
    let text8 = currentLang == 'uz' ? 'Tasdiqlash' : 'Подтвердить';
    let text9 = currentLang == 'uz' ? 'Telefon raqam topilmadi' : 'Номер телефона не найден';
    let text10 = currentLang == 'uz' ? 'Ro\'yxatdan o\'tish' : 'Регистрация';
    let text11 = currentLang == 'uz' ? 'Parol va ismingizni kiriting' : 'Введите пароль и имя';
    let text12 = currentLang == 'uz' ? 'Qayta tiklash' : 'Восстановить';
    let text13 = currentLang == 'uz' ? 'Parol kiriting' : 'Введите пароль';
    let text14 = currentLang == 'uz' ? 'Parol va uning tasdiqlanishi bir xil emas' : 'Пароль и его подтверждение не совпадает';
    let text15 = currentLang == 'uz' ? 'SMS-servis tizimda texnik xato.' : 'Ошибка в сервисе СМС-шлюза';
    var check_phone = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        if (phone.toString().trim() === '') {
            $.toast({
                heading: text1,
                text: text2,
                showHideTransition: 'slide',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }
        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");
        if (!regex.test(phone.toString().trim())) {
            $.toast({
                heading: text1,
                text: text3,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }
        if (phone.length === 12) {
            isLoading = true;
            $('.click-login-btn').addClass('the4-loading');
            $('.auth-sidebar-btn').addClass('the4-loading');
            $('.click-login-btn').prop('disabled', true);
            $('.auth-sidebar-btn').prop('disabled', true);
            $.post( '/wp-admin/admin-ajax.php', {
                'action': 'check_phone',
                'params': {
                    'phone_number': phone,
					'lang': currentLang
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                $form.addClass(response['status']);
                if (response['status'] == 'registered') {
                    $form.attr('data-step', 'check-password');
                    $form.attr('data-key', '');
                    $.toast({
                        heading: text4,
                        text: text5,
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#password').focus();
                } else {
                    if (response['result']) {
                        if(response['result']['error_code'] == 0){
                            var _Seconds = $form.find('#auth-timer').text();
                            var int;
                            $form.attr('data-step', 'check-otp');
                            $.toast({
                                heading: text6,
                                text: text7,
                                showHideTransition: 'slide',
                                bgColor: '#7f8c8d',
                                textColor: '#fff',
                                loaderBg: '#3C4242',
                                icon: 'info'
                            });
                            $form.find('#confirmation-code').focus();
                            $form.find('#phone-number').attr('readonly', 'readonly');
                            $form.find('[type=submit]').text(text8);
                            $form.attr('data-key', response.key);
                            int = setInterval(function() {
                                if (_Seconds > 0) {
                                    _Seconds--;
                                    $form.find('#auth-timer').text(_Seconds);
                                } else {
                                    clearInterval(int);
                                    $form.find('.auth-conf-timer').hide();
                                    $form.find('#auth-repeat').show();
                                }
                            }, 1000);
                        } else {
                            $.toast({
                                heading: text1,
                                text: text15,
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                            $form.attr('data-step', 'check-phone');
                            $form.attr('data-key', '');
                        }
                    } else {
                        if(response.error.message){
                            $.toast({
                                heading: text1,
                                text: response.error.message,
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                        } else {
                            $.toast({
                                heading: text1,
                                text: text15,
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                        }
                        $form.attr('data-step', 'check-phone');
                        $form.attr('data-key', '');
                    }
                }
            }, 'json');
        }
    };
    var check_otp = function ($form) {
        var sms_code = $form.find('#confirmation-code').val();
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var key = $form.data('key');
        if (sms_code.toString().trim() == '') {
            $.toast({
                heading: text1,
                text: text7,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#confirmation-code').focus();
            return false;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post('/wp-admin/admin-ajax.php', {
            'action': 'check_otp',
            'params': {
                'sms_code': sms_code,
                'key': key,
                'phone': phone
            }
        },
        function (response) {
            isLoading = false;
            $('.click-login-btn').removeClass('the4-loading');
            $('.auth-sidebar-btn').removeClass('the4-loading');
            $('.click-login-btn').prop('disabled', false);
            $('.auth-sidebar-btn').prop('disabled', false);
            if (!response['error']) {
                $form.attr('data-step', 'register');
                $.toast({
                    heading: text10,
                    text: text11,
                    showHideTransition: 'slide',
                    bgColor: '#7f8c8d',
                    textColor: '#fff',
                    loaderBg: '#3C4242',
                    icon: 'info'
                });
                $form.find('#password').focus();
                $form.find('[type=submit]').text(text10);
                $form.attr('data-key', '');
                $form.find('#auth-repeat').hide();
            } else {
                $.toast({
                    heading: text1,
                    text: response.error,
                    showHideTransition: 'slide',
                    bgColor: '#E74C3C',
                    textColor: '#fff',
                    loaderBg: '#9A3328',
                    icon: 'error'
                });
            }
        }, 'json');
    };
    var check_password = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        if (password.toString().trim() == '') {
            $.toast({
                heading: text1,
                text: text5,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return false;
        }

        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_auth',
                'params': {
                    'phone_number': phone,
                    'password': password
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.reload();
                } else {
                    $.toast({
                        heading: text1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error',
                        hideAfter: false
                    });
                }
            },
            'json');

    };
    var register = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        var confirmation = $form.find('#password_confirmation').val();
        var display_name = $form.find('#display_name').val();
        if (password !== confirmation) {
            $.toast({
                heading: text1,
                text: text14,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_register',
                'params': {
                    'phone_number': phone,
                    'password': password,
                    'display_name': display_name
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.reload();
                } else {
                    $.toast({
                        heading: text1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };
    var check_reset_phone = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        if (phone.toString().trim() === '') {
            $.toast({
                heading: text1,
                text: text2,
                showHideTransition: 'slide',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }
        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");
        if (!regex.test(phone.toString().trim())) {
            $.toast({
                heading: text1,
                text: text3,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }
        if (phone.length === 12) {
            isLoading = true;
            $('.click-login-btn').addClass('the4-loading');
            $('.click-login-btn').prop('disabled', true);
            $.post('/wp-admin/admin-ajax.php', {
                'action': 'check_reset_phone',
                'params': {
                    'phone_number': phone,
					'lang': currentLang
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                $form.addClass(response['status']);
                if (response['status'] == 'registered') {
                    var _Seconds = $form.find('#auth-timer').text();
                    var int;
                    $form.attr('data-step', 'check-reset-otp');
                    $.toast({
                        heading: text6,
                        text: text7,
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#confirmation-code').focus();
                    $form.find('#phone-number').attr('readonly', 'readonly');
                    $form.find('[type=submit]').text(text8);
                    $form.attr('data-key', response.key);
                    int = setInterval(function() {
                        if (_Seconds > 0) {
                            _Seconds--;
                            $form.find('#auth-timer').text(_Seconds);
                        } else {
                            clearInterval(int);
                            $form.find('.auth-conf-timer').hide();
                            $form.find('#auth-repeat').show();
                        }
                    }, 1000);
                } else {
                    if (!response['error']) {
                        $.toast({
                            heading: text1,
                            text: text9,
                            showHideTransition: 'slide',
                            bgColor: '#E74C3C',
                            textColor: '#fff',
                            loaderBg: '#9A3328',
                            icon: 'error'
                        });
                    } else {
                        $.toast({
                            heading: text1,
                            text: response.error.message,
                            showHideTransition: 'slide',
                            bgColor: '#E74C3C',
                            textColor: '#fff',
                            loaderBg: '#9A3328',
                            icon: 'error'
                        });
                    }
                }
            }, 'json');
        }
    };
    var check_reset_otp = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var sms_code = $form.find('#confirmation-code').val();
        var key = $form.data('key');
        if (sms_code.toString().trim() == '') {
            $.toast({
                heading: text1,
                text: text7,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#confirmation-code').focus();
            return false;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'check_otp',
                'params': {
                    'phone': phone,
                    'sms_code': sms_code,
                    'key': key
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                if (!response['error']) {
                    $form.attr('data-step', 'reset');
                    $.toast({
                        heading: text12,
                        text: text13,
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#password').focus();
                    $form.find('[type=submit]').text(text12);
                    $form.attr('data-key', '');
                    $form.find('#auth-repeat').hide();
                } else {
                    $.toast({
                        heading: text1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
        'json');
    };
    var reset = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        var confirmation = $form.find('#password_confirmation').val();
        var display_name = $form.find('#display_name').val();
        if (password !== confirmation) {
            $.toast({
                heading: text1,
                text: text14,
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_reset',
                'params': {
                    'phone_number': phone,
                    'password': password,
                    'display_name': display_name
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.replace("https://market.click.uz/");
                } else {
                    $.toast({
                        heading: text1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };
    var repeat = function($form){
        $form.find('#auth-repeat').hide();
        $form.find('#auth-timer').text(59);
        $form.find('.auth-conf-timer').show();
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        $.post( '/wp-admin/admin-ajax.php', {
            'action': 'check_phone',
            'params': {
                'phone_number': phone
            }
        },
        function (response) {
            if (response['result']) {
                if(response['result']['error_code'] == 0){
                    var _Seconds = 59;
                    var int;
                    $.toast({
                        heading: text6,
                        text: text7,
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#confirmation-code').focus();
                    $form.attr('data-key', response.key);
                    int = setInterval(function() {
                        if (_Seconds > 0) {
                            _Seconds--;
                            $form.find('#auth-timer').text(_Seconds);
                        } else {
                            clearInterval(int);
                            $form.find('.auth-conf-timer').hide();
                            $form.find('#auth-repeat').show();
                        }
                    }, 1000);
                } else {
                    $.toast({
                        heading: text1,
                        text: text15,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                    $form.attr('data-step', 'check-phone');
                }
            } else {
                if(response.error.message){
                    $.toast({
                        heading: text1,
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                } else {
                    $.toast({
                        heading: text1,
                        text: text15,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
                $form.attr('data-step', 'check-phone');
            }
        }, 'json');
    };
    $(function () {
        $('.click-login #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');
        $('.click-reset #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');
		$('#change-number').click(function(){
            $('form.click-login').attr('data-step', 'check-phone');
        });
		$('#change-number-otp').click(function(){
            $('form.click-login').attr('data-step', 'check-phone');
        });
        $('#auth-repeat').click(function(){
            $form = $(this).parent('.click-login');
            repeat($form);
        });
        $('form.click-login').on('submit', function () {   
            var $form = $(this);
            var step = $form.attr('data-step');
            switch (step) {
                case 'check-phone':
                    check_phone($form);
                    break;
                case 'check-otp':
                    check_otp($form);
                    break;
                case 'check-password':
                    check_password($form);
                    break;
                case 'register':
                    register($form);
                    break;
            }
            return false;
        });
        $('form.click-reset').on('submit', function () {   

            var $form = $(this);

            var stepReset = $form.attr('data-step');

            switch (stepReset) {
                case 'check-reset-phone':
                    check_reset_phone($form);
                    break;
                case 'check-reset-otp':
                    check_reset_otp($form);
                    break;
                case 'reset':
                    reset($form);
                    break;
            }

            return false;
        });
    });
});