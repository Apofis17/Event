<?php

define('DS', DIRECTORY_SEPARATOR); // разделитель для путей к файлам
$sitePath = realpath(dirname(__FILE__) . DS);
define('SITE_PATH', $sitePath . DS); // путь к корневой папке сайта
// для подключения к бд
define('DB_USER', 'postgres');
define('DB_PASS', 'psql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'event');
define('PORT', '5432');

