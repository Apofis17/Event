<?php
// включим отображение всех ошибок
error_reporting (E_ALL); 

// подключаем конфиг
include ('../config.php'); 

// Соединяемся с БД
$dbObject = new PDO('pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
$dbObject->exec('SET CHARACTER SET utf8');

// подключаем ядро сайта
include (SITE_PATH . DS . 'core' . DS . 'core.php');

// Загружаем router
$router = new Router($registry);
// записываем данные в реестр
$registry->set('router', $router);
// задаем путь до папки контроллеров.
$router->setPath(SITE_PATH . 'controllers');
// запускаем маршрутизатор
$router->start();
