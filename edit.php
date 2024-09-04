<?php
require_once(__DIR__ . '/paper_management.php');

session_start();

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $sql = "SELECT * FROM documents WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $paper = $result->fetch_assoc();
    } else {
        echo "No paper found with that ID.";
        exit;
    }
} else {
    echo "No paper ID provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $pid = $_POST['pid'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $caname = $_POST['caname'];
    $public = $_POST['public'];
    $date = $_POST['date'];
    $pages = $_POST['pages'];
    $stype = $_POST['stype'];
    $source = $_POST['source'];
    $vol = $_POST['vol'];
    $no = $_POST['no'];
    $note = $_POST['note'];


    if (!empty($_FILES['file']['name'])) {
        $target_directory = "uploads/";
        $target_file = $target_directory . basename($_FILES['file']['name']);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $filename = $_FILES["file"]["name"];

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file = $target_file; 
        } else {
            echo "Error uploading your file.";
        }
    } else {
        $filename = $paper['file'];
    }

    $sql = "UPDATE documents SET title = ?, author = ?, caname = ?, public = ?, `date` = ?, pages = ?, stype = ?, source = ?, vol = ?, `no` = ?, `file` = ?, note = ? WHERE pid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssi", $title, $author, $caname, $public, $date, $pages, $stype, $source, $vol, $no, $filename, $note, $pid);

    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: welcome.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>


<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Paper</title>
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
            <h1 style="text-align: center;">編輯文獻</h1>
            <h3 style="text-align: center;">Edit paper</h3>
            <br/>
            <div class="box">
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
                <label>論文名稱</label>
                <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($paper['title']); ?>">
                <br/><br/>
                <label>作者</label>
                <input type="text" name="author" class="form-control" required value="<?php echo htmlspecialchars($paper['author']); ?>">
                <br/><br/>
                <label>文獻分類</label>
                <input type="text" name="caname" class="form-control" required value="<?php echo htmlspecialchars($paper['caname']); ?>">
                <br/><br/>
                <label>是否公開</label>
                <select name="public" class="select-form-control">
                    <option value="1">Yes</option>
                    <option value="0" 
                        <?php if ($paper['public'] == 0) echo 'selected'; ?>
                        >No</option>
                </select>
                <br/><br/>
                <label>出版時間</label>
                <input type="text" name="date" class="form-control" required value="<?php echo htmlspecialchars($paper['date']); ?>">
                <br/><br/>
                <label>頁數</label>
                <input type="text" name="pages" class="form-control" required value="<?php echo htmlspecialchars($paper['pages']); ?>">
                <br/><br/>
                <label>論文類別</label>
                <select name="stype" class="select-form-control">
                    <option value="journal" <?php if ($paper['stype'] == 'journal') echo 'selected'; ?>>Journal</option>
                    <option value="conference" <?php if ($paper['stype'] == 'conference') echo 'selected'; ?>>Conference</option>
                </select>
                <br/><br/>
                <label>期刊/會議名稱</label>
                <input type="text" name="source" class="form-control" required value="<?php echo htmlspecialchars($paper['source']); ?>">
                <br/><br/>
                <label>Vol</label>
                <input type="number" name="vol" class="form-control" value="<?php echo htmlspecialchars($paper['vol']); ?>">
                <br/><br/>
                <label>No</label>
                <input type="number" name="no" class="form-control" value="<?php echo htmlspecialchars($paper['no']); ?>">
                <br/><br/>
                <label>備註</label>
                <input type="text" name="note" class="form-control" value="<?php echo htmlspecialchars($paper['note']); ?>">
                <br/><br/>
                <label>論文檔案</label>
                <input type="file" name="file" class="select-form-control">
                <br>Current File: <?php echo htmlspecialchars($paper['file']); ?>
                <br/><br/>
                <button type="submit" name="submit" class="button">Update Paper</button>
                <br/><br/><br/>
            </div>  
        </div>
    </form>
</body>
</html>
