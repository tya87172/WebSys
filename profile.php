<!DOCTYPE html>
<html lang="en">
<script defer src="js/ryan.js"></script>

<?php
    include "inc/nav.inc.php";
    include "inc/header.inc.php";
    include "inc/head.inc.php";

    function retrieveUser(){
      global $id, $fname, $lname, $email;

      $id = $_SESSION["user_id"];

      // Create database connection.
      $config = parse_ini_file('/var/www/private/db-config.ini');
      if (!$config)
      {
          $errorMsg = "Failed to read database config file.";
          $success = false;
      }
      else
      {
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
              // Prepare the statement:
              $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");

              // Bind & execute the query statement:
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0)
              {
                  // Note that user ID field is unique, so should only have
                  // one row in the result set.
                  $row = $result->fetch_assoc();
                  $fname = $row["fname"];
                  $lname = $row["lname"];
                  $email = $row["email"];
              }
              else
              {
                  $errorMsg = "User not found";
                  $success = false;
              }
              $stmt->close();
          }

          $conn->close();
      }
    }

    retrieveUser();
?>

<div class="container light-style flex-grow-1 container-p-y">

    <h2 class="font-weight-bold py-3 mb-4">
    Profile Settings
    </h2>

    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
      <div class="card overflow-hidden">
        <div class="row">
            <div class="col-3">
                <div class="list-group list-group-flush" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="list-home">Edit Account Details</a>
                    <a class="list-group-item list-group-item-action" id="list-password-list" data-bs-toggle="list" href="#list-password" role="tab" aria-controls="list-password">Change Password</a>
                    <a class="list-group-item list-group-item-action" id="list-history-list" data-bs-toggle="list" href="#list-history" role="tab" aria-controls="list-history">Order History</a>
                    <a class="list-group-item list-group-item-action" id="list-wishlist-list" data-bs-toggle="list" href="#list-wishlist" role="tab" aria-controls="list-wishlist">Wishlist</a>
                </div>
            </div>
            <div class="col-9">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                    <div class="card-body">
                      <form action="process_profile_update.php" method="post">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input required maxlength="60" type="text" name="fname" class="form-control mb-1" value="<?php echo htmlspecialchars($fname); ?>">
                            <br>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input required maxlength="60" type="text" name="lname" class="form-control mb-1" value="<?php echo htmlspecialchars($lname); ?>"><br>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input required maxlength="60" type="email" name="email" class="form-control mb-1" value="<?php echo htmlspecialchars($email); ?>"><br>
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                      </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="list-password" role="tabpanel" aria-labelledby="list-password-list">
                    <div class="card-body">
                      <form action="process_change_password.php" method="post" id="changePasswordForm">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input required type="password" name="currentpwd" class="form-control mb-1" placeholder="Enter current password">
                            <br>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input required type="password" name="newpwd" id="newpwd" onkeyup="checkPasswordStrength()" class="form-control mb-1" placeholder="Enter new password"><br>
                            <p id="passwordStrength"></p><br>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input required type="password" name="confirmpwd" class="form-control mb-1" placeholder="Confirm password"><br>
                        </div>
                        <div class="form-group">
                          <button type="submit" id="submitButton" class="btn btn-primary">Change Password</button>
                        </div>
                      </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="list-history" role="tabpanel" aria-labelledby="list-history-list">...</div>
                <div class="tab-pane fade" id="list-wishlist" role="tabpanel" aria-labelledby="list-wishlist-list">...</div>
            </div>
        </div>
    </div>
    <?php } else { 
      echo "<h1>" . $errorMsg . "</h1><br>";
      ?>
      <h1>Please log in to view your account details. Thank you.</h1>
    <?php } ?>
    
</div>

</html>