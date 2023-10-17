<!-- This is admin page for managing the products -->
<?php
include '../connect.php';

// Add Function
if (isset($_POST['add_product'])) {
  $p_productname = $_POST['product_name'];
  $p_category = $_POST['category'];
  $p_productprice = $_POST['product_price'];
  $p_productdes = $_POST['product_des'];
  $p_stock = $_POST['stock'];
  $p_image = $_FILES['product_image']['name'];
  $tempname = $_FILES['product_image']['tmp_name'];
  $folder = '../uploads/' . $p_image;

  $query = mysqli_query($db, 'SELECT * FROM products WHERE product_name="' . $p_productname . '"');

  if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_des']) || empty($_POST['category'])) {
    echo "<script>alert('Please fill in all the details');window.location='admin_product.php';</script>";
  } elseif (empty($_FILES['product_image']['name'])) {
    echo "<script>alert('Please insert an image');window.location='admin_product.php';</script>";
  } elseif (mysqli_num_rows($query) > 0) {
    $message[] = 'Product already exists';
    // If product exist in database display a message
  } else {
    // Insert the details into database
    $sql = "INSERT INTO products (product_name,category,product_price,product_des,stock,product_image)VALUES
            ('$p_productname','$p_category','$p_productprice','$p_productdes','$p_stock','$p_image')";

    mysqli_query($db, $sql);

    if (move_uploaded_file($tempname, $folder)) {
      $message[] = 'Product Added To Database';
    } else {
      $message[] = 'Sorry, Something Went Wrong';
    }
  }
}
// Update function
if (isset($_POST['update'])) {
  $id = $_POST['edit_id'];
  $product_name = $_POST['product_name'];
  $category = $_POST['category'];
  $product_price = $_POST['product_price'];
  $product_des = $_POST['product_des'];
  $product_stock = $_POST['stock'];

  // Retrieve old image name from database based on id
  $query = "SELECT product_image FROM products WHERE product_id = $id";
  $result = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($result);
  $old_image = $row['product_image'];

  if (isset($_FILES['profile']['name']) && ($_FILES['profile']['name'] != "")) {
    $size = $_FILES['profile']['size'];
    $temp = $_FILES['profile']['tmp_name'];
    $type = $_FILES['profile']['type'];
    $profile_name = $_FILES['profile']['name'];
    unlink("../uploads/$old_image");
    move_uploaded_file($temp, "../uploads/$profile_name");
  } else {
    $profile_name = $old_image;
  }

  $sql = "UPDATE products SET product_name='$product_name', category = '$category',product_price='$product_price',product_des='$product_des',
          stock='$product_stock',product_image='$profile_name' WHERE product_id = $id";

  mysqli_query($db, $sql);

  $message[] = 'Updated';
}

