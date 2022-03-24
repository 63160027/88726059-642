<?php

session_start();
if(!isset($_SESSION['loggined'])){
    header('Location: login.php');
}

require_once("dbconfig.php");

// ตรวจสอบว่ามีการ post มาจากฟอร์ม ถึงจะเพิ่ม
if ($_POST){
    $doc_num = $_POST['doc_num'];
    $doc_title = $_POST['doc_title'];
    $doc_start_date = $_POST['doc_start_date'];
    $doc_to_date = $_POST['doc_to_date'];
    $doc_status = $_POST['doc_status'];
    $doc_file_name = $_FILES['doc_file_name']['name'];

    // insert a record by prepare and bind
    // The argument may be one of four types:
    //  i - integer
    //  d - double
    //  s - string
    //  b - BLOB

    // ในส่วนของ INTO ให้กำหนดให้ตรงกับชื่อคอลัมน์ในตาราง actor
    // ต้องแน่ใจว่าคำสั่ง INSERT ทำงานใด้ถูกต้อง - ให้ทดสอบก่อน
    $sql = "INSERT 
            INTO documents (doc_num, doc_title, doc_start_date, doc_to_date, doc_status, doc_file_name) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssss", $doc_num, $doc_title, $doc_start_date, $doc_to_date, $doc_status, $doc_file_name);
    $stmt->execute();

    //uploads!!
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["doc_file_name"]["name"]);
    $fileType="pdf";
    $realname="a.pdf";
    if (move_uploaded_file($_FILES["doc_file_name"]["tmp_name"], $target_file)) {
      //echo "The file ". htmlspecialchars( basename( $_FILES["doc_file_name"]["name"])). " has been uploaded.";
    } else {
      //echo "Sorry, there was an error uploading your file.";
    }
    //

    // redirect ไปยัง actor.php
    header("location: documents.php"); //output
}
echo "<div align='center'>Hello ".$_SESSION['stf_name'] . "<div>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>เพิ่ม</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
    function check() {
        var doc_num = document.getElementById("doc_num").value;
        document.getElementById("disp").innerHTML = doc_num;
        var xhttp = new XMLHttpRequest();
        console.log("hello");
        xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status ==200 ) {
                document.getElementById("disp").innerHTML = this.responseText;
                if (this.responseText != ""){
                    document.getElementById("submit").disabled = true;
                    document.getElementById("disp").innerHTML = "<a href='addstafftodocument.php?id=" + 
                    this.responseText + "'>จัดการกรรมการ</a>";
                }else{
                    document.getElementById("submit").disabled = false;
                    document.getElementById("disp").innerHTML = "";
                }
            }
        };
        console.log("hello");
        xhttp.open("GET", "check.php?docnum=" + doc_num, true);
        console.log("hello");
        xhttp.send();
    }
</script>
</head>

<body style="background-color:#FEF5ED">
    <div class="container">
        <div align='left'><h1>เพิ่ม</h1><div>
        <h3><a href='documents.php'><span class='glyphicon glyphicon-chevron-left'></span></a> </h3>
        <form action="newdocuments.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div align='left'><label for="doc_num">เลขที่</label><div>
                <div align='left'><input type="text" class="form-control" name="doc_num" id="doc_num" onkeyup="check()"><div>
                <h3><div id = "disp"></div></h3>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_title">เรื่อง</label><div>
                <div align='left'><input type="text" class="form-control" name="doc_title" id="doc_title"><div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_start_date">วันที่เริ่มต้น</label><div>
                <div align='left'><input type="date" class="form-control" name="doc_start_date" id="doc_start_date"><div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_to_date">วันที่สิ้นสุด</label><div>
                <div align='left'><input type="date" class="form-control" name="doc_to_date" id="doc_to_date"><div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_status">สถานะ</label><br><div>
                <div align='left'><input type="radio"  name="doc_status" id="doc_status" value="Active"> Active <br><div>
                <div align='left'><input type="radio"  name="doc_status" id="doc_status" value="Expire"> Expire<div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_file_name">ชื่อไฟล์เอกสาร</label><div>
                <div align='left'><input type="file" class="form-control" name="doc_file_name" id="doc_file_name"><div>
            </div><br>
            <div align='left'><button type="submit" class="btn btn-success" id="submit">Save</button><div>
        </form>
</body>

</html>