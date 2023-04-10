<!DOCTYPE html>
<html>

<head>
    <title>Interface de contrôle FSP</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container">
        <h3 class="mt-5">Interface de contrôle FSP</h3>

        <form method="post">
            <div class="form-group mt-5">
                <label for="waterTemp">Water Temp</label>
                <input type="number" class="form-control" id="waterTemp" name="waterTemp" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <?php

        $servername = "localhost";
        $username = "root";
        $password = "e";
        $dbname = "fsp";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        ?>

        <?php

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get input value
            $target_temp = $_POST["waterTemp"];
            if ($target_temp != '') {
                // Insert input value into "target-temp" column of "target" table
                $sql = "INSERT INTO target (target_temp) VALUES ('$target_temp');";
                if ($conn->query($sql) === TRUE) {
                    $new_id = $conn->insert_id;
                    header("Location: {$_SERVER['PHP_SELF']}?new_id=$new_id");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
        ?>

        <!-- Add a canvas element to render the chart -->
        <div class="mt-5">
            <canvas id="temperatureChart"></canvas>
        </div>

        <div class="table-responsive mt-5">
            <?php
            // Check if a new record was inserted
            if (isset($_GET['new_id'])) {
                $new_id = $_GET['new_id'];
            }
            ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Température eau</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // SQL query to select all rows from the "Data" table
                    $sql = "SELECT id_data, date_data, water_temp FROM Data ORDER BY id_data DESC";

                    // Execute the query and store the result
                    $result = $conn->query($sql);

                    // If the query returned any rows, loop through and print the id
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='p-2'>" . $row["id_data"] . "</td>";
                            echo "<td class='p-2'>" . $row["date_data"] . "</td>";
                            echo "<td class='p-2'>" . $row["water_temp"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>0 results</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>Température cible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // SQL query to select all rows from the "Data" table
                    $sql = "SELECT id_target, date_target, target_temp FROM target ORDER BY id_target DESC";

                    // Execute the query and store the result
                    $result = $conn->query($sql);

                    // If the query returned any rows, loop through and print the id
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            if (isset($new_id) && $new_id == $row["id_target"]) {
                                echo "<td class='p-2 bg-success'>" . $row["id_target"] . "</td>";
                                echo "<td class='p-2 bg-success'>" . $row["date_target"] . "</td>";
                                echo "<td class='p-2 bg-success'>" . $row["target_temp"] . "</td>";
                            } else {
                                echo "<td class='p-2'>" . $row["id_target"] . "</td>";
                                echo "<td class='p-2'>" . $row["date_target"] . "</td>";
                                echo "<td class='p-2'>" . $row["target_temp"] . "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>0 results</td></tr>";
                    }
                    ?>

                    <?php
                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <?php
            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Fetch the data using AJAX
        async function fetchData() {
            const response = await fetch('fetch_data.php');
            const data = await response.json();
            return data;
        }

        fetchData().then(data => {
            const ctx = document.getElementById('temperatureChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        {
                            label: 'Water Temperature',
                            data: data.waterTempData.map((point, index) => ({ x: index, y: point.y })),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        },
                    ],
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear',
                            display: true,
                            title: {
                                display: true,
                                text: 'Data Points',
                            },
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Temperature',
                            },
                        },
                    },
                },
            });
        });
    </script>
</body>

</html>