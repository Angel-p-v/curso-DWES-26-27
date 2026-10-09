<?php

class UserRepository{

    public static function getUserById($id){
        $db=DB::connect();
        $query="SELECT * FROM users WHERE id=$id";
        $result=$db->query($query);
        if($user=$result->fetch_assoc()){
            return new User($user['id'], $user['username']);
        }else{
            return null;
        }
    }
}