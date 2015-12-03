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
        $result = $module->result_by(array('id' => $id));
        if (!$result) {
            http_redirect('/error/?code=004');
            exit();
        }
        if (empty($result[0]['ava'])) {
            $this->template->vars('urlAva', '/img/no_ava.jpg');
        } else {
            $file = explode('static', $result[0]['ava']);
            $this->template->vars('urlAva', $file[count($file) - 1]);
        }
        $model = new Model_profileEvent();
        $modelImage = new Model_imageEvent();
        $events = $model->allEvents();
        if(!empty($events)){
            foreach($events as $val){
                $result = $modelImage->imageByEvent($val['id']);
                if(!empty($result)){
                    $val['images'] = array();
                    foreach($result as $image){
                        array_push($val['image'], $image);
                    }
                }
            }
        }
        $this->template->vars()
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
        try {
            session_start();
            $id = $_POST['id'];
            if (empty($id)) {
                echo json_encode(array('status' => 'error', 'code' => '005'));
                exit();
            }
            $array = array(
                'id' => $id,
                'user_id' => $_SESSION['user'][0],
                'message' => $_POST['message'],
                'coordinates' => $_POST['coordinates'],
                'date_start' => $_POST['date_start'],
                'date_stop' => $_POST['date_stop'],
                'address' => $_POST['address'],
            );
            $model = new Model_profileEvent();
            $result = $model->addEventFull($array);
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '006'));
                exit();
            }
            $model = new Model_imageEvent();
            $images = $_FILES;
            foreach ($images as $value) {
                $image = $this->fileLoad($value);
                $model->addImage($id, $image);
            }
            echo json_encode(array('status' => 'ok', 'code' => '0'));
        }
        catch (Exception $e){
            echo json_encode(array('status' => 'error', 'code' => '000'));
            exit();
        }

    }

}