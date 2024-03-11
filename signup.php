<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // TODO: Perform validation and insert into the database
    // Connect to the RDS MySQL database
    $host = "test.c1cei6qi8n1k.us-east-2.rds.amazonaws.com";  // Replace with your RDS endpoint
    $dbname = "xashyclassdb";  // Replace with your database name
    $username = "admin";  // Replace with your database username
    $password = "adminadmin";  // Replace with your database password

    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the user data into the database
        $stmt = $dbh->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Redirect to signin.php after successful signup
        header("Location: signin.php");
        exit; // Ensure no further code execution after redirect
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <form method="POST" action="signup.php">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
