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
    $dbname = "achievements";

    // Kết nối đến MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //user
    $servername_user = "localhost";
    $username_user = "root";
    $password_user = "";
    $dbname_user = "user";

    // Kết nối đến MySQL - Database user
    $conn_user = new mysqli($servername_user, $username_user, $password_user, $dbname_user);

    // Kiểm tra kết nối
    if ($conn_user->connect_error) {
        die("Connection failed:" . $conn_user->connect_error);
    }

    // Truy vấn danh sách sự kiện từ Database event
    $query_users = "SELECT user_id FROM participants"; 
    $result_users = $conn_user->query($query_users);


    // Mảng để lưu trữ danh sách user
    $users = array();

    // Lặp qua kết quả và lưu vào mảng
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row['user_id'];
    }

    // event
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
    $query_events = "SELECT marathon_ID FROM participants";
    $result_events = $conn_event->query($query_events);

    // Mảng để lưu trữ danh sách sự kiện
    $events = array();

    // Lặp qua kết quả và lưu vào mảng
    while ($row = $result_events->fetch_assoc()) {
        $events[] = $row['marathon_ID'];
    }

    // Xử lý khi form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Hàm kiểm tra rỗng
        function isEmpty($value) {
            return empty($value) || trim($value) === '';
        }

        $marathon_ID = $_POST["marathon_ID"];
        $user_id = $_POST["user_id"];
        $time_record = $_POST["time_record"];

        // Kiểm tra các trường không được để trống
        if (isEmpty($marathon_ID) || isEmpty($user_id) || isEmpty($time_record)) {
            echo "Please complete all information!";
            exit();
        }

        // Kiểm tra xem thông tin đăng ký đã tồn tại hay chưa
        $checkQuery = "SELECT * FROM participants WHERE
                        marathon_ID = '$marathon_ID' AND
                        user_id = '$user_id'";

        $checkResult = $conn->query($checkQuery);

        if ($checkResult === FALSE) {
            echo "Error in check query: " . $conn->error;
            exit();
        }

        if ($checkResult->num_rows > 0) {
            // Thông tin đăng ký đã tồn tại
            echo "Marathon ID and User ID have been recorded!";
            exit();
        }

         // Thực hiện truy vấn để xác định standing
         $standingQuery = "SELECT COUNT(*) + 1 AS standings FROM participants 
         WHERE marathon_ID = '$marathon_ID' AND time_record <= '$time_record'
         ORDER BY time_record ASC";
        $standingResult = $conn->query($standingQuery);
 
     if ($standingResult === FALSE) {
         echo "Error in standing query: " . $conn->error;
         exit();
     }
 
     if ($standingResult->num_rows > 0) {
         $standingRow = $standingResult->fetch_assoc();
         $standings = $standingRow['standings'];
     
         // Cập nhật standings cho tất cả các người tham gia có thời gian nhỏ hơn hoặc bằng thời gian mới
         $updateStandingsQuery = "UPDATE participants SET standings = standings + 1 
             WHERE marathon_ID = '$marathon_ID' AND time_record <= '$time_record'";
         $conn->query($updateStandingsQuery);
     } else {
         $standings = 1;
     } 

        // Chuẩn bị và thực hiện truy vấn SQL để chèn dữ liệu vào bảng participants
        $sql = "INSERT INTO participants (marathon_ID, user_id, time_record, standings)
                VALUES ('$marathon_ID', '$user_id', '$time_record', '$standings')";

        if ($conn->query($sql) === TRUE) {
            // Đăng ký thành công, chuyển hướng về trang achievements.php
            header("Location: achievements.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Truy vấn SQL để lấy toàn bộ dữ liệu từ bảng participants
    $sql = "SELECT * FROM participants ORDER BY time_record ASC";
    $result = $conn->query($sql);

    // Đóng kết nối
    $conn->close();
    $conn_user->close();
    $conn_event->close();
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
    <link rel="stylesheet" href="static/styless.css">
    <link rel="stylesheet" href="static/styles_3.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Marthon Admin</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: rebeccapurple;" href="user.php">Marathon Admin</a>
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
                <h2 class="navbar-brand mx-auto">List of registered candidates</h2>
                
                <button type="button" id="addButton" class="btn btn-success d-block mx-auto"><i class='bx bxs-message-alt-add'></i> Add Achievements</button>
            </div>
        </div>
        
        <div id="addForm" class="container mx-auto"  style="position: relative; display: none;">
            <form class="form-register-03 mx-auto" action="achievements.php" method="post">
                <label for="marathon_ID">Select Marathon ID:</label>
                <select name="marathon_ID" required>
                <option value="" disabled selected>Select Marathon ID</option>
                    <?php
                        foreach ($events as $event) {
                            echo "<option value=\"$event\">$event</option>";
                        }
                    ?>
                </select>

                <label for="user_id">Select User ID:</label>
                <select name="user_id" required>
                <option value="" disabled selected>Select User ID</option>
                    <?php
                        foreach ($users as $user) {
                            echo "<option value=\"$user\">$user</option>";
                        }
                    ?>
                </select>

                <label for="time_record">Time Record:</label>
                <input type="text" name="time_record" pattern="([0-9]+):([0-5][0-9]):[0-5][0-9].[0-9]{1,3}" placeholder="--:--:--.---" required>

                <hr>
                <div class="mx-auto">
                    <button type="submit" class="btn btn-primary d-block mx-auto">Add Achievements</button>
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
                    // Create an array to store unique marathon IDs
                    $uniqueMarathonIDs = array();

                    // Group rows by marathon_ID
                    while ($row = $result->fetch_assoc()) {
                        $marathonID = $row["marathon_ID"];
                        $uniqueMarathonIDs[$marathonID][] = $row;
                    }

                    // Iterate through unique marathon IDs and display tables
                    foreach ($uniqueMarathonIDs as $marathonID => $rows) {
                        $standing = 1;
                        echo "<h2 style='text-align: center; color: blue; background-color: yellow;'>Marathon ID: $marathonID</h2>";
                        echo "<table class='table table-bordered border-primary table-group-divider table-striped table-hover' style='text-align: center;'>";
                        echo "<thead >
                                <tr>
                                    <th>Standing</th>
                                    <th>User ID</th>
                                    <th>Time Cord</th>
                                    <th>Action</th>
                                </tr>
                             </thead>";
                        echo "<tbody class='border-primary table-group-divider'>";

                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $standing . "</td>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["time_record"] . "</td>";
                            echo "<td>";
                            echo "<a href='delete_achievements.php?id=" . $row["time_record"] . "' onclick='return confirm(\"Are you sure?\");' style='text-align: center; display: block;'>
                                    <i class='bx bxs-trash'></i>
                                </a>";
                            echo "</td>";
                            echo "</tr>";
                            $standing++;
                        }

                        echo "</tbody></table>";
                        echo "<br><hr><br>";
                        
                    }
                    
                } else {
                    echo "There are no recorded achievements yet.";
                }
            ?>
        </div>
    </header>
</body>
</html>