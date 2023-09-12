<?php 
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
            'InsertedPage'            
        ];        
    }
