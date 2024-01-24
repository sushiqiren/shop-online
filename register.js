// Filename: register.js
// Author: Huaxing Zhang
// ID: 102078766
// Main function: let ShopOnline new customer register to the system and remember the customer information.

// set up XMLHTTPRequest Object to be used later
let xhr = false;
if (window.XMLHttpRequest)
{ 
    xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{ 
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
// register function to be used when user clicks register button
function register() {    
    // set up variants about the values user entered
    const firstName = document.getElementById("firstname").value;
    const surname = document.getElementById("surname").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirmpassword = document.getElementById("confirmpassword").value;    
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xhr.open("GET", "register.php?firstname=" + encodeURIComponent(firstName) + "&surname=" + encodeURIComponent(surname) + "&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password) + "&confirmpassword=" + encodeURIComponent(confirmpassword) + "&id=" + Number(new Date), true);
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {            
            // get the XMLHTTPRequest object responseText and return it to JSON format            
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // alert the user about successful regitration message
                alert("Congratulations! You have successfully registered the ShopOnline account.");
                // Redirect to the bidding page                
                window.location.href = "bidding.htm";
            } else {
                // Handle register error
                const response = JSON.parse(xhr.responseText);
                document.getElementById("registered").innerHTML = response.error;
            }
        } 
    }
    xhr.send(null);    
}
// clearFields function to be used when user clicks reset button
function clearFields() {
    // clear fields in the table
	document.getElementById("firstname").value = "";
	document.getElementById("surname").value = "";
	document.getElementById('email').value="";
	document.getElementById('password').value="";
	document.getElementById('confirmpassword').value = "";
	document.getElementById('registered').innerHTML = "";
}
