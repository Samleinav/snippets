<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Snippets\Http\Controllers\SnippetsController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'snippets', 'as' => 'snippets.'], function () {
        Route::match(['get', 'post'], 'toggle/{snippet}', [
            'as' => 'toggle',
            'uses' => 'Botble\Snippets\Http\Controllers\SnippetsController@toggle',
            'permission' => 'snippets.edit',
        ]);
        
        Route::get('settings', [
            'as' => 'settings',
            'uses' => 'Botble\Snippets\Http\Controllers\SnippetsController@settings',
            'permission' => 'snippets.index',
        ]);
        
        Route::post('rescue', [
            'as' => 'rescue',
            'uses' => 'Botble\Snippets\Http\Controllers\SnippetsController@rescue',
            'permission' => 'snippets.index',
        ]);
        Route::post('run-preview', [
            'as' => 'run-preview',
            'uses' => 'Botble\Snippets\Http\Controllers\SnippetsController@runPreview',
            'permission' => 'snippets.edit',
        ]);
        Route::resource('', 'Botble\Snippets\Http\Controllers\SnippetsController')->parameters(['' => 'snippets']);
    });
});
