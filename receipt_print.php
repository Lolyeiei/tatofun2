<?php
session_start();
include '../config.php'; // เชื่อมต่อฐานข้อมูล

$order_id = $_GET['id']; // รับ ID มาจาก URL

// SQL สำหรับดึงข้อมูลหัวออเดอร์
$sql_order = "SELECT * FROM tb_orders WHERE order_id = '$order_id'";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

// SQL สำหรับดึงรายการอาหาร (JOIN กับ tb_menu)
$sql_items = "SELECT od.*, m.name_menu 
              FROM tb_order_details od 
              JOIN tb_menu m ON od.id_menu = m.id_menu 
              WHERE od.order_id = '$order_id'";
$res_items = mysqli_query($conn, $sql_items);
?>

<div id="receipt" style="width: 400px; margin: auto; padding: 20px; border: 1px solid #eee;">
    <h2 style="text-align: center;">TatoFun Fries</h2>
    <p>เลขที่ใบสั่งซื้อ: #<?php echo $order['order_id']; ?></p>
    ... (โค้ดส่วนที่เหลือของคุณ) ...
</div>