<?php
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
if (!empty($name) || !empty($email) || !empty($phone) || !empty($message)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "contact-form";
    //create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    if (mysqli_connect_error()) {
        die('Connect Error(' . mysqli_connect_errno() . ')' . mysqli_connect_error());
    } else {
        $SELECT = "SELECT email From contact_page Where email = ? Limit 1";
        $INSERT = "INSERT Into contact_page (name, email, phone, message) values(?, ?, ?, ?)";
        //Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum == 0) {
            $stmt->close();
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssis", $name, $email, $phone, $message);
            $stmt->execute();
            session_start();
            $_SESSION['success_message'] = "Contact form saved successfully.";
            header("Location: contact-us.html");
            exit();
        } else {
            $_SESSION['error_message'] = "Something Went Wrong.";
            header("Location: contact-us.html");
        }
        $stmt->close();
        $conn->close();
    }
} else {
    $_SESSION['error_message'] = "Something Went Wrong.";
    header("Location: contact-us.html");
    die();
}
