<?php
/**
 * Description of database
 * Just connect to MySQL DB with config variables
 * @author Ondřej
 */
class database extends PDO {    
    function __construct(){
        $dns = config::db_driver().":dbname=".config::db_name().";host=".config::db_server();
        parent::__construct($dns, config::db_user(), config::db_password());
        $this->exec("set names utf8");
    }
}
