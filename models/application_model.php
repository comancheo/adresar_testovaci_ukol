<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of app_model
 *
 * @author Comancheo
 */
class application_model extends base_model {

    protected $db; // variable of DB class
    protected $a; //array of item columns
    protected $table="adresar"; //DB table for model
    protected $columns; //DB columns names - field names of this->a

    public function __construct($a = null) {
        parent::__construct($a);
    }
}
