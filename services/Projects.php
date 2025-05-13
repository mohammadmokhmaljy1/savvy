<?php 
include_once "conn.php";

class Projects {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // إضافة مشروع جديد
    public function addProject($title, $description, $begin_date, $end_date, $price, $notes, $employee_manager_id, $client_id) {
        $sql = "INSERT INTO `PROJECTS` 
                (`title`, `description`, `begin_date`, `end_date`, `price`, `notes`, `employee_manager_id`, `client_id`)
                VALUES (:title, :description, :begin_date, :end_date, :price, :notes, :employee_manager_id, :client_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":begin_date", $begin_date);
        $stmt->bindParam(":end_date", $end_date);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":notes", $notes);
        $stmt->bindParam(":employee_manager_id", $employee_manager_id);
        $stmt->bindParam(":client_id", $client_id);
        return $stmt->execute();
    }

    // تعديل أحد المشاريع
    public function updateProject($id, $title, $description, $begin_date, $end_date, $price, $notes) {
        $sql = "UPDATE `PROJECTS` SET 
                `title` = :title, 
                `description` = :description, 
                `begin_date` = :begin_date, 
                `end_date` = :end_date, 
                `price` = :price, 
                `notes` = :notes 
                WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":begin_date", $begin_date);
        $stmt->bindParam(":end_date", $end_date);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":notes", $notes);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // حذف أحد المشاريع
    public function deleteProject($id) {
        $sql = "DELETE FROM `PROJECTS` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // إلغاء مشروع
    public function cancelProject($id) {
        $sql = "UPDATE `PROJECTS` SET `is_canceled` = 1 WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // إنهاء مشروع
    public function finishProject($id) {
        $sql = "UPDATE `PROJECTS` SET `is_finish` = 1 WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // البحث ضمن المشاريع
    public function searchProjects($keyword) {
        $sql = "SELECT * FROM `PROJECTS` WHERE `title` LIKE :kw OR `description` LIKE :kw";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%" . $keyword . "%";
        $stmt->bindParam(":kw", $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مشاريع السنة الحالية
    public function getProjectsByCurrentYear() {
        $currentYear = date("Y");
        $sql = "SELECT * FROM `PROJECTS` WHERE YEAR(`begin_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $currentYear);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مشاريع أحد السنين
    public function getProjectsByYear($year) {
        $sql = "SELECT * FROM `PROJECTS` WHERE YEAR(`begin_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مجموع دخل كل المشاريع لأحد السنين
    public function getTotalIncomeByYear($year) {
        $sql = "SELECT SUM(`price`) AS total_income FROM `PROJECTS` WHERE YEAR(`begin_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_income'] ?? 0;
    }

    // عرض مجموع دخل كل المشاريع للسنة الحالية
    public function getTotalIncomeByCurrentYear() {
        $currentYear = date("Y");
        $sql = "SELECT SUM(`price`) AS total_income FROM `PROJECTS` WHERE YEAR(`begin_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $currentYear);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_income'] ?? 0;
    }

    // عرض أحد المشاريع عن طريق id
    public function getProjectById($id) {
        $sql = "SELECT p.*, c.name AS client_name 
                FROM `PROJECTS` p
                LEFT JOIN `CLIENTS` c ON p.client_id = c.id
                WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

    // المشروع التي قيد العمل حالياً:
    public function getOngoingProjects() {
        $sql = "SELECT * FROM `PROJECTS` WHERE `is_finish` = 0 AND `is_canceled` = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض المشاريع الملغاة:
    public function getCanceledProjects() {
        $sql = "SELECT * FROM `PROJECTS` WHERE `is_canceled` = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}