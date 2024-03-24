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

<div class="card overflow-hidden">
  <div class="row no-gutters row-bordered row-border-light">
    <div class="col-md-3 pt-0">
      <div class="list-group list-group-flush account-settings-links">
        <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#account-general">General</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#account-change-password">Change password</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#account-info">Info</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#account-social-links">Social links</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#account-connections">Connections</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#account-notifications">Notifications</a>
      </div>
    </div>
    <div class="col-md-9">
      <div class="tab-content">
        <div class="tab-pane fade active show" id="account-general">
          <div class="card-body media align-items-center">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="d-block ui-w-80">
            <div class="media-body ml-4">
              <label class="btn btn-outline-primary">
                Upload new photo
                <input type="file" class="account-settings-fileinput">
              </label> &nbsp;
              <button type="button" class="btn btn-default md-btn-flat">Reset</button>

              <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
            </div>
          </div>
          <hr class="border-light m-0">

          <div class="card-body">
            <div class="form-group">
              <label class="form-label">Username</label>
              <input type="text" class="form-control mb-1" value="nmaxwell">
            </div>
            <div class="form-group">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" value="Nelle Maxwell">
            </div>
            <div class="form-group">
              <label class="form-label">E-mail</label>
              <input type="text" class="form-control mb-1" value="nmaxwell@mail.com">
              <div class="alert alert-warning mt-3">
                Your email is not confirmed. Please check your inbox.<br>
                <a href="javascript:void(0)">Resend confirmation</a>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Company</label>
              <input type="text" class="form-control" value="Company Ltd.">
            </div>
          </div>

        </div>
        <div class="tab-pane fade" id="account-change-password">
          <div class="card-body pb-2">

            <div class="form-group">
              <label class="form-label">Current password</label>
              <input type="password" class="form-control">
            </div>

            <div class="form-group">
              <label class="form-label">New password</label>
              <input type="password" class="form-control">
            </div>

            <div class="form-group">
              <label class="form-label">Repeat new password</label>
              <input type="password" class="form-control">
            </div>

          </div>
        </div>
        <div class="tab-pane fade" id="account-info">
          <div class="card-body pb-2">

            <div class="form-group">
              <label class="form-label">Bio</label>
              <textarea class="form-control" rows="5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nunc arcu, dignissim sit amet sollicitudin iaculis, vehicula id urna. Sed luctus urna nunc. Donec fermentum, magna sit amet rutrum pretium, turpis dolor molestie diam, ut lacinia diam risus eleifend sapien. Curabitur ac nibh nulla. Maecenas nec augue placerat, viverra tellus non, pulvinar risus.</textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Birthday</label>
              <input type="text" class="form-control" value="May 3, 1995">
            </div>
            <div class="form-group">
              <label class="form-label">Country</label>
              <select class="custom-select">
                <option>USA</option>
                <option selected="">Canada</option>
                <option>UK</option>
                <option>Germany</option>
                <option>France</option>
              </select>
            </div>


          </div>
          <hr class="border-light m-0">
          <div class="card-body pb-2">

            <h6 class="mb-4">Contacts</h6>
            <div class="form-group">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" value="+0 (123) 456 7891">
            </div>
            <div class="form-group">
              <label class="form-label">Website</label>
              <input type="text" class="form-control" value="">
            </div>

          </div>
  
        </div>
        <div class="tab-pane fade" id="account-social-links">
          <div class="card-body pb-2">

            <div class="form-group">
              <label class="form-label">Twitter</label>
              <input type="text" class="form-control" value="https://twitter.com/user">
            </div>
            <div class="form-group">
              <label class="form-label">Facebook</label>
              <input type="text" class="form-control" value="https://www.facebook.com/user">
            </div>
            <div class="form-group">
              <label class="form-label">Google+</label>
              <input type="text" class="form-control" value="">
            </div>
            <div class="form-group">
              <label class="form-label">LinkedIn</label>
              <input type="text" class="form-control" value="">
            </div>
            <div class="form-group">
              <label class="form-label">Instagram</label>
              <input type="text" class="form-control" value="https://www.instagram.com/user">
            </div>

          </div>
        </div>
        <div class="tab-pane fade" id="account-connections">
          <div class="card-body">
            <button type="button" class="btn btn-twitter">Connect to <strong>Twitter</strong></button>
          </div>
          <hr class="border-light m-0">
          <div class="card-body">
            <h5 class="mb-2">
              <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i class="ion ion-md-close"></i> Remove</a>
              <i class="ion ion-logo-google text-google"></i>
              You are connected to Google:
            </h5>
            nmaxwell@mail.com
          </div>
          <hr class="border-light m-0">
          <div class="card-body">
            <button type="button" class="btn btn-facebook">Connect to <strong>Facebook</strong></button>
          </div>
          <hr class="border-light m-0">
          <div class="card-body">
            <button type="button" class="btn btn-instagram">Connect to <strong>Instagram</strong></button>
          </div>
        </div>
        <div class="tab-pane fade" id="account-notifications">
          <div class="card-body pb-2">

            <h6 class="mb-4">Activity</h6>

            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input" checked="">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Email me when someone comments on my article</span>
              </label>
            </div>
            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input" checked="">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Email me when someone answers on my forum thread</span>
              </label>
            </div>
            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Email me when someone follows me</span>
              </label>
            </div>
          </div>
          <hr class="border-light m-0">
          <div class="card-body pb-2">

            <h6 class="mb-4">Application</h6>

            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input" checked="">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">News and announcements</span>
              </label>
            </div>
            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Weekly product updates</span>
              </label>
            </div>
            <div class="form-group">
              <label class="switcher">
                <input type="checkbox" class="switcher-input" checked="">
                <span class="switcher-indicator">
                  <span class="switcher-yes"></span>
                  <span class="switcher-no"></span>
                </span>
                <span class="switcher-label">Weekly blog digest</span>
              </label>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="text-right mt-3">
  <button type="button" class="btn btn-primary">Save changes</button>&nbsp;
  <button type="button" class="btn btn-default">Cancel</button>
</div>

</div>
</html>
