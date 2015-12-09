<?php

Abstract Class Model_Base
{

    protected $db;
    protected $table;
    private $dataResult;


    public function __construct($select = false)
    {
        // объект бд коннекта
        global $dbObject;
        $this->db = $dbObject;

        // имя таблицы
        $modelName = get_class($this);
        $arrExp = explode('_', $modelName);
        $tableName = strtolower($arrExp[1]);
        $this->table = $tableName;
    }

    public function type_normal($type, $val){
        $sql = sprintf("SELECT * FROM CAST('%s' as %s)", $val, $type);
        $this->_getResult($sql);
        return $this->getOneRow();
    }

    public function result_by($array)
    {
        $str = '';
        $num = 0;
        foreach ($array as $key => $value) {
            $num ++;
            $str = $str . sprintf($key . "='%s' , ", $value);
        }
        $pos = strripos($str, ',');
        $str = substr_replace($str, ' ', $pos);
        $str = str_replace(',', 'and', $str);
        $this->select(array('where' => $str));
        $result = $this->getAllRows();
        return $result;
    }

    function getAllRows()
    {
        if (!isset($this->dataResult) OR empty($this->dataResult)) return false;
        return $this->dataResult;
    }

    // получить все записи

    function getRowById($id)
    {
        try {
            $db = $this->db;
            $stmt = $db->query("SELECT * from $this->table WHERE id = $id");
            $this->dataResult = $stmt->fetch();
            return $this->getOneRow();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    // получить одну запись

    function getOneRow()
    {
        if (!isset($this->dataResult) OR empty($this->dataResult)) return false;
        return $this->dataResult[0];
    }

    // получить запись по id

    public function save()
    {
        $arrayAllFields = $this->fieldTable();
        $arraySetFields = array();
        $arrayData = array();
        foreach ($arrayAllFields as $key => $value) {
            if (!empty($value)) {
                $arraySetFields[] = $key;
                $arrayData[] = $value;
            }
        }
        $forQueryFields = implode(', ', $arraySetFields);
        $rangePlace = array_fill(0, count($arraySetFields), '?');
        $forQueryPlace = implode(', ', $rangePlace);

        try {
            $db = $this->db;
            $stmt = $db->prepare("INSERT INTO $this->table ($forQueryFields) values ($forQueryPlace)");
            $result = $stmt->execute($arrayData);
        } catch (PDOException $e) {
            echo 'Error : ' . $e->getMessage();
            echo '<br/>Error sql : ' . "'INSERT INTO $this->table ($forQueryFields) values ($forQueryPlace)'";
            exit();
        }

        return $result;
    }

    public function maxId(){
        $sql = 'select * from '.$this->table.'_id';
        try {
            $db = $this->db;
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll();
            return $rows;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    // запись в базу данных

    public function deleteBySelect($select)
    {
        $sql = $this->_getSelect($select);
        try {
            $db = $this->db;
            $result = $db->exec("DELETE FROM $this->table " . $sql);
        } catch (PDOException $e) {
            echo 'Error : ' . $e->getMessage();
            echo '<br/>Error sql : ' . "'DELETE FROM $this->table " . $sql . "'";
            exit();
        }
        return $result;
    }

    // составление запроса к базе данных

    /**
     * @return bool
     */
    public function update()
    {
        $arrayAllFields = array_keys($this->fieldTable());
        $arrayForSet = array();
        foreach ($arrayAllFields as $field) {
            if (!empty($this->$field)) {
                if ($field != 'id') {
                    $arrayForSet[] = $field . " = '" . $this->$field . "'";
                } else {
                    $whereID = $this->$field;
                }
            }
        }
        if (!isset($arrayForSet) OR empty($arrayForSet)) {
            echo "Array data table `$this->table` empty!";
            exit;
        }
        if (!isset($whereID) OR empty($whereID)) {
            echo "Login table `$this->table` not found!";
            exit;
        }

        $strForSet = implode(', ', $arrayForSet);

        try {
            $db = $this->db;
            $stmt = $db->prepare("UPDATE $this->table SET $strForSet WHERE id = $whereID");
            $result = $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error : ' . $e->getMessage();
            echo '<br/>Error sql : ' . "'UPDATE $this->table SET $strForSet WHERE `id` = $whereID'";
            exit();
        }
        return $result;
    }

    // выполнение запроса к базе данных

    protected function select($select)
    {
        if(!empty($select)){
            $sql = $this->_getSelect($select);
            if ($sql) $this->_getResult("SELECT * FROM $this->table" . $sql);
        }
        else {
            $this->_getResult("SELECT * FROM $this->table");
        }
    }

    // уделение записей из базы данных по условию

    private function _getSelect($select)
    {
        if (is_array($select)) {
            $allQuery = array_keys($select);
            array_walk($allQuery, function (&$val) {
                $val = strtoupper($val);
            });

            $querySql = "";
            if (in_array("WHERE", $allQuery)) {
                foreach ($select as $key => $val) {
                    if (strtoupper($key) == "WHERE") {
                        $querySql .= " WHERE " . $val;
                    }
                }
            }

            if (in_array("GROUP", $allQuery)) {
                foreach ($select as $key => $val) {
                    if (strtoupper($key) == "GROUP") {
                        $querySql .= " GROUP BY " . $val;
                    }
                }
            }

            if (in_array("ORDER", $allQuery)) {
                foreach ($select as $key => $val) {
                    if (strtoupper($key) == "ORDER") {
                        $querySql .= " ORDER BY " . $val;
                    }
                }
            }

            if (in_array("LIMIT", $allQuery)) {
                foreach ($select as $key => $val) {
                    if (strtoupper($key) == "LIMIT") {
                        $querySql .= " LIMIT " . $val;
                    }
                }
            }
            return $querySql;
        }
        return false;
    }


    // обновление записи. Происходит по ID

    private function _getResult($sql)
    {
        try {
            $db = $this->db;
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll();
            $this->dataResult = $rows;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}