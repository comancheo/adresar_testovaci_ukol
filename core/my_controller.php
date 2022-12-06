<?php
/**
 * Description of my_controler
 *
 * @author Comancheo
 */
class my_controler {

    public $data; //array of variables for render

    public function __construct() {
    }

    public function e403($atributes) {
        header('HTTP/1.0 403 Forbidden');
        echo "ERROR 403 - Forbidden";
    }

    public function e404($atributes) {
        header("HTTP/1.0 404 Not Found");
        echo "ERROR 404 - Page not found"; //No matching function -> Error 404
    }
    
    /* Preparing method for view file */

    public function render($view_file = "./views/view.php") {
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }
        require_once $view_file;
    }
}
