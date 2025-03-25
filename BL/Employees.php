<?php 
include_once "../DAL/conn.php";

class Employees {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function index() {
        $sql = "SELECT * FROM `EMPLOYEE`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM `EMPLOYEE` WHERE `email` = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee && password_verify($password, $employee['password'])) {
            return $employee;
        }
        return false;
    }

    public function addEmployee($name, $email, $salary, $position, $phone, $cv_file, $skill, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO `EMPLOYEE`
        (`name`, `email`, `salary`, `phone`, `cv_file`, `skill`,  `password`, `position`) 
        VALUES (:name, :email, :salary, :phone, :cv_file, :skill, :password, :position)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":salary", $salary);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":cv_file", $cv_file);
        $stmt->bindParam(":skill", $skill);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":position", $position);
        return $stmt->execute();
    }

    public function deleteEmployee($id) {
        $sql = "DELETE FROM `EMPLOYEE` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // تعديل بيانات موظف
    public function updateEmployee($id, $name, $email, $salary, $position, $phone, $cv_file, $skill) {
        $sql = "UPDATE `EMPLOYEE` SET 
        `name` = :name, 
        `email` = :email, 
        `salary` = :salary, 
        `position` = :position 
        `phone` = :phone, 
        `cv_file` = :cv_file, 
        `skill` = :skill, 
        WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":salary", $salary);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":cv_file", $cv_file);
        $stmt->bindParam(":skill", $skill);
        $stmt->bindParam(":position", $position);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // البحث عن موظف
    public function searchEmployee($keyword) {
        $sql = "SELECT * FROM `EMPLOYEE` WHERE `name` LIKE :keyword OR `salary` LIKE :keyword OR `email` LIKE :keyword OR `position` LIKE :keyword";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->bindParam(":keyword", $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}