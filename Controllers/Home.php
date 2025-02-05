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
            session_start();
        }
    
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            die();
        }
    
        $msg = ['msg' => 'Error inesperado', 'estado' => false, 'tipo' => 'error']; // Mensaje por defecto
    
        if (isset($_POST)) {
            if (empty($_POST['title']) || empty($_POST['start'])) {
                $msg = ['msg' => 'Todos los campos son obligatorios', 'estado' => false, 'tipo' => 'warning'];
            } else {
                $usuario_id = $_SESSION['usuario_id'];
                $title = $_POST['title'];
                      // Asegurar el formato correcto de la fecha y hora
                $start = date('Y-m-d H:i:s', strtotime($_POST['start']));
                $color = $_POST['color'];
                $id = $_POST['id'];
    
                if ($id == '') {
                    $data = $this->model->registrar($title, $start, $color, $usuario_id);
                    if ($data == 'ok') {
                        $msg = ['msg' => 'Evento Registrado', 'estado' => true, 'tipo' => 'success'];
                    } else {
                        $msg = ['msg' => 'Error al Registrar', 'estado' => false, 'tipo' => 'error'];
                    }
                } else {
                    $data = $this->model->modificar($title, $start, $color, $id);
                    if ($data == 'ok') {
                        $msg = ['msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success'];
                    } else {
                        $msg = ['msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'error'];
                    }
                }
            }
        }
    
        echo json_encode($msg); // Asegurarse de que siempre se devuelva JSON
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
                      // Asegurar el formato correcto de la fecha y hora
                $start = date('Y-m-d H:i:s', strtotime($_POST['start']));
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
    public function completar($id) {
        $data = $this->model->completarEvento($id);
        $msg = $data == 'ok' ? 
            ['msg' => 'Evento marcado como completado', 'estado' => true, 'tipo' => 'success'] :
            ['msg' => 'Error al completar el evento', 'estado' => false, 'tipo' => 'error'];
        
        echo json_encode($msg);
        die();
    }
    public function reactivar($id) {
        $data = $this->model->reactivarEvento($id);
        $msg = $data == 'ok' ? 
            ['msg' => 'Evento reactivado con éxito', 'estado' => true, 'tipo' => 'success'] :
            ['msg' => 'Error al reactivar el evento', 'estado' => false, 'tipo' => 'error'];
        
        echo json_encode($msg);
        die();
    }
    
}
