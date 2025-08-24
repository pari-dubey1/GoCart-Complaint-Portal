<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); 

    require_once 'db_connect.php'; 

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    try {
        $complaintId = 'COM-' . strtoupper(bin2hex(random_bytes(6)));
    } catch (Exception $e) {
        $complaintId = 'COM-' . time();
    }

    $orderId = $_POST['orderId'];
    $orderDate = $_POST['orderDate'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $complaintType = $_POST['complaintType'];
    $complaintDetails = $_POST['complaintDetails'];

    $stmt = $conn->prepare("INSERT INTO complaints (id, orderId, orderDate, name, email, phone, type, details, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $stmt->bind_param("ssssssss", $complaintId, $orderId, $orderDate, $name, $email, $phone, $complaintType, $complaintDetails);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'complaintId' => $complaintId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting complaint: ' . $stmt->error]);
    }

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
    <title>GoCart - File a Complaint</title>
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
            <p class="lead mb-4 animate__animated animate__fadeInUp">We value your feedback and are committed to resolving your concerns promptly.</p>
            <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                <a href="#complaint-form" class="btn btn-light btn-lg px-4">File a Complaint</a>
                <a href="checkstatus.php" class="btn btn-outline-light btn-lg px-4">Check Status</a>
            </div>
        </div>
    </section>

    <section id="complaint-form" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">File a Complaint</h3>
                </div>
                <div class="card-body">
                    <form id="complaintForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="orderId" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="orderId" name="orderId" required placeholder="e.g., SW12345678">
                                <div class="invalid-feedback">
                                    Please provide your order ID.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="orderDate" class="form-label">Order Date</label>
                                <input type="date" class="form-control" id="orderDate" name="orderDate" required>
                                <div class="invalid-feedback">
                                    Please select the order date.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">
                                    Please provide your full name.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback">
                                    Please provide a valid phone number.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="complaintType" class="form-label">Complaint Type</label>
                                <select class="form-select" id="complaintType" name="complaintType" required>
                                    <option value="" selected disabled>Select complaint type</option>
                                    <option value="delivery">Delivery Issues</option>
                                    <option value="damage">Damaged Goods</option>
                                    <option value="missing">Missing Items</option>
                                    <option value="wrong">Wrong Items</option>
                                    <option value="service">Customer Service</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a complaint type.
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="complaintDetails" class="form-label">Complaint Details</label>
                                <textarea class="form-control" id="complaintDetails" name="complaintDetails" rows="4" required placeholder="Please provide detailed information about your complaint..."></textarea>
                                <div class="invalid-feedback">
                                    Please provide details about your complaint.
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree" required>
                                    <label class="form-check-label" for="agree">
                                        I confirm that the information provided is accurate
                                    </label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Submit Complaint</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 id="successMessage">Your complaint has been submitted successfully!</h4>
                    <p class="text-muted">Complaint ID: <span id="successComplaintId"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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