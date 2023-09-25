<?php
    namespace biblionetApp\Core;
    class Menu {
        use Model;
        protected $db_table = 'mcpyv_menu';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'menutype', 
            'title', 
            'alias', 
            'note', 
            'path', 
            'link', 
            'type', 
            'published', 
            'parent_id', 
            'level', 
            'component_id', 
            'checked_out', 
            'checked_out_time', 
            'browserNav', 
            'access', 
            'img', 
            'template_style_id', 
            'params', 
            'lft', 
            'rgt', 
            'home', 
            'language',
            'client_id', 
            'publish_up', 
            'publish_down'
        ];        
    }
