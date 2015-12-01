<script src="/js/sing.js"></script>
<div id="in" class="full_block collapse">
    <div class="sing in col-lg-3 col-md-4 col-lg-offset-4 col-md-offset-4">
        <div class="row">
            <h4>Авторизация</h4>
        </div>
        <span class="row">
        <label for="username">Логин : </label>
        <input type="text" id="username_in" value="tihon"><br>
        </span><span class="row">
        <label for="password">Пароль : </label>
        <input type="password" id="password_in" value="1234">
        </span>

        <div class="status status_in row display_none" data="in">
        </div>
        <biv class="row">
            <button class="btn my_btn btn-sm" onclick="isApply('in')">Войти</button>
            <button class="btn my_btn_not btn-sm" data-toggle="collapse" data-target="#in">Отмена</button>
        </biv>
    </div>
</div>
<div id="up" class="full_block collapse">
    <div class="sing up col-lg-3 col-md-4 col-lg-offset-4 col-md-offset-4">
        <div class="row">
            <h4>Регистрация</h4>
        </div>
        <span class="row">
        <label for="username">Логин : </label>
        <input type="text" id="username_up" value="tihon"><br>
        </span><span class="row">
        <label for="password">Пароль : </label>
        <input type="password" id="password_up" value="1234">
        </span>
        <span class="row">
        <label for="password2">Повторите : </label>
        <input type="password" id="password2" value="1234">
        </span>

        <div class="status status_up row display_none ">
        </div>
        <biv class="row">
            <button class="btn my_btn btn-sm" onclick="isApply('up')">Готово</button>
            <button class="btn my_btn_not btn-sm" data-toggle="collapse" data-target="#up">Отмена</button>
        </biv>
    </div>
</div>
<div class="col-lg-10 col-md-10 col-lg-offset-1 col-md-offset-1">
    <div id="map"></div>
</div>
