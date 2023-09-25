<?php
    namespace biblionetApp\Core;
    trait Controller {
        public function view($view, $data = []){
            $header = "../app/Views/includes/header.php";
            $filename = "../app/Views/" . $view . ".view.php";
            $footer = "../app/Views/includes/footer.php";
            if (file_exists($header)) {
                require $header;
            }
            if (file_exists($filename)){
                require $filename;
            }else {
                require "../app/Views/404.view.php";
            }
            if (file_exists($footer)) {
                require $footer;
            }
        }
    }