## shop-online system for listing and bidding


### Description
The project is to develop a web-based selling and buying system called ShopOnline. ShopOnline allows sellers to list items for selling and buyers to bid for the items based on the English Auction strategy, which is a popular strategy used in many systems such as eBay, real-estate auction. Five components (registration, login, listing, bidding, and maintenance) of ShopOnline are included for this project.



### Technology used in the project
HTML, CSS, JavaScript, PHP, DOM, XML, XMLHttpRequest, and XPath/XSLT


### Brief instructions on how to use the system

	For registration and login task
For an existing customer, the email address and password are expected and checked against the XML document. If a customer is matched against the
information stored in the XML document, the customer information will be
remembered by the system for the following sessions (for either bidding or listing) before log-out. The system will be switched to the bidding page (bidding.htm) and current auction items will be shown up; otherwise, login failure message will be displayed for invalid email address or password. If customers want to log out of system, they can click “Log Out” button on the top-right corner of the screen.
For a new customer, system needs to get input of first name, surname, email address, password, and confirm password to validate. All inputs including first name, surname, email address, password, re-typed password (for double checking) must be given, and the email address is unique, and the email address is valid. The format of email addresses is local-part@domain-part, local part may contain uppercase and lowercase English letters, digits 0 to 9, special characters (!#$%&'*+-/=?^_`{|}~) and dots, and dot is not the first or last character and it does not appear consecutively. For domain part, it should include letters or digits or hyphens and hyphen is not the first or last character for each domain label. If there is problem with the above checking, the corresponding error message will be displayed; otherwise, the system will generate a customer id before sending the message. If there is no error, customer will get an alert of showing the successful registration information and system will redirect customer to bidding page (bidding.htm). At the same time, the customer will receive an email from ShopOnline confirming customer ID and password. The customer information will also be remembered by the system for the following sessions (for either bidding or listing) before log-out. If customers want to log out of system, they can click “Log Out” button on the top-right corner of the screen.

	For listing the items to be placed bid or to be bought it now task
If the customer needs to sell something, he can click Listing Button on the top left of screen, and then input item name, category, description, reserve price, buy-it-now price, start price (default 0), and duration. In particular, the start price must be no more than the reserve price and the reserve price must be less than the buy-it-now price. At least one field in the duration (day, hour, minute) must be provided to proceed. Duration should be at least 1 minute.
For category, a drop-down menu list with its item values retrieved from existing
categories in auction.xml plus an “other” item allowing the seller to list an item
belonging to new categories.
The system will validate user inputs and generate an item number and add the item listing information together with the customer id of the seller and other system generated information in auction.xml document.
If there is any problem validating user input, there will be relevant error messages displayed under the input area with red colour fonts. If there is no problem with the inputs, the system will generate an item number, start date and start time, and add them together with the customer id of the seller and
the seller’s inputs to the auction.xml document. 
Finally, system will return the generated item number, start date and start time and display the message as format of  “Thank you! Your item has been listed in ShopOnline. The item number is <itemNumber>, and the bidding starts now: <startTime> on <startDate>” under the inputted area.

	For placing bidding price and buy it now task
System will periodically (every 5 seconds) retrieve all items in the XML documents and neatly display them to the customer. For each item, the item number, name, category, description (first 30 characters), buy-it-now price,
current bid price, and time left will be displayed. 
For each displayed item, if the item has not been sold and the time left is greater than zero, two buttons: Place Bid and Buy It Now button will be displayed; otherwise, either the item has been sold or the time expired information will be shown to customer. If the item has not been sold but the time left is less than or equal to zero, “The item for bidding has been expired.” red colour message will be shown under the item area. And the time left message will disappear.
If the Place Bid button is clicked, a pop-up window will show up to take the new bid price as input, and the bid request with the new bid price, item number and bidder’s customer id will be sent for processing. If the new bid price is higher than the current bid price of the item and the item is not sold, the item in the auction.xml document with the new bid price and the new bidder’s customer id will be updated, and system will send back an acknowledgement alert “Thank you! Your bid is recorded in ShopOnline.”; otherwise, system will send back a message alert “Sorry, your bid is not valid.” to the customer. If the customer place a bid price higher than buy it now price, an alert message “Sorry, your bid is not valid. The bidding price can’t be higher than the buy it now price.” will be displayed to the customer.
If the Buy It Now button is clicked, the buy-it-now request will be sent for
processing with the item number and customer id. And the item in the
auction.xml document is changed with the current bid price by the buy-it-now price, the bidder ID by the customer id, and the status will be set to “sold”. Then an acknowledgement alert “Thank you for purchasing this item.” will be sent back to the customer. And “The item has been sold.” red colour message will be shown under the item area. The time left message will disappear.

	For system administrators to maintain the auction items and report results
There are two buttons “Process Auction Items” and “Generate Report” on the middle of the browser after user clicks Maintenance Button on top right navigation menu. 
If the “Process Auction Items” button is pressed, the system will check each item with “in_progress” status in the auction.xml document to see if it is expired by calculating the time left. If the time left is zero (or negative), then check the current bid price and reserve price to determine the status of the item (“sold” or “failed”) and change the status of the item in the auction.xml document accordingly. Once the processing is finished, a message alert will be shown to admin that the process is complete.
If the “Generate Report” button is pressed, the system will retrieve all sold or failed items and compute the revenue (assume that system charges 3% of the sold price from each sold item and charges 1% of the reserved price from each failed item) from these items. The list of the sold or failed items (all information about the item except the description) formatted as a table will be displayed. The total number of sold and failed items and the revenue will also be displayed under the displayed table. Finally, these items will be removed from the auction.xml file. 

## 🤝 Contributing
### Submit a pull request

If you'd like to contribute, please fork the repository and open a pull request to the `main` branch.
