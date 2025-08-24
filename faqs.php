<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCart - FAQs</title>
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

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="text-center mb-5 animate__animated animate__fadeInDown">Frequently Asked Questions</h1>
                
                <div class="accordion" id="faqAccordion">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How can I check my complaint status?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can easily check your complaint status by visiting the "Check Status" page. You will need the <strong>Order ID</strong> from your purchase and the <strong>email address</strong> you used when you filed the complaint.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What do the different complaint statuses mean?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Pending:</strong> We have received your complaint and it is currently in the queue to be reviewed by our support team.</li>
                                    <li><strong>Resolved:</strong> Our team has taken action to resolve your issue. Please check the administrator response in your complaint details for more information.</li>
                                    <li><strong>Rejected:</strong> After review, your complaint was deemed invalid or could not be verified. Please check the administrator response for the reason.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How long does it typically take to resolve a complaint?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our goal is to review and provide an initial response to all complaints within <strong>3-5 business days</strong>. The total time to full resolution may vary depending on the complexity of the issue.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What if I'm not satisfied with the resolution?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                If you are not satisfied with the outcome of your complaint, please contact our support team directly by emailing <a href="mailto:support@gocart.com">support@gocart.com</a> and referencing your original Complaint ID.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

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