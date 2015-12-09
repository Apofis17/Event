<?php

Class Controller_Message Extends Controller_Base
{
    public $layouts = "layout";

    function index()
    {
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
        session_start();
        if(empty($_POST['event'])){
            $id = $_SESSION['user'][3];
        }
        else{
            $id = $_POST['event'];
        }
        $user_id = $_SESSION['user'][0];
        $this->template->vars('menu',
            array('Назад' => 'onclick="goHref()"'));
        $model = new Model_correspondence();
        $modelUser = new Model_profileUser();
        $modelEvent = new Model_profileEvent();
        $result = $model->ajaxMessage(0, $id);
        $maxId = $model->maxId();
        $event_user = $modelEvent->userByEvent($id);
        foreach($result as $key=>$value){
            $user = $modelUser->result_by(array("id" => $value['user_id']));
            $ava = explode('static', $user[0]['ava']);
            if(!empty($event_user) && $value['user_id'] == $event_user){
                $result[$key]['user'] = true;
            }
            else {
                $result[$key]['user'] = false;
                if ($value['user_id'] == $user_id) {
                    $result[$key]['us'] = true;
                } else {
                    $result[$key]['us'] = false;
                }
            }
            $result[$key]['ava'] = $ava[count($ava) -1];
            $result[$key]['login'] = $user[0]['login'];
        }
        $this->template->vars('event', $_SESSION['user'][3]);
        $this->template->vars('message', $result);
        $this->template->vars('maxId', $maxId[0]['last_value']);
        $this->template->view('index');
    }

    function addMessage(){
        try {
            session_start();
            $array = array(
                'user_id' => $_SESSION['user'][0],
                'event_id' => $_POST['event'],
                'text' => $_POST['text'],
                'date' => date('Y-m-d'),
            );
            $model = new Model_correspondence();
            $result = $model->addMessage($array);
            if(!$result){
                echo json_encode(array('status' => 'error', 'code' => '0'));
                exit();
            }
            else{
                echo json_encode(array('status' => 'ok', 'code' => '0'));
                exit();
            }
        }
        catch (Exception $e){
            echo json_encode(array('status' => 'error', 'code'=>'000'));
            exit();
        }
    }

    function userEvent(){
        $active = $this->isActive();
        if($active < 2){
            http_redirect('/');
            exit();
        }
        session_start();
        $id = $_SESSION['user'][0];
        $model = new Model_profileEvent();
        $modelCor = new Model_correspondence();
        $event = $model->allEvents($id, 1);
        $result = $modelCor->ajaxMessage(0, $event[0]['id']);
        $modelUser = new Model_profileUser();
        $event_user = $model->userByEvent($event[0]['id']);
        $maxId = $modelCor->maxId();
        foreach($result as $key=>$value){
            $user = $modelUser->result_by(array("id" => $value['user_id']));
            $ava = explode('static', $user[0]['ava']);
            if(!empty($event_user) && $value['user_id'] == $event_user){
                $result[$key]['user'] = true;
            }
            else {
                $result[$key]['user'] = false;
                if ($value['user_id'] == $id) {
                    $result[$key]['us'] = true;
                } else {
                    $result[$key]['us'] = false;
                }
            }
            $result[$key]['ava'] = $ava[count($ava) -1];
            $result[$key]['login'] = $user[0]['login'];
        }
        $this->template->vars('menu',
            array('Назад' => 'onclick="goHref()"'));
        $this->template->vars('event', $event[0]['id']);
        $this->template->vars('maxId', $maxId[0]['last_value']);
        $this->template->vars('message', $result);
        $this->template->vars('events', $event);
        $this->template->view('event_mes');
    }

    function newMessage(){
        session_start();
        $user_id = $_SESSION['user'][0];
        $id = $_POST['event'];
        $max = $_POST['id'];
        $model = new Model_correspondence();
        $maxId = $model->maxId();
        $modelUser = new Model_profileUser();
        $modelEvent = new Model_profileEvent();
        if($max < $maxId[0]['last_value']){
            $result = $model->ajaxMessage($max, $id);
            $event_user = $modelEvent->userByEvent($id);
            foreach($result as $key=>$value){
                $user = $modelUser->result_by(array("id" => $value['user_id']));
                $ava = explode('static', $user[0]['ava']);
                if(!empty($event_user) && $value['user_id'] == $event_user){
                    $result[$key]['user'] = 2;
                }
                else {
                    $result[$key]['user'] = 1;
                    if ($value['user_id'] == $user_id) {
                        $result[$key]['us'] = 2;
                    } else {
                        $result[$key]['us'] = 1;
                    }
                }
                $result[$key]['ava'] = $ava[count($ava) -1];
                $result[$key]['login'] = $user[0]['login'];
            }
            if(!$result){
                echo json_encode(array('status'=>'error', 'code'=>'000'));
                exit();
            }
            echo json_encode(array('status'=>'ok', 'attr'=>$result, 'maxId'=>$maxId[0]['last_value']));
        }
        else{
            echo json_encode(array('status'=>'ok', 'attr'=>0));
        }
    }

} 
