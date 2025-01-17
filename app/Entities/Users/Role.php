<?php

namespace App\Entities\Users;

use App\Entities\ReferenceAbstract;

/**
 * Role reference class.
 */
class Role extends ReferenceAbstract
{
    /**
     * List of user role.
     *
     * @var array
     */
    protected static $lists = [
        1 => 'admin',
        2 => 'worker',
        3 => 'client'
    ];

    /**
     * Get role name by id.
     *
     * @param  int  $singleId
     * @return string
     */
    public static function getNameById($roleId)
    {
        return trans('user.roles.'.static::$lists[$roleId]);
    }

    /**
     * Get real role name by id.
     *
     * @param  int  $singleId
     * @return string
     */
    public static function getRealRoleNameById($roleId)
    {
        return static::$lists[$roleId];
    }

    /**
     * Get role id by name.
     *
     * @param  string  $roleName
     * @return string
     */
    public static function getIdByName($roleName)
    {
        return array_search($roleName, static::$lists);
    }

    /**
     * Get user roles in array format.
     *
     * @return array
     */
    public static function toArray()
    {
        $lists = [];
        foreach (static::$lists as $key => $value) {
            $lists[$key] = $value;
        }

        return $lists;
    }
}
