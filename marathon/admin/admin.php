<?php
    session_start();

    // Kiểm tra nếu đã đăng nhập thì chuyển hướng đến trang admin
    if (isset($_SESSION['admin'])) {
        header("Location: user.php");
        exit();
    }

    // Kiểm tra nếu có dữ liệu được gửi từ form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Kiểm tra tên đăng nhập và mật khẩu
        if ($username === "admin" && $password === "admin") {
            // Đăng nhập thành công, tạo biến session cho admin
            $_SESSION['admin'] = true;

            // Chuyển hướng đến trang user.php
            header("Location: user.php");
            exit();
        } else {
            $error_message = "Username or password is incorrect.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.15/jquery.bxslider.min.js"></script>
    <link rel="stylesheet" href="static/bootstrap.css">
    <link rel="stylesheet" href="static/styles_3.css">
    <title>Login Admin</title>
</head>
<body>

    <h2 class="text-align-center" style="background-color: #33CCFF;">Login Marathon Admin</h2>

    <?php
        // Hiển thị thông báo lỗi (nếu có)
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
    ?>
        <form class="mx-auto form-register-03" action="admin.php" method="post" 
        style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 250px; margin-top: 150px">
            <label for="username">User name:</label>
            <input type="text" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br><br>
            <button type="submit" class="btn btn-primary d-block mx-auto">Login</button>
        </form>

    <script>
        // Kiểm tra nếu đang ở trang admin.php thì thêm overflow: hidden vào body
        if (window.location.pathname.endsWith('admin.php')) {
            document.body.style.overflow = 'hidden';
        }
    </script>
    
</body>
</html>
