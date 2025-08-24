<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkOrderId']) && isset($_POST['checkEmail'])) {
    require_once 'db_connect.php';

    $orderId = $_POST['checkOrderId'];
    $email = $_POST['checkEmail'];

    $stmt = $conn->prepare("SELECT id, date, type, status FROM complaints WHERE orderId = ? AND email = ?");
    $stmt->bind_param("ss", $orderId, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $complaints = [];
    while($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($complaints);

    $stmt->close();
    $conn->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['details_id'])) {
    require_once 'db_connect.php';

    $complaintId = $_GET['details_id'];
    $stmt = $conn->prepare("SELECT * FROM complaints WHERE id = ?");
    $stmt->bind_param("s", $complaintId);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaint = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($complaint);

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCart - Check Status</title>
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
    <section class="hero-section text-center animate__animated animate__fadeIn">
        <div class="container">
            <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Customer Complaint Portal</h1>
            <p class="lead mb-4 animate__animated animate__fadeInUp">We value your feedback and are committed to resolving your concerns promptly</p>
            <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                <a href="complaint.php" class="btn btn-outline-light btn-lg px-4">File a Complaint</a>
                <a href="#check-status" class="btn btn-light btn-lg px-4">Check Status</a>
            </div>
        </div>
    </section>

    <section id="check-status" class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Check Complaint Status</h3>
                    </div>
                    <div class="card-body">
                        <form id="statusForm">
    
                            <div class="row g-3">
                            <div class="col-md-6">
                                <label for="checkOrderId" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="checkOrderId" name="checkOrderId" required placeholder="Enter your order ID">
                                <div class="invalid-feedback">
                                    Please provide your order ID.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="checkEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="checkEmail" name="checkEmail" required placeholder="Enter your registered email">
                                <div class="invalid-feedback">
                                    Please provide your registered email.
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Check Status</button>
                            </div>
                            </div>
                        </form>    
                        <div id="statusResults" class="mt-4 hidden">
                            <h4 class="mb-4">Your Complaint Details</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Complaint ID</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="statusResultsBody">
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="complaintDetailModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complaint Details #<span id="complaintIdModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                            <p><strong>Date Filed:</strong> <span id="modalDate"></span></p>
                            <p><strong>Complaint Type:</strong> <span id="modalType"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span id="modalStatus" class="status-badge">Pending</span></p>
                            <p><strong>Last Updated:</strong> <span id="modalUpdated"></span></p>
                        </div>
                        <div class="col-12 mt-3">
                            <h6>Complaint Details</h6>
                            <p id="modalDetails"></p>
                        </div>
                        <div class="col-12 mt-3" id="modalResponseContainer">
                            <h6>Administrator Response</h6>
                            <p id="modalResponse">No response yet.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <button id="fab" class="floating-btn btn btn-primary pulse">
        <i class="fas fa-headset"></i>
    </button>

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
                        <li>Phone: 1800-SWIFT-DEL</li>
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