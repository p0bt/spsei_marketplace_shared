<?php
namespace SpseiMarketplace\Core;

class Router
{
    private $views_dir;
    private $routes = [];
    private $filters = [];

    public function __construct()
    {
        $this->views_dir = "views";
        $this->read_routes();
        $this->read_filters();
    }

    private function read_routes()
    {
        include "routes.php";
        $this->routes = $routes;
    }

    private function read_filters()
    {
        include "routes.php";
        $this->filters = $filters;
    }

    public function filter($url)
    {
        if(isset($this->filters) && !empty($this->filters))
        {
            $keys = array_keys($this->filters);

            foreach($this->filters as $filter_name => $filters_arr) 
            {
                if(array_search($url, $filters_arr) !== false)
                {
                    if(str_contains($filter_name, "!"))
                    {
                        $filter_name = str_replace("!", "", $filter_name);
                        if(Filter::$filter_name()) 
                        {
                            $this->redirect_back();
                            die;
                        }
                    }
                    else
                    {
                        if(!Filter::$filter_name()) 
                        {
                            $this->redirect_back();
                            die;
                        }
                    }
                }
            }
        }
    }
    
    /*
    * Routes
    */
    public function route($url)
    {
        $require = "Error:page_not_found";

        if(isset($this->routes) && !empty($this->routes))
        {
            if(array_key_exists($url, $this->routes))
            {
                $require = $this->routes[$url];
            }
        }

        $this->call_controller_method($require);
    }

    public function call_controller_method($string)
    {
        $namespace = "SpseiMarketplace\\Controllers\\";

        $arr = explode(":", $string);
        $controller = $namespace . $arr[0] . "Controller";
        $method = $arr[1];


        $co = new $controller();
        $co->$method();
    }

    public function redirect_back()
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        else
        {
            header('Location: ' . "/domu");
        }
        die;
    }
}