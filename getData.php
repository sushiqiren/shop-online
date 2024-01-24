<?php
// Filename: getData.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for getting data of auction.xml file from server side.

// set up file path
$url = '../../data/auction.xml';
// create new DomDocument object instance
$doc = new DomDocument('1.0');
// load the xml file
$doc->load($url);
// Set the content type to XML
header('Content-Type: text/xml');
// return back the response of xml file
echo ($doc->saveXML());
?>