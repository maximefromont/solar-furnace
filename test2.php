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
        // Close the connection
        $conn->close();
        ?>

        <!-- Add a canvas element to render the chart -->
        <div class="mt-5">
            <canvas id="temperatureChart"></canvas>
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
                        {
                            label: 'Target Temperature',
                            data: data.targetTempData.map((point, index) => ({ x: index, y: point.y })),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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