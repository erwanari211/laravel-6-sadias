<?php

return [
    'crud' => [
        'index'  => 'List Channel',
        'create' => 'Tambah',
        'show'   => 'Detail',
        'edit'   => 'Ubah',
        'delete' => 'Hapus',
    ],
    'table' => [
        'columns' => [
            'no'      => 'No',
            'actions' => 'Pilihan',
            'owner'   => 'Pemilik',
            'name'    => 'Nama',
            'slug'    => 'Slug',
        ],
        'delete_confirmation' => 'Hapus Channel?',
    ],
    'form' => [
        'name'        => 'Nama Channel',
        'slug'        => 'Slug',
        'description' => 'Deskripsi',
        'back'        => 'Kembali',
        'save'        => 'Simpan',
        'edit'        => 'Ubah',
    ],
    'attributes' => [
        'owner'       => 'Pemilik',
        'name'        => 'Nama',
        'slug'        => 'Slug',
        'description' => 'Deskripsi',
        'is_active'   => 'Aktif',
    ],
    'messages' => [
        'data_created' => 'Channel telah tersimpan',
        'data_updated' => 'Channel telah diperbarui',
        'data_deleted' => 'Channel telah dihapus',
    ],
];
