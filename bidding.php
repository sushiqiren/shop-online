<?php
// Filename: bidding.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to place bid price by updating xml file from server side.

// set up session
session_start();
// transfer the session about bidder ID
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
// handle the itemID and bidPrice from XMLHTTPRequest
if (isset($_GET['itemID']) && isset($_GET['bidPrice'])) {    
    
    $itemID = $_GET['itemID'];
    $bidPrice = $_GET['bidPrice'];

    // Set up the xml file path
    $xmlfile = '../../data/auction.xml';
    $doc = new DomDocument('1.0');
    // handle the place bid function by conditional check
    if (file_exists($xmlfile)) {
        $doc->load($xmlfile);

        // Loop through item entries in the XML
        $items = $doc->getElementsByTagName('item');

        foreach ($items as $item) {
            // get itemID by the tag name
            $searchID = $item->getElementsByTagName('itemID')->item(0)->nodeValue;
            if ($searchID === $itemID) {
                // get bid price and bidder ID and buy it now price from the related tag name
                $currentBidPrice = $item->getElementsByTagName('bidPrice')->item(0)->nodeValue;
                $currentBidderID = $item->getElementsByTagName('bidderID')->item(0)->nodeValue;
                $buyNowPrice = $item->getElementsByTagName('buyItNowPrice')->item(0)->nodeValue;
                $status = $item->getElementsByTagName('status')->item(0)->nodeValue;
                if (($bidPrice > $buyNowPrice) && ($status !== 'sold')) {
                    // if bid price higher than the buy it now price, return back another response text
                    echo "Sorry, your bidding price is already higher than the buy it now price. If you want to be the standing bidder, just click the buy it now button to get the item. Thanks!";
                    break;
                }
                if (($bidPrice > $currentBidPrice) && ($status !== 'sold')) {
                    // if bid price is more than the current bid price, and item status is still not sold, replace the bidPrice and bidderID value with the new one
                    $item->getElementsByTagName('bidPrice')->item(0)->nodeValue = $bidPrice;
                    $item->getElementsByTagName('bidderID')->item(0)->nodeValue = $bidderID;
                    // return back the response text
                    echo "Thank you! Your bid is recorded in ShopOnline.";
                    break;
                } else {
                    // if conditions not matched, return back another response text
                    echo "Sorry, your bid is not valid.";
                    break;
                }
            }            
        }
        // update the auction.xml file
        $doc->save($xmlfile);
    }
}

?>