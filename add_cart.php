<?php
session_start();
include 'connect.php';

$successMessage = "";
$errorMessage = "";

if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Check if stock is available
    $stockCheck = "SELECT stock FROM products WHERE product_id = ?";
    $stockCheckStmt = $db->prepare($stockCheck);
    $stockCheckStmt->bind_param("i", $product_id);
    $stockCheckStmt->execute();
    $stockResult = $stockCheckStmt->get_result();

    if ($stockResult->num_rows > 0) {
        $row = $stockResult->fetch_assoc();
        $stock = $row['stock'];

        if ($stock > 0) {
            // There is stock available, proceed with adding to the cart
            $update_stock = "UPDATE products SET stock = stock - 1 WHERE product_id = ?";
            $update_stock_stmt = $db->prepare($update_stock);
            $update_stock_stmt->bind_param("i", $product_id);

            if ($update_stock_stmt->execute()) {
                // Successfully updated the stock

                // Check if the same product is already in the cart
                $sql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // If the same product is already in the cart, increase its quantity by 1
                    $row = $result->fetch_assoc();
                    $cart_id = $row['cart_id'];
                    $current_quantity = $row['quantity'];
                    $new_quantity = $current_quantity + 1;

                    $update = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
                    $update_stmt = $db->prepare($update);
                    $update_stmt->bind_param("ii", $new_quantity, $cart_id);
                    if ($update_stmt->execute()) {
                        $successMessage = "Product quantity updated in the cart.";
                    } else {
                        $errorMessage = "Error updating product quantity: " . $db->error;
                    }
                    $update_stmt->close();
                } else {
                    // Insert if the product is not in the cart
                    $quantity = 1;
                    $insert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                    $insert_stmt = $db->prepare($insert);
                    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
                    if ($insert_stmt->execute()) {
                        $successMessage = "Product added to the cart.";
                    } else {
                        $errorMessage = "Error adding product to the cart: " . $db->error;
                    }
                    $insert_stmt->close();
                }

            } else {
                $errorMessage = "Error updating product stock: " . $update_stock_stmt->error;
            }

            // Close the stock update statement
            $update_stock_stmt->close();
        } else {
            $errorMessage = "No stock available for this product, Please <a href='contact.php'>contact</a> admin to restock";
        }
    } else {
        $errorMessage = "Product not found.";
    }

    // Close the stock check statement
    $stockCheckStmt->close();
} else {
    $errorMessage = "Please login to add products to the cart.";
}

header('Location: products.php?success=' . urlencode($successMessage) . '&error=' . urlencode($errorMessage));
exit();
?>
