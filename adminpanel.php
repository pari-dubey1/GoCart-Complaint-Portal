<?php
session_start(); 

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adminUsername'])) {
    $username = $_POST['adminUsername'];
    $password = $_POST['adminPassword'];

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['loggedin'] = true;
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: complaint.php"); 
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'get_all_complaints') {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        http_response_code(403); 
        echo json_encode(['error' => 'Not authorized']);
        exit;
    }
    require_once 'db_connect.php';
    $result = $conn->query("SELECT id, orderId, name, type, status, date FROM complaints ORDER BY date DESC");
    $complaints = $result->fetch_all(MYSQLI_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($complaints);
    $conn->close();
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hiddenComplaintId'])) {
     if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        http_response_code(403);
        echo json_encode(['error' => 'Not authorized']);
        exit;
    }
    require_once 'db_connect.php';
    $id = $_POST['hiddenComplaintId'];
    $status = $_POST['updateStatus'];
    $response = $_POST['adminResponse'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ?, response = ?, updated = NOW() WHERE id = ?");
    $stmt->bind_param("sss", $status, $response, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
     header("Location: complaint.php?login=required");
     exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCart - Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="assets/GO CART.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="complaint.php">
             <img src="assets/GO CART.png" alt="GoCart company logo" width="70" height="70" class="d-inline-block align-text-center me-2">GoCart
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="complaint.php">File Complaint</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="checkstatus.php">Check Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="admin-login-link">Admin Login</a>
                </li>
            </ul>
        </div>
    </div>
    </nav>
    
    <section id="admin-section" class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 admin-panel">
                <h2 class="text-center mb-4">Admin Dashboard</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Complaint Management</h4>
                    <a id="logout-btn" class="btn btn-danger btn-sm" href="adminpanel.php?action=logout">Logout</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminComplaintsTable">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div id="loginModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Admin Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="adminUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="adminUsername" name="adminUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="d-grid">
                        <a href="adminpanel.php?action=logout" id="logout-btn" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <div id="adminComplaintModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Complaint #<span id="adminComplaintId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order ID:</strong> <span id="adminOrderId"></span></p>
                            <p><strong>Customer:</strong> <span id="adminCustomerName"></span></p>
                            <p><strong>Email:</strong> <span id="adminCustomerEmail"></span></p>
                            <p><strong>Phone:</strong> <span id="adminCustomerPhone"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date:</strong> <span id="adminDate"></span></p>
                            <p><strong>Type:</strong> <span id="adminType"></span></p>
                            <p><strong>Status:</strong> <span id="adminStatus" class="status-badge">Pending</span></p>
                        </div>
                        <div class="col-12 mt-3">
                            <h6>Complaint Details</h6>
                            <p id="adminDetails"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <form id="adminUpdateForm">
                                <input type="hidden" id="hiddenComplaintId" name="hiddenComplaintId">
                                <div class="mb-3">
                                    <label for="updateStatus" class="form-label">Update Status</label>
                                    <select class="form-select" id="updateStatus" name="updateStatus" required>
                                        <option value="pending">Pending</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="adminResponse" class="form-label">Administrator Response</label>
                                    <textarea class="form-control" id="adminResponse" name="adminResponse" rows="3" placeholder="Enter your response to the customer..."></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Update Complaint</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>GoCart Complaint Portal</h5>
                    <p>We're committed to providing excellent service and resolving your concerns promptly.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="complaint.php" class="text-white">File a Complaint</a></li>
                        <li><a href="checkstatus.php" class="text-white">Check Status</a></li>
                        <li><a href="faqs.php" class="text-white">FAQs</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li>Email: support@gocart.com</li>
                        <li>Phone: 1800-GO-CART</li>
                        <li>Hours: 9AM-6PM, Mon-Fri</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">© 2025 GoCart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="script.js"></script>
</body>
</html>