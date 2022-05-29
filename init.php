<?php
session_start();
ob_start();

function my_autoload($class)
{
    if (preg_match('/Controller$/', $class))
        require("src/Controllers/" . $class . ".php");
    else
    {
        if(file_exists("src/Models/" . $class . ".php"))
            require("src/Models/" . $class . ".php");
        else
            require("src/Core/" . $class . ".php");

    }
}

spl_autoload_register("my_autoload");