<?php
class Views{

    public function getView($controlador, $vista, $data="")
    {
        echo "<script>console.log('Cargando vista: Views/" . get_class($controlador) . "/$vista.php');</script>";
        
        $controlador = get_class($controlador);
        if ($controlador == "Home") {
            $vista = "Views/".$vista.".php";
        }else{
            $vista = "Views/".$controlador."/".$vista.".php";
        }
        require $vista;
    }
}


?>