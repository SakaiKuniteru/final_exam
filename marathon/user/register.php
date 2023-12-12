<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";

    // Kết nối đến MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed:" . $conn->connect_error);
    }

    $servername_event = "localhost";
    $username_event = "root";
    $password_event = "";
    $dbname_event = "event";

    // Kết nối đến MySQL - Database event
    $conn_event = new mysqli($servername_event, $username_event, $password_event, $dbname_event);

    // Kiểm tra kết nối
    if ($conn_event->connect_error) {
        die("Connection failed:" . $conn_event->connect_error);
    }

    // Truy vấn danh sách sự kiện từ Database event
    $query_events = "SELECT event_name FROM participants";
    $result_events = $conn_event->query($query_events);

    // Mảng để lưu trữ danh sách sự kiện
    $events = array();

    // Lặp qua kết quả và lưu vào mảng
    while ($row = $result_events->fetch_assoc()) {
        $events[] = $row['event_name'];
    }

    // Xử lý khi form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Hàm kiểm tra rỗng
        function isEmpty($value) {
            return empty($value) || trim($value) === '';
        }
        $user_id = $_POST["user_id"];
        $full_name = $_POST["full_name"];
        $nationality = $_POST["nationality"];
        $passport_no = $_POST["passport_no"];
        $gender = $_POST["gender"];
        $age = $_POST["age"];
        $email = $_POST["email"];
        $phone_number = $_POST["phone_number"];
        $address = $_POST["address"];
        $competition = $_POST["competition"];

        // Kiểm tra các trường không được để trống
        if (isEmpty($user_id) || isEmpty($full_name) || isEmpty($nationality) || isEmpty($passport_no) || isEmpty($gender) || isEmpty($age) || isEmpty($email) || isEmpty($phone_number) || isEmpty($address) || isEmpty($competition)) {
            echo "Please complete all information!";
            exit();
        }

        // Kiểm tra tuổi tối thiểu là 16
        if ($age < 16) {
            echo "You are not yet 16 years old so you cannot register!";
            exit();
        }

        // Kiểm tra tuổi tối đa là 40
        if ($age > 40) {
            echo "You are over 40 years old so you cannot register!";
            exit();
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format!";
            exit();
        }

        // Kiểm tra xem thông tin đăng ký đã tồn tại hay chưa
        $checkQuerys = "SELECT * FROM participants WHERE
                        user_id = '$user_id' AND
                        competition = '$competition'";

        $checkResults = $conn->query($checkQuerys);

        if ($checkResults->num_rows > 0) {
            // Thông tin đăng ký đã tồn tại
            echo "User id already exists!";
            exit();
        }

        // Kiểm tra xem thông tin đăng ký đã tồn tại hay chưa
        $checkQuery = "SELECT * FROM participants WHERE
                        user_id = '$user_id' AND
                        full_name = '$full_name' AND
                        nationality = '$nationality' AND
                        passport_no = '$passport_no' AND
                        gender = '$gender' AND
                        age = $age AND
                        email = '$email' AND
                        phone_number = '$phone_number' AND
                        address = '$address' AND
                        competition = '$competition'";

        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Thông tin đăng ký đã tồn tại
            echo "You have registered for the contest!";
            exit();
        }

        // Chuẩn bị và thực hiện truy vấn SQL để chèn dữ liệu vào bảng participants
        $sql = "INSERT INTO participants (user_id, full_name, nationality, passport_no, gender, age, email, phone_number, address, competition)
                VALUES ('$user_id', '$full_name', '$nationality', '$passport_no', '$gender', $age, '$email', '$phone_number', '$address', '$competition')";

        if ($conn->query($sql) === TRUE) {
            // Đăng ký thành công, chuyển hướng về trang user.php
            header("Location: user.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Đóng kết nối
    $conn->close();
    $conn_event->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/bxslider/4.2.15/jquery.bxslider.min.js"></script>
        <link rel="stylesheet" href="static/bootstrap.css">
        <link rel="stylesheet" href="static/styles_2.css"> 
        <link rel="stylesheet" href="static/styless.css">
        <title>Register</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: rebeccapurple;" href="user.php">Hanoi International Marathon</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">Register</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="event.php">Event List</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <br>
        <h2 class="text-align-center" style="color: blue;">Register to participate in the Marathon</h2>
        <div class="container mx-auto" style="position: relative;">
            <form class="form_register mx-auto" action="register.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <label for="user_id">User ID:</label>
                        <input type="number" name="user_id" required>

                        <label for="full_name">Full Name:</label>
                        <input type="text" name="full_name" required>

                        <label for="nationality">Nationality:</label>
                        <input type="text" name="nationality" required>

                        <label for="passport_no">Passport No:</label>
                        <input type="number" name="passport_no" required>

                        <label>Gender:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male">
                            <label class="form-check-label" for="inlineRadio1">Male</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female">
                            <label class="form-check-label" for="inlineRadio2">Female</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="Other">
                            <label class="form-check-label" for="inlineRadio3">Other</label>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="age">Age:</label>
                        <input type="number" name="age" required>
                        <label for="email">Email:</label>
                        <input type="email" name="email" required>

                        <label for="phone_number">Phone Number:</label>
                        <input type="tel" name="phone_number" required>

                        <label for="address">Address:</label>
                        <input name="address" required>

                        <label for="event_name">Select Event:</label>
                        <select name="competition" required>
                        <option value="" disabled selected>Select event</option>
                            <?php
                            // Hiển thị danh sách sự kiện trong dropdown
                            foreach ($events as $event) {
                                echo "<option value=\"$event\">$event</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="mx-auto">
                    <button type="submit" class="btn btn-primary d-block mx-auto">Register</button>
                </div>
            </form>
        </div>
    </header>
</body>
</html>