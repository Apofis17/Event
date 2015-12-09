var youPosition = [];
var add_event = {};
var map;
var coordinates;
var number = 0;
var event_img = {};
var ava;
var id = null;
var geocoder = new google.maps.Geocoder;
$(function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            youPosition[0] = position.coords.latitude; // излвекаем широту
            youPosition[1] = position.coords.longitude; // извлекаем долготу
        });
    } else {
        youPosition[0] = 0;
        youPosition[1] = 0;
    }

    function handleFileAva(evt) {
        var f = evt.target.files[0];
        if (!typeImage(f)) {
            return;
        }
        ava = f;
        var reader = new FileReader();
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
        console.log(event_img);
        if ($('#xw').children().length == 0) {
            $('.image').addClass('display_none')
        }
    });

    $('body').on('click', '.save', function(){
        var address = $('#address').val();
        var message = $('#message').val();
        var start = $('#date_start').val();
        var stop = $('#date_stop').val();
        var date_start = new Date(Number(start.substr(6,4)), Number(start.substr(3,2)) -1, Number(start.substr(0,2)));
        var date_stop = new Date(Number(stop.substr(6,4)), Number(stop.substr(3,2)) -1, Number(stop.substr(0,2)));
        var now = new Date();
        var fd = new FormData();
        for(var i in event_img){
            fd.append(i, event_img[i])
        }
        coordinates = coordinates[0] + ' ' + coordinates[1];
        if(coordinates == undefined || address == '' || message == '' ) return false;
        if(date_start > date_stop || date_stop < now ) return false;
        fd.append('coordinates', coordinates);
        fd.append('address', address);
        fd.append('message', message);
        fd.append('date_start', start);
        fd.append('date_stop', stop);
        fd.append('id', add_event.id);
        $.ajax({
            url: '/settings/addEventFull/',
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
                    window.location.href = '/settings/?' + data.code
                }
            }
        })
    }).on('click', '.reload', function(){
        var fd = new FormData();
        var address = $('#address').val();
        var message = $('#message').val();
        var start = $('#date_start').val();
        var stop = $('#date_stop').val();
        if(coordinates == undefined || address == '' || message == '' ) return false;
        coordinates = coordinates[0] + ' ' + coordinates[1];
        fd.append('coordinates', coordinates);
        if(EventData[id].address != address){
            fd.append('address', address)
        }
        if(EventData[id].message != message){
            fd.append('message', message)
        }

        var date_start = new Date(Number(start.substr(6,4)), Number(start.substr(3,2)) -1, Number(start.substr(0,2)));
        var date_stop = new Date(Number(stop.substr(6,4)), Number(stop.substr(3,2)) -1, Number(stop.substr(0,2)));
        var now = new Date();
        if(date_start > date_stop || date_stop < now ) return false;
        if(EventData[id].date_start != start){
            fd.append('date_start', start)
        }
        if(EventData[id].date_stop != stop){
            fd.append('date_stop', stop)
        }
        var delete_img = '';
        for(var i in EventData[id].images){
            if(event_img.indexOf(String(EventData[id].images[i])) < 0){
                delete_img += EventData[id].images[i] + ' ';
            }
        }
        console.log(delete_img)
        fd.append('delete_img', delete_img);
        for(var i in event_img){
            if(EventData[id].images.indexOf(i) < 0) {
                fd.append(i, event_img[i]);
            }
        }
        fd.append('id', id);
        console.log(fd);
        $.ajax({
            url: '/settings/reloadEvent/',
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
                    window.location.href = '/settings/'
                }
            }
        })
    })
    $('.add-event').click(function () {
        $('.d2').addClass('save');
        initialize(false);
    });

    $('.full_list_events').on('click', '.reload_event', function () {
        $('.d2').addClass('reload');
        id = $(this).attr('data');
        $('#address').val(EventData[id].address);
        $('#message').val(EventData[id].message);
        $('#date_start').val(EventData[id].date_start);
        $('#date_stop').val(EventData[id].date_stop);
        $('.information-event').removeClass('display_none');
        initialize(true);
        var t = false;
        for (var i = 0; EventData[id].images != undefined && i < EventData[id].images.length; i++) {
            t = true;
            $('#xw').append('<div class="one-img" data="'+i+'"><img class="delet_img" src="' + EventData[id].images[i] + '"/></div>');
        }
        if (t) {
            $('.image').removeClass('display_none');
        }
        event_img = [];
        if(EventData[id].images != undefined) {
            for(var i in EventData[id].images){
                event_img[i] = EventData[id].images[i]
            }
        }
        number = event_img.length - 1;

    }).on('click', '.delete_event_all', function () {
        var idi = $(this).attr('data');
        $('.event[data=' + idi + ']').remove();
        delete EventData[idi];
        $.ajax({
            url: '/settings/deleteEventAll/',
            type: 'POST',
            data: {id:idi},
            success: function (request) {
                var data = eval("(" + request + ")");
                if (data.status == 'error') {
                    window.location.href = '/error/?' + data.code
                }
                else {
                    return
                }
            }
        })
    });
    $('.d3').on('click', function () {
        $('#address').val('');
        $('#message').val('');
        $('#date_start').val('');
        $('#date_stop').val('');
        $('.information-event').addClass('display_none');
        $('.image').addClass('display_none');
        $('#xw').children().remove();
        if (add_event.marker != undefined) {
            add_event.marker.setMap(null);
        }
        add_event = {};
        coordinates = [];
        number = 0;
        event_img = {};
        id = null;
        google.maps.event.clearListeners(map, 'click');
        google.maps.event.clearListeners(add_event.marker, 'dragend')
    });
});

