<?php 
session_start(); 
include 'config.php'; 

// 1. ระบบดักจับ Role (Staff ให้ไปหน้า Staff)
if (isset($_SESSION['role']) && $_SESSION['role'] == 'staff') { 
    header("Location: staff/index_st.php"); 
    exit(); 
} 

// 2. ดึงข้อมูลโลโก้และแบนเนอร์
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
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-dark: #263238;
            --tato-light: #fffbf0;
        }
        body { font-family: 'Kanit', sans-serif; background-color: var(--tato-light); color: var(--tato-dark); overflow-x: hidden; }
        .navbar { background-color: var(--tato-yellow) !important; border-bottom: 4px solid var(--tato-orange); padding: 0.8rem 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .nav-link { font-size: 1.15rem; font-weight: 600; color: var(--tato-dark) !important; margin: 0 10px; transition: 0.3s; }
        .nav-link:hover { color: white !important; transform: translateY(-3px); }
        .carousel-item img { height: 500px; object-fit: cover; width: 100%; display: block; }
        .menu-card { border: none; border-radius: 25px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: white; overflow: hidden; }
        .menu-card:hover { transform: translateY(-15px); box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important; }
        .floating-cart-btn { position: fixed; bottom: 30px; right: 30px; z-index: 1000; border-radius: 50px; padding: 12px 25px; background: var(--tato-dark); color: white; border: 2px solid var(--tato-yellow); box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-decoration: none; transition: 0.3s; }
        .main-footer { background-color: var(--tato-dark); color: #ecf0f1; padding: 60px 0 20px 0; border-top: 6px solid var(--tato-orange); }
        .footer-title { color: var(--tato-yellow); font-weight: 600; margin-bottom: 20px; }
        .footer-text { color: rgba(255,255,255,0.7); font-size: 0.95rem; }
        .social-icon { width: 40px; height: 40px; line-height: 40px; background: rgba(255,255,255,0.1); display: inline-block; border-radius: 50%; color: var(--tato-yellow); text-align: center; transition: 0.3s; margin-right: 10px; }
        .social-icon:hover { background: var(--tato-yellow); color: var(--tato-dark); transform: translateY(-5px); }
        @media (max-width: 768px) { .carousel-item img { height: 280px; } }
    </style>
</head>
<body>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="cart.php" class="floating-cart-btn" id="cart-floating">
            <i class="bi bi-cart-fill me-2 text-warning"></i> ตะกร้าของฉัน 
            <span class="badge bg-warning text-dark ms-1 rounded-pill" id="cart-count">
                <?php
                $count = 0;
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $item) { $count += $item['qty']; }
                }
                echo $count;
                ?>
            </span>
        </a>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container-fluid px-lg-5"> 
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="<?php echo $logo_path; ?>" width="55" height="55" class="me-2 rounded-circle bg-white shadow-sm" style="border: 2px solid var(--tato-orange);">
                <span class="fw-bold fs-3 text-dark">Tato<span class="text-white">Fun</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto"> 
                    <li class="nav-item"><a class="nav-link active" href="index.php">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">เมนูอาหาร</a></li>
                    <li class="nav-item"><a class="nav-link" href="promotion.php">โปรโมชั่น</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if(!isset($_SESSION['fullname'])): ?>
                        <a href="login.php" class="btn btn-dark rounded-pill px-4 fw-bold">เข้าสู่ระบบ</a>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-light rounded-pill dropdown-toggle fw-bold shadow-sm px-3 py-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['fullname'] ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 p-2">
                                <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2"></i>โปรไฟล์</a></li>
                                <li><a class="dropdown-item py-2" href="order_history.php"><i class="bi bi-clock-history me-2"></i>ประวัติการสั่งซื้อ</a></li>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item py-2 text-primary fw-bold" href="admin/index_ad.php"><i class="bi bi-speedometer2 me-2"></i> จัดการระบบ (Admin)</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"><img src="<?= $banner1_path ?>" class="d-block w-100"></div>
                <div class="carousel-item"><img src="<?= $banner2_path ?>" class="d-block w-100"></div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">🍟 เมนู <span style="color:var(--tato-orange)">แนะนำ</span> สำหรับคุณ</h2>
            <p class="text-muted">ความอร่อยที่คุณไม่ควรพลาด</p>
        </div>
        <div class="row g-4">
            <?php
            $sql_menu = "SELECT * FROM tb_menu LIMIT 4";
            $res_menu = mysqli_query($conn, $sql_menu);
            while($row = mysqli_fetch_assoc($res_menu)):
            ?>
            <div class="col-6 col-md-3">
                <div class="card menu-card h-100 shadow-sm text-center p-3">
                    <img src="admin/img_ad/<?= $row['img_menu'] ?>" class="card-img-top rounded-4 mb-3" style="height: 180px; object-fit: cover;" onerror="this.src='img/no1.png';">
                    <h5 class="fw-bold"><?= $row['name_menu'] ?></h5>
                    <p class="text-warning fw-bold fs-5">฿<?= number_format($row['price_menu'], 0) ?></p>

                    <div class="mb-3">
                        <select class="form-select form-select-sm rounded-pill topping-select" id="topping_<?= $row['id_menu'] ?>">
                            <option value="No Topping">-- เลือกท็อปปิ้ง --</option>
                            <option value="ชีส">ชีส (+0฿)</option>
                            <option value="โนริสาหร่าย">โนริสาหร่าย (+0฿)</option>
                            <option value="ปาปริก้า">ปาปริก้า (+0฿)</option>
                            <option value="วิงซ์แซ่บ">วิงซ์แซ่บ (+0฿)</option>
                        </select>
                    </div>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <button type="button" class="btn btn-warning w-100 rounded-pill fw-bold py-2 btn-add-cart" data-id="<?= $row['id_menu'] ?>">
                            <i class="bi bi-cart-plus me-1"></i> สั่งเลย
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-warning w-100 rounded-pill fw-bold py-2">
                            <i class="bi bi-cart-plus me-1"></i> สั่งเลย
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 text-center text-lg-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <img src="<?php echo $logo_path; ?>" width="50" height="50" class="me-2 rounded-circle bg-white p-1">
                        <h3 class="fw-bold mb-0 text-white">Tato<span style="color: var(--tato-yellow);">Fun</span></h3>
                    </div>
                    <p class="footer-text">อร่อย สนุก ไปกับมันฝรั่งทอดคุณภาพเยี่ยมที่เราคัดสรรมาเพื่อคุณโดยเฉพาะ</p>
                </div>
                <div class="col-lg-4 px-lg-5">
                    <h5 class="footer-title">ติดต่อเรา</h5>
                    <p class="footer-text mb-2 text-white"><i class="bi bi-geo-alt-fill text-warning me-2"></i> สุขุมวิท ศรีราชา จ.ชลบุรี 20110</p>
                    <p class="footer-text mb-2 text-white"><i class="bi bi-telephone-fill text-warning me-2"></i> 099-999-9999</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">เวลาเปิดทำการ</h5>
                    <p class="mb-1 text-white">ทุกวัน: 10:00 น. - 21:00 น.</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center footer-text small">
                <p class="mb-0">© 2026 <strong>TatoFun</strong>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        $('.btn-add-cart').click(function() {
            var menu_id = $(this).data('id');
            var topping_val = $('#topping_' + menu_id).val();
            var btn = $(this);
            
            btn.prop('disabled', true);

            $.ajax({
                url: 'cart_action.php',
                method: 'POST',
                data: {
                    id: menu_id,
                    topping: topping_val,
                    action: 'add'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#cart-count').text(response.new_count);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'เพิ่มลงตะกร้าแล้ว!',
                            text: 'เมนู: ' + topping_val,
                            showConfirmButton: false,
                            timer: 1200
                        });
                    } else {
                        alert('เกิดข้อผิดพลาด กรุณาลองใหม่');
                    }
                },
                error: function() {
                    alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>