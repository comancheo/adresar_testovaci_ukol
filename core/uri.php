<?php

/**
 * Description of uri
 *
 * @author Comancheo
 */
class uri {

    protected $server_name;
    protected $protocol;
    protected $request_uri;

    public function __construct() {
        $this->server_name = filter_input(INPUT_SERVER, "SERVER_NAME");
        $this->request_uri = filter_input(INPUT_SERVER, "REQUEST_URI");
        $https = (!empty(filter_input(INPUT_SERVER, 'HTTPS')) && filter_input(INPUT_SERVER, 'HTTPS') !== 'off') || filter_input(INPUT_SERVER, 'SERVER_PORT') == 443;
        if ($https) {
            $this->protocol = "https";
        } else {
            $this->protocol = "http";
        }
    }

    /**
     * Metoda pro refresh stránky
     * @param boolean|string $server_name SERVER_NAME
     * @param boolean $query_string Vzít i query string?
     */
    public final function refresh($server_name = false, $query_string = true) {
        if (!$server_name)
            $server_name = $this->server_name;
        if (!$query_string) {
            $e = explode('?', $this->request_uri);
            $uri = $e[0];
        } else {
            $uri = $this->request_uri;
        }
        header('location:' . $this->protocol . '://' . $server_name . $uri);
        exit();
    }

    /**
     * Metoda pro přesměrování
     * @param string $url URL adresa
     * @param boolean $permanent Jedná se o permanentní přesměrování?
     */
    public function redirect($url = "") {
        if (@$url[0] != "/" && strpos($url, 'http://') !== 0) {
            $url = "/" . $url;
        }
        header("location: " . $url);
        die("exit redirect");
    }

    /*
     * Routing method to get access to matching methods
     * this should be in own class, but... It's just training
     */

    public function routing() {
        $uri = parse_url($this->request_uri, PHP_URL_PATH);
        $parts = explode("/", $uri);
        unset($parts[0]); //unsets not useful part
        $parts[1] = strtolower($parts[1]);
        $method = 1;
        /*
         * Check if part[1] is controller, if not - load home controller, usefull for adding more controllers like admin...
         */
        if(isset($parts[1]) && !empty($parts[1]) && $this->is_controller($parts[1])){ 
            $controller = new $parts[1];
            $method=2;
            unset($parts[1]);//unset controller part
        }else{
            $controller = new application();
        }
        /*
         * Check if method part is method or parametr
         */
        if (empty($parts[$method])) {
            $controller->index($parts);
        } else if (method_exists($controller, $parts[$method])) {
                $function = $parts[$method];
                unset($parts[$method]); //unsets function part
                $controller->$function($parts); //call method with array of parameters
        } else {
            //404
            $controller->e404($parts); //Invalid address/Method -> Error 404
        }
    }
    /*
     * Check if string is controller
     */
    private function is_controller($name){
        return file_exists("./controllers/".$name.".php");
    }
}
