<?php
    namespace biblionetApp\Core;
    class  Abbookauth {
        use Model;
        protected  $db_table = 'mcpyv_abbookauth';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'idbook',
            'idauth'
        ];
    }
