<?php
include_once "../DAL/conn.php";

class Clients {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // عرض جميع العملاء
    public function getAllClients() {
        $sql = "SELECT * FROM `CLIENTS` ORDER BY `id` DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // إضافة عميل جديد
    public function addClient($name, $address, $email, $phone, $first_visit_date, $company_name) {
        $sql = "INSERT INTO `CLIENTS` 
                (`name`, `address`, `email`, `phone`, `first_visit_date`, `company_name`) 
                VALUES (:name, :address, :email, :phone, date(now()), :company_name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":company_name", $company_name);
        return $stmt->execute();
    }

    // حذف عميل
    public function deleteClient($id) {
        $sql = "DELETE FROM `CLIENTS` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // تعديل بيانات عميل
    public function updateClient($id, $name, $address, $email, $phone, $company_name) {
        $sql = "UPDATE `CLIENTS` SET 
                `name` = :name, 
                `address` = :address, 
                `email` = :email, 
                `phone` = :phone, 
                `company_name` = :company_name 
                WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":company_name", $company_name);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // عرض عملاء سنة معينة حسب أول زيارة
    public function getClientsByYear($year) {
        $sql = "SELECT * FROM `CLIENTS` WHERE YEAR(`first_visit_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // البحث بين العملاء:
    public function searchClients($keyword) {
        $sql = "SELECT * FROM `CLIENTS` 
                WHERE `name` LIKE :kw 
                   OR `email` LIKE :kw 
                   OR `phone` LIKE :kw 
                   OR `company_name` LIKE :kw";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%" . $keyword . "%";
        $stmt->bindParam(":kw", $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // عرض عدد عملاء السنة الحالية:
    public function countClientsByYear() {
        $sql = "SELECT COUNT(*) as total FROM `CLIENTS` WHERE YEAR(`first_visit_date`) = year(now())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // عرض بيانات أحد العملاء:
    public function getClientById($id) {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM `PROJECTS` p WHERE p.client_id = c.id) AS project_count
                FROM `CLIENTS` c
                WHERE c.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   
}