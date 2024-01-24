// Filename: listing.js
// Author: Huaxing Zhang
// ID: 102078766
// Main function: ShopOnline listing items for auction or buying function

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
// list function for fetching data from response of XMLHTTPReqest object
function list() {
    // set up variants about the values user entered
    const itemName= document.getElementById("name").value;
    const category = document.getElementById("category").value;
    const description = document.getElementById("description").value;
    const startprice = document.getElementById("startprice").value;
    const reserveprice = document.getElementById("reserveprice").value;
    const buyprice = document.getElementById("buyprice").value;
    const day = document.getElementById("day").value;
    const hour = document.getElementById("hour").value;
    const minute = document.getElementById("minute").value;
    const newCategory = document.getElementById("newCategory").value;
    // create and configure an XMLHttpRequest object for making HTTP requests to the server by conditional check for category input
    if (category === "Other") {
        xhr.open("GET", "listing.php?itemname=" + encodeURIComponent(itemName) + "&category=" + encodeURIComponent(newCategory) + "&description=" + encodeURIComponent(description) + "&startprice=" + encodeURIComponent(startprice) + "&reserveprice=" + encodeURIComponent(reserveprice) + "&buyprice=" + encodeURIComponent(buyprice) + "&day=" + encodeURIComponent(day) + "&hour=" + encodeURIComponent(hour) + "&minute=" + encodeURIComponent(minute) + "&id=" + Number(new Date), true);
    } else {
        xhr.open("GET", "listing.php?itemname=" + encodeURIComponent(itemName) + "&category=" + encodeURIComponent(category) + "&description=" + encodeURIComponent(description) + "&startprice=" + encodeURIComponent(startprice) + "&reserveprice=" + encodeURIComponent(reserveprice) + "&buyprice=" + encodeURIComponent(buyprice) + "&day=" + encodeURIComponent(day) + "&hour=" + encodeURIComponent(hour) + "&minute=" + encodeURIComponent(minute) + "&id=" + Number(new Date), true);
    }    
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // display the response message to the user
            document.getElementById("listed").innerHTML = xhr.responseText;            
        }
    }
    xhr.send(null);
}
// getCategories function for the new category option to get input from user
function getCategories() {
    // get category element object
    const categoryDropdown = document.getElementById("category");
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xhr.open("GET", "listing.php?getCategories=true" + "&id=" + Number(new Date), true);
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // create option element for category dropdown menu
            const defaultOption = document.createElement("option");
            defaultOption.value = "select";
            defaultOption.textContent = "Please select";
            defaultOption.selected = "selected";
            categoryDropdown.appendChild(defaultOption);
            // set up the json variable of XMLHttpRequest object response
            const categories = JSON.parse(xhr.responseText);
            // Populate the category dropdown with categories            
            categories.forEach((category) => {
                const option = document.createElement("option");
                option.value = category;
                option.textContent = category;
                categoryDropdown.appendChild(option);
            });
            // create other option element for category dropdown menu
            const lastOption = document.createElement("option");
            lastOption.value = "Other";
            lastOption.textContent = "Other";
            categoryDropdown.appendChild(lastOption);
        }
    };
    xhr.send(null);
}
// setNewCategory function for handling new category displaying
function setNewCategory() {
    const newCategoryInput = document.getElementById("newCategoryInput");
    const categoryDropdown = document.getElementById("category");

    if (categoryDropdown.value === "Other") {        
        newCategoryInput.style.display = "grid";
        newCategoryInput.style.gridTemplateColumns = "180px 300px";
    } else {        
        newCategoryInput.style.display = "none";
    }
}

// clearListing function for clearing the input fields and error message area
function clearListing() {
    // clear fields in the table
	document.getElementById("name").value = "";
	document.getElementById("category").value = "select";
    document.getElementById("newCategoryInput").style.display = "none";
	document.getElementById('description').value= "";
	document.getElementById('startprice').value= "0.00";
	document.getElementById('reserveprice').value = "";
    document.getElementById('buyprice').value = "";
    document.getElementById("day").value = ""; 
    document.getElementById("hour").value = ""; 
    document.getElementById("minute").value = ""; 
    document.getElementById("day").placeholder = "Day";
    document.getElementById("hour").placeholder = "Hour";
    document.getElementById("minute").placeholder = "Min";
	document.getElementById('listed').innerHTML = "";
}

// Get categories updated when the page loads
window.onload = function () {
    getCategories();
};