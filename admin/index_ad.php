<!--เสร็จแล้ว-->
<?php
session_start();

/** * 1. แก้ไขการเช็คสิทธิ์ (Protection)
 * ตรวจสอบว่ามี Role เป็น 'admin' หรือไม่ ถ้าไม่ใช่ให้ดีดออกไปหน้า Login หลัก
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // ปรับ Path ให้ถอยออกไปหาหน้า login.php ที่อยู่ด้านนอก
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun Admin - Dashboard</title>
    <link rel="icon" type="image/png" href="img_ad/logo.png">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        
        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: #fffdf0; 
            min-height: 100vh;
        }

        .navbar { 
            background-color: #ffc107; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        }

        .admin-card { 
            border: none; 
            border-radius: 25px; 
            transition: all 0.3s ease;
            background: #ffffff;
            height: 100%; 
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 20px rgba(0,0,0,0.03);
        }

        .admin-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 15px 30px rgba(255, 193, 7, 0.15); 
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .info-box {
            background-color: #ffffff;
            border-left: 5px solid #ffc107;
            border-radius: 15px;
        }

        .text-dark-yellow {
            color: #856404;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container"> 
            <a class="navbar-brand fw-bold" href="index_ad.php">
                <img src="img_ad/LOGO3.png" alt="Logo" width="45" class="me-2"> TatoFun Admin
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 d-none d-sm-inline fw-bold text-dark">
                    <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['fullname']; ?> (แอดมิน)
                </span>
                <a href="../logout.php" class="btn btn-dark btn-sm rounded-pill px-4 shadow-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark-yellow"><i class="bi bi-stars me-2"></i>ระบบจัดการหลังบ้าน</h1>
            <p class="text-muted">ยินดีต้อนรับเข้าสู่ระบบจัดการ TatoFun</p>
        </div>

        <div class="row g-4 row-cols-1 row-cols-md-3">
            
            <div class="col d-flex">
                <div class="card admin-card p-4 text-center w-100 border-top border-5 border-warning">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-egg-fried fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">จัดการเมนูอาหาร</h4>
                        <p class="text-muted small">เพิ่ม แก้ไข ลบรายการอาหาร และตั้งราคาสินค้าภายในร้าน</p>
                        
                        <div class="mt-auto">
                            <hr class="my-4 text-secondary opacity-25">
                            <a href="manage_menu.php" class="btn btn-warning w-100 py-2 rounded-pill fw-bold shadow-sm">เข้าสู่หน้าจัดการ</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col d-flex">
                <div class="card admin-card p-4 text-center w-100 border-top border-5 border-danger">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-megaphone fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">จัดการโปรโมชั่น</h4>
                        <p class="text-muted small">กำหนดช่วงเวลาส่วนลด สร้างแบนเนอร์โปรโมชั่น และกิจกรรมพิเศษ</p>
                        
                        <div class="mt-auto">
                            <hr class="my-4 text-secondary opacity-25">
                            <a href="manage_promotion.php" class="btn btn-danger w-100 py-2 rounded-pill fw-bold shadow-sm">เข้าสู่หน้าจัดการ</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col d-flex">
                <div class="card admin-card p-4 text-center w-100 border-top border-5 border-primary">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">โลโก้ & แบนเนอร์</h4>
                        <p class="text-muted small">อัปโหลดโลโก้ร้านใหม่ หรือเปลี่ยนรูปภาพสไลด์หน้าแรก (Homepage)</p>
                        
                        <div class="mt-auto">
                            <hr class="my-4 text-secondary opacity-25">
                            <a href="manage_logobanner.php" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">เข้าสู่หน้าจัดการ</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5 p-4 info-box shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1 text-dark-yellow"><i class="bi bi-info-circle-fill me-2"></i>คำแนะนำในการใช้งาน</h5>
                    <p class="text-muted mb-0 small">การแก้ไขข้อมูลในหน้านี้จะส่งผลต่อหน้าเว็บไซต์หลักทันที กรุณาตรวจสอบความถูกต้องก่อนบันทึกข้อมูล</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="../index.php" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                        <i class="bi bi-eye me-1"></i> ดูหน้าเว็บหลัก
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>