<?php
    namespace biblionetApp\Core\Controller;
    class _404{
        use Controller;
        public function index($data = []){
            $this->view('404'); 
        }
    }