<?php
    session_start();
    require "../vendor/autoload.php";
    DEBUG ? ini_set("display_errors", 1) : ini_set("display_errors", 0);
    $app = new App;