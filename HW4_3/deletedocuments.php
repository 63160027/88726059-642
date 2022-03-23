<?php

session_start();
if(!isset($_SESSION['loggined'])){
    header('Location: login.php');
}else{
    echo "<div align='center'>Hello ".$_SESSION['stf_name'] . "<div>";
}

require_once("dbconfig.php");

// ตรวจสอบว่ามีการ post มาจากฟอร์ม ถึงจะลบ
if ($_POST){
    // ดึงค่าที่โพสจากฟอร์มตาม name ที่กำหนดในฟอร์มมากำหนดให้ตัวแปร $id
    $id = $_POST['id'];

    //ลบข้อมูลในตาราง doc_staff
    $sql = "DELETE 
            FROM doc_staff
            WHERE doc_staff.doc_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // เตรียมคำสั่ง DELETE
    $sql = "DELETE 
            FROM documents
            WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // redirect ไปยังหน้า actor.php
    header("location: documents.php");
} else {
    // ดึงค่าที่ส่งผ่านมาทาง query string มากำหนดให้ตัวแปร $id
    $id = $_GET['id'];
    $sql = "SELECT *
            FROM documents
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_object();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ลบข้อมูล</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body style="background-color:#FEF5ED">
    <div class="container">
        <div align='left'><h1>ลบข้อมูล</h1></div>
        <div align='left'><h3><a href='documents.php'><span class='glyphicon glyphicon-chevron-left'></span></a> </h3></div>
        <table class="table table-hover">
            <tr>
                <div align='left'><th style='width:120px'>เลขที่</th></div>
                <div align='left'><td><?php echo $row->doc_num;?></td></div>
            </tr>
            <tr>
                <div align='left'><th>เรื่อง</th></div>
                <div align='left'><td><?php echo $row->doc_title;?></td></div>
            </tr>
            <tr>
                <div align='left'><th>วันที่เริ่มต้น</th></div>
                <div align='left'><td><?php echo $row->doc_start_date;?></td></div>
            </tr>
            <tr>
                <div align='left'><th>วันที่สิ้นสุด</th></div>
                <div align='left'><td><?php echo $row->doc_to_date;?></td></div>
            </tr>
            <tr>
                <div align='left'><th>สถานะ</th></div>
                <div align='left'><td><?php echo $row->doc_status;?></td></div>
            </tr>
            <tr>
                <div align='left'><th>ชื่อไฟล์เอกสาร</th></div>
                <div align='left'><td><?php echo $row->doc_file_name;?></td></div>
            </tr>

        </table>
        <form action="deletedocuments.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row->id;?>">
            <div align='left'><input type="submit" value="Confirm delete" class="btn btn-danger"></div><br>
            <div align='left'><button type="button" class="btn btn-warning" onClick="window.history.back()">Cancel Delete</button></div>
        </form>
</body>

</html>