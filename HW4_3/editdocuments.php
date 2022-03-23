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
    $id = $_POST['id'];
    $doc_num = $_POST['doc_num'];
    $doc_title = $_POST['doc_title'];
    $doc_start_date = $_POST['doc_start_date'];
    $doc_to_date = $_POST['doc_to_date'];
    $doc_status = $_POST['doc_status'];
    $doc_file_name = $_FILES['doc_file_name']['name'];

    if ($doc_file_name<>""){
        $sql = "UPDATE documents
        SET doc_num = ?, 
            doc_title = ?, 
            doc_start_date = ?, 
            doc_to_date = ?, 
            doc_status = ?, 
            doc_file_name = ?

        WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssi", $doc_num, $doc_title, $doc_start_date, $doc_to_date, $doc_status, $doc_file_name, $id);
        $stmt->execute();

        //uploads!!
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["doc_file_name"]["name"]);
        if (move_uploaded_file($_FILES["doc_file_name"]["tmp_name"], $target_file)) {
        //echo "The file ". htmlspecialchars( basename( $_FILES["doc_file_name"]["name"])). " has been uploaded.";
        } else {
        //echo "Sorry, there was an error uploading your file.";
        }
    }else{
        $sql = "UPDATE documents
        SET doc_num = ?, 
            doc_title = ?, 
            doc_start_date = ?, 
            doc_to_date = ?, 
            doc_status = ?

        WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssi", $doc_num, $doc_title, $doc_start_date, $doc_to_date, $doc_status, $id);
        $stmt->execute();
    }
    
    header("location: documents.php");
} else {
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
    <title>แก้ไข</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body style="background-color:#FEF5ED">
    <div class="container">
        <div align='left'><h1>แก้ไข</h1></div>
        <div align='left'><h3><a href='documents.php'><span class='glyphicon glyphicon-chevron-left'></span></a> </h3></div>
        <form action="editdocuments.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
                <div align='left'><label for="doc_num">เลขที่</label></div>
                <div align='left'><input type="text" class="form-control" name="doc_num" id="doc_num" value="<?php echo $row->doc_num;?>">
            </div></div>
            <div class="form-group">
                <div align='left'><label for="doc_title">เรื่อง</label></div>
                <div align='left'><input type="text" class="form-control" name="doc_title" id="doc_title" value="<?php echo $row->doc_title;?>"></div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_start_date">วันที่เริ่มต้น</label></div>
                <div align='left'><input type="date" class="form-control" name="doc_start_date" id="doc_start_date" value="<?php echo  $row->doc_start_date;?>"></div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_to_date">วันที่สิ้นสุด</label></div>
                <div align='left'><input type="date" class="form-control" name="doc_to_date" id="doc_to_date" value="<?php echo $row->doc_to_date;?>"></div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_status">สถานะ</label><br></div>
                <div align='left'><input type="radio"  name="doc_status" id="doc_status" value="Active"
                    <?php if($row->doc_status == "Active"){echo "checked";}?>> Active <br></div>
                    <div align='left'><input type="radio"  name="doc_status" id="doc_status" value="Expire"
                    <?php if($row->doc_status == "Expire"){echo "checked";}?>> Expire</div>
            </div>
            <div class="form-group">
                <div align='left'><label for="doc_file_name">ชื่อไฟล์เอกสาร</label></div>
                <div align='left'><input type="file" class="form-control" name="doc_file_name" id="doc_file_name" value="<?php echo $row->doc_file_name;?>"></div>
            </div>
            <input type="hidden" name="id" value="<?php echo $row->id;?>">
            <div align='left'><button type="submit" class="btn btn-success">Update</button></div>
        </form>
</body>

</html>