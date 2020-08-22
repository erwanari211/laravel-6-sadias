<?php

return [
    'crud' => [
        'index'  => 'List',
        'create' => 'Tambah',
        'show'   => 'Detail',
        'edit'   => 'Ubah',
        'delete' => 'Hapus',
    ],
    'attributes' => [

        'author_id' => 'Author',
        'unique_code' => 'Kode Unik',
        'title' => 'Judul',
        'slug' => 'Slug',
        'content' => 'Isi',
        'status' => 'Status',

    ],

    'form' => [
        'dropdown' => [
            'statuses' => [
                'draft' => 'Draft',
                'published' => 'Dipublish',
                'archived' => 'Diarsipkan',
            ]
        ]
    ]
];
