<?php
    define("APP_NAME", "Biblionet Script");
    define("APP_DESC", "Biblionet Script Version 1");
    define("DEBUG", true);
    define('biblionetUsername', 'nickpsal@gmail.com');
    define('biblionetPassword', ')5Ac4m%AEtbrd1%z$GJKCjRN');
    if ($_SERVER['SERVER_NAME'] == '127.0.0.1' or  $_SERVER['SERVER_NAME'] == 'localhost') {
        define('ROOT', 'http://127.0.0.1/biblionet_script/public');
        define('URL', 'http://127.0.0.1/biblionet_script');
        define("Json_Path","data/");
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', 'toor');
        define('DB_NAME', 'mvc_db');
    }else {
        define('ROOT', 'https://' . $_SERVER['HTTP_HOST'] . '/biblionet_script/public/');
        define('URL', 'https://' . $_SERVER['HTTP_HOST'] . '/biblionet_script/');
        define("Json_Path","/var/www/vhosts/koyinta.gr/projects.datatex.gr/biblionet_script/public/data/");
        define('DB_HOST', 'localhost');
        define('DB_USER', 'koyinta588443_projects');
        define('DB_PASS', 'gbrCBRM2908');
        define('DB_NAME', 'koyinta588443_projects');
    }
