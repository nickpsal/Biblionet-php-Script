<?php
    namespace biblionetApp\Core;
    class biblionetScript {
        use Model;
        protected $db_table = 'biblionetScript';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'lastDate',
            'InsertedMonth',
            'InsertedYear',
            'InsertedPage',     
            'InsertedAuthors',
            'InsertedCategories',
            'InsertedPublishers',
            'InsertedBooks'
        ];        
        protected $insertAllowedColumns = [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'lastDate' => 'VARCHAR(255)',
            'InsertedMonth' => 'VARCHAR(255)',
            'InsertedYear' => 'VARCHAR(255)',
            'InsertedPage' => 'VARCHAR(255)',     
            'InsertedAuthors' => 'VARCHAR(255)',
            'InsertedCategories' => 'VARCHAR(255)',
            'InsertedPublishers' => 'VARCHAR(255)',
            'InsertedBooks' => 'VARCHAR(255)'
        ]; 
    }
