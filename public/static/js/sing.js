var map;
var youPosition = [];
var markers = {};
var images = [];
var now;
var last;
function position() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function pos(position) {
            youPosition[0] = position.coords.latitude;
            youPosition[1] = position.coords.longitude;
            console.log(youPosition);
            initialize();
        });
    } else {
        youPosition[0] = 0;
        youPosition[1] = 0;
        initialize();
    }
}
$(function () {
    console.log(allEvents);
    position();
    $('#xs').on('click', '.one-img', function(){
        var id = $(this).attr('data');
        $('[data='+now+']').removeClass('visi');
        $($('#carousel').children('img')).attr('src',images[id]);
        $(this).addClass('visi');
        now = Number(id);
    });
    $('.left').on('click', function(){
        $('[data='+now+']').removeClass('visi');
        id = now == 0 ? images.length - 1 : now - 1;
        $($('#carousel').children('img')).attr('src', images[id]);
        $('[data='+id+']').addClass('visi')
        now = id;
    });
    $('.right').on('click', function(){
        $('[data='+now+']').removeClass('visi');
        id = now == images.length - 1 ? 0 : now + 1;
        $($('#carousel').children('img')).attr('src', images[id]);
        $('[data='+id+']').addClass('visi');
        now = id;
    });
    $('#close_images').on('click', function(){
        $($('#carousel').children('img')).remove();
        $($('#xs').children()).remove();
        now = 0;
        images = []
    });
    $('#map').on('click', '#event_img',function() {
        var id = $(this).attr('data');
        var event_id = allEvents[id].id;
        $.ajax({
            url: '/imgEvent/',
            type: 'POST',
            data: {id: event_id},
            success: function (request) {
                var data = eval('(' + request + ')');
                console.log(data);
                if(data.status == 'error'){
                    if(data.code == '000'){
                        window.location.href = '/error/?'+data.code;
                    }
                    else{

                    }
                }else{
                    var img= document.createElement('img');
                    $(img).attr('src', data.attr[0].image);
                    $('#carousel').append(img);
                    now = 0;
                    for(var i in data.attr){
                        var div = document.createElement('div');
                        $(div).attr('class', 'one-img');
                        $(div).attr('data', i);
                        div.innerHTML = [
                            '<img src="', data.attr[i].image, '"/>'].join('');
                        $('#xs').append(div);
                        images.push(data.attr[i].image)
                    }
                    $('[data='+now+']').addClass('visi');
                }
            }
        })
    }).on('click', '.message', function(){
        var id = $(this).attr('data');
        $.ajax({
            url: '/nextMessage/',
            type: 'POST',
            data: {id: allEvents[id].id},
            success: function (request) {
                var data = eval('(' + request + ')');
                if (data.status == 'error') {
                    window.location.href = '/error/?' + data.code;
                }
                else {
                    window.location.href = '/message/'
                }
            }
        })
    })
});

function initialize() {
    var myLatlng = new google.maps.LatLng(youPosition[0], youPosition[1]);
    var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: '/img/you.png'
    });
    for(var i in allEvents) {
        coordinates = allEvents[i].coordinates;
        addMarker(i)
    }
}

function addMarker(id) {
    var location = new google.maps.LatLng(Number(coordinates[0]), Number(coordinates[1]));
    var image = '/img/marker.png';
    markers[id] = {};
    markers[id].marker = new google.maps.Marker({
        position: location,
        draggable: false,
        map: map,
        icon: image
    });

    if(is == 0) {
        var str = allEvents[id][0] == 0 ? '' : '<button class="btn my_btn btn-sm" id="event_img" data="' +
        id + '" data-toggle="collapse" data-target="#slider_block">Показать фото</button>';
        var contentString = '<div id="content" data="' + id + '">' +
            '<div id="siteNotice">' + allEvents[id].address +
            '</div>' +
            '<div id="bodyContent">' +
            '<p>' + allEvents[id].message +
            '<p><span><strong>Начало : </strong> '+allEvents[id].date_start+'</span></p>'+
            '<p><span><strong>Конец : </strong> '+allEvents[id].date_stop+'</span></p>'+
            '' + str +
            '</div></div>';
    }
    else{
        var str = allEvents[id][0] == 0 ? '' : '<button class="btn my_btn btn-sm" id="event_img" data="' +
        id + '" data-toggle="collapse" data-target="#slider_block">Показать фото</button>';
        var contentString = '<div id="content" data="' + id + '">' +
            '<div id="siteNotice">' + allEvents[id].address +
            '</div>' +
            '<div id="bodyContent">' +
            '<p>' + allEvents[id].message +
            '<p><span><strong>Начало : </strong> '+allEvents[id].date_start+'</span></p>'+
            '<p><span><strong>Конец : </strong> '+allEvents[id].date_stop+'</span></p>'+
            '</p>' + str +
            '<button class="btn my_btn btn-sm message" data="' + id + '" >Сообщения</button>' +
            '</div></div>';
    }
    markers[id].infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    markers[id].marker.addListener('click', function() {
        markers[id].infowindow.open(map, markers[id].marker);
    });
}

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
    if(url == undefined) {
        window.location.href = '/';
    }else{
        window.location.href = url;
    }
}