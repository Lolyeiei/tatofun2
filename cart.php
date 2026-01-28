<?php
session_start();
include 'config.php';

// เตรียมตัวแปร
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$grand_total = 0;
$total_qty = 0;

// คำนวณจำนวนชิ้นรวมล่วงหน้า
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $total_qty += (int)($item['qty'] ?? 0);
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตะกร้าของฉัน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfaf0; font-family: 'Kanit', sans-serif; }
        .navbar { background-color: #fff !important; padding: 15px 0; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fff; }
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 12px; }
        .btn-checkout { background-color: #ffc107; color: #000; font-weight: 600; border-radius: 12px; border: none; padding: 12px; transition: 0.3s; text-decoration: none; display: block; text-align: center; }
        .btn-checkout:hover:not([disabled]) { background-color: #ff9800; transform: translateY(-2px); color: #000; }
        .table thead th { border-top: none; color: #888; font-weight: 400; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="menu.php">
            <i class="bi bi-chevron-left"></i> กลับไปหน้าเมนู
        </a>
        <span class="fw-bold fs-5 text-dark">Tato<span class="text-warning">Fun</span> Cart</span>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card p-4">
                <h5 class="fw-bold mb-4">รายการสินค้าในตะกร้า (<?= $total_qty ?>)</h5>
                
                <?php if (empty($cart_items)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3">ยังไม่มีสินค้าในตะกร้าของคุณ</p>
                        <a href="menu.php" class="btn btn-warning rounded-pill px-4 mt-2">ไปเลือกเมนูกันเลย!</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th colspan="2">สินค้า</th>
                                    <th>ราคา/ชิ้น</th>
                                    <th class="text-center">จำนวน</th>
                                    <th>รวม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($cart_items as $key => $item): 
                                    // คืนค่าที่อาจจะไม่มีเพื่อป้องกัน Error
                                    $price = isset($item['price']) ? (float)$item['price'] : 0;
                                    $qty = isset($item['qty']) ? (int)$item['qty'] : 0;
                                    $subtotal = $price * $qty;
                                    $grand_total += $subtotal;
                                    $img = !empty($item['img']) ? "admin/img_ad/" . $item['img'] : "img/no1.png";
                                ?>
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="<?= $img ?>" class="product-img shadow-sm" onerror="this.src='img/no1.png';">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($item['name'] ?? 'ไม่มีชื่อเมนู') ?></div>
                                        <small class="text-warning fw-bold"><i class="bi bi-stars"></i> <?= htmlspecialchars($item['topping'] ?? 'ไม่เลือกผงโรย') ?></small>
                                    </td>
                                    <td>฿<?= number_format($price, 0) ?></td>
                                    <td class="text-center" style="width: 100px;">
                                        <span class="badge bg-light text-dark p-2 w-75 fs-6 border"><?= $qty ?></span>
                                    </td>
                                    <td class="fw-bold text-dark">฿<?= number_format($subtotal, 0) ?></td>
                                    <td class="text-end">
                                        <a href="cart_action.php?action=remove&key=<?= $key ?>" 
                                           class="btn btn-sm btn-outline-danger border-0" 
                                           onclick="return confirm('ต้องการลบรายการนี้ใช่หรือไม่?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card cart-card p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">สรุปคำสั่งซื้อ</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ยอดรวมสินค้า</span>
                    <span>฿<?= number_format($grand_total, 0) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">ค่าจัดส่ง</span>
                    <span class="text-success fw-bold">ฟรี</span>
                </div>
                <hr class="opacity-50">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">ยอดรวมสุทธิ</span>
                    <span class="fw-bold fs-5 text-dark">฿<?= number_format($grand_total, 0) ?></span>
                </div>
                
                <a href="checkout.php" class="btn btn-checkout w-100 py-3 shadow-sm <?= empty($cart_items) ? 'disabled pointer-events-none opacity-50' : '' ?>">
                    ดำเนินการชำระเงิน
                </a>
            </div>
        </div>
    </div>
</div>
// ตัวอย่างการ Loop แสดงผลใน cart.php
foreach ($_SESSION['cart'] as $key => $item) {
    echo "<tr>";
    echo "<td><img src='admin/img_ad/{$item['img']}' width='50'></td>";
    echo "<td>
            <strong>{$item['name']}</strong><br>
            <small class='text-muted'>ท็อปปิ้ง: {$item['topping']}</small> 
          </td>";
    echo "<td>" . number_format($item['price'], 2) . "</td>";
    echo "<td>{$item['qty']}</td>";
    // ส่วนสำคัญ: ตอนลบต้องส่งค่า $key (เช่น 5_ชีส) ไปที่ cart_action.php
    echo "<td><a href='cart_action.php?action=remove&key={$key}' class='btn btn-danger btn-sm'>ลบ</a></td>";
    echo "</tr>";
}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
include 'config.php';

// เตรียมตัวแปร
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$grand_total = 0;
$total_qty = 0;

// คำนวณจำนวนชิ้นรวมล่วงหน้า
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $total_qty += (int)($item['qty'] ?? 0);
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตะกร้าของฉัน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdfaf0; font-family: 'Kanit', sans-serif; }
        .navbar { background-color: #fff !important; padding: 15px 0; }
        .cart-card { border: none; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fff; }
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 12px; }
        .btn-checkout { background-color: #ffc107; color: #000; font-weight: 600; border-radius: 12px; border: none; padding: 12px; transition: 0.3s; text-decoration: none; display: block; text-align: center; }
        .btn-checkout:hover:not([disabled]) { background-color: #ff9800; transform: translateY(-2px); color: #000; }
        .table thead th { border-top: none; color: #888; font-weight: 400; font-size: 0.9rem; }
        .topping-badge { background-color: #fff3cd; color: #856404; font-size: 0.75rem; padding: 4px 10px; border-radius: 50px; border: 1px solid #ffeeba; }
    </style>
</head>
<body>

<nav class="navbar navbar-light sticky-top shadow-sm">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-3 py-1" href="menu.php">
            <i class="bi bi-chevron-left"></i> กลับไปหน้าเมนู
        </a>
        <span class="fw-bold fs-5 text-dark">Tato<span class="text-warning">Fun</span> Cart</span>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card p-4">
                <h5 class="fw-bold mb-4">รายการสินค้าในตะกร้า (<?= $total_qty ?>)</h5>
                
                <?php if (empty($cart_items)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3">ยังไม่มีสินค้าในตะกร้าของคุณ</p>
                        <a href="menu.php" class="btn btn-warning rounded-pill px-4 mt-2">ไปเลือกเมนูกันเลย!</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th colspan="2">สินค้า</th>
                                    <th>ราคา</th>
                                    <th class="text-center">จำนวน</th>
                                    <th>รวม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($cart_items as $key => $item): 
                                    $price = (float)($item['price'] ?? 0);
                                    $qty = (int)($item['qty'] ?? 0);
                                    $subtotal = $price * $qty;
                                    $grand_total += $subtotal;
                                    $img_path = !empty($item['img']) ? "admin/img_ad/" . $item['img'] : "img/no1.png";
                                ?>
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="<?= $img_path ?>" class="product-img shadow-sm" onerror="this.src='img/no1.png';">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($item['name'] ?? 'เมนูไม่มีชื่อ') ?></div>
                                        <span class="topping-badge">
                                            <i class="bi bi-magic me-1"></i><?= htmlspecialchars($item['topping'] ?? 'No Topping') ?>
                                        </span>
                                    </td>
                                    <td>฿<?= number_format($price, 0) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2"><?= $qty ?></span>
                                    </td>
                                    <td class="fw-bold">฿<?= number_format($subtotal, 0) ?></td>
                                    <td class="text-end">
                                        <a href="cart_action.php?action=remove&key=<?= urlencode($key) ?>" 
                                           class="btn btn-sm btn-outline-danger border-0" 
                                           onclick="return confirm('ต้องการลบรายการนี้ใช่หรือไม่?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card cart-card p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">สรุปคำสั่งซื้อ</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ยอดรวมสินค้า</span>
                    <span>฿<?= number_format($grand_total, 0) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">ค่าจัดส่ง</span>
                    <span class="text-success fw-bold">ฟรี</span>
                </div>
                <hr class="opacity-50">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">ยอดรวมสุทธิ</span>
                    <span class="fw-bold fs-5 text-warning">฿<?= number_format($grand_total, 0) ?></span>
                </div>
                
                <a href="checkout.php" class="btn btn-checkout w-100 py-3 shadow-sm <?= empty($cart_items) ? 'disabled pointer-events-none opacity-50' : '' ?>">
                    ดำเนินการชำระเงิน <i class="bi bi-arrow-right ms-2"></i>
                </a>
                
                <div class="text-center mt-3">
                    <small class="text-muted"><i class="bi bi-shield-check"></i> ชำระเงินปลอดภัย 100%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>