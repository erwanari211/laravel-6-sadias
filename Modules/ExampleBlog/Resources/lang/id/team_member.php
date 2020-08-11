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

        'user_id' => 'User ID',
        'team_id' => 'Team ID',
        'role_name' => 'Nama Role',
        'is_active' => 'Aktif',
        'email' => 'Email',

    ],
    'form' => [
        'dropdown' => [
            'roles' => [
                'admin' => 'Admin',
                'editor' => 'Editor',
                'author' => 'Author',
            ]
        ]
    ],
    'messages' => [
        'user_already_exists' => 'User sudah ada',
        'owner_cannot_be_updated' => 'Data owner tidak dapat diubah',
    ],
];
