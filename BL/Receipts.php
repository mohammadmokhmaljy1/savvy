<?php 
include_once "../DAL/conn.php";

class Receipt {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // 🟢 إضافة قبض جديد
    public function addReceipt($receipt_no, $employee_id, $receipt_date, $amount, $receipt_note) {
        $sql = "INSERT INTO `RECEIPT` 
            (`receipt_no`, `employee_id`, `receipt_date`, `amount`, `receipt_note`)
            VALUES (:receipt_no, :employee_id, :receipt_date, :amount, :receipt_note)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":receipt_no", $receipt_no);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":receipt_date", $receipt_date);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":receipt_note", $receipt_note);
        return $stmt->execute();
    }

    // 🟡 تعديل بيانات قبض
    public function updateReceipt($id, $receipt_no, $employee_id, $receipt_date, $amount, $receipt_note) {
        $sql = "UPDATE `RECEIPT` SET 
            `receipt_no` = :receipt_no,
            `employee_id` = :employee_id,
            `receipt_date` = :receipt_date,
            `amount` = :amount,
            `receipt_note` = :receipt_note
        WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":receipt_no", $receipt_no);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":receipt_date", $receipt_date);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":receipt_note", $receipt_note);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // 🔴 حذف قبض
    public function deleteReceipt($id) {
        $sql = "DELETE FROM `RECEIPT` WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // 🔷 عرض مقبوضات الشهر الحالي
    public function getCurrentMonthReceipts() {
        $sql = "SELECT * FROM `RECEIPT` 
                WHERE MONTH(`receipt_date`) = MONTH(CURRENT_DATE()) 
                AND YEAR(`receipt_date`) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔷 عرض مقبوضات شهر معين (تُرسل قيم month و year)
    public function getReceiptsByMonth($month, $year) {
        $sql = "SELECT * FROM `RECEIPT` 
                WHERE MONTH(`receipt_date`) = :month 
                AND YEAR(`receipt_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":month", $month);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔷 عرض مقبوضات السنة الحالية
    public function getCurrentYearReceipts() {
        $sql = "SELECT * FROM `RECEIPT` 
                WHERE YEAR(`receipt_date`) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔷 عرض مقبوضات سنة محددة
    public function getReceiptsByYear($year) {
        $sql = "SELECT * FROM `RECEIPT` 
                WHERE YEAR(`receipt_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ عرض مجموع مقبوضات السنة الحالية
    public function getTotalOfCurrentYear() {
        $sql = "SELECT SUM(`amount`) AS total_amount 
                FROM `RECEIPT` 
                WHERE YEAR(`receipt_date`) = YEAR(CURRENT_DATE())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_amount'] : 0;
    }

    // عرض مقبوضات أحد الموظفين
    public function getReceiptsByEmployee($employee_id) {
        $sql = "SELECT * FROM `RECEIPT` WHERE `employee_id` = :employee_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // عرض مجموع قبوضات أحد الموظفين ضمن أحد السنين:
    public function getTotalByEmployeeYear($employee_id, $year) {
        $sql = "SELECT SUM(`amount`) AS total_amount 
                FROM `RECEIPT` 
                WHERE `employee_id` = :employee_id 
                AND YEAR(`receipt_date`) = :year";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_amount'] : 0;
    }
}