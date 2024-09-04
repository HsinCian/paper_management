<?php
require_once(__DIR__ . '/paper_management.php');

session_start();
if(!isset($_SESSION["loggedin"])){
    header("location: ./login.php");
    exit;
}

$userIp = "";
if (!empty($_SERVER["HTTP_CLIENT_IP"])){
    $userIp = $_SERVER["HTTP_CLIENT_IP"];
}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $userIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
}else{
    $userIp = $_SERVER["REMOTE_ADDR"];
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $caname = $_POST["caname"];
    $note = $_POST["note"];
    $userid = $_SESSION['userid'];

    $sql = "SELECT `caname` FROM `categories` WHERE `caname` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $caname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "這個分類名稱已存在。";
    } else {
        $sql = "INSERT INTO `categories` (`caname`, `userid`, `note`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $caname, $userid, $note);
        (mysqli_stmt_execute($stmt));
    }
    mysqli_stmt_close($stmt);
}

?>

<html>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=1, minimum-scale=1.0, maximum-scale=3.0">
    <title>我的分類 - 新增分類</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>


<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }
    aside {
        position: fixed;
        float:left;
        width: 230px;
        background-color: #b0c4de;
        padding: 10px;
        height: 100vh;
    }
    a {
        padding: 2px;
        background: none;
        color: black;
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    .pic {
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }
    .top-pic {
        width: 40px;
        height: 40px;
        margin-right: 5px;
    }
    .box {
        background-color: #fff; 
        background-clip: border-box; 
        border-radius: 5px; 
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        padding: 10px;
        margin-left: 50px;
        margin-right: 40px;
    }
    .form-control {
        display: block;
        width: 100%;
        height: 40px;
        padding: 15px;
        font-weight: 400;
        color: gray;
        background-clip: border-box;
        border: 1px solid #d3d3d3;
        border-radius: 5px;
        margin-top: 8px;
    }
    .button {
        display: block;
        background-clip: border-box;
        border: 1px solid #fff;
        border-radius: 5px;
        background-color: #ffa500;
        color: #fff;
        font-size: 16px;
        margin: 0 auto;
    }
</style>


<body>
    <aside>
        <a href="welcome.php">
            <img src="./pic/papers6.png" class="top-pic">
            <h3><b>文獻管理系統</b></h3>
        </a>
        <hr/><br/>

        <div style="text-align: center;"><b>帳號：<?= $_SESSION["userid"]; ?></b></div>
        <div style="text-align: center;"><b>姓名：<?= $_SESSION["username"]; ?></b></div>
        <br/><hr/>

        <ul>
            <li style="list-style-type: none;">
                <a href="welcome.php">
                    <img src="./pic/my.png" class="pic">
                    <p><b>我的文獻庫</b></p>
                </a>
                <ul>
                    <li style="list-style-type: none;">
                        <a href="welcome.php">
                            <img src="./pic/list.png" class="pic">
                            <p><b>我的文獻</b></p>
                        </a>
                    </li>
                    <li style="list-style-type: none;">
                        <a href="welcome.php">
                            <img src="./pic/cate.png" class="pic">
                            <p><b>我的分類</b></p>
                        </a>
                    </li>
                </ul>
            </li>

            <li style="list-style-type: none;">
                <a href="welcome.php">
                    <img src="./pic/share.png" class="pic">
                    <p><b>共享文獻庫</b></p>
                </a>
                <ul>
                    <li style="list-style-type: none;">
                        <a href="welcome.php">
                            <img src="./pic/globe.png" class="pic">
                            <p><b>共享文獻</b></p>
                        </a>
                    </li>
                </ul>
            </li>

            <li style="list-style-type: none;">   
                <a href="./logout.php">
                    <img src="./pic/exit.png" class="pic">
                    <p><b>登出</b></p>
                </a>
            </li>
        </ul>
        
    </aside>

    <form method="POST" action="">
        <div style="padding: 10px; margin-left: 230px; background-color: #f5f5f5; height: 100%;">
            <span style="color: gray; margin-left: 20px;">我的文獻庫 / </span><a href="category.php" style="display: inline; color: gray;">我的分類 /</a>
            <a href="addcategory.php" style="display: inline; color: blue;">新增分類</a>
            <h1 style="text-align: center;">新增分類</h1>
            <h3 style="text-align: center;">Add new categories</h3>
            <br/>
            <div class="box">
                <div style="margin-top: 30px;">
                    <label for="caname" style="padding: 5px;"><b>類別名稱 *</b></label>
                    <input type="text" name="caname" class="form-control" id="caname" placeholder="不可重複 20字內" required value="">
                </div>
                <br/>
                <div style="margin-bottom: 40px;">
                    <label for="note" style="padding: 5px;"><b>備註</b></label>
                    <input type="text" name="note" class="form-control" style="height: 100px;" id="note" placeholder="100字內">
                </div>
                
                <div style="margin-bottom: 80px;">
                    <button type="submit" name="submit" class="button"><b>新增</b></button>
                </div>
            </div>
        </div>
    </form>

</body>

</html>
