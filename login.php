<?php
// Filename: login.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to login to the system by entering email and password.

// set up session
session_start();
// set up session for passing login email value among pages
if (!isset($_SESSION["loginid"])) {
    $_SESSION["loginid"] = "";
}
// set up session for login status for passing status to other pages
if (!isset($_SESSION["loginStatus"])) {
    $_SESSION["loginStatus"] = false;
}
// set up header for the file
header('Content-Type: application/json');

// Set up initial error state and message
$check = true;
$errMsg = array("error" => "");

if (isset($_POST["email"]) && isset($_POST["password"])) {
    // use trim() method to remove whitespace from the beginning and end of user text input
    $email = trim($_POST["email"]);    
    $password = $_POST["password"];
    // Check user input
    if (empty($email)) {
        $errMsg["error"] .= "<p class='error'>Email must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($password)) {
        $errMsg["error"] .= "<p class='error'>Password must be provived to proceed.</p>";
        $check = false;
    }    
    // set up initial invalid password and email status
    $invalidPassword = false;
    $invalidEmail = false;
    $invalidEmailCounter = 0;

    if ($check) {
        // Set up the xml file path
        $xmlfile = '../../data/customer.xml';
        $doc = new DomDocument('1.0');
        if (file_exists($xmlfile)) {
            $doc->load($xmlfile);
        
            // Loop through customer entries in the XML
            $customers = $doc->getElementsByTagName('customer');            

            foreach ($customers as $customer) {
                $storedEmail = $customer->getElementsByTagName("email")->item(0)->nodeValue;
                $storedPassword = $customer->getElementsByTagName("password")->item(0)->nodeValue;
                $storedID= $customer->getElementsByTagName("id")->item(0)->nodeValue;

                // Check if the credentials match
                if (($email === $storedEmail) && ($password === $storedPassword)) {
                    // set up login successful array
                    $response = array("success" => true);
                    echo json_encode($response);                    
                    $_SESSION["loginid"] = $storedID;
                    $_SESSION["loginStatus"] = true;
                    break;
                } elseif (($email === $storedEmail) && ($password !== $storedPassword)) {
                    // set invalid password status to be true
                    $invalidPassword = true;                    
                } 
                if ($email !== $storedEmail) {
                    $invalidEmailCounter++;
                    $totalLength = $customers->length;
                    if ($invalidEmailCounter === $totalLength) {
                        // set invalid email status to be true
                        $invalidEmail = true;
                    }
                }
            }
            if ($invalidPassword) {
                // add invalid password error message
                $errMsg["error"] .= "<p class='error'>Invalid password. Please try again.</p>";
            }
            if ($invalidEmail) {
                // add invalid email error message
                $errMsg["error"] .= "<p class='error'>Invalid email, user does not exist. Please go to register page.</p>";
            }
        } else {
            // add user not exist error message
            $errMsg["error"] .= "<p class='error'>User does not exist. Please go to register page.</p>";
        }       
    }

    if ($errMsg["error"] != "") {
        // return back the error message response
        echo json_encode($errMsg);
    }
}

?>