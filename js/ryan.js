document.addEventListener("DOMContentLoaded", function ()
{
// Code to be executed when the DOM is ready (i.e. the document is
// fully loaded):
checkPasswordStrength();
});

function checkPasswordStrength() {
    // Initialize variables
    var strength = 0;
    var tips = "";
    var newpwd = document.getElementById('newpwd').value;
  
    // Check password length
    if (newpwd.length < 12) {
      tips += "* Make the password longer. Minimum 12 characters. <br>";
    } else {
      strength += 1;
    }
  
    // Check for mixed case
    if (newpwd.match(/[a-z]/) && newpwd.match(/[A-Z]/)) {
      strength += 1;
    } else {
      tips += "* Use both lowercase and uppercase letters. <br>";
    }
  
    // Check for numbers
    if (newpwd.match(/\d/)) {
      strength += 1;
    } else {
      tips += "* Include at least one number. <br>";
    }
  
    // Check for special characters
    if (newpwd.match(/[^a-zA-Z\d]/)) {
      strength += 1;
    } else {
      tips += "* Include at least one special character. <br>";
    }
  
    // Return results
    // Get the paragraph element
    var strengthElement = document.getElementById("passwordStrength");
    var submitButton = document.getElementById("submitButton");

    // Return results
    if (strength < 2) {
      strengthElement.innerHTML = "Weak password. Your password should meet the following requirements : <br><br>" + tips;
      strengthElement.style.color = "red";
      submitButton.disabled = true;
      console.log('submit button disabled!');
    } else if (strength === 2) {
      strengthElement.innerHTML = "Medium password. Your password should meet the following requirements : <br><br>" + tips;
      strengthElement.style.color = "blue";
      submitButton.disabled = true;
      console.log('submit button disabled!');
    } else if (strength === 3) {
      strengthElement.innerHTML = "Strong password. Your password should meet the following requirements : <br><br>" + tips;
      strengthElement.style.color = "orange";
      submitButton.disabled = true;
      console.log('submit button disabled!');
    } else {
      strengthElement.innerHTML = "Very Strong Password! <br>" + tips;
      strengthElement.style.color = "green";
      submitButton.disabled = false;
      console.log('submit button enabled!');
    }
}