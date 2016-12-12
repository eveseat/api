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

    Route::group(['middleware' => 'web'], function () {

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

            // `Route::controller` Macros were removed in 5.3. Sad.
            Route::group(['prefix' => 'user/auth'], function () {

                Route::post('login', 'AuthenticationController@postLogin');
            });

            Route::group(['prefix' => 'role'], function () {

                Route::get('/', 'RoleController@getIndex');
                Route::get('/detail/{id}', 'RoleController@getDetail');
                Route::post('/new', 'RoleController@postNew');
                Route::delete('/remove/{role_id}', 'RoleController@deleteRemove');
                Route::get('/grant-user-role/{user_id}/{role_id}', 'RoleController@getGrantUserRole');
                Route::get('/revoke-user-role/{user_id}/{role_id}', 'RoleController@getRevokeUserRole');
            });

            Route::group(['prefix' => 'role/query'], function () {

                Route::get('/permissions', 'RoleLookupController@getPermissions');
                Route::get('/role-check/{user_identifier}/{role_identifier}', 'RoleLookupController@getRoleCheck');
                Route::get('/permission-check/{user_identifier}/{role_identifier}', 'RoleLookupController@getPermissionCheck');
            });

            Route::group(['prefix' => 'character'], function () {

                Route::get('/assets/{character_id}', 'CharacterController@getAssets');
                Route::get('/bookmarks/{character_id}', 'CharacterController@getBookmarks');
                Route::get('/channels/{character_id}', 'CharacterController@getChannels');
                Route::get('/contacts/{character_id}', 'CharacterController@getContacts');
                Route::get('/info/{character_id}', 'CharacterController@getInfo');
                Route::get('/industry/{character_id}', 'CharacterController@getIndustry');
                Route::get('/killmails/{character_id}', 'CharacterController@getKillmails');
                Route::get('/market-orders/{character_id}', 'CharacterController@getMarketOrders');
                Route::get('/contracts/{character_id}', 'CharacterController@getContracts');
                Route::get('/sheet/{character_id}', 'CharacterController@getSheet');
                Route::get('/skills/{character_id}', 'CharacterController@getSkills');
                Route::get('/skill-in-training/{character_id}', 'CharacterController@getSkillInTraining');
                Route::get('/skill-queue/{character_id}', 'CharacterController@getSkillQueue');
                Route::get('/wallet-journal/{character_id}', 'CharacterController@getWalletJournal');
                Route::get('/wallet-transactions/{character_id}', 'CharacterController@getWalletTransactions');
                Route::get('/employment-history/{character_id}', 'CharacterController@getEmploymentHistory');
                Route::get('/implants/{character_id}', 'CharacterController@getImplants');
                Route::get('/jump-clones/{character_id}', 'CharacterController@getJumpClones');
                Route::get('/account-info/{character_id}', 'CharacterController@getAccountInfo');
                Route::get('/mail/{character_id}', 'CharacterController@getMail');
                Route::get('/notifications/{character_id}', 'CharacterController@getNotifications');
                Route::get('/pi/{character_id}', 'CharacterController@getPi');
                Route::get('/standings/{character_id}', 'CharacterController@getStandings');
                Route::get('/research/{character_id}', 'CharacterController@getResearch');
                Route::get('/calendar/{character_id}', 'CharacterController@getCalendar');
            });

            Route::group(['prefix' => 'corporation'], function () {

                Route::get('/all', 'CorporationController@getAll');
                Route::get('/assets/{corporation_id}', 'CorporationController@getAssets');
                Route::get('/assets-by-location/{corporation_id}', 'CorporationController@getAssetsByLocation');
                Route::get('/assets-contents/{corporation_id}/{parent_asset_id?}/{parent_item_id?}',
                    'CorporationController@getAssetsByLocation');
                Route::get('/bookmarks/{corporation_id}', 'CorporationController@getBookmarks');
                Route::get('/contacts/{corporation_id}', 'CorporationController@getContacts');
                Route::get('/contracts/{corporation_id}', 'CorporationController@getContracts');
                Route::get('/divisions/{corporation_id}', 'CorporationController@getDivisions');
                Route::get('/industry/{corporation_id}', 'CorporationController@getIndustry');
                Route::get('/killmails/{corporation_id}', 'CorporationController@getKillmails');
                Route::get('/market-orders/{corporation_id}', 'CorporationController@getMarketOrders');
                Route::get('/member-security/{corporation_id}', 'CorporationController@getMemberSecurity');
                Route::get('/member-security-logs/{corporation_id}', 'CorporationController@getMemberSecurityLogs');
                Route::get('/member-security-titles/{corporation_id}', 'CorporationController@getMemberSecurityTitles');
                Route::get('/pocos/{corporation_id}', 'CorporationController@getPocos');
                Route::get('/sheet/{corporation_id}', 'CorporationController@getSheet');
                Route::get('/standings/{corporation_id}', 'CorporationController@getStandings');
                Route::get('/starbases/{corporation_id}/{starbase_id?}', 'CorporationController@getStarbases');
                Route::get('/wallet-divisions/{corporation_id}', 'CorporationController@getWalletDivisions');
                Route::get('/wallet-journal/{corporation_id}', 'CorporationController@getWalletJournal');
                Route::get('/wallet-transactions/{corporation_id}', 'CorporationController@getWalletTransactions');
            });
        });

    });
});
