<?php 
session_start(); 
include 'config.php'; 

// 1. ระบบดักจับ Role (Security Gate)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'staff' || $_SESSION['role'] == 'admin') { 
        header("Location: admin/index_ad.php"); 
        exit(); 
    }
} 

// 2. ดึงข้อมูลโลโก้และแบนเนอร์จาก Database
$sql = "SELECT * FROM tb_logobanner";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}

$logo_path    = !empty($images[1]) ? "admin/img_ad/".$images[1] : "img/Logo.png";
$banner1_path = !empty($images[2]) ? "admin/img_ad/".$images[2] : "img/no1.png";
$banner2_path = !empty($images[3]) ? "admin/img_ad/".$images[3] : "img/no2.png";
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TatoFun - Fresh & Fun Fries</title>
    <link rel="icon" href="<?php echo $logo_path; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --tato-yellow: #ffca28; --tato-orange: #f57c00; --tato-dark: #263238; --tato-light: #fffbf0; }
        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-light); color: var(--tato-dark); }
        .navbar { background-color: var(--tato-yellow) !important; border-bottom: 3px solid var(--tato-orange); }
        .nav-link { color: #263238 !important; font-weight: 500; }
        .nav-link:hover { color: white !important; }
        .carousel-item img { height: 450px; object-fit: cover; }
        
        /* สไตล์ปุ่มตะกร้าลอย */
        .floating-cart {
            position: fixed; bottom: 30px; right: 30px; z-index: 1000;
            background: var(--tato-dark); color: white; padding: 15px 25px;
            border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-decoration: none; transition: 0.3s;
        }
        .floating-cart:hover { transform: scale(1.1); color: var(--tato-yellow); }
        .btn-logout { background-color: white; color: #dc3545; border: 1px solid #dc3545; border-radius: 20px; font-weight: 600; }
        .btn-logout:hover { background-color: #dc3545; color: white; }
        .text-orange { color: var(--tato-orange); }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'member'): ?>
    <a href="cart.php" class="floating-cart">
        <i class="bi bi-cart-fill me-2"></i> ตะกร้าของฉัน <span class="badge bg-warning text-dark ms-1">0</span>
    </a>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" width="45" height="45" class="me-2 rounded-circle bg-white shadow-sm">
                <span class="fw-bold fs-4 text-dark">Tato<span class="text-white">Fun</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-3 ps-lg-3">
                    <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">เมนูอาหาร</a></li>
                    <li class="nav-item"><a class="nav-link" href="promotion.php">โปรโมชั่น</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <?php if(!isset($_SESSION['fullname'])): ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-dark rounded-pill px-4 shadow-sm">เข้าสู่ระบบ</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <span class="text-dark fw-bold me-2"><?= $_SESSION['fullname'] ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-logout btn-sm px-3 shadow-sm" href="logout.php">ออกจากระบบ</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="<?= $banner1_path ?>" class="d-block w-100"></div>
            <div class="carousel-item"><img src="<?= $banner2_path ?>" class="d-block w-100"></div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4">เมนูแนะนำ</h2>
        <div class="row g-4">
            <?php
            $sql_menu = "SELECT * FROM tb_menu LIMIT 6"; 
            $res_menu = mysqli_query($conn, $sql_menu);
            while($row_m = mysqli_fetch_assoc($res_menu)):
                $img_path = "admin/img_ad/" . $row_m['img_menu']; 
            ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="<?= $img_path ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='img/no1.png';">
                    
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="fw-bold"><?= $row_m['name_menu'] ?></h5>
                        <p class="text-orange fw-bold fs-5">฿<?= number_format($row_m['price_menu'], 2) ?></p>
                        
                        <div class="mt-auto">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'member'): ?>
                                <a href="cart_action.php?id=<?= $row_m['id_menu'] ?>&action=add" 
                                   class="btn btn-warning rounded-pill w-100 fw-bold shadow-sm">
                                   <i class="bi bi-cart-plus-fill me-1"></i> เพิ่มลงตะกร้า
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-outline-secondary rounded-pill w-100 btn-sm">
                                    <i class="bi bi-lock-fill"></i> ล็อกอินเพื่อสั่งซื้อ
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="py-5 bg-white border-top mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">TatoFun</h5>
                    <p class="text-muted small">ความสุขคำโตๆ กับมันฝรั่งทอดคุณภาพดีที่เราตั้งใจมอบให้คุณในทุกๆ วัน</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">ติดต่อเรา</h6>
                    <p class="small text-muted"><i class="bi bi-geo-alt"></i> สุขุมวิท ศรีราชา จ.ชลบุรี</p>
                    <p class="small text-muted"><i class="bi bi-telephone"></i> 099-999-9999</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">ติดตามเรา</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-warning fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-warning fs-4"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-warning fs-4"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <p class="text-center small text-muted mb-0">© 2026 TatoFun. Fresh & Fun Fries. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>