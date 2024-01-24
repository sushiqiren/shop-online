<?php
// Filename: listing.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to listing items to be sold or bidding from server side.

// set up session
session_start();
// set up session of bidder ID to trace bidder information
$bidderID = "";
if (isset($_SESSION["bidderID"])) {    
    $bidderID = $_SESSION["bidderID"];
}
// set up session of login id to distinguish the id is from login page
$loginid = "";
if (isset($_SESSION["loginid"])) {
    $loginid = $_SESSION["loginid"];
    $_SESSION["bidderID"] = $_SESSION["loginid"];
}
// set up session of login status to make sure the right status passing
$loginStatus = false;
if (isset($_SESSION["loginStatus"])) {
    $loginStatus = $_SESSION["loginStatus"];
}
// set up session of register id to distinguish the id is from register page
$registerid = "";
if (isset($_SESSION["registerid"])) {
    $registerid = $_SESSION["registerid"];
    $_SESSION["bidderID"] = $_SESSION["registerid"];
}
// set up session of register status to make sure the right status passing 
$registerStatus = false;
if (isset($_SESSION["registerStatus"])) {
    $registerStatus = $_SESSION["registerStatus"];
}

// Set up initial error state and message
$check = true;
$errMsg = "";
// Conditional check to see if all XMLHTTPRequest parameters are set 
if (isset($_GET['itemname']) && isset($_GET['category']) && isset($_GET['description']) && isset($_GET['startprice']) && 
isset($_GET['reserveprice']) && isset($_GET['buyprice']) && isset($_GET['day']) && isset($_GET['hour']) && isset($_GET['minute'])) {
    // set up variant value of XMLHTTPRequest parameters and use trim() method to remove whitespace from the beginning and end of user text input
    $itemname = trim($_GET['itemname']);
    $category = trim($_GET['category']);
    $description = trim($_GET['description']);
    $startprice = $_GET['startprice'];
    $reserveprice = $_GET['reserveprice'];
    $buyprice = $_GET['buyprice'];
    $day = $_GET['day'];
    $hour = $_GET['hour'];
    $minute = $_GET['minute'];
    // set up initial status for auction
    $initialStatus = "in_progress";
    // set up start date and start time for auction
    date_default_timezone_set('Australia/Melbourne');
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');
    // constructDuration function to format the correct duration style to display
    function constructDuration($days, $hours, $minutes)
    {
        // set up the correct duration string format
        $durationToAdd = "P" . $days . "D" . "T" . $hours ."H" . $minutes . "M";
        // get the new DateTime object instance format
        $result = new DateTime(date('Y-m-d H:i:s'));
        // add the new DateInterval object instance to the above instance
        $result->add(new DateInterval($durationToAdd));
        //date->format will return string object
        $result = $result->format('Y-m-d H:i:s');
        return $result;
    }    
    // user input validation and error message set up
    if (empty($itemname)) {
        $errMsg .= "<p class='error'>Item Name must be provided to proceed.</p>";        
        $check = false;
    }
    if ($category === 'select') {
        $errMsg .= "<p class='error'>Category must be selected to proceed.</p>";        
        $check = false;
    }
    if (empty($description)) {
        $errMsg .= "<p class='error'>Description must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($startprice)) {
        $errMsg .= "<p class='error'>Start price must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($reserveprice)) {
        $errMsg .= "<p class='error'>Reserve Price must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($buyprice)) {
        $errMsg .= "<p class='error'>Buy It Now Price must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($day)) {
        $day = 0;
    }
    if (empty($hour)) {
        $hour = 0;
    }
    if (empty($minute)) {
        $minute = 0;
    }
    if ($hour > 23) {
        $errMsg .= "<p class='error'>Hour value should not be greater than 23.</p>";
        $check = false;
    }
    if ($minute > 59) {
        $errMsg .= "<p class='error'>Minute value should not be greater than 59.</p>";
        $check = false;
    }    
    // handle the all empty duration input condition
    if (empty($day) && empty($hour) && empty($minute)) {
        $day = 0;
        $hour = 0;
        $minute = 0;
        $errMsg .= "<p class='error'>At least one field in the duration (day, hour, minute) must be provided to proceed.
                    Duration should be at least 1 minute.</p>";
        $check = false;
    }
    // set up duration format
    $duration = constructDuration($day, $hour, $minute);
    // validate start price and reserve price
    if ($startprice > $reserveprice) {
        $errMsg .= "<p class='error'>Start price must be no more than the reserve price.</p>";
        $check = false;
    }
    // validate reserver price and buy it now price
    if ($reserveprice >= $buyprice) {
        $errMsg .= "<p class='error'>Reserve price must be less than the buy-it-now price.</p>";
        $check = false;
    }
    // if there is any error message, return back the response to client
    if ($errMsg != "") {
        echo $errMsg;
    }

    // Set up the xml file path
    $xmlfile = '../../data/auction.xml';
    // create new DomDocument instances
    $doc = new DomDocument('1.0');
    // if there is not any error, handle the listing action
    if ($check) {
        // if auction.xml file does not exist, create a new one
        if (!file_exists($xmlfile)){ 
            // if the xml file does not exist, create a root node $items
            $items = $doc->createElement('items');
            $doc->appendChild($items);

            //create an item node under items node
            $items = $doc->getElementsByTagName('items')->item(0);
            $item = $doc->createElement('item');
            $items->appendChild($item);

            if (!empty($loginid) && $loginStatus) {
                // create customer id node
                $customeridNode = $doc->createElement('customerID');
                $item->appendChild($customeridNode);
                $customeridValue = $doc->createTextNode($loginid);
                $customeridNode->appendChild($customeridValue);
            } elseif (!empty($registerid) && $registerStatus) {
                // create customer id node
                $customeridNode = $doc->createElement('customerID');
                $item->appendChild($customeridNode);
                $customeridValue = $doc->createTextNode($registerid);
                $customeridNode->appendChild($customeridValue);
            }            

            // create item id node start with 100
            $idNode = $doc->createElement('itemID');
            $item->appendChild($idNode);
            $idValue = $doc->createTextNode(100);
            $idNode->appendChild($idValue);
            
            // create first name node
            $itemName = $doc->createElement('itemName');
            $item->appendChild($itemName);
            $itemnameValue = $doc->createTextNode($itemname);
            $itemName->appendChild($itemnameValue);
            
            // create category node
            $categoryNode = $doc->createElement('category');
            $item->appendChild($categoryNode);
            $categoryValue = $doc->createTextNode($category);
            $categoryNode->appendChild($categoryValue);

            //create description node
            $descriptionNode = $doc->createElement('description');
            $item->appendChild($descriptionNode);
            $descriptionValue = $doc->createTextNode($description);
            $descriptionNode->appendChild($descriptionValue);
            
            //create start price node
            $startPriceNode = $doc->createElement('startingPrice');
            $item->appendChild($startPriceNode);
            $startPriceValue = $doc->createTextNode($startprice);
            $startPriceNode->appendChild($startPriceValue);

            //create reserve price node
            $reservePriceNode = $doc->createElement('reservePrice');
            $item->appendChild($reservePriceNode);
            $reservePriceValue = $doc->createTextNode($reserveprice);
            $reservePriceNode->appendChild($reservePriceValue);

            //create buy it now price node
            $buyPriceNode = $doc->createElement('buyItNowPrice');
            $item->appendChild($buyPriceNode);
            $buyPriceValue = $doc->createTextNode($buyprice);
            $buyPriceNode->appendChild($buyPriceValue);

            //create bid price node
            $bidPriceNode = $doc->createElement('bidPrice');
            $item->appendChild($bidPriceNode);
            $bidPriceValue = $doc->createTextNode($startprice);
            $bidPriceNode->appendChild($bidPriceValue);

            //create duration node
            $durationNode = $doc->createElement('duration');
            $item->appendChild($durationNode);
            $durationValue = $doc->createTextNode($duration);
            $durationNode->appendChild($durationValue);

            //create status node
            $statusNode = $doc->createElement('status');
            $item->appendChild($statusNode);
            $statusValue = $doc->createTextNode($initialStatus);
            $statusNode->appendChild($statusValue);

            //create current date node
            $currentDateNode = $doc->createElement('currentDate');
            $item->appendChild($currentDateNode);
            $currentDateValue = $doc->createTextNode($currentDate);
            $currentDateNode->appendChild($currentDateValue);

            //create current time node
            $currentTimeNode = $doc->createElement('currentTime');
            $item->appendChild($currentTimeNode);
            $currentTimeValue = $doc->createTextNode($currentTime);
            $currentTimeNode->appendChild($currentTimeValue);

            //create bidder ID node
            $bidderIDNode = $doc->createElement('bidderID');
            $item->appendChild($bidderIDNode);
            $bidderIDValue = $doc->createTextNode($bidderID);
            $bidderIDNode->appendChild($bidderIDValue);

            //save the xml file
            $doc->formatOutput = true;
            $doc->save($xmlfile);

            // return back the successful listing information
            echo "<p>Thank you! Your item has been listed in ShopOnline.
            The item number is " . 100 . ", and the bidding starts now: " . $currentTime . " on " . $currentDate . ".</p>";
        }
        else {
            // if auction.xml exists, then update the file
            $doc->preserveWhiteSpace = FALSE;
            $doc->load($xmlfile);

            $maxItemId = 0;
            // create new item ID based on the previous ID
            $items = $doc->getElementsByTagName('item');
            foreach ($items as $item) {
                $itemId = $item->getElementsByTagName("itemID")->item(0)->nodeValue;
                if ($itemId > $maxItemId) {
                    $maxItemId = $itemId;
                }
            }           

            $newItemId = $maxItemId + 1;

            //create an item node under items node
            $items = $doc->getElementsByTagName('items')->item(0);
            $item = $doc->createElement('item');
            $items->appendChild($item);

            if (!empty($loginid) && $loginStatus) {
                // create customer id node
                $customeridNode = $doc->createElement('customerID');
                $item->appendChild($customeridNode);
                $customeridValue = $doc->createTextNode($loginid);
                $customeridNode->appendChild($customeridValue);
            } elseif (!empty($registerid) && $registerStatus) {
                // create customer id node
                $customeridNode = $doc->createElement('customerID');
                $item->appendChild($customeridNode);
                $customeridValue = $doc->createTextNode($registerid);
                $customeridNode->appendChild($customeridValue);
            }            

            // create item id node start with new item ID
            $idNode = $doc->createElement('itemID');
            $item->appendChild($idNode);
            $idValue = $doc->createTextNode($newItemId);
            $idNode->appendChild($idValue);
            
            // create first name node
            $itemName = $doc->createElement('itemName');
            $item->appendChild($itemName);
            $itemnameValue = $doc->createTextNode($itemname);
            $itemName->appendChild($itemnameValue);
            
            // create category node
            $categoryNode = $doc->createElement('category');
            $item->appendChild($categoryNode);
            $categoryValue = $doc->createTextNode($category);
            $categoryNode->appendChild($categoryValue);

            //create description node
            $descriptionNode = $doc->createElement('description');
            $item->appendChild($descriptionNode);
            $descriptionValue = $doc->createTextNode($description);
            $descriptionNode->appendChild($descriptionValue);
            
            //create start price node
            $startPriceNode = $doc->createElement('startingPrice');
            $item->appendChild($startPriceNode);
            $startPriceValue = $doc->createTextNode($startprice);
            $startPriceNode->appendChild($startPriceValue);

            //create reserve price node
            $reservePriceNode = $doc->createElement('reservePrice');
            $item->appendChild($reservePriceNode);
            $reservePriceValue = $doc->createTextNode($reserveprice);
            $reservePriceNode->appendChild($reservePriceValue);

            //create buy it now price node
            $buyPriceNode = $doc->createElement('buyItNowPrice');
            $item->appendChild($buyPriceNode);
            $buyPriceValue = $doc->createTextNode($buyprice);
            $buyPriceNode->appendChild($buyPriceValue);

            //create bid price node
            $bidPriceNode = $doc->createElement('bidPrice');
            $item->appendChild($bidPriceNode);
            $bidPriceValue = $doc->createTextNode($startprice);
            $bidPriceNode->appendChild($bidPriceValue);

            //create duration node
            $durationNode = $doc->createElement('duration');
            $item->appendChild($durationNode);
            $durationValue = $doc->createTextNode($duration);
            $durationNode->appendChild($durationValue);

            //create status node
            $statusNode = $doc->createElement('status');
            $item->appendChild($statusNode);
            $statusValue = $doc->createTextNode($initialStatus);
            $statusNode->appendChild($statusValue);

            //create current date node
            $currentDateNode = $doc->createElement('currentDate');
            $item->appendChild($currentDateNode);
            $currentDateValue = $doc->createTextNode($currentDate);
            $currentDateNode->appendChild($currentDateValue);

            //create current time node
            $currentTimeNode = $doc->createElement('currentTime');
            $item->appendChild($currentTimeNode);
            $currentTimeValue = $doc->createTextNode($currentTime);
            $currentTimeNode->appendChild($currentTimeValue);

            //create bidder ID node
            $bidderIDNode = $doc->createElement('bidderID');
            $item->appendChild($bidderIDNode);
            $bidderIDValue = $doc->createTextNode($bidderID);
            $bidderIDNode->appendChild($bidderIDValue);

            //save the xml file
            $doc->formatOutput = true;
            $doc->save($xmlfile);

            // return back the successful listing information
            echo "<p>Thank you! Your item has been listed in ShopOnline.
            The item number is " . $newItemId . ", and the bidding starts now: " . $currentTime . " on " . $currentDate . ".</p>";
        }    

    }
}
// handle creating the new category action 
if (isset($_GET['getCategories'])) {
    // Read auction.xml
    $xmlfile = '../../data/auction.xml';
    $doc = new DomDocument('1.0');
    // if the xml file exists, add new category
    if (file_exists($xmlfile)) {
        $doc->load($xmlfile);
        $itemList = $doc->getElementsByTagName('item');
        
        // Extract existing categories
        $categories = [];
        foreach ($itemList as $item) {
            // get category node value
            $storedCategory = $item->getElementsByTagName("category")->item(0)->nodeValue;
            // if the category is the new one, add it to the array
            if (!in_array($storedCategory, $categories)) {
                $categories[] = $storedCategory;
            }
        }

        // Return categories as JSON
        header('Content-Type: application/json');
        echo json_encode($categories);
    } else {
        // If the XML file doesn't exist, return an empty JSON array
        header('Content-Type: application/json');
        echo json_encode([]);
    }
    exit; // Ensure the script stops executing after sending the JSON response   
}
?>