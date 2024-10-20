<?php
include 'connect.php';

if (isset($_POST['productSend']) && isset($_POST['typeSend']) && isset($_POST['quantitySend']) && isset($_POST['expirationSend']) && isset($_FILES['imageSend'])) {

    $productSend = $_POST['productSend'];
    $quantitySend = $_POST['quantitySend'];
    $expirationSend = $_POST['expirationSend'];
    $typeSend = $_POST['typeSend'];
    
    $image = $_FILES['imageSend'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    
    $uploadDir = 'uploads/';
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $newImageName = uniqid("IMG_", true) . '.' . $imageExtension; // Unique file name
    $uploadFile = $uploadDir . $newImageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageExtension, $allowedTypes)) {
        echo "Error: Only image files (JPG, JPEG, PNG, GIF) are allowed.";
        exit();
    }

    if (move_uploaded_file($imageTmpName, $uploadFile)) {
        $stmt = $con->prepare("INSERT INTO product (productname, quantity, exp, image, type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $productSend, $quantitySend, $expirationSend, $newImageName, $typeSend); // "siss" - string, integer, string, string
        
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error: Could not execute query.";
        }
        
        $stmt->close();
    } else {
        echo "Error: File upload failed.";
    }
    // Example in insert.php or edit.php

if ($currentQuantity < $lowStockThreshold) {
    $userEmail = 'user_email@example.com';
    $productName = 'Product Name';
    $currentQuantity = 5; // Current stock quantity

    if (sendLowStockAlert($userEmail, $productName, $currentQuantity)) {
        echo "Low stock alert sent!";
    } else {
        echo "Failed to send alert.";
    }
}
}
?>
