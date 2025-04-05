<?php

class Reports {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // إضافة تقرير
    public function addReport($title, $description, $employee_id, $project_id) {
        $sql = "INSERT INTO `REPORTS` (`title`, `description`, `date`, `employee_id`, `project_id`) 
                VALUES (:title, :description, date(now()), :employee_id, :project_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":project_id", $project_id);
        return $stmt->execute();
    }

    // حذف تقرير
    public function deleteReport($id) {
        $sql = "DELETE FROM `REPORTS` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // تعديل تقرير
    public function updateReport($id, $title, $description, $project_id) {
        $sql = "UPDATE `REPORTS` SET 
                `title` = :title, 
                `description` = :description,  
                `project_id` = :project_id 
                WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // عرض تقارير أحد الموظفين مع اسمه
    public function getReportsByEmployee($employee_id) {
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE r.employee_id = :employee_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض جميع التقارير مع اسم الموظف حسب السنة والشهر
    public function getReportsByYearMonth($year, $month) {
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE YEAR(r.date) = :year AND MONTH(r.date) = :month";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->bindParam(":month", $month);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض جميع التقارير مع اسم الموظف للشهر الحالي
    public function getReportsForCurrentMonth() {
        $currentYear = date("Y");
        $currentMonth = date("m");
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE YEAR(r.date) = :year AND MONTH(r.date) = :month";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $currentYear);
        $stmt->bindParam(":month", $currentMonth);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض أحد التقارير حسب id
    public function getReportById($id) {
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE r.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // عرض تقارير أحد المشاريع:
    // عرض تقارير مشروع معين مع اسم الموظف
    public function getReportsByProject($project_id) {
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE r.project_id = :project_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض التقارير التي لا ترتبط بمشروع
    public function getReportsWithoutProject() {
        $sql = "SELECT r.*, e.name AS employee_name 
                FROM `REPORTS` r
                LEFT JOIN `EMPLOYEES` e ON r.employee_id = e.id
                WHERE r.project_id IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}