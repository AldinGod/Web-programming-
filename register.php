<?php
    // Include the database connection file
    include 'MysqlConnection.php';

    $payload = $_REQUEST;

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    try {
        // Retrieve form data
        $email = $_REQUEST["email"];
        $password = $_REQUEST["password"];

        // Check if all required fields are filled
        if (empty($email) || empty($username)) {
            throw new Exception("All fields are required");
        }

        // Prepare and execute the SQL statement to check if email already exists
        $SELECT = "SELECT email FROM User WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($SELECT);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            throw new Exception("Someone already registered using this email");
        }
        
        // Prepare and execute the SQL statement to insert data into the User table
        $INSERT = "INSERT INTO user (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($INSERT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Close the database connection
        $conn = null;

    } catch (Exception $e) {
        // Display the error message
        echo "Error: " . $e->getMessage();
    }
?>