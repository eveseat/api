<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

Route::group([
    'namespace' => 'Seat\Api\Http\Controllers',
], function () {

    // Http Routes to the API Key Administration Section
    Route::group([
        'namespace'  => 'Admin',
        'middleware' => 'bouncer:superuser',
        'prefix'     => 'api-admin'
    ], function () {

        Route::get('/', [
            'as'   => 'api-admin.list',
            'uses' => 'ApiAdminController@listTokens']);
        Route::post('/', [
            'as'   => 'api-admin.token.create',
            'uses' => 'ApiAdminController@generateToken']);
        Route::get('/logs/{token_id}', [
            'as'   => 'api-admin.token.logs',
            'uses' => 'ApiAdminController@showLogs']);
        Route::get('/delete/{token_id}', [
            'as'   => 'api-admin.token.delete',
            'uses' => 'ApiAdminController@deleteToken']);

    });

    // Http Routes to the SeAT API itself
    Route::group([
        'namespace'  => 'Api',
        'middleware' => 'api.auth',
        'prefix'     => 'api'
    ], function () {

        // The version 1 API! :D
        Route::group(['namespace' => 'v1', 'prefix' => 'v1'], function () {

            // Define the transfer method before the resource controller
            Route::get('key/transfer/{key_id}/{user_id}', 'ApiKeyController@transfer');
            Route::resource('key', 'ApiKeyController');

            Route::resource('user', 'UserController');
            Route::controller('user/auth', 'AuthenticationController');
            Route::resource('role', 'RoleController');
            Route::controller('role/query', 'RoleLookupController');

            Route::controller('character', 'CharacterController');
            Route::controller('corporation', 'CorporationController');
        });

    });
});
