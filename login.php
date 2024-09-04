<?php
require_once(__DIR__ . '/paper_management.php');

session_start();
if(isset($_SESSION["loggedin"])){
    header("location: ./welcome.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // 取得 POST 過來的資料
    $userid=$_POST["userid"];
    $password=$_POST["password"];
    $password_hash=password_hash($password,PASSWORD_DEFAULT);

    // 以帳號進資料庫查詢
    $sql = "SELECT `userid`, `password`, `username` FROM `users` WHERE `userid`=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_userid, $result_password, $result_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // 驗證密碼
    if(password_verify($password, $result_password)){
        // 密碼通過驗證
        session_start();
        // 把資料存入 Session
        $_SESSION["loggedin"] = true;
        $_SESSION["userid"] = $result_userid;
        $_SESSION["username"] = $result_name;
        //$_SESSION["memberEmail"] = $result_email;
        // 轉跳到會員頁面
        header("location: ./welcome.php");
        exit;
    }else{
        // 密碼驗證失敗
        $test = password_hash("123",PASSWORD_DEFAULT);
        echo '<script>alert("帳號或密碼錯誤.\nIncorrect ID or Password.");</script>';
        echo '<script>console.log("'.$test.'");</script>';
    }
}

// Close connection
mysqli_close($conn);
	
?>
<!doctype html>
<html lang="zh-hant">

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="login.css" rel="stylesheet">
    <title>登入範例</title>
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form method="POST" action="">
            <h2 class="h2 mb-2 fw-bold">文獻管理系統</h2>
            <h1 class="h1 mb-4 fw-bold">登入</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="inputUsername" placeholder="帳號" name="userid">
                <label for="inputUsername">User ID</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="inputPassword" placeholder="密碼" name="password">
                <label for="inputPassword">Password</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary fw-bold" type="submit">login</button>
        </form>
    </main>

    <!-- <?= password_hash("123",PASSWORD_DEFAULT); ?> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>