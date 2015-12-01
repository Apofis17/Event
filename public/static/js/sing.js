$(function initialize() {
    var myLatlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
        zoom: 8,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);
});

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function isApply(str) {
    var username = getCookie('gatsbu');
    var password = '';
    if (str != 'out') {
        username = $('#username_' + str).val();
        password = $('#password_' + str).val();
        var status = $('.status_' + str);
        status.removeClass('error').removeClass('ok').text('');
        if (username == '' || password == '') {
            status.removeClass('display_none');
            status.addClass('error').removeClass('ok').text('* Нужно заполнить все поля');
            return;
        }
        if (str == 'up') {
            var password2 = $('#password2').val();
            if (password2 != password) {
                status.removeClass('display_none');
                status.addClass('error').removeClass('ok').text('* Пароли должны совпадать');
                return;
            }
        }
    }
    $.ajax({
        url: '/sing/' + str + '/',
        type: 'get',
        data: {username: username, password: password},
        success: function (request) {
            var data = eval("(" + request + ")");
            if (data.status == 'error') {
                status.removeClass('display_none');
                if (data.code == '1') {
                    status.addClass('error').removeClass('ok').text('*Логин занят. Ввелите Другой!');
                } else if (data.code == '2') {
                    status.addClass('error').removeClass('ok').text('*Пользователь не найдет!');
                } else if (data.code == '3') {
                    status.addClass('error').removeClass('ok').text('*Пароль или логин не верный!');
                }
                else {
                    window.location.href = '/error/?code=' + data.code;
                }
            } else if (data.status == 'ok') {
                if (data.code == '0') {
                    status.removeClass('display_none');
                    status.addClass('ok').removeClass('error')
                        .text('Регистрация прошла успешно. Авторизуйтесь!');
                    return;
                }
                if (data.code == '1') {
                    var sol = data.args.gatsbu;
                    var date = new Date(new Date().getTime() + 2592000 * 1000);
                    document.cookie = "gatsbu=" + sol + "; path=/; expires=" + date.toUTCString();
                }
                if (data.code == '2') {
                    time = new Date(0);
                    document.cookie = "gatsbu=; path=/; expires=" + time.toUTCString();
                    document.cookie = "PHPSESSID=; path=/; expires=" + time.toUTCString();
                }
                window.location.href = '/';
            }
        }
    })
}

function goHref(url) {
    window.location.href = url;
}