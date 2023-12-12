<?php
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

    // Truy vấn SQL để lấy toàn bộ dữ liệu từ bảng participants
    $sql = "SELECT * FROM participants ORDER BY time_record ASC";
    $result = $conn->query($sql);

    // Đóng kết nối
    $conn->close();
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
        <link rel="stylesheet" href="static/styless.css">
        <title>Marathon User</title>
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
                            <a class="nav-link active" aria-current="page" href="event.php">Even List</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="image/image1.jpg" class="d-block w-100 h-px" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="image/image2.jpg" class="d-block w-100 h-px" alt="...">
                    </div> 

                    <div class="carousel-item">
                        <img src="image/image3.jpg" class="d-block w-100 h-px" alt="...">
                    </div>

                    <div class="carousel-item">
                        <img src="image/image4.jpg" class="d-block w-100 h-px" alt="...">
                    </div>

                    <div class="carousel-item">
                        <img src="image/image5.jpg" class="d-block w-100 h-px" alt="...">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleIndicators'));

                    myCarousel._element.addEventListener('slid.bs.carousel', function (event) {
                        // Lấy index của slide hiện tại
                        var currentIndex = event.to;

                        // Lấy tổng số slide
                        var totalSlides = myCarousel._items.length;

                        // Nếu đang ở slide cuối cùng, chuyển đến slide đầu tiên
                        if (currentIndex === totalSlides - 1) {
                            myCarousel.to(0);
                        }
                    });
                });
            </script>
        </header>

        <section>
            <br>
            <div class="container">
                <h3>- Welcome athletes and fans to Hanoi International Marathon 2023 - a classy international sports event in the capital Hanoi! We are very happy and proud to welcome all of you to join us in conquering legendary runs through the most beautiful streets of the city with a team of enthusiastic volunteers and companions from all over the world.</h3>
                <br>
                <h3>- If you have not yet registered to participate, quickly click <a href="register.php" style="text-decoration: none;">[here]</a> to experience thrilling moments and challenge yourself in the exciting atmosphere of this great event. Don't miss the opportunity to join a community of sports lovers, where passion and positive energy spread from every step.</h3>
                <br>
                <h3>- In addition, you can also see detailed information about ongoing competitions as well as related events at our official website <a href="event.php" style="text-decoration: none;">[here]</a>. Join us, immerse yourself in the exciting atmosphere and gain unforgettable experiences at Hanoi International Marathon!</h3>
                <br>
                <h3>- Below are the leaderboards of previous competitions, which chronicle the incredible achievements of outstanding athletes. This is a great source of encouragement for you to have more motivation and encouragement to reach new achievements. Consider carefully and let your name become part of Hanoi International Marathon history!</h3>
                
            </div>
            <br><br>
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
                                    </tr>
                                </thead>";
                            echo "<tbody class='border-primary table-group-divider'>";

                            foreach ($rows as $row) {
                                echo "<tr>";
                                // echo "<td>" . (isset($row["standings"]) ? $row["standings"] : "") . "</td>";
                                echo "<td>" . $standing . "</td>";
                                echo "<td>" . $row["user_id"] . "</td>";
                                echo "<td>" . $row["time_record"] . "</td>";
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
        </section>
    </body>
</html>