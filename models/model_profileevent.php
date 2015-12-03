<?php

Class Model_profileEvent Extends Model_Base
{
    protected $id;
    protected $user_id;
    protected $coordinates;
    protected $message;
    protected $date_start;
    protected $date_stop;
    protected $address;

    public function allEvents(){
        $this->select(false);
        $result = $this->getAllRows();
        return $result;
    }

    public function addEvent($user, $coordinate)
    {
        $this->deleteTable();
        $this->user_id = $user;
        $this->coordinates = $coordinate;
        $result = $this->save();
        if (!$result) return false;
        $result = $this->result_by(array(
            'coordinates' => $coordinate,
            'user_id' => $user
        ));
        return $result[0]['id'];
    }

    public function deleteTable()
    {
        $this->id = null;
        $this->user_id = null;
        $this->coordinates = null;
        $this->message = null;
        $this->date_start = null;
        $this->date_stop = null;
        $this->address = null;
    }

    function addEventFull($array){
        $this->deleteTable();
        $result = $this->result_by(array(
            'id' => $array['id'],
            'user_id' => $array['user_id'],
        ));
        if(!$result) return false;
        foreach ($array as $key=>$value) {
            $t = $key == 'date_start';
            if($key == 'date_start' || $key =='date_stop') {
                $result = $this->type_normal('DATE', $value);
                $this->$key = $result[0];
            }
            else{
                $this->$key = $value;
            }

        }
        $result = $this->update();
        return $result;
    }

    public function fieldTable()
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'coordinates' => $this->coordinates,
            'message' => $this->message,
            'date_start' => $this->date_start,
            'date_stop' => $this->date_stop,
            'address' => $this->address
        );
    }
}