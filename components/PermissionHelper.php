<?php

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;

class PermissionHelper
{
    private static $permissionsAndRoles;

    static function per($name){
        return Yii::$app->user->can($name);
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getRolesAndPermissionsByUser(int $userId): array
    {
        if (self::$permissionsAndRoles === null) {
            $authManager = Yii::$app->getAuthManager();

            $permissions = $authManager->getPermissionsByUser($userId);
            $roles = $authManager->getRolesByUser($userId);
            $userPermissionsAndRoles = ArrayHelper::merge($permissions, $roles);
            foreach ($roles as $roleName => $roleArray) {
                $userPermissionsAndRoles = ArrayHelper::merge($userPermissionsAndRoles, $authManager->getChildRoles($roleName));
            }
            self::$permissionsAndRoles = $userPermissionsAndRoles;
        }
        return self::$permissionsAndRoles;
    }

    public static function can($permissionName)
    {
        $userId = Yii::$app->user->isGuest;
        if($userId){
            return false;
        }
        if (Yii::$app->user->id === 1 && Yii::$app->user->identity->username === "admin") {
            return true;
        }
        if (Yii::$app->user->id === 1 && Yii::$app->user->identity->username === "admin") {
            return false;
        }
        $arr = self::getRolesAndPermissionsByUser(Yii::$app->user->identity->id);
        return isset($arr[$permissionName]);
    }

    public static function orCan($permissionNames = [])
    {
        $userId = Yii::$app->user->isGuest;
        if($userId){
            return false;
        }
        if (Yii::$app->user->id === 1 && Yii::$app->user->identity->username === "admin") {
            return true;
        }
        $arr = self::getRolesAndPermissionsByUser(Yii::$app->user->identity->id);
        foreach ($permissionNames as $permissionName) {
            if (isset($arr[$permissionName])) {
                return true;
            }
        }
        return false;
    }
}