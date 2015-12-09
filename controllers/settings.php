<?php

Class Controller_Settings Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    function index()
    {
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
        $this->template->vars('menu',
            array('Назад' => 'onclick="goHref()"'));
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
        $events = $model->allEvents($id, 1);
        $response = array();
        if(!empty($events)){
            foreach($events as $val){
                $coordinates = explode(' ', $val['coordinates']);
                $start = empty($val['date_start']) ? '' : date("d.m.Y", strtotime($val['date_start']));
                $stop = empty($val['date_stop']) ? '' : date("d.m.Y", strtotime($val['date_stop']));
                array_push($response, array(
                    'address' => $val['address'],
                    'id' => $val['id'],
                    'date_start' => $start,
                    'date_stop' => $stop,
                    'message' => $val['message'],
                    'coordinates' => $coordinates,
                ));
                $result = $modelImage->imageByEvent($val['id']);
                if(!empty($result)){
                    $response[count($response) - 1]['images'] = array();
                    foreach($result as $image){
                        $image_load = explode('static', $image['image']);
                        array_push($response[count($response) - 1]['images'], $image_load[count($image_load) - 1]);
                    }
                }
            }
        } else {
            $response = false;
        }
        $this->template->vars('events', $response);
        $this->template->view('index');
    }


    function loadingAva()
    {
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
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
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
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

    function addEventFull()
    {
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
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

    function deleteEventAll()
    {
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
        try {
            $id = $_POST['id'];
            session_start();
            $user = $_SESSION['user'][0];
            $model = new Model_profileEvent();
            $result = $model->deleteAllEvent($id, $user);
            if(!$result){
                echo json_encode(array('status' => 'error', 'code' => '007'));
                exit();
            }
            $model = new Model_imageEvent();
            $result = $model->imageByEvent($id);
            if(!$result){
                echo json_encode(array('status' => 'ok', 'code' => '0'));
                exit();
            }
            $result = $model->deleteImage('event_id', $id);
            $model = new Model_correspondence();
            $result = $model->deleteCor('event_id', $id);
            if(!$result){
                echo json_encode(array('status' => 'error', 'code' => '007'));
                exit();
            }
            else{
                echo json_encode(array('status' => 'ok', 'code' => '0'));
                exit();
            }

        }
        catch(Exception $e){
            echo json_encode(array('status' => 'error', 'code' => '000'));
            exit();
        }
    }
    function reloadEvent(){
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
        try {
            $id = $_POST['id'];
            $del = explode(' ', $_POST['delete_img']);
            array_pop($del);
            session_start();
            $user = $_SESSION['user'][0];
            $address = null;
            if (isset($_POST['address']))
                $address = $_POST['address'];
            $message = null;
            if (isset($_POST['message']))
                $message = $_POST['message'];
            $start = null;
            if (isset($_POST['date_start']))
                $start = $_POST['date_start'];
            $stop = null;
            if (isset($_POST['date_stop']))
                $stop = $_POST['date_stop'];
            $coordinates = $_POST['coordinates'];
            $array = array(
                'id' => $id,
                'user_id' => $user,
                'coordinates' => $coordinates,
                'address' => $address,
                'message' => $message,
                'date_start' => $start,
                'date_stop' => $stop,
            );
            $model = new Model_profileEvent();
            $result = $model->addEventFull($array);
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '010'));
                exit();
            }
            $model = new Model_imageEvent();
            foreach ($del as $key => $value) {
                $model->deleteImage('image', '/home/apofis/PHP/event/public/static' . $value);
            }
            $images = $_FILES;
            foreach ($images as $value) {
                $image = $this->fileLoad($value);
                $model->addImage($id, $image);
            }
            echo json_encode(array('status' => 'ok', 'code' => '0'));
            exit();
        }
        catch(Exception $e){
            echo json_encode(array('status' => 'error', 'code' => '010'));
            exit();
        }
    }

}