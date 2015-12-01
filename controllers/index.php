<?php

Class Controller_Index Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    // экшен
    function index()
    {
        if (!isset($_COOKIE['gatsbu'])) {
            $this->template->vars('menu',
                array('Авторизация' => 'data-toggle="collapse" data-target="#in"',
                    'Регистрация' => 'data-toggle="collapse" data-target="#up"'));
            $this->template->view('index');
            exit();
        }
        session_start();
        if (!isset($_SESSION['user'])) {
            $model = new Model_profileUser();
            $result = $model->user_by(array("hash" => $_COOKIE['gatsbu']));
            if (!$result) {
                $this->template->vars('menu',
                    array('Авторизация' => 'data-toggle="collapse" data-target="#in"',
                        'Регистрация' => 'data-toggle="collapse" data-target="#up"'));
                $this->template->view('index');
                exit();
            }
            $_SESSION['user'] = array($result[0]['id'], $result[0]['login']);
        }
        $this->template->vars('menu',
            array('Настройки' => 'onclick="goHref(/settings/)     "', 'Выход' => sprintf("onclick='isApply(%s)'", '"out"'), 'Сообщения' => 'data="ms"'));
        $this->template->view('index');
    }
} 
