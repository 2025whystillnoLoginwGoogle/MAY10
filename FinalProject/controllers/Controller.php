<?php
session_start();
require_once "../bl/UserManager.php";
require_once "../helper/send.php";

$usermanager = new UserManager();

if (isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "contact") {
        $name    = htmlspecialchars($_POST["firstName"] . " " . $_POST["lastName"]);
        $email   = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST["message"]);

        if (!$email) {
            echo "Invalid email format.";
            exit;
        }

        $body = "<h3>New Message</h3><p><strong>Name:</strong> $name</p><p><strong>Email:</strong> $email</p><p><strong>Message:</strong><br>$message</p>";
        $result = sendEmail("admin@yourdomain.com", "Admin", "Contact Form Submission", $body);

        echo ($result === true) ? "Email sent successfully!" : "Failed: " . $result;
        exit;
    } 
    else if ($action == "register") {
        $usermanager->registerFunc($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["role"]);
        exit;
    } 
    else if ($action == "login") {
        $usermanager->loginFunc($_POST["email"], $_POST["password"]);
        exit;
    }
    else if ($action == "getAllUsers") {
        $usermanager->getAllUsersFunc();
        exit;
    }
    else if ($action == "deleteUser") {
        $usermanager->deleteUserFunc($_POST["id"]);
        exit;
    }
    else if ($action == "updateUser") {
        $usermanager->updateUserFunc($_POST["id"], $_POST["firstName"], $_POST["lastName"], $_POST["email"]);
        exit;
    }
}
?>
