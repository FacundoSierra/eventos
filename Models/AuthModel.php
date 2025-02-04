<?php

class AuthModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        return $this->select($sql, [$email]);
    }

    public function registerUser($nombre, $email, $password)
    {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        return $this->save($sql, [$nombre, $email, $password]);
    }
}
?>
