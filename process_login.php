<!DOCTYPE html>
<html lang="en">

<body>
    <?php
        include "inc/nav.inc.php";
        include "inc/header.inc.php";
        include "inc/head.inc.php";
    ?>
    <main class="container">
        <?php
        /* . Here we can use the $_POST[] superglobal variable 
        to retrieve the values submitted in the
        form.
        */
            
            $email = $errorMsg = $pwd_hashed = $fname = $lname = $privilege = $id = "";  // declaring global variables
            $success = true;

            function authenticateUser(){
                // saying the variables used in this function are referenced from the global variable outside the scope
                global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success, $id, $privilege; 
                // Create database connection.
                $config = parse_ini_file('/var/www/private/db-config.ini');
                if (!$config)
                {
                    $errorMsg = "Failed to read database config file.";
                    $success = false;
                }
                else{
                    $conn = new mysqli(
                        $config['servername'],
                        $config['username'],
                        $config['password'],
                        $config['dbname']
                        );
                    // Check connection
                    if ($conn->connect_error)
                    {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                    }
                    else
                    {
                        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0)
                        {
                            // Note that email field is unique, so should only have
                            // one row in the result set.
                            $row = $result->fetch_assoc();
                            $fname = $row["fname"];
                            $lname = $row["lname"];
                            $pwd_hashed = $row["password"];
                            $id = $row["member_id"];
                            $privilege = $row["privilege"];
                            // Check if the password matches:
                            if (!password_verify($_POST["pwd"], $pwd_hashed))
                            {
                            // Don't be too specific with the error message - hackers don't
                            // need to know which one they got right or wrong. :)
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                            }
                        }
                        else
                        {
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                        }
                        $stmt->close();
                    }
                    echo "<script>console.log('closing connection...')</script>";
                    $conn->close();
                }
            }

            
            if (empty($_POST["email"]))
            {
                $errorMsg .= "Email is required.<br>";
                $success = false;
            }
            else if (empty($_POST["pwd"])){
                $errorMsg .= "Password is required.<br>";
                $success = false;
            }
            else
            {
                $email = sanitize_input($_POST["email"]);
                // Additional check to make sure e-mail address is well-formed.
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                $errorMsg .= "Invalid email format.";
                $success = false;
                }
                else{
                    
                    $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                    // try{
                    authenticateUser();
                    // }
                    // catch (Exception $e){
                    //     $success = false;
                    //     $errorMsg = $e->getMessage();
                    // }
                    
                }
            }
            if ($success)
            {
                $_SESSION['user_id'] = $id;
                $_SESSION['logged_in'] = true;
                $_SESSION['privilege'] = $privilege;
                
                echo "<script>console.log('Success.....')</script>";
                echo "<h4>Login successful!</h4>";
                echo "<p>Welcome: " . $fname . $lname;
                echo "<br>";
                echo "<a href='/'><button>Home</button></a>";
                
            }
            else
            {   
                echo "<script>console.log('Failed.....')</script>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a href='login.php'><button>Return to Login</button></a>";
            }
            /*
            * Helper function that checks input for malicious or unwanted content.
            */
            function sanitize_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
        ?>
    </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>



