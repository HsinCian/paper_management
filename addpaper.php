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

$categories = [];
$sql = "SELECT caname FROM categories"; 
if ($result = mysqli_query($conn, $sql)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    mysqli_free_result($result);
}


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST["pid"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $caname = $_POST["caname"];
    $public = $_POST["public"];
    $date = $_POST["date"];
    $pages = $_POST["pages"];
    $source = $_POST["source"];
    $vol = $_POST["vol"];
    $no = $_POST["no"];
    $note = $_POST["note"];
    $userid = $_SESSION['userid'];
    $stype = $_POST['stype'];

    $sql = "SELECT `pid` FROM `documents` WHERE `pid` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $pid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "這篇論文已存在。";
    } else {

        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
            $allowed = array("pdf" => "application/pdf", "doc" => "application/msword", "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            $filename = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
        
            if (in_array($filetype, $allowed)) {
                // Check whether file exists before uploading it
                if (file_exists("upload/" . $filename)) {
                    //echo $filename . " is already exists.";
                } else {
                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $filename);
                    //echo "Your file was uploaded successfully.";
                } 
            } else {
                //echo "Error: There was a problem uploading your file. Please try again."; 
            }
        } else {
            echo "Error: " . $_FILES["file"]["error"];
        }

        $sql = "INSERT INTO `documents` (`pid`, `title`, `author`, `caname`, `public`, `date`, `pages`, `source`, `vol`, `no`, `note`, `userid`, `file`, `stype`, `editor`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssssssss",$pid, $title, $author, $caname, $public, $date, $pages, $source, $vol, $no, $note, $userid, $filename, $stype, $userid);
        if (mysqli_stmt_execute($stmt)) {
            //echo "新文獻已成功添加。";
        } else {
            //echo "錯誤：無法新增文獻。";
        }
    }
    mysqli_stmt_close($stmt);
}

?>

<html>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=1, minimum-scale=1.0, maximum-scale=3.0">
    <title>我的文獻 - 新增文獻</title>
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
    .select-form-control {
        display: block;
        width: 100%;
        height: 40px;
        padding: 5px;
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

    <form method="POST" action="" enctype="multipart/form-data">
        <div style="padding: 10px; margin-left: 230px; background-color: #f5f5f5; height: 100%;">
            <span style="color: gray; margin-left: 20px;">我的文獻庫 / </span><a href="welcome.php" style="display: inline; color: gray;">我的文獻 / </a>
            <a href="addpaper.php" style="display: inline; color: blue;">新增文獻</a>
            <h1 style="text-align: center;">新增文獻</h1>
            <h3 style="text-align: center;">Add new papers</h3>
            <br/>
            <div class="box">
                <div style="margin-top: 30px;">
                    <label for="pid" style="padding: 5px;"><b>文獻代號 *</b></label>
                    <input type="text" name="pid" class="form-control" id="pid" placeholder="不可重複 20字內數字" required value="">
                </div>
                <br/>
                <div>
                    <label for="title" style="padding: 5px;"><b>論文名稱 *</b></label>
                    <input type="text" name="title" class="form-control" id="title" placeholder="100字內" required value="">
                </div>
                <br/>
                <div>
                    <label for="author" style="padding: 5px;"><b>作者 *</b></label>
                    <input type="text" name="author" class="form-control" id="author" placeholder="以逗號隔開 100字內" required value="">
                </div>
                <br/>
                <div>
                    <label for="caname" style="padding: 5px;"><b>文獻分類 *</b></label>


                    <?php
                        $userid = $_SESSION['userid']; 
                        $sql = "SELECT caname FROM categories WHERE userid = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            echo '<select name="caname" class="select-form-control" id="caname" required>';
                            echo '<option value="">選擇分類</option>';
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($row['caname']).'">'.htmlspecialchars($row['caname']).'</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '沒有可用的分類';
                        }

                        $stmt->close();
                        $conn->close();
                    ?>
                </div>
                <br/>
                <div>
                    <label for="public" style="padding: 5px;"><b>是否公開 *</b></label>
                    <select name="public" class="select-form-control" id="public" required>
                        <option value=""></option>
                        <option value="1" >公開 public</option>
                        <option value="0" >不公開 non-public</option>
                    </select>
                </div>
                <br/>
                <div>
                    <label for="date" style="padding: 5px;"><b>出版時間 *</b></label>
                    <input type="text" name="date" class="form-control" id="date" placeholder="年份" required value="">
                </div>
                <br/>
                <div>
                    <label for="pages" style="padding: 5px;"><b>頁數 *</b></label>
                    <input type="text" name="pages" class="form-control" id="pages" placeholder="文獻頁數" required value="">
                </div>
                <br/>
                <div>
                    <label for="stype" style="padding: 5px;"><b>論文類別 *</b></label>
                    <select name="stype" class="select-form-control" id="stype" onchange="changeSourceType(this);">
                        <option value=""></option>
                        <option value="journal" >期刊論文 Journal</option>
                        <option value="conference" >會議論文 Conference</option>
                    </select>
                </div>
                <br/>
                <!--Jorurnal-->
                <div id="stype-journal" style="display: none;">
                    <div>
                        <label for="source"><b>期刊名稱 *</b></label>
                        <input type="text" name="source" class="form-control" id="source" placeholder="100字內">
                    </div>
                    <br/>
                    <div>
                        <label for="vol"><b>Vol. *</b></label>
                        <input type="number" name="vol" class="form-control" id="vol" placeholder="">
                    </div>
                    <br/>
                    <div>
                        <label for="no"><b>No. *</b></label>
                        <input type="number" name="no" class="form-control" id="no" placeholder="">
                    </div>
                </div>
                <!--Conference-->
                <div id="stype-conference" style="display: none;">
                    <div>
                        <label for="source"><b>會議名稱 *</b></label>
                        <input type="text" name="source" class="form-control" id="source" placeholder="100字內">
                    </div>
                </div>
                <br/>
                <div>
                    <label for="file" style="padding: 5px;"><b>論文檔案 *</b></label>
                    <input type="file" name="file" class="form-control" style="height: 50px;" id="file" placeholder="上傳檔案" required>
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

<script>
    function changeSourceType(inStype) {
      var stype = inStype.value;
      if (stype == "journal") {
        $("#stype-conference").hide();
        $("#stype-journal").show();
      }
      else if (stype == "conference") {
        $("#stype-journal").hide();
        $("#stype-conference").show();
      }
      else {
        $("#stype-journal").hide();
        $("#stype-conference").hide();
      }
    }
</script>
</html>
