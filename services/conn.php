<?php
class Database {
    private $host = "localhost";
    private $db_name = "prog_company_manage";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $e) {
            die("خطأ في الاتصال: " . $e->getMessage());
        }

        return $this->conn;
    }
}