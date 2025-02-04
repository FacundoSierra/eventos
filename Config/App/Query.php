<?php
class Query extends Conexion{
    private $pdo, $con, $sql, $datos;
    public function __construct() {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }
    public function select(string $sql, array $params)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        
        if (!empty($params)) {  // Si hay parámetros, los pasamos a execute()
            $resul->execute($params);
        } else {
            $resul->execute();
        }
    
        return $resul->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectAll(string $sql)
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params);
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        try {
            $insert = $this->con->prepare($this->sql); // Preparar consulta
            $data = $insert->execute($this->datos); // Ejecutar consulta
    
            if ($data) {
                $res = 1; // Éxito en la ejecución
            } else {
                $res = 0; // Fallo en la ejecución
            }
        } catch (PDOException $e) {
            // Registrar el error en los logs del servidor o mostrarlo en desarrollo
            error_log("Error en consulta SQL: " . $e->getMessage()); // Registrar error
            $res = 0; // Retornar fallo si ocurre una excepción
        }
    
        return $res; // Retornar resultado
    }
    
    public function insertar(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if ($data) {
            $res = $this->con->lastInsertId();
        } else {
            $res = 0;
        }
        return $res;
    }
}


?>