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
            
            $id = $fname = $lname = $email = "";  // declaring global variables
            $success = true;

            function updateProfile(){
                // saying the variables used in this function are referenced from the global variable outside the scope
                global $id, $fname, $lname, $email; 

                $id = $_SESSION["user_id"];

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
                        $stmt = $conn->prepare("UPDATE users SET fname =?, lname=?, email=? WHERE user_id=?");
                        $stmt->bind_param("sssi", $fname, $lname, $email, $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if (!$stmt->execute())
                        {
                            // throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                            $success = false;
                        }
            
                        $stmt->close();
                    }
                    echo "<script>console.log('closing connection...')</script>";
                    $conn->close();
                }
            }

            if (empty($_POST["fname"]))
            {
                $errorMsg .= "First Name is required.<br>";
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
                    
                    
                    // try{
                    updateProfile();
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
                echo "<h4>Update profile successful!</h4>";
                echo "<br>";
                echo "<a href='profile.php'><button>Back to Profile Settings</button></a>";
            }
            else
            {   
                echo "<script>console.log('Failed.....')</script>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo "<a href='profile.php'><button>Back to Profile Settings</button></a>";
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



