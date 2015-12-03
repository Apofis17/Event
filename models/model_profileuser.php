<?php

Class Model_profileUser Extends Model_Base
{
    protected $id;
    protected $login;
    protected $password;
    protected $is_active;
    protected $is_superuser;
    protected $hash;
    protected $ava;

    function addAva($hash, $id)
    {
        $this->deleteTable();
        $this->id = $id;
        $this->ava = $hash;
        return $this->update();
    }

    public function deleteTable()
    {
        $this->id = null;
        $this->password = null;
        $this->login = null;
        $this->is_active = null;
        $this->is_superuser = null;
        $this->ava = null;
    }

    public function in_user($id)
    {
        $this->deleteTable();
        $this->id = $id;
        $this->is_active = 't';
        $result = $this->update();
        if (!$result) return false;
        return true;
    }

    public function add_user($array)
    {
        $this->deleteTable();
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        $result = $this->save();
        if (!$result) return false;
        $user = $this->result_by(array("login" => $this->login));
        $this->deleteTable();
        $this->id = $user[0]['id'];
        $this->hash = md5($user[0]['id'] . $user[0]['login']);
        $result = $this->update();
        if (!$result) return false;
        return true;
    }


    public function out_user($hash)
    {
        $this->deleteTable();
        $user = $this->result_by(array("hash" => $hash));
        if (!$user) return false;
        $this->id = $user[0]['id'];
        $this->is_active = 'f';
        $result = $this->update();
        return $result;
    }

    public function fieldTable()
    {
        return array(
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'is_active' => $this->is_active,
            'is_superuser' => $this->is_superuser,
            'hash' => $this->hash,
            'ava' => $this->ava
        );
    }
}
