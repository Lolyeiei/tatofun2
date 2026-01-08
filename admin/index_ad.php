<?php
session_start();

/** * 1. ตรวจสอบสิทธิ์ (Protection)
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); 
    exit();
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun Admin - Dashboard</title>
    <link rel="icon" type="image/png" href="img_ad/logo.png">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #212529;
            --tato-bg: #f8f9fa;
            --tato-white: #ffffff;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background: radial-gradient(circle at top right, #fffdf0, #f8f9fa);
            min-height: 100vh;
        }

        /* Navbar Custom */
        .navbar { 
            background: var(--tato-yellow) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-bottom: 3px solid var(--tato-orange);
        }

        /* Dashboard Cards */
        .admin-card { 
            border: none; 
            border-radius: 30px; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        /* ใส่แสงเรืองๆ เมื่อ Hover ตามสีของ Card */
        .card-menu:hover { box-shadow: 0 15px 35px rgba(255, 193, 7, 0.25); transform: translateY(-10px); }
        .card-promo:hover { box-shadow: 0 15px 35px rgba(220, 53, 69, 0.2); transform: translateY(-10px); }
        .card-media:hover { box-shadow: 0 15px 35px rgba(13, 110, 253, 0.2); transform: translateY(-10px); }

        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 2rem;
            transition: 0.3s;
        }

        .admin-card:hover .icon-box {
            transform: scale(1.1) rotate(10deg);
        }

        /* สไตล์ปุ่มแบบใหม่ */
        .btn-action {
            border-radius: 15px;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
        }

        .btn-action:hover {
            letter-spacing: 1px;
            filter: brightness(0.9);
        }

        /* Info Box Glassmorphism */
        .info-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .page-title {
            color: var(--tato-dark);
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: var(--tato-orange);
            bottom: 0;
            left: 25%;
            border-radius: 2px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container"> 
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index_ad.php">
                <div class="bg-white rounded-circle p-1 me-2 shadow-sm">
                    <img src="img_ad/LOGO3.png" alt="Logo" width="40">
                </div>
                <span class="text-dark">TatoFun <span class="fw-light">Admin</span></span>
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="bg-dark bg-opacity-10 px-3 py-1 rounded-pill me-3 d-none d-md-block">
                    <i class="bi bi-person-badge-fill me-1"></i> 
                    <strong><?php echo $_SESSION['fullname']; ?></strong>
                </div>
                <a href="../logout.php" class="btn btn-dark rounded-pill px-4 shadow-sm btn-sm">
                    <i class="bi bi-power"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold page-title mb-3">แผงควบคุมผู้ดูแลระบบ</h1>
            <p class="text-muted">จัดการร้าน TatoFun ของคุณได้ง่ายๆ ในที่เดียว</p>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4 col-md-6">
                <div class="card admin-card card-menu h-100 p-4 border-top border-warning border-5">
                    <div class="card-body d-flex flex-column">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-layout-text-sidebar-reverse"></i>
                        </div>
                        <h4 class="fw-bold">จัดการเมนูอาหาร</h4>
                        <p class="text-muted mb-4">ควบคุมรายการอาหารทั้งหมด ตั้งแต่ราคาจนถึงสถานะความพร้อมเสิร์ฟ</p>
                        <div class="mt-auto">
                            <a href="manage_menu.php" class="btn btn-warning w-100 btn-action shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i> จัดการเลย
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card admin-card card-promo h-100 p-4 border-top border-danger border-5">
                    <div class="card-body d-flex flex-column">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                        <h4 class="fw-bold">จัดการโปรโมชั่น</h4>
                        <p class="text-muted mb-4">สร้างแคมเปญลดราคาให้น่าสนใจ เพื่อเพิ่มยอดขายให้กับร้าน</p>
                        <div class="mt-auto">
                            <a href="manage_promotion.php" class="btn btn-danger w-100 btn-action shadow-sm text-white">
                                <i class="bi bi-lightning-charge me-2"></i> จัดการเลย
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card admin-card card-media h-100 p-4 border-top border-primary border-5">
                    <div class="card-body d-flex flex-column">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-images"></i>
                        </div>
                        <h4 class="fw-bold">โลโก้ & แบนเนอร์</h4>
                        <p class="text-muted mb-4">ปรับแต่งหน้าตาเว็บไซต์ อัปโหลดรูปภาพโฆษณาที่หน้าแรก</p>
                        <div class="mt-auto">
                            <a href="manage_logobanner.php" class="btn btn-primary w-100 btn-action shadow-sm text-white">
                                <i class="bi bi-pencil-square me-2"></i> จัดการเลย
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5 p-4 info-glass shadow-sm border">
            <div class="row align-items-center">
                <div class="col-md-1 text-center d-none d-md-block">
                    <i class="bi bi-shield-lock-fill fs-1 text-warning"></i>
                </div>
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1">ความปลอดภัยของระบบ</h5>
                    <p class="text-muted mb-0 small">ระบบจะทำการตรวจสอบสิทธิ์ทุกครั้งที่มีการเข้าถึง หากพบปัญหาหรือความผิดปกติกรุณาติดต่อผู้พัฒนา</p>
                </div>
                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                    <a href="../index.php" class="btn btn-outline-dark btn-sm rounded-pill px-4">
                        <i class="bi bi-house-door me-1"></i> กลับหน้าหลักร้าน
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>