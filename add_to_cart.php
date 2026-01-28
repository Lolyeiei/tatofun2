<?php
session_start();
include 'config.php';

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // ดึงข้อมูลสินค้าจากฐานข้อมูล tb_menu
    $result = mysqli_query($conn, "SELECT * FROM tb_menu WHERE id_menu = '$id'");
    $item = mysqli_fetch_assoc($result);

    if($item) {
        // สร้างอาเรย์ตะกร้าสินค้า
        $cart_item = [
            'id'    => $item['id_menu'],
            'name'  => $item['name_menu'],
            'price' => $item['price_menu'],
            'qty'   => 1
        ];

        // ตรวจสอบว่ามีสินค้าเดิมอยู่ในตะกร้าหรือยัง
        if(isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id] = $cart_item;
        }
        
        // ส่งกลับไปหน้าเดิมพร้อมแจ้งเตือนสำเร็จ
        header("Location: menu.php?action=success");
    } else {
        header("Location: menu.php?action=error");
    }
}
?>