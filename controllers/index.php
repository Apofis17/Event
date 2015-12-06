<?php

Class Controller_Index Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    // экшен
    function index()
    {
        $active = $this->isActive();
        if ($active < 2) {
            $this->template->vars('menu',
                array('Авторизация' => 'data-toggle="collapse" data-target="#in"',
                    'Регистрация' => 'data-toggle="collapse" data-target="#up"'));
            $this->template->view('index');
            exit();
        }
        $this->template->vars('menu',
            array('Настройки' => 'onclick="goHref(/settings/)"', 'Выход' => sprintf("onclick='isApply(%s)'", '"out"'), 'Сообщения' => 'data="ms"'));
        $this->template->view('index');
    }
} 
