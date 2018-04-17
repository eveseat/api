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

namespace Seat\Api\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiToken.
 * @package Seat\Api\Models
 */
class ApiToken extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['token', 'allowed_src', 'comment'];

    /**
     * Make sure we cleanup logs on delete.
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {

        // Cleanup the user
        $this->logs()->delete();

        return parent::delete();
    }

    /**
     * Return the logs for a token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {

        return $this->hasMany(ApiTokenLog::class);
    }
}
