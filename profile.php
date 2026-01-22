<?php
session_start();
include 'config.php';

// ตรวจสอบ Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tb_member WHERE id_member = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>โปรไฟล์ของฉัน - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #f8f9fa; }
        .profile-card { border: none; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card profile-card p-4">
                <h4 class="fw-bold mb-4 text-center">ข้อมูลส่วนตัว</h4>
                <form action="update_profile.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="name_member" class="form-control" value="<?= htmlspecialchars($user['name_member'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone_member" class="form-control" value="<?= htmlspecialchars($user['phone_member'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ที่อยู่ปัจจุบัน</label>
                        <textarea name="address_member" class="form-control" rows="3"><?= htmlspecialchars($user['address_member'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill">บันทึกข้อมูล</button>
                    <a href="menu.php" class="btn btn-outline-secondary w-100 rounded-pill mt-2">กลับไปหน้าเมนู</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>