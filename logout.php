<?php
// Filename: logout.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to logout by terminating the sessions.

// set up session
session_start();
// unset the session transferred from other pages
session_unset();
// destroy the session
session_destroy();
// redirect to login.htm page after logout
header("Location: login.htm");

?>