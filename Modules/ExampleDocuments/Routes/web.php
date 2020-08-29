<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'example-documents', 'as' => 'example.documents.'], function () {
    Route::get('/', 'ExampleDocumentsController@index');

    Route::group(['prefix' => 'form-components'], function () {
        Route::get('/', 'FormComponentBootstrapController@index');
        Route::post('/', 'FormComponentBootstrapController@store')->name('form-components.store');
    });

    Route::get('/flash-message', 'FlashController@index');
    Route::get('/sweet-alert', 'SweetAlertController@index');
    Route::get('/pdf', 'PdfController@index');

    Route::resource('/upload-file', 'UploadFileController');
    Route::resource('/image-resize', 'ImageResizeController');

    Route::get('/export-excel', 'ExportExcelController@export')->name('excel.exports.export');
    Route::get('/import-excel', 'ImportExcelController@displayImportForm')->name('excel.imports.form');
    Route::post('/import-excel', 'ImportExcelController@import')->name('excel.imports.import');
});
