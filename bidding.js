// Filename: bidding.js
// Author: Huaxing Zhang
// ID: 102078766
// Main function: ShopOnline bidding function JavaScript code 

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
// showItem function to fetch data from XMLHTTPReqest object and display information to client
function showItem() {
    // create and configure an XMLHttpRequest object for making HTTP requests to the server
    xhr.open("GET", "getData.php?id=" + Number(new Date), true);
    // set an event handler for the onreadystatechange event of the XMLHttpRequest object
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const xmlContent = xhr.responseXML;
            if (xmlContent !== null) {
                const item = xmlContent.getElementsByTagName("item");
                const itemShown = document.getElementById("itemDisplay");
                itemShown.innerHTML = "";
                // loop through items in auction.xml file to show every item in the list
                for (let i = 0; i < item.length; i++) {
                    const itemID = item[i].getElementsByTagName("itemID")[0].textContent;
                    const itemName = item[i].getElementsByTagName("itemName")[0].textContent;
                    const category = item[i].getElementsByTagName("category")[0].textContent;
                    const rawDescription = item[i].getElementsByTagName("description")[0].textContent;
                    let description = "";
                    if (rawDescription.length > 0 && rawDescription.length <= 30) {
                        description = rawDescription;
                    } else {
                        description = rawDescription.slice(0, 30) + " ....";
                    }                    
                    const buyNowPrice = item[i].getElementsByTagName("buyItNowPrice")[0].textContent;
                    const bidPrice = item[i].getElementsByTagName("bidPrice")[0].textContent;
                    const duration = item[i].getElementsByTagName("duration")[0].textContent;
                    const status = item[i].getElementsByTagName("status")[0].textContent;                                   
                    // create relevant html elements to neatly display the item
                    // create fieldset element
                    const fieldset = document.createElement("fieldset");
                    // create br element to have a new break
                    const br = document.createElement("br");
                    // create div and span elements for displaying auction items
                    const idDiv = document.createElement("div");
                    idDiv.className = "form";                    
                    const idName = document.createElement("span");
                    idName.textContent = "Item No:";
                    idDiv.appendChild(idName);
                    const idValue = document.createElement("span");
                    idValue.textContent = itemID;
                    idDiv.appendChild(idValue);
                    fieldset.appendChild(idDiv);                    

                    const nameDiv = document.createElement("div");
                    nameDiv.className = "form";
                    const name = document.createElement("span");
                    name.textContent = "Item Name:";
                    nameDiv.appendChild(name);
                    const nameValue = document.createElement("span");
                    nameValue.textContent = itemName;
                    nameDiv.appendChild(nameValue);
                    fieldset.appendChild(nameDiv);

                    const categoryDiv = document.createElement("div");
                    categoryDiv.className = "form";
                    const categoryName = document.createElement("span");
                    categoryName.textContent = "Category:";
                    categoryDiv.appendChild(categoryName);
                    const categoryValue = document.createElement("span");
                    categoryValue.textContent = category;
                    categoryDiv.appendChild(categoryValue);
                    fieldset.appendChild(categoryDiv);

                    const descriptionDiv = document.createElement("div");
                    descriptionDiv.className = "form";
                    const descriptionName = document.createElement("span");
                    descriptionName.textContent = "Description:";
                    descriptionDiv.appendChild(descriptionName);
                    const descriptionValue = document.createElement("span");
                    descriptionValue.textContent = description;
                    descriptionDiv.appendChild(descriptionValue);
                    fieldset.appendChild(descriptionDiv);

                    const buyNowPriceDiv = document.createElement("div");
                    buyNowPriceDiv.className = "form";
                    const buyNowPriceName = document.createElement("span");
                    buyNowPriceName.textContent = "Buy It Now Price:";
                    buyNowPriceDiv.appendChild(buyNowPriceName);
                    const buyNowPriceValue = document.createElement("span");
                    buyNowPriceValue.textContent = buyNowPrice;
                    buyNowPriceDiv.appendChild(buyNowPriceValue);
                    fieldset.appendChild(buyNowPriceDiv);

                    const bidPriceDiv = document.createElement("div");
                    bidPriceDiv.className = "form";
                    const bidPriceName = document.createElement("span");
                    bidPriceName.textContent = "Bid Price:";
                    bidPriceDiv.appendChild(bidPriceName);
                    const bidPriceValue = document.createElement("span");
                    bidPriceValue.textContent = bidPrice;
                    bidPriceDiv.appendChild(bidPriceValue);                    
                    fieldset.appendChild(bidPriceDiv);

                    fieldset.appendChild(br);
                    // calculate time difference between duration and the current time
                    let timeDiff = new Date(duration) - new Date();
                    let timeLeft = formatTimeDifference(timeDiff);
                    
                    if (status !== "sold" && timeDiff > 0) {
                        // create div and span elements for time left
                        const timeLeftDiv = document.createElement("div");
                        timeLeftDiv.className = "form";                    
                        const timeLeftName = document.createElement("span");
                        timeLeftName.textContent = "";
                        timeLeftDiv.appendChild(timeLeftName);
                        const timeLeftValue = document.createElement("span");
                        const timeLeftEm = document.createElement("em");
                        timeLeftEm.textContent = timeLeft;
                        timeLeftValue.appendChild(timeLeftEm);
                        timeLeftDiv.appendChild(timeLeftValue);
                        fieldset.appendChild(timeLeftDiv);
                        // create bid button elements for item
                        const buttonDiv = document.createElement("div");
                        buttonDiv.className = "form";
                        const buttonDivName = document.createElement("span");
                        buttonDivName.textContent = "";
                        buttonDiv.appendChild(buttonDivName);
                        const buttonDivValue = document.createElement("span");
                        const placeBidButton = document.createElement("button");
                        placeBidButton.id = "placeBid";
                        placeBidButton.textContent = "Place Bid";
                        placeBidButton.style.backgroundColor = "orange";
                        placeBidButton.style.width = "100px";
                        placeBidButton.style.height = "25px";

                        // Create a space element along with CSS
                        const space = document.createElement("span");
                        space.style.marginRight = "10px";
                        // create buy it now button element for item                                            
                        const buyItNowButton = document.createElement("button");
                        buyItNowButton.id = "buyNow";
                        buyItNowButton.textContent = "Buy It Now";
                        buyItNowButton.style.backgroundColor = "green";
                        buyItNowButton.style.width = "100px";
                        buyItNowButton.style.height = "25px";

                        buttonDivValue.appendChild(placeBidButton);
                        buttonDivValue.appendChild(space);
                        buttonDivValue.appendChild(buyItNowButton);
                        buttonDiv.appendChild(buttonDivValue);

                        fieldset.appendChild(buttonDiv);
                        // Handle place bid button clicking
                        placeBidButton.onclick = function () {
                            const biddingModal = document.getElementById("biddingModal");
                            const closeModal = document.querySelector(".close");
                            const submitBidButton = document.getElementById("submit");
                            const bidInput = document.getElementById("bidPrice");
                        
                            // Open the modal when the "Place Bid" button is clicked    
                            biddingModal.style.display = "block";    
                        
                            // Close the modal when the close button (X) is clicked
                            closeModal.addEventListener("click", () => {
                                biddingModal.style.display = "none";
                            });
                            
                            // Handle bid submission
                            submitBidButton.addEventListener("click", () => {
                                const newBidPrice = bidInput.value;
                                xhr.open("GET", "bidding.php?itemID=" + encodeURIComponent(itemID) + "&bidPrice=" + encodeURIComponent(newBidPrice) + "&id=" + Number(new Date), true);
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) { 
                                        // return back the response message alert when user clicks place bid button
                                        alert(xhr.responseText);                                                                            
                                    }
                                }
                                xhr.send(null);
                                // Close the modal
                                biddingModal.style.display = "none";                                
                            });

                            // Add an event listener to the input field for the Enter key
                            bidInput.addEventListener("keyup", function (event) {
                                if (event.key === "Enter") {
                                    // Simulate a click on the "Confirm" button when Enter is pressed
                                    submitBidButton.click();
                                }
                            });
                                                       
                        }                        

                        // Handle buy it now submission
                        buyItNowButton.onclick = function () {                            
                            xhr.open("GET", "buyNow.php?itemID=" + encodeURIComponent(itemID) + "&buyNowPrice=" + encodeURIComponent(buyNowPrice) + "&id=" + Number(new Date), true);
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // return back the response message alert when user clicks buy it now button
                                    alert(xhr.responseText);                                 
                                }
                            }
                            xhr.send(null);
                            // not displaying the time left when the item is sold
                            timeLeftDiv.style.display = "none";
                        }

                    } else if (status === "sold") {
                        // handle the sold status displaying
                        const soldStatusShow = document.createElement("div");
                        const soldStatus = document.createElement("p");
                        soldStatus.className = "info";
                        soldStatus.textContent = "The item has been sold.";
                        soldStatusShow.appendChild(soldStatus);
                        fieldset.appendChild(soldStatusShow);
                    } else if (timeDiff <= 0) {
                        // handle the expired status displaying
                        const timeShow = document.createElement("div");
                        const time = document.createElement("p");
                        time.className = "info";
                        time.textContent = "The time for item bidding is expired.";
                        timeShow.appendChild(time);
                        fieldset.appendChild(timeShow);
                    }                    

                    itemShown.appendChild(fieldset);
                    itemShown.appendChild(br);
                }
            }
        }
    }
    xhr.send(null);
}
// function for formatting the time left
function formatTimeDifference(timeDiff) {
    // calculating the seconds, minutes, hours, and days parameters from timeDiff input
    const seconds = Math.floor((timeDiff / 1000) % 60);
    const minutes = Math.floor((timeDiff / 1000 / 60) % 60);
    const hours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));   
    // set up time left format
    let result = days + ' days ' + hours + ' hours ' + minutes + ' minutes and ' + seconds + ' seconds remaining.';
    // Trim any extra whitespace
    return result.trim(); 
}
// set up onload event handler to diplay the items every five seconds
window.onload = function() {    
    showItem();
    setInterval(showItem, 5000);
}