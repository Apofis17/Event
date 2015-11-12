<?php

Class Controller_Index Extends Controller_Base {    
    // шаблон
    public $layouts = "first_layouts";
     
    // экшен
    function index() {
        $model = new Model_Users();
        $userInfo = $model->getUser();
        $this->template->vars('userInfo', $userInfo);
        $this->template->view('index');
    }   
} 
