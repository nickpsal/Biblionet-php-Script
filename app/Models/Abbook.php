<?php
    class  Abbook {
        use Model;
        protected  $db_table = 'mcpyv_abbook';   
        protected $order_col = "id";
        protected $order_type = "desc";
        protected $limit = 50;
        protected $offset = 0;
        protected $update_id = 'id';
        protected $allowedColumns = [
            'asset_id',
            'title',
            'subtitle',
            'alias',
            'ideditor',
            'price',
            'pag',
            'pag_index',
            'user_id',
            'created_by_alias',
            'description',
            'other_info',
            'image',
            'docsfolder',
            'file',
            'year',
            'idlocation',
            'idlibrary',
            'vote',
            'numvote',
            'hits',
            'state',
            'catid',
            'qty',
            'isbn',
            'issn',
            'doi',
            'numpublication',
            'approved',
            'userid',
            'url',
            'url_label',
            'url2',
            'url2_label',
            'url3',
            'url3_label',
            'dateinsert',
            'catalogo',
            'checked_out',
            'checked_out_time',
            'access',
            'metakey',
            'metadesc',
            'metadata',
            'language',
            'ordering',
            'params',
            'note',
            'editedby'
        ];
    }
