<?php
include '../config.php';

if(isset($_GET['menu_id']) && isset($_GET['menu_name'])) {
    $menu_id = $_GET['menu_id'];
    $menu_name = mysqli_real_escape_string($conn, $_GET['menu_name']);
    $msg = "แจ้งด่วน: เมนู $menu_name หมดแล้ว โปรดตรวจสอบสต็อก!";

    // บันทึกลงตารางแจ้งเตือน
    $sql = "INSERT INTO tb_notifications (message, status) VALUES ('$msg', 'unread')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('ส่งการแจ้งเตือนด่วนแล้ว!'); window.location='view_stock.php';</script>";
    }
}
?>