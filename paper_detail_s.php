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

?>

<html>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=1, minimum-scale=1.0, maximum-scale=3.0">
    <title>文獻管理系統</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>


<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }
    aside {
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
    table {
        width: 95%; 
        border-collapse: collapse;
        margin: 20px auto; 
    }

    th, td {
        padding: 12px 15px; 
        text-align: left; 
        border-bottom: 2px solid #fff;  
    }

    th {
        width: 150px;
        background-color: #FFF8D7; 
        color: black; 
        font-size: 16px; 
    }

    td {
        background-color: #ECF5FF;
        font-size: 14px; 
    }

    tr:hover {
        background-color: #f5f5f5; 
    }

    .box {
        background-color: #fff; 
        background-clip: border-box; 
        border-radius: 5px; 
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        padding: 5px;
        margin-left: 100px;
        margin-right: 100px;
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
                        <a href="category.php">
                            <img src="./pic/cate.png" class="pic">
                            <p><b>我的分類</b></p>
                        </a>
                    </li>
                </ul>
            </li>

            <li style="list-style-type: none;">
                <a href="#">
                    <img src="./pic/share.png" class="pic">
                    <p><b>共享文獻庫</b></p>
                </a>
                <ul>
                    <li style="list-style-type: none;">
                        <a href="sharepaper.php">
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

    <div style="padding: 10px; margin-left: 230px; background-color: #f5f5f5; height: 100vh;">
        <span style="color: gray; margin-left: 20px;">共享文獻庫 / </span><a href="sharepaper.php" style="display: inline; color: blue;">共享文獻</a>
        <br/><br/><br/>
        <div class="box">
        <?php
        if (isset($_GET['pid'])) {
            $pid = intval($_GET['pid']); // 確保是整數

            $sql = "SELECT `pid`, `title`, `author`, `caname`, `pages`, `date`, `source`, `vol`, `no`, `note`, `file` FROM documents WHERE pid = $pid";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                
                $row = $result->fetch_assoc();
                echo "<table>";
                echo "<tr><th>文獻代號</th><td>" . $row["pid"] . "</td></tr>";
                echo "<tr><th>論文名稱</th><td>" . $row["title"] . "</td></tr>";
                echo "<tr><th>作者</th><td>" . $row["author"] . "</td></tr>";
                echo "<tr><th>文獻類別</th><td>" . $row["caname"] . "</td></tr>";
                echo "<tr><th>頁數</th><td>" . $row["pages"] . "</td></tr>";
                echo "<tr><th>出版時間</th><td>" . $row["date"] . "</td></tr>";
                echo "<tr><th>資料來源</th><td>" . $row["source"] . "</td></tr>";
                echo "<tr><th>Vol.</th><td>" . $row["vol"] . "</td></tr>";
                echo "<tr><th>No.</th><td>" . $row["no"] . "</td></tr>";
                echo "<tr><th>備註</th><td>" . $row["note"] . "</td></tr>";
                echo "<tr><th>論文檔案</th><td><a style='text-decoration: underline;' href='upload/" . $row["file"] . "' target='_blank'>" . $row["file"] . "</a></td></tr>";
                echo "</table>";
            }
        }
        ?>

        </div>
    </div>
</body>

</html>
