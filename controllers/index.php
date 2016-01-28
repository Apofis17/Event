<?php

Class Controller_Index Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    // экшен
    // gitHub
	// экшен
    function index()
    {
        $active = $this->isActive();
        if ($active < 2) {
            $this->template->vars('menu',
                array('Авторизация' => 'data-toggle="collapse" data-target="#in"',
                    'Регистрация' => 'data-toggle="collapse" data-target="#up"'));
            $this->template->vars('is', 0);
        }else {
            $this->template->vars('is', 1);
            session_start();
            $model = new Model_profileEvent();
            $result = $model->eventByUser($_SESSION['user'][0]);
            if (!$result) {
                $this->template->vars('menu',
                    array('Настройки' => 'onclick="goHref(/settings/)"',
                        'Выход' => sprintf("onclick='isApply(%s)'", '"out"')));
            } else {
                $this->template->vars('menu',
                    array('Настройки' => 'onclick="goHref(/settings/)"',
                        'Выход' => sprintf("onclick='isApply(%s)'", '"out"'), 'ms' => 'a'));
            }
        }
        $this->template->vars('event',
            $_SESSION['user'][3]);
        $this->template->vars('events',
            $this->events());
        $this->template->view('index');
    }

    function events()
    {
        $model = new Model_profileEvent();
        $model_imd = new Model_imageEvent();
        $result = $model->allEvents(0,0);
        $rt = [];
        foreach($result as $key=>$value){
            $id = $value['id'];
            $img = $model_imd->result_by(array('event_id' => $id));
            $result[$key]['0'] = !$img ? 0 : 1;
            $result[$key]['user_id'] = 0;
            $result[$key][1] = 0;
            $result[$key]['coordinates'] =  explode(' ', $value['coordinates']);
            if(!empty($value['address'])){
                $rt[$key] = $result[$key];
            }
        }
        return $rt;
    }

    function imgEvent(){
        try {
            $id = $_POST['id'];
            $model = new Model_imageEvent();
            $result = $model->imageByEvent($id);
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => 'Фото к событию нету'));
                exit();
            }
            foreach ($result as $key=>$value) {
                $result[$key]['event_id'] = 0;
                $result[$key]['id'] = 0;
                $result[$key][0] = 0;
                $result[$key][1] = 0;
                $result[$key][2] = 0;
                $image_load = explode('static', $value['image']);
                $result[$key]['image'] = $image_load[count($image_load) - 1];
            }
            echo json_encode(array('status' => 'ok', 'attr'=>$result));
            exit();
        }
        catch (Exception $e){
            echo json_encode(array('status' => 'error', 'code'=>'000'));
            exit();
        }
    }
    function nextMessage(){
        $active = $this->isActive();
        if($active < 2){
            echo json_encode(array('status' => 'error', 'code'=>'000'));
            exit();
        }
        try {
            $id = $_POST['id'];
            session_start();
            $_SESSION['user'][3] = $id;
            echo json_encode(array('status' => 'ok'));
            exit();
        }
        catch (Exception $e){
            echo json_encode(array('status' => 'error', 'code'=>'000'));
            exit();
        }
    }
}
