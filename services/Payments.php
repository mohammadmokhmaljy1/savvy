<?php 
include_once "conn.php";

class Payments {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // 1. إضافة دفعة جديدة
    public function addPayment($client_id, $project_id, $amount, $payment_date, $payment_method, $notes) {
        $sql = "INSERT INTO `payments` 
        (`client_id`, `project_id`, `amount`, `payment_date`, `payment_method`, `notes`)
        VALUES (:client_id, :project_id, :amount, :payment_date, :payment_method, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":client_id", $client_id);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $payment_date);
        $stmt->bindParam(":payment_method", $payment_method);
        $stmt->bindParam(":notes", $notes);
        return $stmt->execute();
    }

    // 2. حذف دفعة
    public function deletePayment($id) {
        $sql = "DELETE FROM `payments` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // 3. تعديل دفعة
    public function updatePayment($id, $client_id, $project_id, $amount, $payment_date, $payment_method, $notes) {
        $sql = "UPDATE `payments` SET 
                `client_id` = :client_id,
                `project_id` = :project_id,
                `amount` = :amount,
                `payment_date` = :payment_date,
                `payment_method` = :payment_method,
                `notes` = :notes
            WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":client_id", $client_id);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $payment_date);
        $stmt->bindParam(":payment_method", $payment_method);
        $stmt->bindParam(":notes", $notes);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // 4. عرض مدفوعات الشهر الحالي مع عنوان المشروع واسم العميل
    public function getCurrentMonthPayments() {
        $sql = "SELECT P.*, PR.title AS project_title, C.name AS client_name
                FROM `payments` P
                JOIN `projects` PR ON PR.id = P.project_id
                JOIN `clients` C ON C.id = P.client_id
                WHERE MONTH(P.payment_date) = MONTH(CURDATE())
                AND YEAR(P.payment_date) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. عرض جميع مدفوعات السنة الحالية مع عنوان المشروع واسم العميل
    public function getCurrentYearPayments() {
        $sql = "SELECT P.*, PR.title AS project_title, C.name AS client_name
                FROM `payments` P
                JOIN `projects` PR ON PR.id = P.project_id
                JOIN `clients` C ON C.id = P.client_id
                WHERE YEAR(P.payment_date) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. عرض مدفوعات شهر ما مع عنوان المشروع واسم العميل
    public function getPaymentsByMonth($month, $year) {
        $sql = "SELECT P.*, PR.title AS project_title, C.name AS client_name
                FROM `payments` P
                JOIN `projects` PR ON PR.id = P.project_id
                JOIN `clients` C ON C.id = P.client_id
                WHERE MONTH(P.payment_date) = :month AND YEAR(P.payment_date) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":month", $month);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 7. عرض مدفوعات سنة ما مع عنوان المشروع واسم العميل
    public function getPaymentsByYear($year) {
        $sql = "SELECT P.*, PR.title AS project_title, C.name AS client_name
                FROM `payments` P
                JOIN `projects` PR ON PR.id = P.project_id
                JOIN `clients` C ON C.id = P.client_id
                WHERE YEAR(P.payment_date) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 8. إجمالي المدفوعات لسنة معينة
    public function getTotalPaymentsByYear($year) {
        $sql = "SELECT SUM(amount) AS total_amount
                FROM `payments`
                WHERE YEAR(payment_date) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 9. إجمالي المدفوعات لشهر معين من سنة معينة
    public function getTotalPaymentsByMonth($month, $year) {
        $sql = "SELECT SUM(amount) AS total_amount
                FROM `payments`
                WHERE MONTH(payment_date) = :month AND YEAR(payment_date) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":month", $month);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}