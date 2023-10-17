if (isset($_POST['complete_payment'])) {
    $user_id = $_POST['user_id'];
    $cart_id = $_POST['cart_id'];
    $total_price = $_POST['total_price'];
    $customer_name = mysqli_real_escape_string($db, $_POST['customer_name']);
    $customer_address = mysqli_real_escape_string($db, $_POST['customer_address']);
    $payment_method = $_POST['payment_method'];
    $packaging_options = $_POST['packaging'];

    // Insert the order into the orders table with all details
    $insert_sql = "INSERT INTO orders (user_id, cart_id, customer_name, address, payment_method, amount, order_date, packaging_options)
                       VALUES ('$user_id', '$cart_id', '$customer_name', '$customer_address', '$payment_method', '$total_price', NOW(), '$packaging_options')";

    // Insert the order into the orders table
    if (mysqli_query($db, $insert_sql)) {
        $order_id = mysqli_insert_id($db);

        // Commit the insertion of the order
        mysqli_commit($db);

        // Now, retrieve the cart items and try to delete them
        // $cart_sql = "SELECT cart_id FROM cart WHERE user_id = '$user_id'";
        // $cart_result = mysqli_query($db, $cart_sql);

        // if ($cart_result) {
        //     while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        //         $cart_id = $cart_row['cart_id'];

        //         // Attempt to delete the item from the cart
        //         $delete_cart_item_sql = "DELETE FROM cart WHERE cart_id = '$cart_id'";
        //         if (mysqli_query($db, $delete_cart_item_sql)) {
        //             // Item deleted successfully from cart
        //         } else {
        //             // Handle deletion error (if necessary)
        //             echo "Error deleting cart item: " . mysqli_error($db);
        //         }
        //     }
        // }
        header('location: receipt.php?order_id=' . $order_id);
        exit();
    } else {
        echo "Error inserting order: " . mysqli_error($db);
    }

    mysqli_close($db);
}