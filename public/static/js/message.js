var last = $('#id_event').val();
console.log(last);
$(function(){
    $('.btn-vklad').on('click', function(){
        var data = $(this).attr('data');
        $('button[data='+last+']').parent('li').removeClass('active');
        $(this).parent('li').addClass('active');
        id = 0;
        last = data;
        $('#id_event').val(data);
        ajax(data)
    });
    document.getElementById('message_block').scrollTop = 99999;
    $('#ok').click(function(){
        var message = $('.message_text').val();
        if(message == '') return;
        var data = $('#id_event').val();
        $.ajax({
            url: '/message/addMessage/',
            type: 'POST',
            data: {text:message, event:data},
            success: function (request) {
                var data = eval('('+request+')');
                if(data.status == 'error'){
                    if(data.code == '0'){
                        window.location.href = '/';
                    }
                    else{
                        window.location.href = '/error/?code='+data.code;
                    }
                }
                else{
                    $('.message_text').val('');
                }
            }
        })
    });
    window.setInterval(function (){
      ajax(-1);
    },2000);
});

function ajax(d){
    $.ajax({
        url: "/message/newMessage/",
        type: "post",
        data: {id:id, event:$('#id_event').val()},
        success: function (response) {
            var data = eval('('+response+')');
            if(d != -1){
                $('#message_block').children().remove();
            }
            if(data.attr != 0) {
                id = data.maxId;
                for (var i in data.attr) {
                    var div = document.createElement('div');
                    $(div).attr('class', 'one_message');
                    $(div).attr('data', data.attr[i].id);
                    var cl ='';
                    if(data.attr[i].user == 2){
                        cl = 'user'
                    }else {
                        if(data.attr[i].us == 2){
                            cl = 'us'
                        }
                    }
                    $(div).addClass(cl);
                    div.innerHTML = ['' +
                    '<div class="ava">' +
                    '<img src="' + data.attr[i].ava + '"></div><div class="info"><div class="block-info">' +
                    '<h5><strong>' + data.attr[i].login + '</strong></h5><div class="text">' + data.attr[i].text + '</div>' +
                    '<div class="date text-right">' + data.attr[i].date + '</div>' +
                    '</div></div>'];
                    $('#message_block').append(div);
                    document.getElementById('message_block').scrollTop = 99999;
                }
            }

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