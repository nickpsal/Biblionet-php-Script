<?php
    namespace biblionetApp\Core;
    class User
    {
        use Model;
        protected $db_table = 'user';
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'fullname',
            'username',
            'password'
        ];
        protected $insertAllowedColumns = [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'fullname' => 'VARCHAR(255)',
            'username' => 'VARCHAR(255)',
            'password' => 'VARCHAR(255)'
        ];
    }
