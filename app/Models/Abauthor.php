<?php
    class Abauthor {
        use Model;
        protected $db_table = 'mcpyv_abauthor';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'lastname',
            'name',
            'alias',
            'image',
            'description',
            'checked_out',
            'checked_out_time',
            'metakey',
            'metadesc',
            'state',
            'language'
        ];        
    }
