<?php
session_start();
include 'config.php'; 

// 1. คำนวณจำนวนชิ้นรวมในตะกร้า
$total_qty = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_qty += (int)($item['qty'] ?? 0);
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เมนูทั้งหมด - TatoFun</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --tato-orange: #f57c00; 
            --tato-yellow: #ffb300; 
            --tato-dark: #2c3e50;
        }
        body { background-color: #fdfaf0; font-family: 'Kanit', sans-serif; color: #444; }
        
        /* 🚀 Header Design */
        .menu-header { 
            background: linear-gradient(135deg, var(--tato-orange), var(--tato-yellow)); 
            color: white; padding: 60px 0; border-radius: 0 0 80px 80px;
            box-shadow: 0 10px 30px rgba(245, 124, 0, 0.2);
        }

        /* 🍟 Card Animation & Design */
        .card-menu { 
            border: none; border-radius: 30px; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            background: #fff; overflow: hidden; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            height: 100%;
        }
        .card-menu:hover { 
            transform: translateY(-12px) scale(1.02); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.12); 
        }
        
        .img-container { overflow: hidden; position: relative; height: 200px; border-radius: 30px 30px 0 0; }
        .card-menu img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
        .card-menu:hover img { transform: scale(1.15) rotate(2deg); }

        .btn-tato {
            background: linear-gradient(to right, var(--tato-orange), var(--tato-yellow));
            color: white; border: none; border-radius: 50px; font-weight: 600; padding: 10px;
            transition: 0.3s;
        }
        .btn-tato:hover { transform: scale(1.1); box-shadow: 0 5px 15px rgba(245, 124, 0, 0.3); color: white; }

        /* 🛒 Floating Cart - ลูกเล่นตะกร้าเด้งตาม */
        .cart-float { position: fixed; bottom: 30px; right: 30px; z-index: 1000; }
        .cart-btn {
            background: var(--tato-dark); color: white; border-radius: 50px; padding: 15px 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-decoration: none; 
            display: flex; align-items: center; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid var(--tato-yellow);
        }
        .cart-btn:hover { 
            background: #1a252f; transform: scale(1.15) translateY(-5px); 
            color: var(--tato-yellow); 
        }

        /* Modal Styling */
        .modal-content { border-radius: 30px; border: none; overflow: hidden; }
        .topping-item { border: 2px solid #f8f9fa; border-radius: 20px; margin-bottom: 12px; cursor: pointer; transition: 0.3s; }
        .topping-item:hover { border-color: var(--tato-orange); background: #fffcf5; transform: translateX(5px); }
        .form-check-input:checked { background-color: var(--tato-orange); border-color: var(--tato-orange); }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container">
        <a class="btn btn-outline-dark rounded-pill px-4 fw-bold" href="index.php">
            <i class="bi bi-arrow-left-short fs-5"></i> กลับหน้าหลัก
        </a>
        <div class="ms-auto">
            <a href="cart.php" class="text-dark text-decoration-none position-relative p-2">
                <i class="bi bi-basket2 fs-3"></i>
                <?php if($total_qty > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__pulse animate__infinite">
                    <?= $total_qty ?>
                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<div class="menu-header text-center mb-5 animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-5 fw-bold">เลือกความ <span class="text-dark">ฟิน</span> ในแบบคุณ</h1>
        <p class="lead opacity-75">อร่อยสุขภาพดีกับผงผักผลไม้สกัดธรรมชาติ 🍎🥕</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php
        $sql = "SELECT * FROM tb_menu ORDER BY id_menu DESC";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)):
                $img_path = "admin/img_ad/" . $row['img_menu'];
                $is_available = (isset($row['menu_stock']) && $row['menu_stock'] == 1); 
        ?>
        <div class="col-6 col-md-4 col-lg-3 animate__animated animate__fadeInUp">
            <div class="card card-menu <?= !$is_available ? 'opacity-75' : '' ?>">
                <div class="img-container">
                    <img src="<?= $img_path ?>" onerror="this.src='img/no1.png';" style="<?= !$is_available ? 'filter: grayscale(1);' : '' ?>">
                </div>
                <div class="card-body d-flex flex-column p-3 p-md-4">
                    <h6 class="fw-bold mb-1 text-truncate"><?= htmlspecialchars($row['name_menu']) ?></h6>
                    <p class="text-muted small mb-3">TatoFun Special Recipe</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-orange">฿<?= number_format($row['price_menu'], 0) ?></span>
                        <?php if($is_available): ?>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button class="btn btn-tato btn-sm px-3 shadow-sm" 
                                        onclick="openToppingModal('<?= $row['id_menu'] ?>', '<?= htmlspecialchars($row['name_menu']) ?>')">
                                    + สั่งเลย
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm small">ล็อกอิน</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-pill">หมดชั่วคราว</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h4 class="mt-3 text-muted">กำลังเตรียมเมนูอร่อยๆ รอสักครู่...</h4>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade animate__animated animate__zoomIn" id="toppingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <form action="cart_action.php?action=add" method="POST">
                <input type="hidden" name="id" id="modal_id_menu">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0" id="modal_menu_name">ชื่อเมนู</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold mb-3 text-warning"><i class="bi bi-magic me-1"></i> เลือกผงโรยสุขภาพ (ฟรี):</label>
                    <div class="topping-item p-3 d-flex align-items-center" onclick="document.getElementById('t1').checked = true">
                        <input class="form-check-input me-3" type="radio" name="topping" id="t1" value="Apple Peel" checked>
                        <label class="form-check-label w-100" for="t1">
                            <strong>🍎 ผงเปลือกแอปเปิ้ล</strong><br>
                            <small class="text-muted">ต้านอนุมูลอิสระและเพิ่มใยอาหาร</small>
                        </label>
                    </div>
                    <div class="topping-item p-3 d-flex align-items-center" onclick="document.getElementById('t2').checked = true">
                        <input class="form-check-input me-3" type="radio" name="topping" id="t2" value="Carrot Peel">
                        <label class="form-check-label w-100" for="t2">
                            <strong>🥕 ผงเปลือกแครอท</strong><br>
                            <small class="text-muted">เบต้าแคโรทีนสูง บำรุงสายตา</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-tato w-100 py-3 fs-5 shadow">
                        <i class="bi bi-cart-plus me-2"></i>ยืนยันสั่งอาหาร
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['user_id']) && $total_qty > 0): ?>
<div class="cart-float animate__animated animate__bounceInRight">
    <a href="cart.php" class="cart-btn">
        <i class="bi bi-cart-fill me-2 text-warning fs-5"></i>
        <span class="fw-bold">ตะกร้าของฉัน</span> 
        <span class="ms-2 badge bg-warning text-dark rounded-pill shadow-sm"><?= $total_qty ?></span>
    </a>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toppingModal = new bootstrap.Modal(document.getElementById('toppingModal'));
    function openToppingModal(id, name) {
        document.getElementById('modal_id_menu').value = id;
        document.getElementById('modal_menu_name').innerText = name;
        toppingModal.show();
    }

    // SweetAlert หลังเพิ่มสำเร็จ
    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        Swal.fire({
            icon: 'success',
            title: 'เพิ่มลงตะกร้าแล้ว!',
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
</script>
</body>
</html>