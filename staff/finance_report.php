<?php
session_start();
include '../config.php'; // เชื่อมฐานข้อมูล

// 1. ดึงยอดรายรับรวม (income)
$res_in = mysqli_query($conn, "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type='income'");
$income = mysqli_fetch_assoc($res_in)['total'] ?? 0;

// 2. ดึงยอดรายจ่ายรวม (expense)
$res_ex = mysqli_query($conn, "SELECT SUM(fin_amount) as total FROM tb_finance WHERE fin_type='expense'");
$expense = mysqli_fetch_assoc($res_ex)['total'] ?? 0;

$profit = $income - $expense; // คำนวณกำไร
?>
<div class="container mt-5">
    <h3 class="fw-bold">📊 สรุปรายงานการเงิน</h3>
    <div class="row g-3 mt-3">
        <div class="col-md-4">
            <div class="card p-3 bg-success text-white">รายรับลูกค้า: ฿<?php echo number_format($income, 2); ?></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-danger text-white">รายจ่ายสต็อก: ฿<?php echo number_format($expense, 2); ?></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-primary text-white">กำไรสุทธิ: ฿<?php echo number_format($profit, 2); ?></div>
        </div>
    </div>
</div>