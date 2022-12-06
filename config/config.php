<?php
/**
 * Description of config
 * Class with static variables just for continue using
 * @author Ondřej
 */
class config {
    /*DB Configuration*/
    static private $db_driver = ""; //PDO driver
    static private $db_user = ""; //DB USER
    static private $db_password = ""; //DB PASS
    static private $db_name = ""; //DB NAME
    static private $db_server = ""; //DB SERVER
    
    public static function db_driver(){
        return self::$db_driver;
    }
    public static function db_user(){
        return self::$db_user;
    }
    public static function db_password(){
        return self::$db_password;
    }
    public static function db_name(){
        return self::$db_name;
    }
    public static function db_server(){
        return self::$db_server;
    }
}
