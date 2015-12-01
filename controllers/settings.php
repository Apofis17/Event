<?php

Class Controller_Settings Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    function index()
    {
        $this->template->vars('menu',
            array('Назад' => '/'));
        session_start();
        $id = $_SESSION['user'][0];
        $module = new Model_profileUser();
        $result = $module->user_by(array('id' => $id));
        if (!$result) {
            http_redirect('/error/?code=004');
        } else {
            if (empty($result[0]['ava'])) {
                $this->template->vars('urlAva', '/img/no_ava.jpg');
            } else {
                $file = explode('static', $result[0]['ava']);
                $this->template->vars('urlAva', $file[count($file) - 1]);
            }
        }
        $this->template->view('index');
    }


    function loadingAva()
    {
        try {
            $hash = $this->fileLoad($_FILES['fileImage']);
            session_start();
            $id = $_SESSION['user'][0];
            $module = new Model_profileUser();
            $result = $module->addAva($hash, $id);
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '004'));
            } else {
                $hash = explode('static', $hash);
                echo json_encode(array('status' => 'ok', 'code' => '0', 'args' => array('img' => $hash[count($hash) - 1])));
            }
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'code' => '004'));
        }
    }

    function addEvent()
    {
        try {
            $coordinate = $_GET['lat'] . ' ' . $_GET['lng'];
            session_start();
            $user = $_SESSION['user'][0];
            $model = new Model_profileEvent();
            $result = $model->addEvent($user, $coordinate);
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '004'));
            } else {
                echo json_encode(array('status' => 'ok', 'code' => '0', 'args' => array('id' => $result)));
            }
            exit();
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'code' => '000'));
        }
    }
    function addEventFull(){
        
    }

}