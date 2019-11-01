<?php

namespace App\Models\Role;

use Zizaco\Entrust\EntrustRole;

/**
 * App\Models\Role\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission\Permission[] $perms
 * @property-read int|null $perms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends EntrustRole
{
}
