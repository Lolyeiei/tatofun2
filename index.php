<?php 
session_start(); 
include 'config.php'; 

// 1. ระบบดักจับ Role (ตรวจสอบสิทธิ์)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') { header("Location: admin/index_ad.php"); exit(); }
    if ($_SESSION['role'] == 'staff') { header("Location: staff/index_st.php"); exit(); }
}

// 2. ดึงข้อมูลโลโก้และแบนเนอร์จากฐานข้อมูล
$sql = "SELECT * FROM tb_logobanner";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}

$logo_path    = !empty($images[1]) ? "admin/img_ad/".$images[1] : "img/Logo.png";
$banner1_path = !empty($images[2]) ? "admin/img_ad/".$images[2] : "img/no1.png";
$banner2_path = !empty($images[3]) ? "admin/img_ad/".$images[3] : "img/no2.png";
$banner3_path = !empty($images[4]) ? "admin/img_ad/".$images[4] : "img/no3.png";
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
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #263238;
            --tato-light: #fffbf0;
        }

        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-light); color: var(--tato-dark); }
        
        /* Navbar */
        .navbar { background-color: var(--tato-yellow) !important; border-bottom: 3px solid var(--tato-orange); }
        .nav-link { font-weight: 500; color: var(--tato-dark) !important; }

        /* Floating Cart */
        .floating-cart {
            position: fixed; bottom: 30px; right: 30px; z-index: 1000;
            background: var(--tato-dark); color: white; padding: 15px 25px;
            border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-decoration: none; transition: 0.3s;
        }
        .floating-cart:hover { transform: scale(1.1); color: var(--tato-yellow); }

        /* Menu Card */
        .menu-card { border: none; border-radius: 20px; transition: 0.3s; background: white; overflow: hidden; }
        .menu-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .btn-order { background: var(--tato-dark); color: white; border-radius: 50px; border: none; padding: 8px 20px; transition: 0.3s; }
        .btn-order:hover { background: var(--tato-orange); }

        /* Footer & Icons */
        .contact-icon { width: 35px; height: 35px; background: #fffbf0; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .social-btn { width: 45px; height: 45px; background: #fff; border: 1px solid #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #555; transition: 0.3s; }
        .social-btn:hover { background: var(--tato-yellow); color: #fff; transform: translateY(-3px); }
        
        /* Carousel Custom */
        .carousel-item img { height: 450px; object-fit: cover; }
        @media (max-width: 768px) { .carousel-item img { height: 250px; } }
    </style>
</head>
<body>

    <a href="cart.php" class="floating-cart">
        <i class="bi bi-cart-fill me-2"></i> ตะกร้าของฉัน <span class="badge bg-warning text-dark ms-1">0</span>
    </a>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" width="45" height="45" class="me-2 rounded-circle bg-white shadow-sm">
                <span class="fw-bold fs-4">Tato<span class="text-white">Fun</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link" href="#menu-section">เมนูอาหาร</a></li>
                    <?php if(!isset($_SESSION['fullname'])): ?>
                        <li class="nav-item"><a href="login.php" class="btn btn-dark rounded-pill px-4">เข้าสู่ระบบ</a></li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= $_SESSION['fullname'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel" style="border-radius: 25px; overflow: hidden;">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?php echo $banner1_path; ?>" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="<?php echo $banner2_path; ?>" class="d-block w-100">
                </div>
                <?php if(!empty($images[4])): ?>
                <div class="carousel-item">
                    <img src="<?php echo $banner3_path; ?>" class="d-block w-100">
                </div>
                <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
            </button>
        </div>
    </div>

    <div class="container py-5" id="menu-section">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">เลือกความ <span class="text-warning">ฟิน</span> ในแบบคุณ</h2>
            <div class="mx-auto bg-warning" style="height: 4px; width: 60px; border-radius: 2px;"></div>
        </div>
        
        <div class="row g-4">
            <?php
            $sql_menu = "SELECT * FROM tb_menu";
            $res_menu = mysqli_query($conn, $sql_menu);
            if (mysqli_num_rows($res_menu) > 0) {
                while($menu = mysqli_fetch_assoc($res_menu)):
                    $m_img = !empty($menu['img_menu']) ? "admin/img_menu/".$menu['img_menu'] : "img/default.png";
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 menu-card shadow-sm">
                    <img src="<?= $m_img ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="fw-bold mb-1"><?= $menu['name_menu'] ?></h5>
                        <p class="text-muted small mb-3">ทอดใหม่ กรอบอร่อย</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5 text-dark">฿<?= number_format($menu['price_menu']) ?></span>
                            <button class="btn btn-order shadow-sm"><i class="bi bi-plus-lg"></i> สั่งเลย</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            } else {
                echo "<div class='col-12 text-center'><p class='text-muted'>ยังไม่มีเมนูในขณะนี้</p></div>";
            }
            ?>
        </div>
    </div>

    <footer class="bg-white pt-5 pb-3 mt-5" style="border-top: 5px solid var(--tato-yellow);">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <img src="<?php echo $logo_path; ?>" width="50" height="50" class="me-2 rounded-circle shadow-sm">
                        <h3 class="fw-bold mb-0">Tato<span style="color: var(--tato-orange);">Fun</span></h3>
                    </div>
                    <p class="text-muted px-4 px-lg-0 small">
                        "ความสุขคำโตๆ กับมันฝรั่งทอดคุณภาพดีที่เราตั้งใจมอบให้คุณในทุกๆ วัน ทอดร้อน สดใหม่ เพื่อความสนุกในทุกคำ"
                    </p>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-4" style="border-left: 4px solid var(--tato-orange); padding-left: 15px;">ข้อมูลการติดต่อ</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="contact-icon me-3 shadow-sm"><i class="bi bi-geo-alt-fill text-warning"></i></div>
                        <span class="text-muted small">สุขุมวิท ข้างทางรถไฟ ศรีราชา จ.ชลบุรี</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="contact-icon me-3 shadow-sm"><i class="bi bi-telephone-fill text-warning"></i></div>
                        <span class="text-muted small">099-999-9999</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="contact-icon me-3 shadow-sm"><i class="bi bi-clock-fill text-warning"></i></div>
                        <span class="text-muted small">เปิดให้บริการ: 09:00 – 20:00 น.</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 text-center text-md-start">
                    <h5 class="fw-bold mb-4" style="border-left: 4px solid var(--tato-orange); padding-left: 15px;">ติดตามเรา</h5>
                    <p class="text-muted small mb-4">ไม่พลาดข่าวสารและส่วนลดดีๆ ติดตามเราเลย</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <a href="#" class="social-btn shadow-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn shadow-sm"><i class="bi bi-tiktok text-dark"></i></a>
                        <a href="#" class="social-btn shadow-sm"><i class="bi bi-instagram text-danger"></i></a>
                    </div>
                </div>
            </div>

            <div class="row mt-5 border-top pt-4">
                <div class="col-md-12 text-center text-muted small">
                    <p>© 2026 <strong>TatoFun</strong>. Fresh & Fun Fries. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>