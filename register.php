<?php
// Filename: register.php
// Author: Huaxing Zhang
// ID: 102078766
// Main function: php code for ShopOnline user to register to the system.

// set up session
session_start();
// set up session of register status for other pages
if (!isset($_SESSION["registerStatus"])) {
    $_SESSION["registerStatus"] = false;
}
// set up session for passing register email among pages
if (!isset($_SESSION["registerid"])) {
    $_SESSION["registerid"] = "";
}

// Set up initial error state and message
$check = true;
$errMsg = array("error" => "");

if (isset($_GET["firstname"]) && isset($_GET["surname"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["confirmpassword"])) {
    // use trim() method to remove whitespace from the beginning and end of user input
    $firstname = trim($_GET["firstname"]);
    $surname = trim($_GET["surname"]);
    $email = trim($_GET["email"]);
    $password = $_GET["password"];
    $confirmpassword = $_GET["confirmpassword"];    

    if (empty($firstname)) {
        $errMsg["error"] .= "<p class='error'>First Name must be provided to proceed.</p>";        
        $check = false;
    }
    if (empty($surname)) {
        $errMsg["error"] .= "<p class='error'>Surname must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($email)) {
        $errMsg["error"] .= "<p class='error'>Email must be provided to proceed.</p>";
        $check = false;
    }
    $pattern = "/^(?!.*\.\.|.*@\.|.*\.$)[a-zA-Z0-9!#$%&'*+\-\/=?^_`{|}~]+(\.[a-zA-Z0-9!#$%&'*+\-\/=?^_`{|}~]+)*@[a-zA-Z0-9]+([a-zA-Z0-9\-]*[a-zA-Z0-9]+)*(\.[a-zA-Z0-9]+[a-zA-Z0-9\-]*[a-zA-Z0-9]+)*$/";
    if (!preg_match($pattern, $email)) {
        $errMsg["error"] .= "<p class='error'>Please use valid email address. The format of email addresses is local-part@domain-part, 
        local part may contain uppercase and lowercase English letters, digits 0 to 9, special characters (!#$%&'*+-/=?^_`{|}~) and dots, 
        and dot is not the first or last character and it does not appear consecutively. 
        For domain part, it should include letters or digits or hyphens and 
        hyphen is not the first or last character for each domain label.</p>";
        $check = false;
    }
    if (empty($password)) {
        $errMsg["error"] .= "<p class='error'>Password must be provided to proceed.</p>";
        $check = false;
    }
    if (empty($confirmpassword)) {
        $errMsg["error"] .= "<p class='error'>Password must be confirmed to proceed.</p>";
        $check = false;
    }
    if ($password != $confirmpassword) {
        $errMsg["error"] .= "<p class='error'>Confirmed password must match entered password.</p>";
        $check = false;
    }
    

    // Set up the xml file path
    $xmlfile = '../../data/customer.xml';
    
    $doc = new DomDocument('1.0');

    if (file_exists($xmlfile)){ // check if the xml file exists
        // load the xml file             
        $doc->load($xmlfile); 
        // Loop through customer elements in the XML
        $customers = $doc->getElementsByTagName('customer');    

        // Loop through customer elements in the XML to find the same email address
        foreach ($customers as $customer) {

            $customerEmail = $customer->getElementsByTagName("email");
            $customerEmail = $customerEmail->item(0)->nodeValue;
            if ($customerEmail === $email) {
                $errMsg["error"] .= "<p class='error'>Email has been used. Please use another email address.</p>";
                $check = false;
            }
        }
    }
    // set up header for the file
    header('Content-Type: application/json');

    if ($errMsg["error"] != "") {
        echo json_encode($errMsg);
    }
    // handle register action
    if ($check) {
        // if customer.xml file doesn't exist, create a new one
        if (!file_exists($xmlfile)){ 
            // if the xml file does not exist, create a root node $customers
            $customers = $doc->createElement('customers');
            $doc->appendChild($customers);

            //create a customer node under customers node
            $customers = $doc->getElementsByTagName('customers')->item(0);
            $customer = $doc->createElement('customer');
            $customers->appendChild($customer);

            // create customer id node start with 1
            $idNode = $doc->createElement('id');
            $customer->appendChild($idNode);
            $idValue = $doc->createTextNode(1);
            $idNode->appendChild($idValue);
            
            // create first name node
            $firstName = $doc->createElement('firstName');
            $customer->appendChild($firstName);
            $firstnameValue = $doc->createTextNode($firstname);
            $firstName->appendChild($firstnameValue);
            
            // create surname node
            $surName = $doc->createElement('surname');
            $customer->appendChild($surName);
            $surnameValue = $doc->createTextNode($surname);
            $surName->appendChild($surnameValue);

            //create email node
            $Email = $doc->createElement('email');
            $customer->appendChild($Email);
            $emailValue = $doc->createTextNode($email);
            $Email->appendChild($emailValue);
            
            //create password node
            $pwd = $doc->createElement('password');
            $customer->appendChild($pwd);
            $pwdValue = $doc->createTextNode($password);
            $pwd->appendChild($pwdValue);

            //save the xml file
            $doc->formatOutput = true;
            $doc->save($xmlfile);
            // set up email sending parameters
            $to = $email;
            $subject = "Welcome to ShopOnline";
            $message = "Dear " . $firstname. ", welcome to use ShopOnline! Your customer id is 1 and the password is ". $password;
            $header = "From registration@shoponline.com.au\r\n";
            // send the email by mail method
            mail($to, $subject, $message, $header, "-r 102078766@student.swin.edu.au");            
            
            // set up register successfully array and sessions
            $response = array("success" => true);
            echo json_encode($response);
            $_SESSION["registerStatus"] = true;
            $_SESSION["registerid"] = 1;
        } else {
            // if customer.xml file exists, add more customers
            $doc->preserveWhiteSpace = FALSE;
            $doc->load($xmlfile);
            // set up new customer ID 
            $maxCustomerId = 0;            
            $customers = $doc->getElementsByTagName('customer');
            foreach ($customers as $customer) {
                $customerId = $customer->getElementsByTagName("id")->item(0)->nodeValue;
                if ($customerId > $maxCustomerId) {
                    $maxCustomerId = $customerId;
                }
            }
            $newCustomerId = $maxCustomerId + 1;

            //create a customer node under customers node
            $customers = $doc->getElementsByTagName('customers')->item(0);
            $customer = $doc->createElement('customer');
            $customers->appendChild($customer);

            // create customer id node by new customer ID
            $idNode = $doc->createElement('id');
            $customer->appendChild($idNode);
            $idValue = $doc->createTextNode($newCustomerId);
            $idNode->appendChild($idValue);
            
            // create first name node
            $firstName = $doc->createElement('firstName');
            $customer->appendChild($firstName);
            $firstnameValue = $doc->createTextNode($firstname);
            $firstName->appendChild($firstnameValue);
            
            // create surname node
            $surName = $doc->createElement('surname');
            $customer->appendChild($surName);
            $surnameValue = $doc->createTextNode($surname);
            $surName->appendChild($surnameValue);

            //create email node
            $Email = $doc->createElement('email');
            $customer->appendChild($Email);
            $emailValue = $doc->createTextNode($email);
            $Email->appendChild($emailValue);
            
            //create password node
            $pwd = $doc->createElement('password');
            $customer->appendChild($pwd);
            $pwdValue = $doc->createTextNode($password);
            $pwd->appendChild($pwdValue);

            //save the xml file
            $doc->formatOutput = true;
            $doc->save($xmlfile);
            // set up email sending parameters
            $to = $email;
            $subject = "Welcome to ShopOnline";
            $message = "Dear " . $firstname. ", welcome to use ShopOnline! Your customer id is " . $newCustomerId . " and the password is ". $password;
            $header = "From registration@shoponline.com.au\r\n";            
            // send email to customer by mail function
            mail($to, $subject, $message, $header, "-r 102078766@student.swin.edu.au");            

            // Set up the successful registration information and sessions           
            $response = array("success" => true);
            echo json_encode($response);
            $_SESSION["registerStatus"] = true;
            $_SESSION["registerid"] = $newCustomerId;
        }
    }
}

?>