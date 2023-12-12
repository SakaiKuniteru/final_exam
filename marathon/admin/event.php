<?php
    session_start();

    // Check if the logout button is clicked
    if (isset($_POST["logout"])) {
        // Destroy the session
        session_destroy();

        // Redirect to the login page
        header("Location: admin.php");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "event";

    // Kết nối đến MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed:" . $conn->connect_error);
    }

    // Xử lý khi form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Hàm kiểm tra rỗng
        function isEmpty($value) {
            return empty($value) || trim($value) === '';
        }
        $marathon_ID = $_POST["marathon_ID"];
        $event_name = $_POST["event_name"];
        $distance = $_POST["distance"];
        $registration_deadline = $_POST["registration_deadline"];
        $competition_day = $_POST["competition_day"];

        // Kiểm tra các trường không được để trống
        if (isEmpty($marathon_ID) || isEmpty($event_name) || isEmpty($distance) || isEmpty($registration_deadline) || isEmpty($competition_day)) {
            echo "Please complete all information!";
            exit();
        }

        // Kiểm tra độ dài của marathon_ID
        if (!is_numeric($marathon_ID) || strlen($marathon_ID) !== 6) {
            echo "Marathon ID must be numeric and 6 digits long!";
            exit();
        }

        // Kiểm tra xem marathon_ID đã tồn tại trong cơ sở dữ liệu chưa
        $check_marathon_id_sql = "SELECT * FROM participants WHERE marathon_ID = '$marathon_ID'";
        $result_marathon_id = $conn->query($check_marathon_id_sql);
        if ($result_marathon_id->num_rows > 0) {
            echo "Marathon ID already exists!";
            exit();
        }

        // Kiểm tra xem event_name đã tồn tại trong cơ sở dữ liệu chưa
        $check_event_name_sql = "SELECT * FROM participants WHERE event_name = '$event_name'";
        $result_event_name = $conn->query($check_event_name_sql);
        if ($result_event_name->num_rows > 0) {
            echo "Event Name already exists!";
            exit();
        }

        // Kiểm tra xem ngày kết thúc đăng ký lớn hơn ngày hôm nay
        if (strtotime($registration_deadline) <= time()) {
            echo "Registration deadline must be greater than today!";
            exit();
        }

        // Kiểm tra xem ngày kết thúc lớn hơn hoặc bằng ngày bắt đầu
        if (strtotime($competition_day) < strtotime($registration_deadline)) {
            echo "The exam date must be greater than the registration due date!";
            exit();
        }

        // Chuẩn bị và thực hiện truy vấn SQL để chèn dữ liệu vào bảng participants
        $sql = "INSERT INTO participants (marathon_ID, event_name, distance, registration_deadline, competition_day)
                VALUES ('$marathon_ID', '$event_name', '$distance', '$registration_deadline', '$competition_day')";

        if ($conn->query($sql) === TRUE) {
            header("Location: event.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


    }
        // Lấy ngày hiện tại
        $current_date = date("Y-m-d");

        // Thực hiện truy vấn SQL để lấy thông tin của toàn bộ sự kiện
        $sql = "SELECT * FROM participants WHERE registration_deadline >= '$current_date' ORDER BY registration_deadline";
        $result = $conn->query($sql);
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
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="static/bootstrap.css">
        <link rel="stylesheet" href="static/styless.css">
        <title>Marthon Admin</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: rebeccapurple;" href="user.html">Marathon Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="user.php">User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="event.php">Event</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="achievements.php">Achievements</a>
                    </li>
                </ul>
                <div class="ms-auto">
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <header>
        <div class="navbar navbar-expand-lg ">
            <div class="container-fluid">
                <h2 class="navbar-brand mx-auto">Create a Marathon</h2>
                <button type="button" id="addButton" class="btn btn-success d-block mx-auto"><i class='bx bxs-message-alt-add'></i> Add Event</button>
            </div>
        </div>

        <div id="addForm" class="container mx-auto" style="position: relative; display: none;">
            <form class="form_register mx-auto" action="event.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <label for="marathon_ID">Marathon ID:</label>
                        <input type="number" name="marathon_ID" required>

                        <label for="event_name">Event name:</label>
                        <input type="text" name="event_name" required>

                        <label for="distance">Distance:</label>
                        <input type="number" name="distance" required>
                    </div>

                    <div class="col-6">
                        <label for="registration_deadline">Registration Deadline:</label>
                        <input type="date" name="registration_deadline" required>

                        <label for="competition_day">Competition Day:</label>
                        <input type="date" name="competition_day" required>
                    </div>
                </div>
                <hr>
                <div class="mx-auto">
                    <button type="submit" class="btn btn-primary d-block mx-auto">Create Event</button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById("addButton").addEventListener("click", function() {
                // Hiển thị hoặc ẩn form khi nút "Thêm" được nhấp
                var form = document.getElementById("addForm");
                form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
            });
        </script>

        <div class="container mx-auto">
            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered border-primary table-group-divider table-striped table-hover' style='text-align: center;'>";
                echo "<thead>
                        <tr>
                            <th>Marathon ID</th>
                            <th>Event Name</th>
                            <th>Distance</th>
                            <th>Registration Deadline</th>
                            <th>Competition Day</th>
                            <th>Action</th>
                        </tr>
                    </thead>";
                echo "<tbody class='border-primary table-group-divider'>";
                while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["marathon_ID"] . "</td>";
                echo "<td style='text-align: left; display: block;'>" . $row["event_name"] . "</td>";
                echo "<td>" . $row["distance"] . " km</td>";
                echo "<td>" . $row["registration_deadline"] . "</td>";
                echo "<td>" . $row["competition_day"] . "</td>";
                echo "<td>";
                echo "<a href='delete_event.php?id=" . $row["marathon_ID"] . "' onclick='return confirm(\"Are you sure?\");'><i class='bx bxs-trash'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
                echo "</tbody></table>";
            } else {
                echo "There are no events.";
            }

            // Đóng kết nối
            $conn->close();
            ?>
        </div>
    </header>
</body>
</html>