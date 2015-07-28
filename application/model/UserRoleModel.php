<?php

class UserRoleModel{
    /**
     * This function changes given users role to desired value
     * 
     * @param type $user_id
     * @param type $new_user_role
     * @return boolean
     */
    public static function changeUserRole($user_id, $new_user_role){
        if(!$user_id || !$new_user_role){
            return false;
        }
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE users SET user_role = :user_role WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id, ':user_role' => $new_user_role));

        if($query->rowCount() == 1){
            return true;
        }
        //ADD ERROR feedback message
        return false;
    }
    
    /**
     * This function gets user role_id
     * 
     * @param type $user_id
     * @return mix
     */
    public static function getUserRole($user_id){
        if(!$user_id){
            return null;
        }
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT user_role FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        $user_role = $query->fetch();
        if($query->rowCount() == 1){
            return $user_role->user_role;
        }
        //ADD ERROR feedback message
        return null;
    }
    
    /**
     * This function returns given role_id's name
     * 
     * @param type $role_id
     * @return mix
     */
    public static function getRoleName($role_id){
        if(!$role_id){
            return null;
        }
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT role_name FROM roles WHERE role_id = :role_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':role_id' => $role_id));
        $role_name = $query->fetch();
        if($query->rowCount() == 1){
            return $role_name->role_name;
        }
        //ADD ERROR feedback message
        return null;
    }
    
    /**
     * This function takes whole permissions from database and return them
     *  for desired role_id
     * 
     * @param type $role_id
     * @return mix
     */
    public static function getRolePermissions($role_id){
        if(!$role_id){
            return null;
        }
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT permission_granted, permission_name FROM permissions WHERE role_id = :role_id";
        $query = $database->prepare($sql);
        $query->execute(array(':role_id' => $role_id));
        $permissions = array();
        
        foreach($query->fetchAll() as $permission){
            $permissions[$permission->permission_name] = $permission->permission_name;
            $permissions[$permission->permission_name] = $permission->permission_granted;
        }
        
        return (object) $permissions;        
    }
    
    /**
     * This only return wheather desired permission's granted or not
     * 
     * @param type $role_id
     * @param type $permission_name
     * @return boolean
     */
    public static function getRolePermission($role_id, $permission_name){
        if(!$role_id || !$permission_name){
            return false;
        }
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT permission_granted FROM permissions
                 WHERE role_id = :role_id AND permission_name = :permission_name LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':role_id' => $role_id, ':permission_name' => $permission_name));
        $permission = $query->fetch();
        
        if($query->rowCount() == 1){
            return $permission->permission_granted;
        }
        
        return false;        
    }
}
