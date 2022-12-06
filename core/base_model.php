<?php

/**
 * Description of base_model
 *
 * @author Comancheo
 */
class base_model {

    protected $db; // variable of DB class
    protected $a; //array of item columns
    protected $table; //DB table for model
    protected $columns; //DB columns names - field names of this->a

    /*
     * Estabilish DB connection
     * Eventualy fill one Item (like getOne, but by array $a)
     */

    public function __construct($a = null) {
        $this->db = new database();
        if ($a) {
            $this->a = $a;
        }
        $this->getColumns();
        return $this;
    }

    protected function getColumns() {
        if (empty($this->columns)) {
            foreach ($this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='" . config::db_name() . "' AND `TABLE_NAME`='" . $this->table . "'", PDO::FETCH_ASSOC) as $col) {
                $this->columns[] = $col["COLUMN_NAME"];
            }
        }
        return $this->columns;
    }

    /*
     * Get one item from DB
     * if ID not set, then get empty Item
     */

    public function getOne($id = null) {
        if (empty($id) && !empty($this->a['id'])) {
            $id = $this->a['id'];
        } elseif(empty($id) && empty($this->a['id'])){
            //prepare empty one
            $this->a = array();
            foreach ($this->columns as $name) {
                $this->a[$name] = "";
            }
            return $this;
        }
        $statement = $this->db->prepare("SELECT * FROM `" . $this->table . "` WHERE `id`=:id LIMIT 1");
        $statement->bindParam(':id', $id);
        $statement->execute();
        $this->a = $statement->fetch(PDO::FETCH_ASSOC);
        return $this;
    }

    /*
     * Get All items in DB and put them in array of objects
     */

    public function getAll() {
        $all = array();
        foreach ($this->db->query("SELECT `id` FROM `" . $this->table . "`", PDO::FETCH_ASSOC) as $item) {
            $all[] = clone $this->getOne($item['id']);
        }
        return $all;
    }

    /*
     * Get All items in DB and put them in array of objects
     */

    public function getAllArray() {
        $all = array();
        foreach ($this->db->query("SELECT * FROM `" . $this->table . "`", PDO::FETCH_ASSOC) as $item) {
            $all[] = $item;
        }
        return $all;
    }
    
    /*
     * Insert Or Update Item by ID
     */

    public function saveOne($a = null) {
        if (empty($a) && !empty($this->a)) {
            $a = $this->a;
        }
        $wtodo = "UPDATE ";
        $where = " WHERE `id`='" . $a['id'] . "'";
        if (empty($a['id'])) {
            $wtodo = "INSERT INTO ";
            $where = "";
        }
        $set = "";
        foreach ($this->columns as $name) {
            $set .= " `" . $name . "`=:" . $name . ",";
        }
        if (substr($set, -1) == ",") {
            $set = substr($set, 0, -1);
        }
        
        $statement = $this->db->prepare($wtodo . "`" . $this->table . "` SET " . $set . $where);
        foreach ($this->columns as $name) {
            $statement->bindParam(':' . $name, $a[$name]);
        }
        $statement->execute();
    }

    /*
     * Delete Item by id 
     */

    public function delete($id = null) {
        $statement = $this->db->prepare("DELETE FROM `" . $this->table . "` WHERE `id` = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    /*
     *  Retruns field of $this->a 
     */

    public function a($name,$value=null) {
        if(isset($value)){
            $this->a[$name]=$value;
        }
        if (isset($this->a[$name])) {
            return $this->a[$name];
        } else {
            return "";
        }
    }

}
