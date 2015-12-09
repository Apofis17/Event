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

    public function allEvents($id, $stat){
        if($stat == 1){
            $str = 'user_id = '.$id;
            $this->select(array('where'=>$str,'ORDER'=>'id'));
        }else{
            $this->select(array('ORDER'=>'id'));
        }
        $result = $this->getAllRows();
        return $result;
    }

    public  function eventByUser($id){
        $str = 'user_id = '.$id;
        $this->select(array('where'=>$str));
        $result = $this->getAllRows();
        if(!$result) return false;
        return count($result);
    }

    public function userByEvent($id){
        $str = 'id = '.$id;
        $this->select(array('where'=>$str));
        $result = $this->getOneRow();
        if(!$result) return false;
        return $result['user_id'];
    }

    public function  deleteAllEvent($id, $user){
        $result = $this->result_by(array(
            'id' => $id,
            'user_id' => $user
        ));
        if (!$result) return false;
        $str = sprintf('id = %s and user_id = %s', $id, $user);
        $result = $this->deleteBySelect(array('where'=>$str));
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
            if($key == 'date_start' || $key =='date_stop') {
                if (!empty($value)) {
                    $result = $this->type_normal('DATE', $value);
                    $this->$key = $result[0];
                }
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