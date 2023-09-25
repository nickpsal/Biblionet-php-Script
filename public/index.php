<?php
    session_start();
    require "../app/Core/init.php";
    DEBUG ? ini_set("display_errors", 1) : ini_set("display_errors", 0);

    use biblionetApp\Core\App;
    $app = new App();