<?php  
session_start(); // Required for session checking

$conn = new mysqli("localhost", "root", "", "style_alley");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMsg = "";

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    $product_sql = "SELECT * FROM products WHERE product_id = $product_id";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();

        $stmt = $conn->prepare("INSERT INTO cart (product_id, product_name, product_price, image_name, image_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isdss",
            $product['product_id'],
            $product['product_name'],
            $product['product_price'],
            $product['image_name'],
            $product['image_data']
        );
        if ($stmt->execute()) {
            $successMsg = "Product added to cart successfully!";
        } else {
            $successMsg = "Failed to add product to cart.";
        }
    }
}

$category_id = 3;
$sql = "SELECT * FROM products WHERE category_id = $category_id ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sweatshirt</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

<!-- Css Styles -->
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="css/nice-select.css" type="text/css">
<link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
<link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 30px 0;
        }

        .message {
            background: #d1e7dd;
            color: #0f5132;
            text-align: center;
            padding: 10px;
            margin: 20px auto;
            width: 80%;
            border-radius: 5px;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            padding: 30px;
        }

        .product-card {
            background: white;
            width: 250px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h4 {
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .product-info p {
            font-size: 16px;
            color: #e53935;
            font-weight: bold;
        }

        .product-buttons form {
            margin-top: 10px;
        }

        .product-buttons button,
        .product-buttons a {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .add-cart {
            background: #28a745;
            color: white;
        }

        .add-cart:hover {
            background: #218838;
        }

        .order-now {
            background: #007bff;
            color: white;
        }

        .order-now:hover {
            background: #0069d9;
        }

        .login-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffeeba;
            color: #856404;
            padding: 25px 40px;
            border-radius: 12px;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: none;
            z-index: 9999;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<?php if ($successMsg): ?>
    <div class="message"><?php echo $successMsg; ?></div>
<?php endif; ?>

<div class="login-message" id="loginMessage">
    Please log in to place an order.<br>Redirecting to login page...
</div>

<h2>SWEATSHIRT - Collection</h2>

<div class="product-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_data']); ?>" alt="Product Image">
                <div class="product-info">
                    <h4><?php echo htmlspecialchars($row['product_name']); ?></h4>
                    <p>₹<?php echo number_format($row['product_price'], 2); ?></p>
                    <div class="product-buttons">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button class="add-cart" type="submit" name="add_to_cart">Add to Cart</button>
                        </form>

                        <?php if (isset($_SESSION['customer_id'])): ?>
                            <a class="order-now" href="order.php?product_id=<?php echo $row['product_id']; ?>">Order</a>
                        <?php else: ?>
                            <button class="order-now" onclick="showLoginMessage()">Order</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found in this category.</p>
    <?php endif; ?>
</div>

<script>
    function showLoginMessage() {
        const msg = document.getElementById("loginMessage");
        msg.style.display = "block";
        setTimeout(() => {
            window.location.href = "login.php";
        }, 3000);
    }
</script>

<?php include 'footer.php'; ?>

<!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/mixitup.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>

<?php $conn->close(); ?>
