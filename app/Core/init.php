<?php
    require_once URL . 'vendor/autoload.php';
    require "config.php";
    require "functions.php";
    require_once "Request.php";
    require "Database.php";
    require "Model.php";
    require "Controller.php";
    require "App.php";
    spl_autoload_register(function($classname) {
        require URL . "../app/Models/" . ucfirst($classname) . ".php";
    });