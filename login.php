<?php
session_start();
include 'config.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') { header("Location: admin/index_ad.php"); exit(); }
    if ($_SESSION['role'] == 'staff') { header("Location: staff/index_st.php"); exit(); }
    header("Location: index.php"); exit();
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM tb_users WHERE username = '$username' AND password = '$password'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $result = mysqli_fetch_array($query); 
        if ($result) {
            $_SESSION['role'] = $result['role']; 
            $_SESSION['fullname'] = $result['fullname']; 
            $_SESSION['user_id'] = $result['user_id']; // อ้างอิงตามโครงสร้าง DB
            
            if($result['role'] == 'admin'){ header("Location: admin/index_ad.php");
            } elseif($result['role'] == 'staff'){ header("Location: staff/index_st.php"); 
            } else { header("Location: index.php"); }
            exit();
        } else { $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"; }
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
        }

        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #ffca28, #f57c00, #ffb300, #ff9100);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow: hidden; /* ล็อคหน้าจอให้ของลอยอย่างเดียว */
            position: relative;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ✨ เอฟเฟกต์ของลอยที่มีมิติ */
        .potato-float {
            position: absolute;
            z-index: 1;
            bottom: -120px;
            font-size: 50px;
            user-select: none;
            pointer-events: none;
            animation: move-up-wiggle 12s infinite ease-in-out;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));
            opacity: 0.8;
        }

        /* 🕺 ท่าเต้น (Wiggle) ให้มันดู "ดิ้น" */
        @keyframes move-up-wiggle {
            0% { transform: translateY(0) rotate(0deg) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            25% { transform: translateY(-25vh) rotate(15deg) translateX(20px); }
            50% { transform: translateY(-50vh) rotate(-15deg) translateX(-20px); }
            75% { transform: translateY(-75vh) rotate(10deg) translateX(15px); }
            90% { opacity: 1; }
            100% { transform: translateY(-115vh) rotate(360deg) translateX(0); opacity: 0; }
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.92); 
            backdrop-filter: blur(10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            z-index: 10;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: 0.4s ease;
        }

        .login-card:hover { transform: translateY(-10px) scale(1.02); }

        .brand-logo { 
            width: 100px; height: 100px;
            border-radius: 50%;
            margin-bottom: 20px; 
            background: #fff;
            padding: 8px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .form-control { 
            border-radius: 18px; padding: 12px 20px; 
            border: 1px solid #eee; background: #f8f9fa;
        }

        .btn-login { 
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange)); 
            border-radius: 18px; padding: 14px; width: 100%; border: none; font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="potato-float" style="left: 8%; animation-duration: 10s;">🍟</div>
    <div class="potato-float" style="left: 22%; animation-duration: 15s; animation-delay: 2s;">🥔</div>
    <div class="potato-float" style="left: 40%; animation-duration: 12s; animation-delay: 4s;">🧀</div>
    <div class="potato-float" style="left: 60%; animation-duration: 18s; animation-delay: 1s;">🍟</div>
    <div class="potato-float" style="left: 75%; animation-duration: 11s; animation-delay: 5s;">🥔</div>
    <div class="potato-float" style="left: 90%; animation-duration: 14s; animation-delay: 3s;">🧀</div>

    <div class="login-card">
        <img src="admin/img_ad/LOGO3.png" alt="Logo" class="brand-logo" onerror="this.src='https://via.placeholder.com/100?text=TatoFun'">
        <h2 class="fw-bold">ยินดีต้อนรับ</h2>
        <p class="text-muted mb-4">เข้าสู่ระบบ TatoFun กันเลย!</p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger p-2 small"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label ms-2 small fw-bold">ชื่อผู้ใช้งาน</label>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label ms-2 small fw-bold">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-login mb-3">เข้าสู่ระบบ</button>
            <div class="small">ยังไม่เป็นสมาชิก? <a href="register.php" class="text-decoration-none fw-bold" style="color:var(--tato-orange)">สร้างบัญชีใหม่</a></div>
        </form>
    </div>

</body>
</html>