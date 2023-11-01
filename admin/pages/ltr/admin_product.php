<?php
include '../../../connect.php';

// Add Function
if (isset($_POST['add_product'])) {
    $p_productname = $_POST['product_name'];
    $p_category = $_POST['category'];
    $p_productprice = $_POST['product_price'];
    $p_productdes = $_POST['product_des'];
    $p_stock = $_POST['stock'];
    $p_image = $_FILES['product_image']['name'];
    $tempname = $_FILES['product_image']['tmp_name'];
    $folder = '../../../uploads/' . $p_image;

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
        unlink("../../../uploads/$old_image");
        move_uploaded_file($temp, "../../../uploads/$profile_name");
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
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Nice lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Nice admin lite design, Nice admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="Nice Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Manage Products</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/niceadmin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../pictures/admin logo.png">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
    <style>
        .rounded-box {
            border-radius: 10px;
            box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous"> -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- Main wrapper -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full"
        data-boxed-layout="full">        
        <?php
        include 'header.php';
        ?>
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="page-breadcrumb">
                <div class="row">
                <div class="col-5 align-self-center">
                        <h4 class="page-title">Manage Products</h4>
                    </div>
                </div>
            </div>
            <!-- End Bread crumb and right sidebar toggle -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <h5 class="card-subtitle"> Add a Product </h5>
                            <!-- Add Product Start-->
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
                            <!-- end add product -->
                            <!-- Edit Product -->
                            <!-- List of product Table -->
                            <table class="table table-striped table-hover rounded">
                                <tr>
                                    <th class=""></th>
                                    <th class="">Product Name</th>
                                    <th class="">Category</th>
                                    <th class="">Price</th>
                                    <th class="">Description</th>
                                    <th class="">Stocks</th>
                                    <th class="">Actions</th>
                                </tr>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><img src="../../../uploads/<?php echo $row['product_image']; ?>" width="100px" height="100px"></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td>RM <?php echo $row['product_price']; ?></td>
                                        <td><?php echo $row['product_des']; ?></td>
                                        <td><?php echo $row['stock']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['product_id']; ?>"><i class="fa-solid fa-pen"></i></button>
                                            <br><br>
                                            <a href="admin_product.php?delete=<?php echo $row['product_id']; ?>" onclick="return confirm('Delete this product?')" class="btn btn-danger">
                                            <i class="fa-solid fa-trash"></i></a>
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
                                                            <img src="../../../uploads/<?php echo $row['product_image']; ?>" alt="Existing Image" width="150">
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
                            <!-- End edit product -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <footer class="footer text-center">
                Designed and Developed by
                <a href="">Teoh</a>.
            </footer>
        </div>
    </div>

    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
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