<?php
class Controller{
    protected Views $views;
    protected  $model;
    public function __construct()
    {
        $this->views = new Views();
        $this->cargarModel();
    }
    public function cargarModel()
    {
        $model = get_class($this)."Model";
        $ruta = "Models/".$model.".php";

        // echo "Buscando modelo: $ruta <br>";
        if (file_exists($ruta)) {
            require_once $ruta;
            $this->model = new $model();
            // echo "Modelo cargado: $model <br>";
        }  else {
            echo "Error: El modelo no existe <br>";
        }
    }
}


?>