<?php
    class App{
        private $controller = 'Home';
        private $method = 'index';
        public function __construct()
        {
            $url = $this->splitURL();
            $filename = "../app/Controllers/" . ucfirst($url[0]) . ".php";
            if (file_exists($filename)){
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
                require $filename;
            }else {
                $this->controller = "_404";
                require "../app/Controllers/_404.php";
            }
            $controller = new $this->controller;
            if (isset($url[1])) {
                if(method_exists($controller, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                }
            }
            call_user_func_array([$controller, $this->method], [$url]);
        }

        private function splitURL() {
            $url = $_GET['url'] ?? 'home';
            $url = (explode('/', $url));
            return $url;
        }
    }