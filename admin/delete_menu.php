<!--เสร็จแล้ว-->
<?php
include('../config.php');

// รับค่า id_menu มาจากปุ่มลบในหน้า manage_menu.php
if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    $sql = "DELETE FROM tb_menu WHERE id_menu = $id";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('ลบรายการสำเร็จ!'); window.location='manage_menu.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>