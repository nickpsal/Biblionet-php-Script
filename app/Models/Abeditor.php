<?php
    class  Abeditor {
        use Model;
        protected  $db_table = 'mcpyv_abeditor';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'id',
            'name',
            'alias',
            'description',
            'checked_out',
            'checked_out_time',
            'metakey',
            'metadesc',
            'state',
            'language'
        ];
    }
