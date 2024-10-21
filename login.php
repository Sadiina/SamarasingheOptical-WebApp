<?php
include 'connect.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    $customerEmail = $_POST['customerEmail'];
    $customerPass = $_POST['customerPass'];
    $customerPassHashed = md5($customerPass); // Hashing password, consider using password_hash() for better security

    //save customer email in session----------------------------------------------------------------
    if ($customerResult->num_rows > 0) {
        $row = $customerResult->fetch_assoc();
        $_SESSION['CustomerEmail'] = $row['CustomerEmail']; // Store customer email in session
        header("Location: home.php"); // Redirect to customer home page
        exit();
    }
    //-----------------------------------------------------------------------------------------------

    // Check if the login credentials are for the admin
    $adminSql = "SELECT * FROM admin WHERE AdminEmail='$customerEmail' AND AdminPassword='$customerPass'";
    $adminResult = $conn->query($adminSql);

    if ($adminResult->num_rows > 0) {
        $row = $adminResult->fetch_assoc();
        $_SESSION['AdminEmail'] = $row['AdminEmail']; // Store admin email in session
        header("Location: Admin/adminHome.php"); // Redirect to admin page
        exit();
    } 
    // Check if the login credentials are for the staff
    else {
        // Query the staff table for matching credentials
        $staffSql = "SELECT * FROM staff WHERE StaffEmail='$customerEmail' AND StaffPassword='$customerPass'";
        $staffResult = $conn->query($staffSql);

        if ($staffResult->num_rows > 0) {
            $row = $staffResult->fetch_assoc();
            $_SESSION['StaffEmail'] = $row['StaffEmail']; // Store staff email in session
            header("Location: staff/staffHome.php"); // Redirect to staff home page
            exit();
        }
        // Check if the login credentials are for the customer
        else {
            $customerSql = "SELECT * FROM customer WHERE CustomerEmail='$customerEmail' AND CustomerPassword='$customerPassHashed'";
            $customerResult = $conn->query($customerSql);

            if ($customerResult->num_rows > 0) {
                $row = $customerResult->fetch_assoc();
                $_SESSION['CustomerEmail'] = $row['CustomerEmail']; // Store customer email in session
                header("Location: home.php"); // Redirect to customer home page
                exit();
            } else {
                // Invalid credentials for all types of users
                header("Location: loginUI.php?error=Incorrect Email or Password !");
                exit();
            }
        }
    }
}
?>