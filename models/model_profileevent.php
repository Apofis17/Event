<?php

Class Model_profileEvent Extends Model_Base
{
    protected $id;
    protected $user_id;
    protected $address;
    protected $coordinates;
    protected $message;
    protected $date_start;
    protected $date_stop;

    public function addEvent($user, $coordinate)
    {
        $this->deleteTable();
        $this->user_id = $user;
        $this->coordinates = $coordinate;
        $result = $this->save();
        if (!$result) return false;
        $result = $this->event_by(array(
            'coordinates' => $coordinate,
            'user_id' => $user
        ));
        return $result[0]['id'];
    }

    public function deleteTable()
    {
        $this->id = null;
        $this->user_id = null;
        $this->address = null;
        $this->coordinates = null;
        $this->message = null;
        $this->date_start = null;
        $this->date_stop = null;
    }

    public function event_by($array)
    {
        $str = '';
        foreach ($array as $key => $value) {
            $str = $str . sprintf($key . "='%s' , ", $value);
        }
        $pos = strripos($str, ',');
        $str = substr_replace($str, ' ', $pos);
        $str = str_replace(',', 'and', $str);
        $this->select(array('where' => $str));
        $result = $this->getAllRows();
        return $result;
    }

    function addEventFull($array){
        $this->deleteTable();
        $result = $this->event_by(array(
            'id' => $array['id'],
            'user_id' => $array['user_id'],
        ));
        if(empty($result)) return false;
        foreach ($array as $key=>$value) {
            $this->$key = $value;
        }
        $result = $this->save();


    }

    public function fieldTable()
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'address' => $this->address,
            'coordinates' => $this->coordinates,
            'message' => $this->message,
            'date_start' => $this->date_start,
            'date_stop' => $this->date_stop
        );
    }
}