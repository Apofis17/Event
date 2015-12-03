<?php

Class Controller_Error Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    // экшен
    function index()
    {
        $error = $this->codeError($_GET['code']);
        if($error == '/') {
            session_start();
            session_destroy();
            http_redirect('/');
            exit();
        }
        $this->template->vars('error', $error);
        $this->template->view('index');
        exit();
    }

    function codeError($code)
    {
        switch ($code) {
            case 000: {
                $error = 'Произошла непредвиденная ошибка';
            }
                break;

            case 001: {
                $error = 'Во время регистрации произошла ошибка. Попробуйте повторить регисттрацию';
            }
                break;

            case 002: {
                $error = 'Во время авторизации произошла ошибка. Попробуйте авторизоватся позднее';
            }
                break;

            case 003: {
                $error = 'Произошла ошибка. Попробуйте удалить куки файлы и авторизоватся заново';
            }
                break;
            case 004: {
                $error = 'Произошла ошибка попробуйте еще раз';
            }break;
            case 005: {
                $error = '/';
            }break;
            case 006: {
                $error = 'Произошла ошибка при добавлении события';
            }break;
            default: {
                $error = 'Все работает!!!';
            }
                break;
        }
        return $error;
    }
}