// Delete function
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  mysqli_query($db, "DELETE FROM products WHERE product_id = '$delete_id'");
  header('location:admin_product.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Your Products</title>
  <link rel="icon" href="../pictures/admin logo.png">
  <style>
    .rounded-box {
      border-radius: 10px;
      box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.3);
      padding: 20px;
    }

    .custom-margin {
      margin-right: 8rem;
      margin-left: 8rem;
      margin-top: 6rem;
    }

    .glowing-button {
      animation: glowing 2s infinite;
    }

    @keyframes glowing {
      0% {
        box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
      }

      50% {
        box-shadow: 0 0 20px rgba(46, 204, 113, 0.7);
      }

      100% {
        box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
      }
    }
  </style>
  <script src="https://kit.fontawesome.com/a84d485a7a.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin_product.php">
        <img src="../pictures/admin logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
        EcoPack Admin
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><img src="../pictures/admin logo.png" alt="Logo" width="30" height="30"> EcoPack</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_product.php"><i class="fa-solid fa-box"></i> Manage Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_donation.php"><i class="fa-solid fa-clock-rotate-left"></i> Customer Purchase History</a>
            </li>
            <!-- Logout button -->
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="fa-solid fa-sign-out"></i> Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="custom-margin">
    <!-- Add Product Button -->
    <button type="button" class="btn btn-success glowing-button" data-bs-toggle="modal" data-bs-target="#addProductModal">
      <i class="fa-solid fa-add"></i> ADD PRODUCT
    </button>

    <!-- Add Module -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post" class="add-item-form" enctype="multipart/form-data">
              <!-- Your form content here -->
              <form action="" method="post" class="add-item-form" enctype="multipart/form-data">
                <label>Product Name</label>
                <input type="text" name="product_name" placeholder="Product Name" class="form-control"><br>
                <!-- Category Selection -->
                <label>Choose Category</label>
                <select class="form-control" name="category" id="category">
                  <option value="Cutlery & Cups">Cutlery & Cups</option>
                  <option value="Bags">Bags</option>
                  <option value="Plates">Plates</option>
                  <option value="Food Containers">Food Containers</option>
                </select><br>
                <label>Product Price (RM)</label>
                <input type="text" name="product_price" placeholder="Product Price" class="form-control"><br>
                <label>Product Description</label>
                <textarea name="product_des" placeholder="Product Description" rows="4" cols="50" class="form-control"></textarea><br>
                <label>Enter Stock Amount</label>
                <input type="number" name="stock" placeholder="Stock Amount" class="form-control"><br>
                <label>Insert Image</label>
                <input type="file" name="product_image" class="form-control" accept="image/png, image/jpg, image/jpeg"><br>
                <input type="submit" name="add_product" value="Add product" class="btn btn-primary">
              </form>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
    $sql = "SELECT * FROM products";
    $result = $db->query($sql);
    $db->close();
    ?>
    <br><br>
    <?php
    if (isset($message)) {
      foreach ($message as $message) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-bell"></i> ' . $message .
          '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
      };
    };
    ?>
    <!-- List of product Table -->
    <div class="rounded-box mt-4 mb-4">
      <h3><i class="fas fa-edit"></i> Edit Products</h3>
      <table class="table table-striped table-hover rounded">
        <tr>
          <th class="bg-dark text-light">Image</th>
          <th class="bg-dark text-light">Product Name</th>
          <th class="bg-dark text-light">Category</th>
          <th class="bg-dark text-light">Product Price</th>
          <th class="bg-dark text-light">Product Description</th>
          <th class="bg-dark text-light">Stocks</th>
          <th class="bg-dark text-light">Actions</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
        ?>
          <tr>
            <td><img src="../uploads/<?php echo $row['product_image']; ?>" width="100px" height="100px"></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td>RM <?php echo $row['product_price']; ?></td>
            <td><?php echo $row['product_des']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
              <button type="button" class="btn btn-success editBtn" style="box-shadow: -1px 8px 20px 1px rgba(143,159,209,0.66);" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['product_id']; ?>"><i class="fa-solid fa-pen-to-square"></i></button>
              <br><br>
              <a href="admin_product.php?delete=<?php echo $row['product_id']; ?>" onclick="return confirm('Delete this product?')" class="btn btn-danger" style="box-shadow: -1px 8px 20px 1px rgba(143,159,209,0.66);">
                <i class="fas fa-trash"></i></a>
            </td>
          </tr>
         
          <!-- UPDATE Edit Modal Pop Up -->
          <div class="modal fade" id="editModal<?php echo $row['product_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-pen-to-square"></i> Edit Products</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                  <div class="modal-body">
                    <!-- Add a container for the existing image preview -->
                    <div class="image-preview d-flex justify-content-center align-items-center">
                      <img src="../uploads/<?php echo $row['product_image']; ?>" alt="Existing Image" width="150">
                    </div>
                    <div class="form-group">
                      <label><b>Product Name</b></label>
                      <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <!-- Category Selection -->
                      <label><b>Category</b></label>
                      <select class="form-control" name="category" id="category">
                        <option value="Cutlery & Cups">Cutlery & Cups</option>
                        <option value="Bags">Bags</option>
                        <option value="Plates">Plates</option>
                        <option value="Food Containers">Food Containers</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label><b>Price</b></label>
                      <input type="text" name="product_price" value="<?php echo $row['product_price']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <label><b>Product Description</b></label>
                      <input type="text" name="product_des" value="<?php echo $row['product_des']; ?>" class="form-control">
                    </div><br>
                    <div class="form-group">
                      <label><b>Stocks</b></label>
                      <input type="number" name="stock" value="<?php echo $row['stock']; ?>" class="form-control">
                    </div><br>
                    <div class="form-group">
                      <label><b>Insert Image</b></label>
                      <input type="file" name="profile" class="form-control form-control-sm" accept="image/png, image/jpg, image/jpeg">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" name="edit_id" value="<?php echo $row['product_id']; ?>">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php } ?>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      // Listen for changes in the file input
      $('input[name="profile"]').change(function() {
        var input = this;
        var imagePreview = $(input).closest('.modal-content').find('.image-preview img');

        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            imagePreview.attr('src', e.target.result);
          };

          reader.readAsDataURL(input.files[0]);
        }
      });
    });
  </script>
</body>

</html>