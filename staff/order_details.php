<?php
session_start();
include '../config.php'; 

// 1. ตรวจสอบสิทธิ์พนักงาน
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}

// 2. ตรวจสอบ ID ออเดอร์
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('กรุณาเลือกออเดอร์ก่อน'); window.location='manage_orders.php';</script>";
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. ดึงข้อมูลรายการอาหาร (ใช้ name_menu และ price_menu ตามจริงใน DB)
$sql = "SELECT m.name_menu, m.price_menu 
        FROM tb_order_details od
        JOIN tb_menu m ON od.menu_id = m.id_menu 
        WHERE od.order_id = '$order_id'";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Kanit', sans-serif; background: #f4f7f6; display: flex; align-items: center; min-height: 100vh; }
        .receipt-card { border: none; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; background: #fff; }
        .receipt-header { background: linear-gradient(45deg, #ffc107, #ff9800); padding: 30px; text-align: center; }
        .total-box { background: #fff9db; border-radius: 15px; padding: 20px; border: 2px dashed #ffe082; }
        .btn-back { background: #212529; color: #fff; border-radius: 12px; padding: 12px; width: 100%; transition: 0.3s; text-decoration: none; display: block; text-align: center; }
        .btn-back:hover { background: #000; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="receipt-card">
                <div class="receipt-header">
                    <i class="bi bi-receipt-cutoff fs-1"></i>
                    <h3 class="fw-bold mb-0">รายละเอียดออเดอร์</h3>
                    <p class="mb-0 opacity-75">หมายเลข #<?php echo $order_id; ?></p>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <thead>
                            <tr class="border-bottom">
                                <th class="pb-3 text-muted small">รายการเมนู</th>
                                <th class="pb-3 text-end text-muted small">ราคา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            while($item = mysqli_fetch_assoc($result)): 
                                $total += $item['price_menu'];
                            ?>
                            <tr>
                                <td class="py-3 fw-bold text-dark"><?php echo htmlspecialchars($item['name_menu']); ?></td>
                                <td class="py-3 text-end fw-bold">฿<?php echo number_format($item['price_menu'], 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="total-box d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold">ยอดรวมสุทธิ</span>
                        <span class="display-6 fw-bold text-danger">฿<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="mt-4">
                        <a href="manage_orders.php" class="btn-back">กลับหน้าจัดการออเดอร์</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>