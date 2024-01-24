// Filename: maintenance.js
// Author: Huaxing Zhang
// ID: 102078766
// Main function: let ShopOnline administrator to maintain the system by clicking two buttons and fetch the XMLHTTPResquest response.

// set up XMLHTTPRequest Object
let xhr = false;
if (window.XMLHttpRequest)
{ 
    xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{ 
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
// processItems function to inform the admin about processing auction items
function processItems() {
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xhr.open("GET", "processItems.php?id=" + Number(new Date), true);
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // alert the admin about processing response
            alert(xhr.responseText);
        }
    }
    xhr.send(null);
}
// report function to display the table report and calculation result
function report() {
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xhr.open("GET", "report.php?id=" + Number(new Date), true);
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // display the table and calculation result about reporting response
            document.getElementById("report").innerHTML = xhr.responseText;
        }
    }
    xhr.send(null);
}