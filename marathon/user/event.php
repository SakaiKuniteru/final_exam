<?php
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
    <title>Event List</title>
    <link rel="stylesheet" href="static/bootstrap.css">
    <link rel="stylesheet" href="static/styless.css">
    <title>Event List</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" style="color: rebeccapurple;" href="user.php">Hanoi International Marathon</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <div class="collapse navbar-collapse " id="navbarSupportedContent">
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
        <h2 class="text-align-center" style="color : blue;">List of current and upcoming events</h2>
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
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "There are no events.";
            }
            ?>
        </div>
    </header>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
