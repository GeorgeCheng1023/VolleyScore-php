<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>


<body id="wrapper">
    <?php include "components\header.php"; ?>

    <div class="container">
        <form action="teamResult.php" method="POST">
            <div class="form-group row mb-2 mt-4">
                <label for="TeamID" class="col-sm-2 col-form-label">隊伍編號</label>
                <div class="col-sm-10">
                    <input type="text" name="TeamID" class="form-control" id="TeamID" placeholder="Enter your team id">
                </div>
            </div>

            <div class="form-group row mb-2 mt-4">
                <label for="TeamName" class="col-sm-2 col-form-label">隊伍名稱</label>
                <div class="col-sm-10">
                    <input type="text" name="TeamName" class="form-control" id="TeamName" placeholder="Enter your team name">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </div>
        </form>

        <?php
        // Assuming you have established a database connection
        $servername = "your_servername";
        $username = "your_username";
        $password = "your_password";
        $dbname = "your_dbname";

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the form data
            $teamID = $_POST["TeamID"];
            $teamName = $_POST["TeamName"];

            // Prepare the SQL update statement
            $sql = "UPDATE Teams SET TeamName = ? WHERE TeamID = ?";

            // Prepare and bind the parameters
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $teamName, $teamID);

            // Execute the update statement
            if ($stmt->execute()) {
                echo "Team updated successfully.";
            } else {
                echo "Error updating team: " . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        }

        // Close the database connection
        $conn->close();
        ?>


    </div>


    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
