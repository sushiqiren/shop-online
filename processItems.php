<?php
// Filename: processItems.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline admin to process the auction items.


// Set up the xml file path
$xmlfile = '../../data/auction.xml';
$doc = new DomDocument('1.0');
// handle teh processing auction items
if (file_exists($xmlfile)) {
    $doc->load($xmlfile);

    // get items by item tag
    $items = $doc->getElementsByTagName('item');
    // set up initial process status 
    $processStatus = false;    
    // Loop through item entries in the XML
    foreach($items as $item) {
        $status = $item->getElementsByTagName('status')->item(0)->nodeValue;
        $duration = $item->getElementsByTagName('duration')->item(0)->nodeValue;
        $bidPrice = $item->getElementsByTagName('bidPrice')->item(0)->nodeValue;
        $reservePrice = $item->getElementsByTagName('reservePrice')->item(0)->nodeValue;
        $startDate = $item->getElementsByTagName('currentDate')->item(0)->nodeValue;
        $startTime = $item->getElementsByTagName('currentTime')->item(0)->nodeValue;
        date_default_timezone_set('Australia/Melbourne');
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        // Combine the current date and time into a single string
        $currentDateTime = $currentDate . ' ' . $currentTime;        
        // Parse the duration and current date-time strings into DateTime objects
        $durationDateTime = new DateTime($duration);
        $currentDateTimeObject = new DateTime($currentDateTime);        
        // conditional check if status equals to in_progress to handle the processing
        if ($status === 'in_progress') {
            // conditional check if the duration has been expired
            if ($currentDateTimeObject >= $durationDateTime) {             
                // conditional check if bid price is greater than or equal to reserve price
                if ($bidPrice >= $reservePrice) {
                    // set up status to be sold and process status to be true
                    $item->getElementsByTagName('status')->item(0)->nodeValue = 'sold';
                    $processStatus = true;                    
                } else {
                    // set up status to be failed and process status to be true
                    $item->getElementsByTagName('status')->item(0)->nodeValue = 'failed';                    
                    $processStatus = true;                    
                }
            }
            
        }        
    }
    // update the auction.xml file
    $doc->save($xmlfile);
    // return back the response text by conditional check of process status
    if ($processStatus) {
        echo "Processing Auction Items Completed.";        
    } else {
        echo "There is nothing to be processed in items.";        
    }
}

?>