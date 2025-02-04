<?php
class HomeModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function registrar($title, $inicio, $color,$usuario_id)
    {
        $sql = "INSERT INTO evento (title, start , color, usuario_id) VALUES (?, ?, ?, ?)";
        $array = array($title, $inicio, $color,$usuario_id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        }else{
            $res = 'error';
        }
        return $this->save($sql, [$title, $inicio, $color, $usuario_id]);
    }
    public function getEventos($usuario_id) {
        $sql = "SELECT * FROM evento WHERE usuario_id = ?";
        return $this->selectAll($sql, [$usuario_id]);
    }
    
    public function modificar($title, $inicio, $color, $id)
    {
        $sql = "UPDATE evento SET title=?, start=?, color=? WHERE id=?";
        $array = array($title, $inicio, $color, $id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function eliminar($id)
    {
        $sql = "DELETE FROM evento WHERE id=?";
        $array = array($id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function dragOver($start, $id)
    {
        $sql = "UPDATE evento SET start=? WHERE id=?";
        $array = array($start, $id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function getUltimoId()
{
    $sql = "SELECT LAST_INSERT_ID() as id";
    $data = $this->select($sql);
    return $data['id'];
}

}

?>