<?php

Class Controller_Sing Extends Controller_Base
{
    // шаблон
    public $layouts = "layout";

    function index()
    {
    }

    function up()
    {
        try {
            $model = new Model_profileUser();
            $username = $_GET['username'];
            $password = md5($_GET['password']);
            if ($model->result_by(array('login' => $username))) {
                echo json_encode(array('status' => 'error', 'code' => '1'));
                exit();
            };
            $result = $model->add_user(array('login' => $username, 'password' => $password));
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '001'));
                exit();
            }
            echo json_encode(array('status' => 'ok', 'code' => '0'));
            exit();
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'code' => '000'));
            exit();
        }
    }

    function in()
    {
        try {
            $model = new Model_profileUser();
            $username = $_GET['username'];
            $password = md5($_GET['password']);
            $result = $model->result_by(array('login' => $username));
            if (!$result) {
                echo json_encode(array('status' => 'error', 'code' => '2'));
                exit();
            }
            if ($result[0]['password'] != $password) {
                echo json_encode(array('status' => 'error', 'code' => '3'));
                exit();
            }
            $hash = $result[0]['hash'];
            $result = $model->in_user($result[0]['id']);
            if (!$result) echo json_encode(array('status' => 'error', 'code' => '002'));
            else echo json_encode(array('status' => 'ok', 'code' => '1', 'args' => array('gatsbu' => $hash)));
            exit();
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'code' => '002'));
            exit();
        }
    }

    function out()
    {
        try {
            $model = new Model_profileUser();
            $hash = $_GET['username'];
            $model->out_user($hash);
            session_start();
            session_destroy();
            echo json_encode(array('status' => 'ok', 'code' => '2'));
            exit();
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'code' => '003'));
            exit();
        }
    }
} 
