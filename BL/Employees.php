<?php 
include_once "../DAL/conn.php";

class Employees {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // عرض جميع الموظفين
    public function index() {
        $sql = "SELECT * FROM `EMPLOYEE`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // تسجيل الدخول
    public function login($email, $password) {
        $sql = "SELECT * FROM `EMPLOYEE` WHERE `email` = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            return 'EMAIL_NOT_FOUND';
        }

        if (!password_verify($password, $employee['password'])) {
            return 'INVALID_PASSWORD';
        }

        return $employee;
    }

    // إضافة موظف جديد
    public function addEmployee($name, $email, $salary, $position, $phone, $cv_file, $skill, $password) {
        // التحقق من أن الإيميل غير مستخدم مسبقًا
        $checkSql = "SELECT COUNT(*) FROM `EMPLOYEE` WHERE `email` = :email";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();
        if ($checkStmt->fetchColumn() > 0) {
            return 'EMAIL_ALREADY_EXISTS';
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO `EMPLOYEE` 
        (`name`, `email`, `salary`, `phone`, `cv_file`, `skill`, `password`, `position`) 
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

    // حذف موظف
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
            `position` = :position, 
            `phone` = :phone, 
            `cv_file` = :cv_file, 
            `skill` = :skill 
        WHERE `id` = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":salary", $salary);
        $stmt->bindParam(":position", $position);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":cv_file", $cv_file);
        $stmt->bindParam(":skill", $skill);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // البحث عن موظف
    public function searchEmployee($keyword) {
        $sql = "SELECT * FROM `EMPLOYEE` 
                WHERE `name` LIKE :keyword 
                OR `salary` LIKE :keyword 
                OR `email` LIKE :keyword 
                OR `position` LIKE :keyword";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->bindParam(":keyword", $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب موظف حسب ID
    public function getEmployeeById($id) {
        $sql = "SELECT * FROM `EMPLOYEE` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // تحديث كلمة مرور الموظف
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE `EMPLOYEE` SET `password` = :password WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}