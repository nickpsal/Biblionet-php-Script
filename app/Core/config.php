<?php
    define("APP_NAME", "Biblionet Script Version 2.6");
    define("APP_DESC", "Biblionet Script Version 2.6");
    define("DEBUG", true);
    define('biblionetUsername', 'nickpsal@gmail.com');
    define('biblionetPassword', ')5Ac4m%AEtbrd1%z$GJKCjRN');
    if ($_SERVER['HTTP_HOST'] == 'www.juliet.gr') {
        define('CoverImagesPath', '/var/www/vhosts/koyinta.gr/juliet.gr/images/biblionet/');
        define('AuthorsImagePath', '/var/www/vhosts/koyinta.gr/juliet.gr/images/biblionet/Authors/');
        define('PDFPath', '/var/www/vhosts/koyinta.gr/juliet.gr/biblionet/public/data/');
        define('ROOT', 'https://' . $_SERVER['HTTP_HOST'] . '/biblionet/public/');
        define('DB_HOST', 'localhost');
        define('DB_USER', 'koyinta588443_juliet.gr');
        define('DB_PASS', 'hT!7Uq@&iX%0F$ak');
        define('DB_NAME', 'koyinta588443_juliet.gr');
    }else {
        define('CoverImagesPath', '/var/www/vhosts/koyinta.gr/projects.datatex.gr/biblionet_testing/images/biblionet/');
        define('AuthorsImagePath', '/var/www/vhosts/koyinta.gr/projects.datatex.gr/biblionet_testing/images/biblionet/Authors/');
        define('PDFPath', '/var/www/vhosts/koyinta.gr/projects.datatex.gr/biblionet_script/public/data/');
        define('ROOT', 'https://' . $_SERVER['HTTP_HOST'] . '/biblionet_script/public/');
        define('URL', 'https://' . $_SERVER['HTTP_HOST'] . '/biblionet_script/');
        define('DB_HOST', 'localhost');
        define('DB_USER', 'koyinta588443_projects');
        define('DB_PASS', 'gbrCBRM2908');
        define('DB_NAME', 'koyinta588443_projects');
    }
