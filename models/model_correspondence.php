<?php

Class Model_correspondence Extends Model_Base
{
    protected $id;
    protected $event_id;
    protected $user_id;
    protected $text;
    protected $parent;
    protected $date;


    public function deleteImage($column, $address){
        $str = sprintf("%s = '%s'",$column, $address);
        $result = $this->deleteBySelect(array('where'=>$str));
        return $result;
    }

    public function addMessage($array){
        foreach($array as $key=>$value){
            $this->$key = $value;
        }
        $result = $this->save();
        return $result;
    }

    public function ajaxMessage($min, $event){
        $str = sprintf('id > %s and event_id = %s',$min, $event);
        $this->select(array('where'=>$str, 'order'=>'id'));
        $result = $this->getAllRows();
        return $result;
    }

    public function deleteTable()
    {
        $this->id = null;
        $this->event_id = null;
        $this->text = null;
        $this->user_id = null;
        $this->parent = null;
        $this->date = null;
    }

    public function fieldTable()
    {
        return array(
            'id' => $this->id,
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'parent' => $this->parent,
            'date' => $this->date,
        );
    }
}