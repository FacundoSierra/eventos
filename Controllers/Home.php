<?php
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
       
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function registrar()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Iniciar sesión solo si no está activa
        }
    
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            die(); // Termina la ejecución aquí
        }
    if (isset($_POST)) {
            if (empty($_POST['title']) || empty($_POST['start'])) {
            }else{
                $usuario_id = $_SESSION['usuario_id'];
                $title = $_POST['title'];
                $start = $_POST['start'];
                $color = $_POST['color'];
                $id = $_POST['id'];
                if ($id == '') {
                    $data = $this->model->registrar($title, $start, $color, $usuario_id);
                    if ($data == 'ok') {
                        $ultimoId = $this->model->getUltimoId(); // Obtén el ID del último registro
                        $msg = array('msg' => 'Evento Registrado', 'estado' => true, 'tipo' => 'success');
                    }else{
                        $msg = array('msg' => 'Error al Registrar', 'estado' => false, 'tipo' => 'error');

                    }
                } else {
                    $data = $this->model->modificar($title, $start, $color, $usuario_id);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'error');
                    }
                }
                
            }
            echo json_encode($msg);
        }
        die();
    }
    public function listar()
    {
        $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que $_SESSION['usuario_id'] esté configurado correctamente
        $data = $this->model->getEventos($usuario_id); // Llama al método corregido
        echo json_encode($data);
    }
    
    
    public function eliminar($id)
    {
        $data = $this->model->eliminar($id);
        if ($data == 'ok') {
            $msg = array('msg' => 'Evento Eliminado', 'estado' => true, 'tipo' => 'success');
        } else {
            $msg = array('msg' => 'Error al Eliminar', 'estado' => false, 'tipo' => 'error');
        }
        echo json_encode($msg);
        die();
    }
    public function drag()
    {
        if (isset($_POST)) {
            if (empty($_POST['id']) || empty($_POST['start'])) {
                $msg = array('msg' => 'Todo los campos son requeridos', 'estado' => false, 'tipo' => 'danger');
            } else {
                $start = $_POST['start'];
                $id = $_POST['id'];
                $data = $this->model->dragOver($start, $id);
                if ($data == 'ok') {
                    $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                } else {
                    $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'error');
                }
            }
            echo json_encode($msg);
        }
        die();
    }
}
