<?php
class Auth extends Controller
{
    public function __construct()
    {
        parent::__construct();
        echo "<script>console.log('Controlador Auth cargado correctamente.');</script>";

    }
   

    public function login()
    {
        echo "<script>console.log('Método login ejecutado correctamente.');</script>";
        $this->views->getView($this, "login");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $usuario = $this->model->getUserByEmail($email);
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header("Location: " . base_url . "Home");
            } else {
                echo json_encode(['msg' => 'Credenciales incorrectas', 'estado' => false]);
            }
        }
    }

    public function register()
    {
        $this->views->getView($this, "register");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($this->model->registerUser($nombre, $email, $password)) {
                echo json_encode(['msg' => 'Usuario registrado', 'estado' => true]);
            } else {
                echo json_encode(['msg' => 'Error en el registro', 'estado' => false]);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . base_url . "Auth/login");
    }
}
?>


