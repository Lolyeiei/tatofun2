<?php

session_start();
// ตรวจสอบสิทธิ์พนักงาน
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit();
}
include '../config.php'; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบพนักงาน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #f4f7f6; }
        .top-bar { background: #ffffff; padding: 15px 0; border-bottom: 1px solid #e0e0e0; }
        .menu-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .color-order { border-bottom: 4px solid #fab005; }
        .color-stock { border-bottom: 4px solid #fa5252; }
        .color-finance { border-bottom: 4px solid #2f9e44; }
    </style>
</head>
<body>
    <div class="top-bar mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <img src="../img/Logo.png" alt="Logo" width="40" class="me-2"> 
            <h5 class="fw-bold mb-0">TatoFun <span class="text-warning">Staff</span></h5>
            <div class="d-flex align-items-center">
                <span class="small me-3">ผู้ใช้งาน: <strong><?php echo $_SESSION['fullname'] ?? 'พนักงาน'; ?></strong></span>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">ออกจากระบบ</a>
            </div>
        </div>
    </div>

    <div class="container text-center pt-2">
        <h3 class="fw-bold text-dark">✨ ระบบจัดการพนักงาน</h3>
        <div class="row mt-5 g-4 justify-content-center">
            <div class="col-md-4">
                <div class="menu-card color-order p-4">
                    <i class="bi bi-clipboard-check fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">จัดการออเดอร์</h5>
                    <a href="manage_orders.php" class="btn btn-warning w-100 mt-3 shadow-sm">ดูออเดอร์</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-card color-stock p-4">
                    <i class="bi bi-box-seam fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold">สต็อกวัตถุดิบ</h5>
                    <a href="view_stock.php" class="btn btn-danger w-100 mt-3 shadow-sm">ดูสต็อก</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-card color-finance p-4">
                    <i class="bi bi-graph-up-arrow fs-1 text-success mb-3"></i>
                    <h5 class="fw-bold">บัญชีร้าน</h5>
                    <a href="finance_report.php" class="btn btn-success w-100 mt-3 shadow-sm">ดูสรุปยอด</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>