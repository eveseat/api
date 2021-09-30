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

namespace Seat\Api;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Seat\Api\Http\Middleware\ApiRequest;
use Seat\Api\Http\Middleware\ApiToken;
use Seat\Services\AbstractSeatPlugin;

/**
 * Class ApiServiceProvider.
 *
 * @package Seat\Api
 */
class ApiServiceProvider extends AbstractSeatPlugin
{
    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    public function boot(Router $router)
    {

        $this->add_routes();

        $this->apply_custom_configuration();

        $this->add_middleware($router);

        $this->add_views();

        $this->add_custom_validators();

        $this->add_migrations();

        $this->add_translations();
    }

    /**
     * Apply any configuration overrides to those config/
     * files published using php artisan vendor:publish.
     *
     * In the case of this service provider, this is mostly
     * configuration items for L5-Swagger.
     */
    public function apply_custom_configuration()
    {

        // Tell L5-swagger where to find annotations. These form
        // part of the controllers themselves.
        $this->registerApiAnnotationsPath([
            __DIR__ . '/Http/Controllers/Api/v2',
            __DIR__ . '/Http/Resources',
        ]);

        config(['l5-swagger.swagger_version' => '3.0']);

        // Use base host configured in the .env file for the swagger host.
        config(['l5-swagger.constants.L5_SWAGGER_CONST_HOST' => sprintf('%s/api', config('app.url'))]);

        // SwaggerUI long description.
        config([
            'l5-swagger.constants.L5_SWAGGER_DESCRIPTION' => 'SeAT API Documentation. All endpoints require an API key.',
        ]);
    }

    /**
     * Include the routes.
     */
    public function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Include the middleware needed.
     *
     * @param $router
     */
    public function add_middleware($router)
    {

        // Authenticate checks that the token is valid
        // from an allowed IP address
        $router->aliasMiddleware('api.auth', ApiToken::class);

        // Ensure incoming request is formed using JSON
        $router->aliasMiddleware('api.request', ApiRequest::class);

    }

    /**
     * Set the path and namespace for the views.
     */
    public function add_views()
    {

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'api');
    }

    /**
     * Add custom validator that are not part of Laravel core.
     */
    public function add_custom_validators()
    {
        Validator::extend('base64image', 'Seat\Api\Http\Validation\Image@validate');
    }

    /**
     * Add the packages translation files.
     */
    public function add_translations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'api');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // Merge the config with anything in the main app
        $this->mergeConfigFrom(
            __DIR__ . '/Config/api.config.php', 'api.config');

        // Include this packages menu items
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
    }

    /**
     * Set the path for migrations which should
     * be migrated by laravel. More informations:
     * https://laravel.com/docs/5.5/packages#migrations.
     */
    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SeAT API';
    }

    /**
     * Return the plugin repository address.
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/eveseat/api';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'api';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'eveseat';
    }
}
