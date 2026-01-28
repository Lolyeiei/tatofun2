<?php
session_start();
include 'config.php';

// 1. ตรวจสอบว่ามีตะกร้าหรือยัง
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// รับค่าจากทั้ง POST (AJAX/Form) และ GET (Remove)
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// --- [ ส่วนที่ 1: เพิ่มสินค้าลงตะกร้า ] ---
if ($action == 'add' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    // ถ้าสั่งจากหน้าแรกจะไม่มี topping ให้ตั้งเป็น 'No Topping'
    $topping = mysqli_real_escape_string($conn, $_POST['topping'] ?? 'No Topping');

    $sql = "SELECT * FROM tb_menu WHERE id_menu = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // 🛡️ หัวใจสำคัญ: สร้าง Unique Key เพื่อแยกสินค้าชนิดเดียวกันแต่คนละ Topping
        // เช่น "5_Cheese" กับ "5_Spicy" จะเป็นคนละแถวกันในตะกร้า
        $cart_key = $id . "_" . str_replace(' ', '', $topping);

        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['qty'] += 1;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'id'      => $id,
                'name'    => $row['name_menu'],
                'price'   => $row['price_menu'],
                'topping' => $topping,
                'img'     => $row['img_menu'],
                'qty'     => 1
            ];
        }

        // เช็คการตอบกลับ
        if ($is_ajax) {
            // คำนวณจำนวนรวมทั้งหมดส่งกลับไปอัปเดต Badge ที่หน้าแรก
            $total_items = 0;
            foreach($_SESSION['cart'] as $item) { $total_items += $item['qty']; }
            echo json_encode(['status' => 'success', 'new_count' => $total_items]);
            exit();
        } else {
            header("Location: menu.php?status=success");
            exit();
        }
    }
}

// --- [ ส่วนที่ 2: ลบสินค้า ] ---
if ($action == 'remove' && isset($_GET['key'])) {
    $key = $_GET['key']; 
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: cart.php");
    exit();
}

// ถ้าไม่มี Action อะไรเลย หรือเข้าถึงตรงๆ ให้เด้งกลับ
header("Location: menu.php");
exit();
?>