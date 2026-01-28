<?php
session_start();
include 'config.php';

// --- ส่วนที่ 1: ตรวจสอบความปลอดภัยและการรับค่า ---

// เช็คว่าต้องมาจากการกดปุ่ม Submit เท่านั้น (ป้องกันการพิมพ์ URL เข้ามาตรงๆ)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: menu.php");
    exit();
}

$cus_name = mysqli_real_escape_string($conn, $_POST['cus_name']);
$cus_tel = mysqli_real_escape_string($conn, $_POST['cus_tel']);
$cus_address = mysqli_real_escape_string($conn, $_POST['cus_address']);
$payment = mysqli_real_escape_string($conn, $_POST['payment']);

// ตรวจสอบ user_id: ถ้ามีให้ใช้เลข id ถ้าไม่มีให้เป็น NULL (สำหรับ Guest)
$user_id = (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) ? $_SESSION['user_id'] : "NULL"; 
$order_date = date("Y-m-d H:i:s");

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// เช็คว่ามีสินค้าในตะกร้าไหม
if (empty($cart_items)) {
    header("Location: menu.php");
    exit();
}

// --- ส่วนที่ 2: คำนวณยอดรวมและตรวจสอบโครงสร้างตะกร้า ---

$total_price = 0;
foreach ($cart_items as $key => $item) {
    // 🛡️ ดักจับ: ถ้าโครงสร้าง Session ผิด (แบบเก่า) ให้ล้างแล้วแจ้งเตือน
    if (!isset($item['id'])) {
        unset($_SESSION['cart']); 
        echo "<script>alert('ข้อมูลตะกร้าไม่ถูกต้อง กรุณาเลือกสินค้าใหม่อีกครั้ง'); window.location='menu.php';</script>";
        exit();
    }

    $id_safe = mysqli_real_escape_string($conn, $item['id']);
    $sql_p = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id_safe'";
    $res_p = mysqli_query($conn, $sql_p);
    
    if ($res_p && $row_p = mysqli_fetch_assoc($res_p)) {
        $price = (float)$row_p['price_menu'];
        $quantity = (int)($item['qty'] ?? 1); // บังคับเป็นตัวเลขป้องกัน Error
        $total_price += ($price * $quantity);
    }
}

// --- ส่วนที่ 3: บันทึกลงตารางหลัก tb_orders ---

// หมายเหตุ: $user_id ไม่ใส่เครื่องหมายครอบเพราะเตรียมค่ามาเผื่อ NULL แล้ว
$sql_order = "INSERT INTO tb_orders (user_id, cus_name, cus_tel, cus_address, total_price, payment, order_status, order_date) 
              VALUES ($user_id, '$cus_name', '$cus_tel', '$cus_address', '$total_price', '$payment', 'รอชำระเงิน', '$order_date')";

if (mysqli_query($conn, $sql_order)) {
    $order_id = mysqli_insert_id($conn);

    // --- ส่วนที่ 4: บันทึกรายละเอียดลงตาราง tb_order_details (Loop สินค้าทีละชิ้น) ---
    
    foreach ($cart_items as $item) {
        if (!isset($item['id'])) continue; // ข้ามถ้าไม่มี ID เพื่อความปลอดภัย

        $id_safe = mysqli_real_escape_string($conn, $item['id']);
        
        // ดึงราคาปัจจุบันจาก DB อีกครั้งเพื่อบันทึกเป็นประวัติ
        $sql_menu = "SELECT price_menu FROM tb_menu WHERE id_menu = '$id_safe'";
        $res_menu = mysqli_query($conn, $sql_menu);
        
        if ($res_menu && $row_menu = mysqli_fetch_assoc($res_menu)) {
            $price = (float)$row_menu['price_menu'];
            $quantity = (int)($item['qty'] ?? 1);
            
            // เตรียมค่า Topping (ถ้ามี)
            $topping = isset($item['topping']) ? mysqli_real_escape_string($conn, $item['topping']) : '';

            // บันทึกรายการลงตาราง Details
            $sql_detail = "INSERT INTO tb_order_details (order_id, id_menu, qty, price) 
                           VALUES ('$order_id', '$id_safe', '$quantity', '$price')";
            mysqli_query($conn, $sql_detail);
        }
    }

    // --- ส่วนที่ 5: เคลียร์ระบบและไปหน้าถัดไป ---

    unset($_SESSION['cart']); // ล้างตะกร้าหลังสั่งสำเร็จ
    echo "<script>
            alert('บันทึกการสั่งซื้อเรียบร้อยแล้ว!');
            window.location.href='checkout_payment.php?order_id=$order_id';
          </script>";

} else {
    // แจ้งเตือนกรณีบันทึกไม่สำเร็จ (เช่น Database หลุด)
    $error_msg = mysqli_real_escape_string($conn, mysqli_error($conn));
    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึก: $error_msg'); window.history.back();</script>";
}
?>