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
        width: 100%;
        border-collapse: collapse; 
        margin: 20px 0; 
        border: none;
    }

    th, td {
        padding: 12px 15px; 
        text-align: left; 

    }

    th {
        background-color: #ECF5FF; 
        color: black;
        font-size: 16px;
    }

    td {
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
        <span style="color: gray; margin-left: 20px;">我的文獻庫 / </span><a href="category.php" style="display: inline; color: blue;">我的分類</a>
        <br/><br/><br/>
        <div class="box">
        <?php
        if (isset($_GET['caid'])) {
            $caid = intval($_GET['caid']); 

            $canameQuery = "SELECT caname FROM categories WHERE caid=$caid";
            $canameResult = $conn->query($canameQuery);
            if ($canameResult->num_rows > 0) {
                $canameRow = $canameResult->fetch_assoc();
                $caname = $canameRow['caname'];
                echo "<h2 style='text-align: center;'>" . $caname . "</h2>";
            }

            $sql = $conn->prepare("SELECT `title`, `author`, `pages`, `date`, `source`, `vol`, `no`, `pid`, `stype` FROM documents WHERE caname=? and FIND_IN_SET(?, userid) > 0");
            $sql->bind_param("ss", $caname, $_SESSION['userid']);
            $sql->execute();
            $result = $sql->get_result();
        
            echo "<table border='1'><tr><th>Papers</th></tr>"; 

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td><a href='paper_detail.php?pid=" . $row["pid"] . "'>";
                    echo "<div class='paper-detail'>";
                    echo "<span class='author'>" . $row["author"] . ",</span>";
                    echo "<span class='title'>\"" . $row["title"] . "\", </span>";
                    if ($row["stype"]=="journal"){
                        echo "<span class='source'><i>" . $row["source"] ."</i>, Vol." . $row["vol"] .", No." . $row["no"] .", pp." . $row["pages"] .", " . $row["date"] . "</span>";
                    } else {
                        echo "<span class='source'><i>" . $row["source"] ."</i>, pp." . $row["pages"] .", " . $row["date"] . "</span>";
                    }
                    echo "</div>";
                    echo "</a></td></tr>";
                    
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
        }else{
            echo "not receive get";
        }
        ?>

        </div>
    </div>
</body>
</html>
