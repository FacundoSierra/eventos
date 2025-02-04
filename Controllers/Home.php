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
    if (!isset($_SESSION['usuario_id'])) {  // ✔ Verifica si el usuario está autenticado
        echo json_encode(['msg' => 'Error: Usuario no autenticado', 'estado' => false, 'tipo' => 'danger']);
        die();
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
                        $msg = array('msg' => 'Evento Registrado', 'estado' => true, 'tipo' => 'success');
                    }else{
                        $msg = array('msg' => 'Error al Registrar', 'estado' => false, 'tipo' => 'danger');
                    }
                } else {
                    $data = $this->model->modificar($title, $start, $color, $usuario_id);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                    }
                }
                
            }
            echo json_encode($msg);
        }
        die();
    }
    public function listar()
    {
        $usuario_id = $_SESSION['usuario_id'];
        $data = $this->model->getEventosPorUsuario($usuario_id);
        echo json_encode($data);
    }
    
    public function eliminar($id)
    {
        $data = $this->model->eliminar($id);
        if ($data == 'ok') {
            $msg = array('msg' => 'Evento Eliminado', 'estado' => true, 'tipo' => 'success');
        } else {
            $msg = array('msg' => 'Error al Eliminar', 'estado' => false, 'tipo' => 'danger');
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
                    $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                }
            }
            echo json_encode($msg);
        }
        die();
    }
}
