# 3-Tier-Architecture

## Objective 

Define all the malpractice on this diagram and how to fix it or make it better 
To adhere to the 5 pillar of AWS architectural best practices 
- Operational Excellence
- Security
- Reliability 
- Performance Efficiency 
- Cost optimization 

![image](https://github.com/lucm9/3-Tier-Architecture/assets/96879757/317cfab0-eb58-40b2-b0df-fe86b27a8359)


## Operational Excellence:
We are not following aws best practices
Automate tasks for reliability - auto scaling group for our EC2 instances 
Multi-AZ feature for our RDS database 

## Security: 
The webapp and the database are provisioned in the public subnet
Client is using a default VPC which comes by default with public subnets placing resources in the public subnet expose them to the internet which expose the resources to major threats
NAT Gateway
HTTPS to secure our connection to the web server using nginx and apache 

##Reliability:
This architecture is lacking proper reliability should the web app or the database fail we don't have any DR in place
I will recommend to configure auto scaling group in multiple AZ should one AZ fail the webapp will still be available in the other region
Stand alone database - in case we have a failure we can't fail over

##Performance Efficiency: 
Should traffic increase the web app will not be able to handle the mass traffic from users as there's only 1 web app
I will configure auto scaling group
There's no way to distribute traffic
Recommend 2 load balancers 1 external and 1 internal to distribute traffic and secure the environment 

## Cost Optimization: 
Although current architecture might be cheaper we can be exposed to spending more on fixes
Implement monitoring tool like cloudwatch for monitoring 

## New Proposed Architecture

![image](https://github.com/lucm9/3-Tier-Architecture/assets/96879757/183b91d3-80ec-4f6b-8524-ad142e5dd597)

Set up a VPC 

Create a VPC
Create subnets as show in the architecture 
Create a route table and associate it with public subnet
Create route table and associate with private subnet 
Create internet gateway and NAT gateway 

SG (Security Group) 
Create security group for ALB 
Create security group for bastion server 
Create security group for Web Servers
Create security group for Rds database (mysql) - Rules should allow web server connection to the database 

Provision Ec2 instance
For bastion ensure to do installation as needed - mysql-client “sudo yum install mysql -y”
Web App - Project 1 ( Application deployment) -made og

Provision RDS database 
Make use of free tier 

Next step is to create an image of the AMI 
Launch template 
Create auto scaling group 



Target Group 
Create target group for web app
Bastion does not need a target group as we don’t need to attach it to a load balancer 

Create ALB (internet facing) 

```
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,  -- Adding UNIQUE constraint
    password VARCHAR(255) NOT NULL,
    voted BOOLEAN DEFAULT FALSE
);
```


Signin.php

 
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
