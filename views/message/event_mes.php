<div class="container">
    <script>
        var id = <?= json_encode($maxId)?>;
    </script>
    <input id="id_event" style="display: none" type="text" disabled value="<?= $event?>">
    <link href="/css/message.css" rel="stylesheet" type="text/css">
    <script src="/js/message.js"></script>
    <ul class="nav nav-tabs">
        <? foreach ($events as $key => $value) {
            if (!empty($value['address'])) {
                ?>
                <li class="<?= $key == 0 ? 'active' : '' ?> vklad">
                    <button class="btn btn-sm btn-vklad" data="<?= $value['id'] ?>"><?= $value['address'] ?></button>
                </li>
            <? }
        } ?>
    </ul>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-md-offset-2 col-lg-offset-2" id="message_block">
            <? if (!empty($message)) { ?>
                <? foreach ($message as $key => $value){ ?>
                    <? if ($value['user']){ ?>
                        <div class="one_message user" data="<?= $value['id'] ?>">
                    <? }else{
                        if ($value['us']) { ?>
                            <div class="one_message us" data="<?= $value['id'] ?>">
                        <? }else{ ?>
                            <div class="one_message " data="<?= $value['id'] ?>">
                        <? } ?>
                    <? } ?>
                    <div class="ava">
                        <img src="<?= $value['ava'] ?>">
                    </div>
                    <div class="info">
                        <div class="block-info">
                            <h5><strong><?= $value['login'] ?></strong></h5>

                            <div class="text"> <?= $value['text'] ?></div>
                            <div class="date text-right"><?= $value['date'] ?></div>
                        </div>
                    </div>
                </div>
        <? }} ?>
    </div>
    <div class="col-lg-8 col-md-8 col-md-offset-2 col-lg-offset-2" id="new_message">
        <textarea class="message_text"></textarea>
        <button class="btn-sm btn my_btn" id="ok">Отправить</button>
    </div>
</div>