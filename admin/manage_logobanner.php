<!--เสร็จแล้ว-->
<?php
session_start();
include '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // ปรับ Path ให้ถอยออกไปหาหน้า login.php ที่อยู่ด้านนอก
    exit();
}
// ดึงข้อมูลรูปภาพทั้งหมด
$sql = "SELECT * FROM tb_logobanner ORDER BY id_lb ASC";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>จัดการโลโก้ & แบนเนอร์ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
        body { background-color: #fff9e6; font-family: 'Kanit', sans-serif; }
        .card-custom { border-radius: 25px; border: none; box-shadow: 0 15px 35px rgba(255, 193, 7, 0.1); background: #ffffff; }
        .img-preview { height: 160px; width: 100%; object-fit: contain; background: #fdfdfd; padding: 10px; border: 2px dashed #ffeeba; border-radius: 20px; }
        .btn-pill { border-radius: 50px; padding: 10px 20px; font-weight: 600; border: none; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-warning">จัดการรูปภาพหน้าเว็บไซต์</h2>

    </div>

    <div class="row g-4">
        <?php 
        $sections = [
            1 => ['title' => 'Logo Shop', 'preview' => 'preview-logo'],
            2 => ['title' => 'Home Banner 1', 'preview' => 'preview-b1'],
            3 => ['title' => 'Home Banner 2', 'preview' => 'preview-b2'],
            4 => ['title' => 'Home Banner 3', 'preview' => 'preview-b3']
        ];

        foreach ($sections as $id => $info) : 
            // กำหนด Path ให้ชัดเจน
            $file_path = "img_ad/" . $images[$id];
            $is_file_ready = (!empty($images[$id]) && file_exists($file_path));
            $display_img = $is_file_ready ? $file_path : "img_ad/default.png";
        ?>
        <div class="col-md-6 col-lg-3">
            <div class="card card-custom p-3 text-center h-100">
                <span class="badge rounded-pill bg-warning text-dark mb-2"><?= $info['title'] ?></span>
                
                <div class="my-2">
                    <img id="<?= $info['preview'] ?>" src="<?= $display_img ?>?v=<?=time()?>" class="img-preview">
                </div>

                <?php if ($is_file_ready): ?>
                    <small class="text-success"><i class="bi bi-check-circle"></i> พบไฟล์ในระบบ</small>
                <?php else: ?>
                    <small class="text-danger"><i class="bi bi-x-circle"></i> ไม่พบไฟล์/ยังไม่ได้อัปโหลด</small>
                <?php endif; ?>

                <form action="process_logobanner.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="id_lb" value="<?= $id ?>">
                    <input type="file" name="img_file" class="form-control form-control-sm rounded-pill mb-2" onchange="previewImage(this, '<?= $info['preview'] ?>')" required>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" name="btn_save" class="btn btn-warning flex-grow-1 btn-sm btn-pill">อัปโหลด</button>
                        <a href="process_logobanner.php?delete_id=<?= $id ?>" class="btn btn-outline-danger btn-sm btn-pill" onclick="return confirm('ลบรูป?')"><i class="bi bi-trash"></i></a>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function previewImage(input, targetId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById(targetId).src = e.target.result; }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>