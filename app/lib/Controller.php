<?php

class Controller
{

    function __construct()
    {
        $this->load_lib("Input");
    }

    /**
     * include library by name / don't use '.php'
     * @param string $lib_name library name
     */
    public function load_lib($lib_name)
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php")) {
            require $_SERVER["DOCUMENT_ROOT"] . "/app/lib/$lib_name.php";
        } else { 
            echo "Library named : $lib_name not found";
            die;
        }
        $this->$lib_name = new $lib_name;
    }

    /**
     * warning - danger to use. use as last.
     */
    public function load_all_models()
    {
        foreach (glob($_SERVER['DOCUMENT_ROOT'] ."/app/model/*.php") as $model) 
        { 
            require_once($model);
            $model_name = basename($model, ".php");
            $this->$model_name = new $model_name;
        }
    }

    /**
     * include model by name / don't use '.php'
     * @param string $model_name model name
     */
    public function load_model($model_name)
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php")) {
            require_once $_SERVER["DOCUMENT_ROOT"] . "/app/model/$model_name.php";
        } else {
            echo "Model named : $model_name not found";
            die;
        }
        $this->$model_name = new $model_name;
    }

    /**
     * view render with variables
     * @param string $file view file name
     * @param array $variables array with data
     */
    public function render($file, $variables = array())
    {
        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php")) {
            echo "view '$file' not found";
        } else {
            extract($variables);
            ob_start();
            require $_SERVER["DOCUMENT_ROOT"] . "/app/templates/" . $file . ".php";
            $render_view = ob_get_clean();
            echo $render_view;
        }
    }

    // includes file from public, like webpage navbar or headers
    public static function load_html($file) {
        if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/public//" . $file . ".php")) {
            echo "header '$file' not found";
        } else {
            require $_SERVER["DOCUMENT_ROOT"] .  "/public//" . $file . ".php";
        }
    }

}
