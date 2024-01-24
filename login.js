// Filename: login.js
// Author: Huaxing Zhang
// ID: 102078766
// Main function: let ShopOnline user login to the system by entering email and password.

// set up XMLHTTPRequest Object to be used later
let xHRObject = false;
if (window.XMLHttpRequest)
{ 
    xHRObject = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{ 
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}

// login function to be used when user clicks the login button
function login() {
    // get the email and password value by DOM operation
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;    
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xHRObject.open("POST", "login.php", true);
    // set XMLHTTPResquest Header
    xHRObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xHRObject.onreadystatechange = function () {
        if ((xHRObject.readyState === 4) && (xHRObject.status === 200)) {
            // get the XMLHTTPRequest object responseText and return it to JSON format            
            const response = JSON.parse(xHRObject.responseText);
            // make conditional judgement for login success or failure
            if (response.success) {
                // Redirect to the bidding page                
                window.location.href = "bidding.htm";
            } else {
                // Handle login failure
                const response = JSON.parse(xHRObject.responseText);
                document.getElementById("errorMsg").innerHTML = response.error;
            }
        }
    }   
    // send data to the server as part of a POST request using the XMLHttpRequest object
    xHRObject.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
}

// clearFields function to be used when user clicks reset button
function clearFields() {
    // clear fields in the table
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";    
    document.getElementById("errorMsg").innerHTML = "";
}