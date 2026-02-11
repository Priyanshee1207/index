<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM user_reg";
$totalUsersResult = $conn->query($totalUsersQuery);
if (!$totalUsersResult) {
    die("Error in totalUsersQuery: " . $conn->error);
}
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

// Fetch total and confirmed bookings
$bookingsQuery = "SELECT COUNT(*) as total_bookings, SUM(status = 'Confirmed') as confirmed_bookings FROM bookingtbl";
$bookingsResult = $conn->query($bookingsQuery);
if (!$bookingsResult) {
    die("Error in bookingsQuery: " . $conn->error);
}
$bookings = $bookingsResult->fetch_assoc();

// Fetch total packages
$totalPackagesQuery = "SELECT COUNT(*) as total_packages FROM packagetbl";
$totalPackagesResult = $conn->query($totalPackagesQuery);
if (!$totalPackagesResult) {
    die("Error in totalPackagesQuery: " . $conn->error);
}
$totalPackages = $totalPackagesResult->fetch_assoc()['total_packages'];

// Fetch total feedback
$totalFeedbackQuery = "SELECT COUNT(*) as total_feedback FROM feedback";
$totalFeedbackResult = $conn->query($totalFeedbackQuery);
if (!$totalFeedbackResult) {
    die("Error in totalFeedbackQuery: " . $conn->error);
}
$totalFeedback = $totalFeedbackResult->fetch_assoc()['total_feedback'];

// Fetch total revenue
$totalRevenueQuery = "SELECT SUM(payment_amount) as total_revenue FROM paymenttbl WHERE status = 'Successful'";
$totalRevenueResult = $conn->query($totalRevenueQuery);

if (!$totalRevenueResult) {
    die("Error in totalRevenueQuery: " . $conn->error);
}

// Fetch the result, default to 0 if null (e.g., no successful payments)
$totalRevenue = $totalRevenueResult->fetch_assoc()['total_revenue'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tourism Management Dashboard</title>
    <style>
        
         /* Sidebar styling */
         .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar h4 {
            color: #f8f9fa;
            padding-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            display: block;
            transition: background-color 0.2s ease-in-out;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #495057;
            border-left: 4px solid #ffc107;
        }

        .main-content {
            margin-left: 220px; /* Sidebar width */
            padding: 40px;
            background-color: #f5f6fa;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            text-align: center;
            padding: 50px;
        }

        .dashboard-header {
            margin-bottom: 40px;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #343a40;
        }

        .revenue-boxes {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box {
            width: 200px;
            padding: 20px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .box h2 {
            font-size: 2rem;
            margin: 0;
        }

        .box p {
            margin: 5px 0 0;
            font-size: 1rem;
        }

        /* Different box colors */
        .box.users { background-color: #007bff; }
        .box.bookings { background-color: #28a745; }
        .box.confirmed { background-color: #ffc107; }
        .box.packages { background-color: #17a2b8; }
        .box.feedback { background-color: #6c757d; }
        .box.revenue { background-color: #dc3545; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <h4 class="text-white text-center">Admin Dashboard</h4>
            <a href="admin.php" id="manageUsersLink">Main Panel</a>
            <a href="manage_user.php" id="manageUsersLink">Manage Users</a>
            <a href="manage_packages.php" id="managePackagesLink">Manage Packages</a>
            <a href="manage_booking.php" id="managebookingslink">manage bookings</a>
            <a href="manage_payment.php" id="managepaymentlink">manage payment</a>
            <a href="manage_feedback.php" id="managefeedbacklink">manage feedback</a>
            <a href="admin_logout.php" id="logoutLink">Logout</a>
        </div>

    <div class="container">
        <div class="dashboard-header">
            <h1>Tourism Management Dashboard</h1>
        </div>
        <div class="revenue-boxes">
            <div class="box users">
                <h2><?php echo $totalUsers; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="box bookings">
                <h2><?php echo $bookings['total_bookings']; ?></h2>
                <p>Total Bookings</p>
            </div>
            <div class="box confirmed">
                <h2><?php echo $bookings['confirmed_bookings']; ?></h2>
                <p>Confirmed Bookings</p>
            </div>
            <div class="box packages">
                <h2><?php echo $totalPackages; ?></h2>
                <p>Total Packages</p>
            </div>
            <div class="box feedback">
                <h2><?php echo $totalFeedback; ?></h2>
                <p>Total Feedback</p>
            </div>
            <div class="box revenue">
                <h2>INR <?php echo number_format($totalRevenue, 2); ?></h2>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>
</body>
</html>
