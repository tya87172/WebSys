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
            
            $id = $currentpwd = $currentpwd_hashed = $newpwd = $confirmpwd = $errorMsg = "";  // declaring global variables
            $success = true;

            function changePassword(){
                // saying the variables used in this function are referenced from the global variable outside the scope
                global $id, $currentpwd, $currentpwd_hashed, $newpwd, $confirmpwd, $success, $errorMsg; 

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
                        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0)
                        {
                            // Note that user ID field is unique, so should only have
                            // one row in the result set.
                            $row = $result->fetch_assoc();
                            $currentpwd_hashed = $row["password"];

                            // Check if the password matches:
                            if (!password_verify($_POST["currentpwd"], $currentpwd_hashed))
                            {
                                $errorMsg .= "Wrong current password entered!";
                                $success = false;
                            }
                            else
                            {
                                $stmt = $conn->prepare("UPDATE users SET password =? WHERE user_id=?");
                                $stmt->bind_param("si", $newpwd, $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            }
                        }
                        
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

            function checkPasswordStrength($password){
                global $success, $errorMsg;

                $strength = 0;

                if(strlen($password) < 12){
                    $errorMsg .= "Your password should have minimum 12 characters.<br>";
                    console.log('Your password should have minimum 12 characters.');
                    $success = false;
                }
                else{
                    $strength += 1;
                }

                if(preg_match("/[a-z]/", $password) && preg_match("/[A-Z]/", $password)){
                    $strength += 1;
                }
                else{
                    $errorMsg .= "Your password should have both lowercase and uppercase letters.<br>";
                    console.log('Your password should have both lowercase and uppercase letters.');
                    $success = false;
                }

                if (preg_match("/\d/", $password)) {
                    $strength += 1;
                }
                else{
                    $errorMsg .= "Your password should have at least one number.<br>";
                    console.log('Your password should have at least one number.');
                    $success = false;
                }

                if (preg_match("/[^a-zA-Z\d]/", $password)) {
                    $strength += 1;
                }
                else{
                    $errorMsg .= "Your password should have at least one special character.<br>";
                    $success = false;
                    console.log('Your password should have at least one special character.');
                }

                if ($strength < 2) {
                    $errorMsg .= "Password strength : <b>Weak</b> <br>";
                    $success = false;
                } else if ($strength === 2) {
                    $errorMsg .= "Password strength : <b>Medium</b> <br>";
                    $success = false;
                } else if ($strength === 3) {
                    $errorMsg .= "Password strength : <b>Good but not strong enough</b> <br>";
                    $success = false;
                }
            }

            if (empty($_POST["currentpwd"]))
            {
                $errorMsg .= "Current Password is required.<br>";
                $success = false;
            }
            else if (empty($_POST["newpwd"])){
                $errorMsg .= "New Password is required.<br>";
                $success = false;
            }
            else if (empty($_POST["confirmpwd"])){
                $errorMsg .= "Confirm Password is required.<br>";
                $success = false;
            }
            else if ($_POST['confirmpwd'] != $_POST['newpwd']){
                $errorMsg .= "Confirm password must be same as new password!<br>";
                $success = false;
            }
            else
            {
                checkPasswordStrength($_POST["newpwd"]);

                $currentpwd = password_hash($_POST["currentpwd"], PASSWORD_DEFAULT);
                $newpwd = password_hash($_POST["newpwd"], PASSWORD_DEFAULT);
                $confirmpwd = $_POST["confirmpwd"];

                changePassword();
            }

            if ($success)
            {
                echo "<script>console.log('Success.....')</script>";
                echo "<h4>Change Password successful!</h4>";
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
            
        ?>
    </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>