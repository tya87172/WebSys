<!DOCTYPE html>
<html lang="en">

<body>
    <?php
        include "../inc/nav.inc.php";
        include "../inc/header.inc.php";
        include "../inc/head.inc.php";
    ?>
    <main class="container">
        <?php
        /* . Here we can use the $_POST[] superglobal variable 
        to retrieve the values submitted in the
        form.
        */
            
            $userid = $privilege = $email = $errorMsg = $pwd_hashed = $fname = $lname = "";  // declaring global variables
            $success = true;

            function saveMemberToDB(){
                // saying the variables used in this function are referenced from the global variable outside the scope
                global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success, $privilege, $userid; 
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
                        if ($result->num_rows > 0 && $result->fetch_assoc()["user_id"]!=$userid )
                        {
                            $errorMsg = "Email exists in database already!";
                            $success = false;
                            $stmt->close();
                        }
                        else{
                            echo $fname;
                            // Prepare the statement:
                            $stmt = $conn->prepare("UPDATE users SET fname =?, lname=?, email=?, privilege=? WHERE user_id=?");
                            // Bind & execute the query statement:
                            $stmt->bind_param("ssssi", $fname, $lname, $email, $privilege, $userid);
                        
                            if (!$stmt->execute())
                            {
                                // throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                                $success = false;
                            }
            
                            $stmt->close();
                        }
                    }
                    echo "<script>console.log('closing connection...')</script>";
                    $conn->close();
                }
            }

            $userid = $_POST["userid"];
            if (empty($_POST["email"]))
            {
                $errorMsg .= "Email is required.<br>";
                $success = false;
            }
            else if (empty($_POST["lname"])){
                $errorMsg .= "Last name is required.<br>";
                $success = false;
            }
            else if (preg_match('/[^a-zA-Z\s]/', $_POST["lname"])) {
                $errorMsg .= "Last name cannot contain special characters or numbers.<br>";
                $success = false;
            }
            else if (preg_match('/[^a-zA-Z\s]/', $_POST["fname"])) {
                $errorMsg .= "First name cannot contain special characters or numbers.<br>";
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
                    $fname = $_POST["fname"];
                    $lname = $_POST["lname"];
                    $privilege = $_POST["privilege"];
                    
                    
                    // try{
                    saveMemberToDB();
                    // }
                    // catch (Exception $e){
                    //     $success = false;
                    //     $errorMsg = $e->getMessage();
                    // }
                    
                }
            }
            if ($success)
            {
                echo "<script>console.log('Success.....')</script>";
                echo "<h4>Update successful!</h4>";
                echo "<br>";
                echo "<a href='table.php'><button>Back to User List</button></a>";
            }
            else
            {   
                echo "<script>console.log('Failed.....')</script>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo '<a href="update_user.php?edit=' . $userid . '"><button>Return to Update</button></a>';
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
        include "../inc/footer.inc.php";
    ?>
</body>
</html>



