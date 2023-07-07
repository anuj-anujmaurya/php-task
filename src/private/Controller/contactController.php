<?php
require_once("../Models/dbConfig.php");

// function to validate all the input data
function validate($arr)
{
    $okay = true;
    // validate name
    $okay &= (preg_match("/^[A-Za-z]+(?: [A-Za-z]+)?$/", $arr['name']) && $arr['name'] != '');
    // validate e-mail
    $okay &= (filter_var($arr['email'], FILTER_VALIDATE_EMAIL) == true);
    // validate phone-number
    $okay &= preg_match("/^\d{10}$/", $arr['mobile']);
    // check if subject is empty
    $okay &= ($arr['subject'] != '');
    // check if message is empty
    $okay &= ($arr['message'] != '');

    return $okay;
}

$vaild = validate($_POST);
$msg = "";
if ($vaild) {
    // send the value to db

    $ip = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT INTO `contact_form`(`name`, `phone`, `mail`, `subject`, `message`, `user_ip`)
     VALUES ('$_POST[name]','$_POST[mobile]','$_POST[email]','$_POST[subject]','$_POST[message]', '$ip')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $msg .= "<h3>Data has been recorded successfully </h3>";

        // send the data on e-mail
        $recipient = 'anuj.anujmaurya@gmail.com';
        $subject = 'New Form Submission';

        $body = "Name: $_POST[name]\n";
        $body .= "Email: $_POST[email]\n";
        $body .= "Message: $_POST[message]\n";
        $body .= "Mobile : $_POST[mobile]]\n";

        $headers = "From: $_POST[name] <$_POST[email]>\r\n";
        $headers .= "Reply-To: $_POST[email]\r\n";

        $mailSent = mail($recipient, $subject, $body, $headers);

        if ($mailSent) {
            $msg .= "Email has been sent.";
        } else {
            $msg .= "There was a problem in sending e-mail.";
        }
    } else {
        $msg = "<h1>Oops, there was some error</h1>";
    }
} else {
    // not all values are filled correctly
    $msg =  "<h3>Please fill the correct data </h3>";
}
$msg .= "<a href='../contact/contact.php'>Go Back </a>";
header("Location: ../View/success/success.php?message=$msg");
exit;
