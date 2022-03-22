<?php
session_start();

$error = "";
if($_POST){
    require_once("dbconfig.php");
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT *
            FROM staff
            WHERE username = ? AND passwd = md5(?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['stf_name'] = $row['stf_name'];
        $_SESSION['is_admin'] = $row['is_admin'];
        $_SESSION['loggined'] = true;
        header('Location: documents.php');
    }else{
        $error = 'Username or password is incorrect';
    }
}else{
    if(isset($_SESSION['loggined'])){
        header('Location: documents.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>

<body>
    <h3>Login</h3>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <br><br>
        <input type="submit" value="login" name="submit">
    </form>
    <div style="">
        <?php  echo $error; ?>
    </div>
</body>

</html>