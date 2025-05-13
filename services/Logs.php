<?php 
include_once "conn.php";

class Logs {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // إضافة عملية
    public function addLog($employee_id, $action) {
        $sql = "INSERT INTO `LOGS` (`employee_id`, `action`, `action_time`)
                VALUES (:employee_id, :action, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":action", $action);
        return $stmt->execute();
    }

    // تعديل عملية
    public function updateLog($id, $employee_id, $action) {
        $sql = "UPDATE `LOGS` SET 
                    `employee_id` = :employee_id,
                    `action` = :action
                WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // حذف عملية
    public function deleteLog($id) {
        $sql = "DELETE FROM `LOGS` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // عرض العمليات التي تمت اليوم
    public function getTodayLogs() {
        $sql = "SELECT l.*, e.name AS employee_name
                FROM `LOGS` l
                LEFT JOIN `EMPLOYEE` e ON l.employee_id = e.id
                WHERE DATE(l.action_time) = CURDATE()
                ORDER BY l.action_time DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض العمليات حسب اسم الموظف
    public function getLogsByEmployeeName($name) {
        $sql = "SELECT l.*, e.name AS employee_name
                FROM `LOGS` l
                LEFT JOIN `EMPLOYEE` e ON l.employee_id = e.id
                WHERE e.name LIKE :name
                ORDER BY l.action_time DESC";
        $stmt = $this->conn->prepare($sql);
        $search = "%$name%";
        $stmt->bindParam(":name", $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض عملية واحدة حسب ID
    public function getLogById($id) {
        $sql = "SELECT l.*, e.name AS employee_name
                FROM `LOGS` l
                LEFT JOIN `EMPLOYEE` e ON l.employee_id = e.id
                WHERE l.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // حذف العمليات الأقدم من عدد معين من الأشهر
    public function clearOldLogs($months = 6) {
        $sql = "DELETE FROM `LOGS` WHERE `action_time` < DATE_SUB(NOW(), INTERVAL :months MONTH)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":months", $months, PDO::PARAM_INT);
        return $stmt->execute();
    }
}