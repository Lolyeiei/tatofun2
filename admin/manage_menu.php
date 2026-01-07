<!--เสร็จแล้ว-->
<?php
// 1. ส่วนการเชื่อมต่อฐานข้อมูล (ต้องอยู่บนสุด)
include('../config.php'); 

// 2. ดึงข้อมูลจากตาราง tb_menu
$sql = "SELECT * FROM tb_menu"; 
$result = mysqli_query($conn, $sql); 

// ตรวจสอบเบื้องต้นว่า Query สำเร็จไหม
if (!$result) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการเมนู - TatoFun</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .table img { object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0"> รายการเมนูอาหาร </h4>
                <a href="add_menu.php" class="btn btn-success btn-sm fw-bold">+ เพิ่มเมนูใหม่</a>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">ชื่อเมนู</th>
                                <th style="width: 20%;">ราคา</th>
                                <th style="width: 20%;">รูปภาพ</th>
                                <th style="width: 20%;" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td><strong><?php echo $row['name_menu']; ?></strong></td>
                                <td><?php echo number_format($row['price_menu']); ?> บาท</td>
                                <td>
                                    <?php if($row['img_menu']) { ?>
                                        <img src="img_ad/<?php echo $row['img_menu']; ?>" width="60" height="60" class="shadow-sm">
                                    <?php } else { ?>
                                        <span class="badge bg-secondary text-white">ไม่มีรูป</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="edit_menu.php?id=<?php echo $row['id_menu']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                                        <a href="delete.php?id=<?php echo $row['id_menu']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('ยืนยันการลบเมนูนี้?')">ลบ</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <a href="index_ad.php" class="btn btn-outline-secondary btn-sm">← กลับหน้าหลัก Admin</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>