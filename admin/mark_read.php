<?php
session_start();
include '../config.php';

// 1. ตรวจสอบสิทธิ์ (Security Layer)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 2. ตรวจสอบค่า ID ที่ส่งมา
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // ทำความสะอาดข้อมูลเพื่อป้องกัน SQL Injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    /**
     * อัปเดตสถานะเป็น 'read' 
     * เพื่อให้หน้า Dashboard ไม่ดึงข้อความนี้ขึ้นมาโชว์อีก
     */
    $sql = "UPDATE tb_notifications SET status = 'read' WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        // หากสำเร็จ ให้กลับไปที่หน้าเดิมพร้อมสถานะ success (ถ้าต้องการนำไปทำ Toast ต่อ)
        header("Location: index_ad.php?msg=read_success");
    } else {
        // หากเกิดข้อผิดพลาดจาก Database
        header("Location: index_ad.php?msg=db_error");
    }
    
} else {
    // หากไม่มีการส่ง ID มา หรือ ID ไม่ใช่ตัวเลข
    header("Location: index_ad.php");
}
exit();
?>