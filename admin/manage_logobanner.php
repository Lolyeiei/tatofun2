<?php
session_start();
include '../config.php';

// 1. ตรวจสอบสิทธิ์ (Protection)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); 
    exit();
}

// 2. ดึงข้อมูลรูปภาพปัจจุบันจากฐานข้อมูล
$sql = "SELECT * FROM tb_logobanner ORDER BY id_lb ASC";
$result = mysqli_query($conn, $sql);
$images = [];
while($row = mysqli_fetch_assoc($result)) {
    $images[$row['id_lb']] = $row['name_lb'];
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการโลโก้ & แบนเนอร์ - TatoFun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --tato-yellow: #ffca28;
            --tato-orange: #f57c00;
            --tato-bg: #fffbf0;
            --tato-white: #ffffff;
        }

        body { 
            font-family: 'Kanit', sans-serif; 
            background-color: var(--tato-bg); 
            min-height: 100vh;
        }

        /* หัวข้อหน้า */
        .page-header {
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange));
            color: #000;
            padding: 40px 0;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(245, 124, 0, 0.2);
        }

        /* การ์ดจัดการรูป */
        .card-custom { 
            border: none;
            border-radius: 25px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
            background: #ffffff;
            transition: 0.3s;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 193, 7, 0.2);
        }

        /* ส่วนแสดงตัวอย่างรูป */
        .img-preview-container {
            height: 180px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fdfdfd;
            border: 2px dashed #ffeeba;
            border-radius: 20px;
            margin-bottom: 15px;
            overflow: hidden;
            padding: 10px;
        }

        .img-preview { 
            max-height: 100%; 
            max-width: 100%; 
            object-fit: contain; 
        }

        /* ปุ่มสไตล์ Tato */
        .btn-tato {
            background: linear-gradient(135deg, var(--tato-yellow), var(--tato-orange));
            border: none;
            color: #000;
            font-weight: 600;
            border-radius: 50px;
            transition: 0.3s;
        }

        .btn-tato:hover {
            filter: brightness(0.9);
            transform: scale(1.02);
            color: #000;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #000;
            border-radius: 50px;
            padding: 8px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #fff;
            color: var(--tato-orange);
        }
    </style>
</head>
<body>

<header class="page-header text-center">
    <div class="container position-relative">
        <a href="index_ad.php" class="btn-back position-absolute start-0 top-0 d-none d-md-