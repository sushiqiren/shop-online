<?php
// Filename: buyNow.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to buy item instantly by updating xml file from server side.

// set up session
session_start();
// transfer the session value about bidder ID
if (!isset($_SESSION['bidderID'])) {
    $_SESSION['bidderID'] = "";
}

$bidderID = "";
if (isset($_SESSION['registerid'])) {
    $_SESSION['bidderID'] = $_SESSION['registerid'];
    $bidderID = $_SESSION['bidderID'];
} else if (isset($_SESSION['loginid'])) {
    $_SESSION['bidderID'] = $_SESSION['loginid'];
    $bidderID = $_SESSION['bidderID'];
}
// handle the itemID and buyNowPrice from XMLHTTPRequest
if (isset($_GET['itemID']) && isset($_GET['buyNowPrice'])) {
    
    $itemID = $_GET['itemID'];
    $buyPrice = $_GET['buyNowPrice'];

    // Set up the xml file path
    $xmlfile = '../../data/auction.xml';
    $doc = new DomDocument('1.0');
    // handle the buy it now function by conditional check
    if (file_exists($xmlfile)) {
        $doc->load($xmlfile);
        // Loop through item entries in the XML
        $items = $doc->getElementsByTagName('item');

        foreach ($items as $item) {
            // get item ID value from tag itemID
            $searchID = $item->getElementsByTagName('itemID')->item(0)->nodeValue;

            if ($searchID === $itemID) {
                // replace the bid price with new buy it now price, and bidder ID with new bidderID, and status with sold status
                $item->getElementsByTagName('bidPrice')->item(0)->nodeValue = $buyPrice;
                $item->getElementsByTagName('bidderID')->item(0)->nodeValue = $bidderID;
                $item->getElementsByTagName('status')->item(0)->nodeValue = 'sold';
                // return back the response text
                echo "Thank you for purchasing this item.";
                break;
            }
        }
        // update the auction.xml file
        $doc->save($xmlfile);
    }
}

?>