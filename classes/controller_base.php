<?php

Abstract Class Controller_Base
{

    public $vars = array();
    protected $registry;
        protected $template; // шаблон
protected $layouts;

    // в конструкторе подключаем шаблоны

    function __construct($registry)
    {
        $this->registry = $registry;
        // шаблоны
        $this->template = new Template($this->layouts, get_class($this));
    }

    function fileLoad($file)
    {
        $media = SITE_PATH . 'public/static/media' . DS;
        $hash = md5_file($file['tmp_name']);
        $dir1 = $media . substr($hash, 0, 2) . DS;
        $dir2 = $dir1 . substr($hash, 2, 2) . DS;
        if (!file_exists($dir1)) {
            mkdir($dir1);
        }
        if (!file_exists($dir2)) {
            mkdir($dir2);
        }
        $type = explode(".", $file['name']);
        $file['name'] = substr($hash, 4, 32) . '.' . $type[count($type) - 1];
        $t = move_uploaded_file($file['tmp_name'], $dir2 . $file['name']);
        return $dir2 . $file['name'];
    }

    abstract function index();
}
