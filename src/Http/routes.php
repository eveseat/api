<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

Route::group([
    'namespace' => 'Seat\Api\Http\Controllers',
], function () {

    Route::group(['middleware' => 'web'], function () {

        // Http Routes to the API Key Administration Section
        Route::group([
            'namespace'  => 'Admin',
            'middleware' => ['auth', 'bouncer:superuser'],
            'prefix'     => 'api-admin',
        ], function () {

            Route::get('/', [
                'as'   => 'api-admin.list',
                'uses' => 'ApiAdminController@listTokens', ]);
            Route::post('/', [
                'as'   => 'api-admin.token.create',
                'uses' => 'ApiAdminController@generateToken', ]);
            Route::get('/logs/{token_id}', [
                'as'   => 'api-admin.token.logs',
                'uses' => 'ApiAdminController@showLogs', ]);
            Route::get('/delete/{token_id}', [
                'as'   => 'api-admin.token.delete',
                'uses' => 'ApiAdminController@deleteToken', ]);

        });
    });

    // Http Routes to the SeAT API itself
    Route::group([
        'namespace'  => 'Api',
        'middleware' => 'api.auth',
        'prefix'     => 'api',
    ], function () {

        // The version 2 API :D
        Route::group(['namespace' => 'v2', 'prefix' => 'v2'], function () {

            Route::group(['prefix' => 'users'], function () {

                Route::get('/{user_id?}', 'UserController@getUsers')->where(['user_id' => '[0-9]+']);
                Route::get('/groups/{group_id?}', 'UserController@getGroups');
                Route::post('/new', 'UserController@postNew');
            });

            Route::group(['prefix' => 'roles'], function () {

                Route::get('/', 'RoleController@getIndex');
                Route::get('/detail/{role_id}', 'RoleController@getDetail');
                Route::post('/new', 'RoleController@postNew');
                Route::delete('/delete/{role_id}', 'RoleController@deleteRole');
                Route::get('/grant-user-role/{user_id}/{role_id}', 'RoleController@getGrantUserRole');
                Route::get('/revoke-user-role/{user_id}/{role_id}', 'RoleController@getRevokeUserRole');
                Route::post('/affiliation/character', 'RoleController@postAddCharacterAffiliation');
                Route::post('/affiliation/corporation', 'RoleController@postAddCorporationAffiliation');

                Route::group(['prefix' => 'query'], function () {

                    Route::get('/permissions', 'RoleLookupController@getPermissions');
                    Route::get('/role-check/{character_id}/{role_name}', 'RoleLookupController@getRoleCheck');
                    Route::get('/permission-check/{character_id}/{permission_name}', 'RoleLookupController@getPermissionCheck');
                });
            });

            Route::group(['prefix' => 'killmails'], function () {

                Route::get('/detail/{killmail_id}', 'KillmailsController@getDetail');
            });

            Route::group(['prefix' => 'character'], function () {

                Route::get('/assets/{character_id}/{item_id?}', 'CharacterController@getAssets');
                Route::get('/bookmarks/{character_id}', 'CharacterController@getBookmarks');
                Route::get('/contacts/{character_id}', 'CharacterController@getContacts');
                Route::get('/industry/{character_id}', 'CharacterController@getIndustry');
                Route::get('/killmails/{character_id}/{killmail_id?}', 'CharacterController@getKillmails');
                Route::get('/market-orders/{character_id}', 'CharacterController@getMarketOrders');
                Route::get('/contracts/{character_id}', 'CharacterController@getContracts');
                Route::get('/sheet/{character_id}', 'CharacterController@getSheet');
                Route::get('/skills/{character_id}', 'CharacterController@getSkills');
                Route::get('/skill-queue/{character_id}', 'CharacterController@getSkillQueue');
                Route::get('/wallet-journal/{character_id}', 'CharacterController@getWalletJournal');
                Route::get('/wallet-transactions/{character_id}', 'CharacterController@getWalletTransactions');
                Route::get('/corporation-history/{character_id}', 'CharacterController@getCorporationHistory');
                Route::get('/jump-clones/{character_id}', 'CharacterController@getJumpClones');
                Route::get('/mail/{character_id}', 'CharacterController@getMail');
                Route::get('/notifications/{character_id}', 'CharacterController@getNotifications');
            });

            Route::group(['prefix' => 'corporation'], function () {

                Route::get('/assets/{corporation_id}', 'CorporationController@getAssets');
                Route::get('/bookmarks/{corporation_id}', 'CorporationController@getBookmarks');
                Route::get('/contacts/{corporation_id}', 'CorporationController@getContacts');
                Route::get('/contracts/{corporation_id}', 'CorporationController@getContracts');
                Route::get('/industry/{corporation_id}', 'CorporationController@getIndustry');
                Route::get('/killmails/{corporation_id}', 'CorporationController@getKillmails');
                Route::get('/market-orders/{corporation_id}', 'CorporationController@getMarketOrders');
                Route::get('/member-tracking/{corporation_id}', 'CorporationController@getMemberTracking');
                Route::get('/sheet/{corporation_id}', 'CorporationController@getSheet');
                Route::get('/wallet-journal/{corporation_id}', 'CorporationController@getWalletJournal');
                Route::get('/wallet-transactions/{corporation_id}', 'CorporationController@getWalletTransactions');
            });
        });

    });
});
