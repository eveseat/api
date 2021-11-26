<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Seat\Api\Http\Controllers',
], function () {

    Route::group(['middleware' => 'web'], function () {

        // Http Routes to the API Key Administration Section
        Route::group([
            'namespace'  => 'Admin',
            'middleware' => ['auth', 'can:global.superuser'],
            'prefix'     => 'api-admin',
        ], function () {
            Route::get('/')
                ->name('api-admin.list')
                ->uses('ApiAdminController@listTokens');

            Route::post('/')
                ->name('api-admin.token.create')
                ->uses('ApiAdminController@generateToken');
            Route::get('/logs/{token_id}')
                ->name('api-admin.token.logs')
                ->uses('ApiAdminController@show');
            Route::get('/delete/{token_id}')
                ->name('api-admin.token.delete')
                ->uses('ApiAdminController@deleteToken');
        });
    });

    // Http Routes to the SeAT API itself
    Route::group([
        'namespace'  => 'Api',
        'middleware' => ['api.request', 'api.auth'],
        'prefix'     => 'api',
    ], function () {

        // The version 2 API :D
        Route::group(['namespace' => 'v2', 'prefix' => 'v2'], function () {

            Route::group(['prefix' => 'users'], function () {

                Route::post('/')->uses('UserController@postNewUser');
                Route::delete('/{user_id}')->uses('UserController@deleteUser');

                Route::get('/')->uses('UserController@getUsers');
                Route::get('/{user_id}')->uses('UserController@show')->where(['user_id' => '[0-9]+']);

                Route::get('/configured-scopes')->uses('UserController@getConfiguredScopes');
            });

            Route::group(['prefix' => 'squads'], function () {
                Route::get('/')->uses('SquadController@index');
                Route::get('/{squad_id}')->uses('SquadController@show');
                Route::post('/')->uses('SquadController@store');
                Route::put('/{squad_id}')->uses('SquadController@update');
                Route::delete('/{squad_id}')->uses('SquadController@destroy');
            });

            Route::group(['prefix' => 'roles'], function () {

                Route::get('/')->uses('RoleController@getIndex');
                Route::get('/{role_id}')->uses('RoleController@getDetail')->where('role_id', '[0-9]+');
                Route::post('/')->uses('RoleController@postNew');
                Route::patch('/{role_id}')->uses('RoleController@patch')->where('role_id', '[0-9]+');
                Route::delete('/{role_id}')->uses('RoleController@deleteRole')->where('role_id', '[0-9]+');
                Route::post('/members')->uses('RoleController@postGrantUserRole');
                Route::delete('/members/{user_id}/{role_id}')->uses('RoleController@deleteRevokeUserRole');

                Route::group(['prefix' => 'query'], function () {

                    Route::get('/permissions')->uses('RoleLookupController@getPermissions');
                    Route::get('/role-check/{character_id}/{role_name}')->uses('RoleLookupController@getRoleCheck');
                    Route::get('/permission-check/{character_id}/{permission_name}')->uses('RoleLookupController@getPermissionCheck');
                });
            });

            Route::group(['prefix' => 'killmails'], function () {

                Route::get('/{killmail_id}')->uses('KillmailsController@getDetail');
            });

            Route::group(['prefix' => 'character'], function () {

                Route::get('/assets/{character_id}')->uses('CharacterController@getAssets');
                Route::get('/contacts/{character_id}')->uses('CharacterController@getContacts');
                Route::get('/industry/{character_id}')->uses('CharacterController@getIndustry');
                Route::get('/killmails/{character_id}/{killmail_id?}')->uses('KillmailsController@getCharacterKillmails');
                Route::get('/market-orders/{character_id}')->uses('CharacterController@getMarketOrders');
                Route::get('/contracts/{character_id}')->uses('CharacterController@getContracts');
                Route::get('/sheet/{character_id}')->uses('CharacterController@getSheet');
                Route::get('/skills/{character_id}')->uses('CharacterController@getSkills');
                Route::get('/skill-queue/{character_id}')->uses('CharacterController@getSkillQueue');
                Route::get('/wallet-journal/{character_id}')->uses('CharacterController@getWalletJournal');
                Route::get('/wallet-transactions/{character_id}')->uses('CharacterController@getWalletTransactions');
                Route::get('/corporation-history/{character_id}')->uses('CharacterController@getCorporationHistory');
                Route::get('/jump-clones/{character_id}')->uses('CharacterController@getJumpClones');
                Route::get('/mail/{character_id}')->uses('CharacterController@getMail');
                Route::get('/notifications/{character_id}')->uses('CharacterController@getNotifications');
            });

            Route::group(['prefix' => 'corporation'], function () {

                Route::get('/assets/{corporation_id}')->uses('CorporationController@getAssets');
                Route::get('/contacts/{corporation_id}')->uses('CorporationController@getContacts');
                Route::get('/contracts/{corporation_id}')->uses('CorporationController@getContracts');
                Route::get('/industry/{corporation_id}')->uses('CorporationController@getIndustry');
                Route::get('/killmails/{corporation_id}')->uses('KillmailsController@getCorporationKillmails');
                Route::get('/market-orders/{corporation_id}')->uses('CorporationController@getMarketOrders');
                Route::get('/member-tracking/{corporation_id}')->uses('CorporationController@getMemberTracking');
                Route::get('/sheet/{corporation_id}')->uses('CorporationController@getSheet');
                Route::get('/structures/{corporation_id}')->uses('CorporationController@getStructures');
                Route::get('/wallet-journal/{corporation_id}')->uses('CorporationController@getWalletJournal');
                Route::get('/wallet-transactions/{corporation_id}')->uses('CorporationController@getWalletTransactions');
            });
        });

    });
});
