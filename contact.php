<?php
session_start();
$conn = new mysqli("localhost", "root", "", "style_alley");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_SESSION['customer_id'] ?? null;
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $customer_id) {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $message = $conn->real_escape_string($_POST['message']);

    if (!empty($customer_name) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO feedback (customer_id, customer_name, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $customer_id, $customer_name, $message);
        if ($stmt->execute()) {
            $success = "✅ Feedback submitted successfully!";
        } else {
            $error = "❌ Error submitting feedback.";
        }
    } else {
        $error = "❌ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Feedback</title>
    <!-- Google Fonts and Styles -->
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }
        .feedback-form {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            resize: vertical;
            font-size: 16px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button[disabled] {
            background: #ccc;
            cursor: not-allowed;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
        }
        .login-reminder {
            color: #d9534f;
            font-size: 15px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="feedback-form">
    <h2>We'd love your feedback!</h2>

    <form method="POST">
        <label for="customer_name">Your Name:</label>
        <input type="text" name="customer_name" id="customer_name" <?= $customer_id ? 'required' : 'disabled' ?>>

        <label for="message">Your Message:</label>
        <textarea name="message" rows="5" <?= $customer_id ? 'required' : 'disabled' ?>></textarea>

        <button type="submit" <?= $customer_id ? '' : 'disabled' ?>>Submit Feedback</button>
    </form>

    <?php if ($success): ?>
        <div class="message" style="color: green;"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message" style="color: red;"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!$customer_id): ?>
        <div class="login-reminder">🔒 Please <a href="login.php">log in</a> to submit feedback.</div>
    <?php endif; ?>
</div>

<!-- Contact Info + Map + Footer -->
<?php include 'footer.php'; ?>
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
