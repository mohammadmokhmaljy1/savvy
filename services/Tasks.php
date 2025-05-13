<?php

include_once "conn.php";

class Tasks {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // إضافة مهمة جديدة
    public function addTask($employee_id, $start_date, $finish_date, $notes, $type, $level, $project_id) {
        $sql = "INSERT INTO `TASKS` (`employee_id`, `start_date`, `finish_date`, `notes`, `type`, `level`, `project_id`)
                VALUES (:employee_id, :start_date, :finish_date, :notes, :type, :level, :project_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":start_date", $start_date);
        $stmt->bindParam(":finish_date", $finish_date);
        $stmt->bindParam(":notes", $notes);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":level", $level);
        $stmt->bindParam(":project_id", $project_id);
        return $stmt->execute();
    }

    // تعديل مهمة
    public function updateTask($id, $employee_id, $start_date, $finish_date, $notes, $type, $level, $project_id) {
        $sql = "UPDATE `TASKS`
                SET `employee_id` = :employee_id,
                    `start_date` = :start_date,
                    `finish_date` = :finish_date,
                    `notes` = :notes,
                    `type` = :type,
                    `level` = :level,
                    `project_id` = :project_id
                WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":start_date", $start_date);
        $stmt->bindParam(":finish_date", $finish_date);
        $stmt->bindParam(":notes", $notes);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":level", $level);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // حذف مهمة
    public function deleteTask($id) {
        $sql = "DELETE FROM `TASKS` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // إنهاء مهمة (بجعل تاريخ النهاية هو تاريخ اليوم)
    public function finishTask($id) {
        $today = date('Y-m-d');
        $sql = "UPDATE `TASKS` SET `finish_date` = :today WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":today", $today);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // عرض مهام موظف ما مع اسم الموظف واسم المشروع
    public function getTasksByEmployee($employee_id) {
        $sql = "SELECT t.*, e.name AS employee_name, p.title AS project_title
                FROM `TASKS` t
                LEFT JOIN `EMPLOYEES` e ON t.employee_id = e.id
                LEFT JOIN `PROJECTS` p ON t.project_id = p.id
                WHERE t.employee_id = :employee_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مهام أحد المشاريع مع اسم المشروع
    public function getTasksByProject($project_id) {
        $sql = "SELECT t.*, p.title AS project_title
                FROM `TASKS` t
                LEFT JOIN `PROJECTS` p ON t.project_id = p.id
                WHERE t.project_id = :project_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مهام الشهر الحالي مع عنوان المشروع
    public function getCurrentMonthTasks() {
        $sql = "SELECT t.*, p.title AS project_title
                FROM `TASKS` t
                LEFT JOIN `PROJECTS` p ON t.project_id = p.id
                WHERE MONTH(t.start_date) = MONTH(CURRENT_DATE()) 
                  AND YEAR(t.start_date) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب مهمة واحدة حسب ID
    public function getTaskById($id) {
        $sql = "SELECT t.*, e.name AS employee_name, p.title AS project_title
                FROM `TASKS` t
                LEFT JOIN `EMPLOYEES` e ON t.employee_id = e.id
                LEFT JOIN `PROJECTS` p ON t.project_id = p.id
                WHERE t.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}