function typeImage(file){
    var type = file.type;
    if(type == 'image/png') return true;
    if(type == 'image/jpeg') return true;
    if(type == 'image/jpg') return true;
    return false
}

function initialize(status) {
    var myLatlng = new google.maps.LatLng(youPosition[0], youPosition[1]);
    var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };
    if (map == undefined) {
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            icon: '/img/you.png'
        })
    }
    if(!status){
        google.maps.event.addListener(map, 'click', function (event) {
            console.log(2);
            addMarker(event.latLng, map);
        });
    } else {
        coordinates = EventData[id].coordinates;
        add_event.marker = new google.maps.Marker({
            position: {lat: Number(coordinates[0]), lng: Number(coordinates[1])},
            draggable: true,
            map: map,
            icon: '/img/marker.png'
        });
        $('.information-event').removeClass('display_none');
        $.datepicker.setDefaults(
            $.extend($.datepicker.regional["ru"])
        );
        $("#date_start").datepicker();
        $("#date_stop").datepicker();
        console.log(EventData[id].address == undefined);
        if(EventData[id].address == undefined){
            geocodeAddress(geocoder, {lat: Number(coordinates[0]), lng: Number(coordinates[1])})
        }
        google.maps.event.addListener(map, 'click', function (event) {
            console.log(1);
            addMrk(event.latLng, map);
        });
    }
}

function addMrk(location, map) {
    coordinates = [location.lat(), location.lng()];
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
    geocodeAddress(geocoder, location);
    google.maps.event.addListener(add_event.marker, 'dragend', function () {
        coordinates = [add_event.marker.getPosition().lat(), add_event.marker.getPosition().lng()];
        geocodeAddress(geocoder, add_event.marker.getPosition());
    });
}

function addMarker(location, map) {
    coordinates = [location.lat(), location.lng()];
    geocodeAddress(geocoder, location);
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
    if(add_event.id == undefined) {
        $.ajax({
            url: '/settings/addEvent/',
            type: 'GET',
            data: {lat: coordinates[0], lng: coordinates[1], address: add_event.address},
            async: false,
            success: function (response) {
                var data = eval("(" + response + ")");
                if (data.status == 'error') {
                    window.location.href = '/error/?' + data.code
                }
                else if (data.status == 'ok') {
                    add_event.id = data.args.id;
                }
            }
        });
    }
    google.maps.event.addListener(add_event.marker, 'dragend', function () {
        coordinates = [add_event.marker.getPosition().lat(), add_event.marker.getPosition().lng()];
        geocodeAddress(geocoder, add_event.marker.getPosition());
    });
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
                $('#address').val(results[0].address_components[1].long_name + ' ,  ' + results[0].address_components[0].long_name);
            } else {
                $('#address').val('Не удалось получить адрес!');
            }
        } else {
            $('#address').val('Не удалось получить адрес!');
        }
    });
}
function goHref(url) {
    if(url == undefined) {
        window.location.href = '/';
    }else{
        window.location.href = url;
    }
}