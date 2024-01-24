<?php
// Filename: report.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline admin to report the result of sold or failed items.

// load XML file into a DOM document
$xmlDoc = new DOMDocument('1.0');
$xmlDoc->formatOutput = true;
$xmlDoc->load("../../data/auction.xml");
// Get the root element of the XML document
$root = $xmlDoc->documentElement;
// load XSL file into a DOM document
$xslDoc = new DomDocument('1.0');
$xslDoc->load("auction.xsl");
// create a new XSLT processor object
$proc = new XSLTProcessor;
// load the XSL DOM object into the XSLT processor
$proc->importStyleSheet($xslDoc);
// transform the XML document using the configured XSLT processor
$strXml= $proc->transformToXML($xmlDoc);
// echo the transformed HTML back to the client
echo ($strXml);

// Find and remove items with 'sold' or 'failed' status
$itemsToRemove = [];

$items = $xmlDoc->getElementsByTagName('item');

foreach ($items as $item) {
    // get status node value
    $status = $item->getElementsByTagName('status')->item(0)->nodeValue;
    // if status is sold or status is failed, then handle the remove action
    if ($status === 'sold' || $status === 'failed') {
        // Collect items to remove
        $itemsToRemove[] = $item;
    }
}

// Remove the collected items
foreach ($itemsToRemove as $item) {
    $root->removeChild($item);
}
// Update the auction.xml file
$xmlDoc->save("../../data/auction.xml");
    
?>