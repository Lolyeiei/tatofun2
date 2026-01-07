<?php
include '../config.php';
// สมมติว่าคุณมีตารางเก็บสต็อก หรือจะดึงจาก tb_menu มาโชว์ก่อนก็ได้
$sql = "SELECT name_menu, id_menu FROM tb_menu"; 
$result = mysqli_query($conn, $sql);
?>
<body class="p-5">
    <h3>📦 สต็อกวัตถุดิบ (ตัวอย่าง)</h3>
    <ul class="list-group mt-3">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <li class="list-group-item d-flex justify-content-between">
                <?php echo $row['name_menu']; ?>
                <span class="badge bg-success">มีของ</span>
            </li>
        <?php endwhile; ?>
    </ul>
    <a href="index_st.php" class="btn btn-secondary mt-3">กลับ</a>
</body>