var youPosition = [];
var add_event = {};
var map;
var coordinates;
var number = 0;
var event_img = {};
var ava;
$(function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            youPosition[0] = position.coords.latitude; // излвекаем широту
            youPosition[1] = position.coords.longitude; // извлекаем долготу
        });
    } else {
        youPosition[0] = 0; // излвекаем широту
        youPosition[1] = 0; // извлекаем долготу
    }

    function handleFileAva(evt) {
        var f = evt.target.files[0];
        if (!typeImage(f)) {
            return;
        }
        ava = f;
        var reader = new FileReader();
        console.log(1);
        reader.onload = (function (theFile) {
            return function (e) {
                var img = document.createElement('img');
                img.setAttribute('src', e.target.result);
                document.getElementById('ava_img').innerHTML = '';
                document.getElementById('ava_img').insertBefore(img, null);
            };
        })(f);
        reader.readAsDataURL(f);
    }

    document.getElementById('ava').addEventListener('change', handleFileAva, false);

    function addAva(){
        if (ava == undefined) return;
        var fd = new FormData();
        fd.append("fileImage", ava);
        $.ajax({
            url: '/settings/loadingAva/',
            type: 'POST',
            contentType: false,
            processData: false,
            data: fd,
            success: function (request) {
                var data = eval("(" + request + ")");
                if (data.status == 'error') {
                    window.location.href = '/error/?' + data.code
                }
                else {
                    $('.ava_img').children('img').attr('src', data.args.img)
                }
            }
        })
    }

    document.getElementsByClassName('loading')[0].addEventListener('click', addAva, false);
    document.getElementsByClassName('add-event')[0].addEventListener('click', initialize, false);

    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        for (var i = 0, f; f = files[i]; i++) {
            number++;
            if (!typeImage(f)) {
                continue;
            }
            $('.image').removeClass('display_none');
            event_img[number] = f;
            f.number = number;
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {
                    var div = document.createElement('div');
                    div.setAttribute('class', 'one-img');
                    div.setAttribute('data', theFile.number);
                    div.innerHTML = ['<img class="delet_img" title="', escape(theFile.name), '" src="', e.target.result, '"/>'].join('');
                    document.getElementById('xw').insertBefore(div, null);
                };
            })(f);
            reader.readAsDataURL(f);
        }
    }

    document.getElementById('event_img').addEventListener('change', handleFileSelect, false);

    $('#xw').on('click', '.delet_img', function () {
        var parent = $(this).parent();
        var data = parent.attr('data');
        event_img[data] = null;
        parent.remove();
        if ($('#xw').children().length == 0) {
            $('.image').addClass('display_none')
        }
    });

    document.getElementById('save').onclick = function(){
        var address = $('#address').val();
        var message = $('#message').val();
        var date_start = new Date($('#date_start').val());
        var date_stop = new Date($('#date_stop').val());
        var now = new Date();
        var fd = new FormData();
        for( i in event_img){
            fd.append(i, event_img[i])
        }
        if(coordinates == undefined || address == '' || message == '' ) return false;
        if(date_start > date_stop || date_stop < now ) return false;
        $.ajax({
            url: '/settings/addEventFull/',
            type: 'POST',
            contentType: false,
            processData: false,
            data: {image: fd,
                coordinates: coordinates,
                address:address,
                message:message,
                date_start: date_start,
                date_stop:date_stop
            },
            success: function (request) {
                var data = eval("(" + request + ")");
                if (data.status == 'error') {
                    window.location.href = '/error/?' + data.code
                }
                else {
                    $('.ava_img').children('img').attr('src', data.args.img)
                }
            }
        })
    }

});

function typeImage(file){
    var type = file.type;
    if(type == 'image/png') return true;
    if(type == 'image/jpeg') return true;
    if(type == 'image/jpg') return true;
    return false
}

function initialize() {
    var myLatlng = new google.maps.LatLng(youPosition[0], youPosition[1]);
    var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };
    if (map != undefined) {
        map.setMap(null);
    }
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: '/img/you.png'
    });
    google.maps.event.addListener(map, 'click', function (event) {
        addMarker(event.latLng, map);
    });
}

function addMarker(location, map) {
    coordinates = [location.lat, location.lng];
    $.ajax({
        url: '/settings/addEvent/',
        type: 'GET',
        data: {lat: coordinates[0], lng: coordinates[1]},
        async: false,
        success: function (response) {
            var data = eval("(" + response + ")");
            if (data.status == 'error') {
                window.location.href = '/error/?' + data.code
            }
            else if (data.status == 'ok') {
                event.id = data.args.id;
            }
        }
    });
    var image = '/img/marker.png';
    if (add_event.marker != undefined) {
        add_event.marker.setMap(null);
    }
    add_event.marker = new google.maps.Marker({
        position: location,
        draggable: true,
        map: map,
        icon: image
    });
    var geocoder = new google.maps.Geocoder;
    google.maps.event.addListener(add_event.marker, 'dragend', function () {
        coordinates = [add_event.marker.getPosition().lat(), add_event.marker.getPosition().lng()];
        geocodeAddress(geocoder, add_event.marker.getPosition());
    });
    geocodeAddress(geocoder, location);
    $('.information-event').removeClass('display_none');
    $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
    );
    $("#date_start").datepicker();
    $("#date_stop").datepicker();
}

function geocodeAddress(geocoder, location) {
    geocoder.geocode({'location': location}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                console.log(results[0].formatted_address);
                $('#address').val(results[0].address_components[1].long_name + ' ,  ' + results[0].address_components[0].long_name);
            } else {
                $('#address').val('Не удалось получить адрес!');
            }
        } else {
            $('#address').val('Не удалось получить адрес!')
        }
    });
};