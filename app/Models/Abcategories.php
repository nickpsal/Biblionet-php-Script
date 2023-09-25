<?php
    namespace biblionetApp\Core;
    class  Abcategories {
        use Model;
        protected  $db_table = 'mcpyv_abcategories';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'asset_id',
            'parent_id',
            'lft',
            'rgt',
            'level',
            'path',
            'extension',
            'title',
            'alias',
            'note',
            'description',
            'published',
            'checked_out',
            'checked_out_time',
            'access',
            'params',
            'metadesc',
            'metakey',
            'metadata',
            'created_user_id',
            'created_time',
            'modified_user_id',
            'modified_time',
            'hits',
            'language',
            'version'
        ];
    }
