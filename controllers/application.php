<?php

/*
 * 
 */

class application extends my_controler {

    protected $app; // variable of DB class

    public function __construct() {
        parent::__construct();
        $this->app = new application_model();
        $this->uri = new uri();
    }

    public function index($atributes) { //Indexpage
        $this->data['app'] = $this;
        $this->data['items'] = $this->app->getAll();
        $this->render("./views/view.php");
    }

    public function ajax_update_new() {
        $post = filter_input_array(INPUT_POST);
        if (isset($post['adresar'])) {
            foreach ($post['adresar'] as $key => $value) {
                $id = null;
                $this->app->a("id", $id);
                if (strpos($key, "new") === FALSE) {
                    $id = $key;
                    $this->app->a("id", $key);
                }
                $new = $this->app->getOne($id);
                $new->a("id", $id);
                $new->a("name",htmlspecialchars($value['name']));
                $new->a("surname", htmlspecialchars($value['surname']));
                $new->a("email'", htmlspecialchars($value['email']));
                $new->a("phone", htmlspecialchars($value['phone']));
                $new->a("note", htmlspecialchars($value['note']));
                $new->saveOne();
            }
        }
    }

    public function add() {
        $new = $this->app->getOne(null);
        $new->a("id", null);
        $new->a("name", htmlspecialchars("jméno"));
        $new->a("surname", htmlspecialchars("příjmení"));
        $new->a("email", htmlspecialchars("comancheo@seznam.cz"));
        $new->a("phone", htmlspecialchars("800900700"));
        $new->a("note", htmlspecialchars("TESTEST"));
        $new->saveOne();
        $this->uri->redirect("/");
    }

    public function ajax_refresh_items() {
        echo json_encode($this->app->getAllArray());
    }

    public function ajax_delete_item() {
        $post = filter_input_array(INPUT_POST);
        if (isset($post['adresar'])) {
            foreach ($post['adresar'] as $key => $value) {
                $id = $key;
                if (isset($value["delete"]) && $value["delete"] == $id) {
                    $this->app->delete($id);
                }
            }
        }
    }

}
