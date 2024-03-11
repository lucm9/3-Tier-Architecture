<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $email = $_POST['email'];
    $password = $_POST['password'];


    // TODO: Perform validation and authentication against the database
    // Connect to the RDS MySQL database
    $host = "172.17.0.2";  // Replace with your RDS endpoint
    $dbname = "xashy";  // Replace with your database name
    $username = "root";  // Replace with your database username
    $password = "admin";  // Replace with your database password


    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // Authenticate the user against the database
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            echo "Logged in successfully!";
        } else {
            echo "Invalid email or password!";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
</head>
<body>
    <h1>Sign In</h1>
    <form method="POST" action="signin.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>


        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>


        <input type="submit" value="Sign In">
    </form>
</body>
</html>
