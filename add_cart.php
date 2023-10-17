<?php
session_start();
include 'connect.php';

$successMessage = "";
$errorMessage = "";

if (isset($_SESSION['user_id']) && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Decrement stock by 1 for the given product_id
    $update_stock = "UPDATE products SET stock = stock - 1 WHERE product_id = ?";
    $update_stock_stmt = $db->prepare($update_stock);
    $update_stock_stmt->bind_param("i", $product_id);

    // Execute the stock update statement
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
        echo "Error updating product stock: " . $update_stock_stmt->error;
    }

    // Close the stock update statement
    $update_stock_stmt->close();
} else {
    // If the user is not logged in
    $errorMessage = "Please login to add products to the cart.";
}

header('Location: products.php?success=' . urlencode($successMessage) . '&error=' . urlencode($errorMessage));
exit();
?>
