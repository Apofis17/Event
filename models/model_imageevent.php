<?php

    Class Model_imageEvent Extends Model_Base
{
    protected $id;
    protected $event_id;
    protected $image;

    public function imageByEvent($id){
        $str = sprintf('event_id = %s', $id);
        $this->select(array('where'=>$str));
        $result = $this->getAllRows();
        return $result;
    }

    public function addImage($id, $image)
    {
        $this->deleteTable();
        $this->event_id = $id;
        $this->image = $image;
        $result = $this->save();
        if (!$result) return false;
        return true;
    }

    public function deleteTable()
    {
        $this->id = null;
        $this->event_id = null;
        $this->image = null;
    }

    public function fieldTable()
    {
        return array(
            'id' => $this->id,
            'event_id' => $this->event_id,
            'image' => $this->image,
        );
    }
}