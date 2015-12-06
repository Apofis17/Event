<div class="container">
    <link href="/css/settings.css" rel="stylesheet" type="text/css">
    <script>
        var Edata = <?= json_encode($events) ?>;
        if(Edata != null){
            var EventData = {};
            for(var i = 0; i < Edata.length; i++){
                EventData[Edata[i].id] = Edata[i]
            }
        }
    </script>
    <script src="/js/settings.js"></script>
    <link type="text/css" href="/custom/css/flick/jquery-ui-1.9.2.custom.min.css" rel="stylesheet"/>
    <script src="/custom/js/jquery-1.8.3.js" type="text/javascript"></script>
    <script src="/custom/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
    <script src="/custom/js/jquery.ui.datepicker-ru.js" type="text/javascript"></script>
    <div class="row">
        <div class="col-md-5 col-lg-5">
            <h4 class="header_title">Загрузить аватарку</h4>
            <label for="ava">
                <span class="btn my_btn btn-sm">Выбрать</span>
            </label>
            <input id="ava" name="ava" class="display_none" type="file">
            <button class="loading">Сохранить</button>
        </div>
        <div class="col-md-5 col-lg-5">
            <div id="ava_img">
                <img width="auto" height="150px " src="<?= $urlAva ?>">
            </div>
        </div>
    </div>
    <br>
    <button class="btn my_btn btn-sm add-event" data-toggle="collapse" data-target="#add">Добавить событие</button>
    <div class="full_block collapse" id="add">
        <div class="container white-block">
            <div class="row information-event display_none">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-md-offset-1 col-lg-offset-1">
                        <h4>Информация о событие</h4>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 text-right">
                                <label for="address">Адрес :</label>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <input id="address" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 text-right">
                                <label for="message">Информация :</label>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <textarea id="message"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 text-right">
                                <label for="date_start">Дата начала :</label>
                            </div>
                            <div class="col-lg-8 col-md-8 pull-left">
                                <input id="date_start" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 text-right">
                                <label for="date_stop">Дата окончания :</label>
                            </div>
                            <div class="col-lg-8  col-md-8 pull-left">
                                <input id="date_stop" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-lg-offset-1 col-md-offset-1">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 text-right">
                                <label>Фото :</label>
                            </div>
                            <label for="event_img" class="col-lg-8 col-md-8 ">
                                <span class="btn my_btn btn-sm pull-left" style="width: 100px">Выбрать</span></label>
                            <input type="file" multiple id="event_img" class="display_none">
                        </div>
                    </div>
                    <div class="image display_none">
                        <div id="xw">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-md-offset-1 col-lg-offset-1 buton-w">
                    <button class="btn my_btn btn-sm d2">Сохранить</button>
                    <button class="btn my_btn_not btn-sm d3" data-toggle="collapse" data-target="#add">Отмена</button>
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
                <div id="map"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <h4 class="header_title">Мои события</h4>
        <div class="container full_list_events">
            <? if (empty($events)) { ?>
                <h5>У вас нету событий!</h5>
            <? } else {?>
                <? foreach($events as $val){ ?>
                    <div class="col-lg-10 col-md-10 col-md-offset-1 col-lg-offset-1 event" data="<?= $val['id'] ?>">
                        <div class="col-lg-12 col-md-12 address_event">
                            <div class="col-lg-2 col-md-2 text-right"> Адрес :</div>
                            <div class="col-lg-9 col-md-9 pull-left"><?= $val['address']?></div>
                        </div>
                        <div class="col-lg-12 col-md-12 message_event">
                            <div class="col-lg-2 col-md-2 text-right"> Информация :</div>
                            <div class="col-lg-9 col-md-9 pull-left"><?= $val['message']?></div>
                        </div>
                        <div class="col-lg-12 col-md-12 date_start_event">
                            <div class="col-lg-2 col-md-2 text-right"> Началo :</div>
                            <div class="col-lg-9 col-md-9 pull-left"><?= $val['date_start']?></div>
                        </div>
                        <div class="col-lg-12 col-md-12 date_stop_event">
                            <div class="col-lg-2 col-md-2 text-right"> Конец :</div>
                            <div class="col-lg-9 col-md-9 pull-left"><?= $val['date_stop']?></div>
                        </div>
                        <div class="col-lg-12 col-md-12 image_event collapse" id="w12_<?= $val['id'] ?>">
                            <div id="xs">
                                <? foreach($val['images'] as $value) {?>
                                    <div class="one-img">
                                        <img src="<?= $value ?>">
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                        <button class="btn my_btn btn-sm" data-toggle="collapse" data-target="#w12_<?= $val['id'] ?>">Фотографии</button>
                        <button class="btn my_btn btn-sm reload_event" data="<?= $val['id']?>" data-toggle="collapse" data-target="#add">Редактировать</button>
                        <button class="btn my_btn_not btn-sm delete_event_all" data="<?= $val['id']?>">Удалить</button>
                    </div>
                    <hr>
                <? } ?>
            <? } ?>
        </div>
    </div>
</div>

