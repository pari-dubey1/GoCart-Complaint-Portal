document.addEventListener('DOMContentLoaded', function() {
    const loginModalEl = document.getElementById('loginModal');
    const loginModal = loginModalEl ? new bootstrap.Modal(loginModalEl) : null;

    const successModalEl = document.getElementById('successModal');
    const successModal = successModalEl ? new bootstrap.Modal(successModalEl) : null;

    const complaintDetailModalEl = document.getElementById('complaintDetailModal');
    const complaintDetailModal = complaintDetailModalEl ? new bootstrap.Modal(complaintDetailModalEl) : null;

    const adminComplaintModalEl = document.getElementById('adminComplaintModal');
    const adminComplaintModal = adminComplaintModalEl ? new bootstrap.Modal(adminComplaintModalEl) : null;


   
    const complaintForm = document.getElementById('complaintForm');
    if (complaintForm) {
        complaintForm.addEventListener('submit', handleComplaintSubmit);
    }

    
    const statusForm = document.getElementById('statusForm');
    if (statusForm) {
        statusForm.addEventListener('submit', handleStatusCheck);
    }

    
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleAdminLogin);
    }

   
    const adminLoginLink = document.getElementById('admin-login-link');
    if (adminLoginLink) {
        adminLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (loginModal) loginModal.show();
        });
    }

    
    const adminUpdateForm = document.getElementById('adminUpdateForm');
    if (adminUpdateForm) {
        adminUpdateForm.addEventListener('submit', handleAdminUpdate);
    }

   
    if (window.location.pathname.endsWith('adminpanel.php')) {
        renderAdminComplaintsTable();
    }
});


async function handleComplaintSubmit(e) {
    e.preventDefault();
    const form = e.target;
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData(form);
    const response = await fetch('complaint.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();

    if (result.success) {
        document.getElementById('successComplaintId').textContent = result.complaintId;
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        form.reset();
        form.classList.remove('was-validated');
    } else {
        alert('Error: ' + result.message);
    }
}


async function handleStatusCheck(e) {
    e.preventDefault();
    const form = e.target;
     if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData(form);
    const response = await fetch('checkstatus.php', {
        method: 'POST',
        body: formData
    });
    const complaints = await response.json();

    const statusResults = document.getElementById('statusResults');
    const statusResultsBody = document.getElementById('statusResultsBody');

    if (complaints.length > 0) {
        renderStatusResults(complaints);
        statusResults.classList.remove('hidden');
    } else {
        alert('No complaints found for that Order ID and Email.');
        statusResults.classList.add('hidden');
    }
}


function renderStatusResults(complaints) {
    const statusResultsBody = document.getElementById('statusResultsBody');
    if (!statusResultsBody) return;
    statusResultsBody.innerHTML = '';

    complaints.forEach(complaint => {
        const row = document.createElement('tr');
        const formattedDate = new Date(complaint.date).toLocaleDateString();
        const statusBadge = `<span class="status-badge status-${complaint.status}">${complaint.status}</span>`;

        row.innerHTML = `
            <td>${complaint.id}</td>
            <td>${formattedDate}</td>
            <td>${complaint.type.charAt(0).toUpperCase() + complaint.type.slice(1)}</td>
            <td>${statusBadge}</td>
            <td><button class="btn btn-sm btn-outline-primary view-details" data-id="${complaint.id}">View</button></td>
        `;
        statusResultsBody.appendChild(row);
    });

    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            showComplaintDetails(this.getAttribute('data-id'), false);
        });
    });
}


async function showComplaintDetails(complaintId, isAdmin) {
    const response = await fetch(`checkstatus.php?details_id=${complaintId}`);
    const complaint = await response.json();
    if (!complaint) {
        alert('Complaint not found.');
        return;
    }

    const modalElement = isAdmin ? document.getElementById('adminComplaintModal') : document.getElementById('complaintDetailModal');
    const targetModal = new bootstrap.Modal(modalElement);

    if (isAdmin) {
        document.getElementById('adminComplaintId').textContent = complaint.id;
        document.getElementById('adminOrderId').textContent = complaint.orderId;
        document.getElementById('adminCustomerName').textContent = complaint.name;
        document.getElementById('adminCustomerEmail').textContent = complaint.email;
        document.getElementById('adminCustomerPhone').textContent = complaint.phone;
        document.getElementById('adminDate').textContent = new Date(complaint.date).toLocaleString();
        document.getElementById('adminType').textContent = complaint.type.charAt(0).toUpperCase() + complaint.type.slice(1);
        document.getElementById('adminDetails').textContent = complaint.details;
        const statusBadge = document.getElementById('adminStatus');
        statusBadge.className = `status-badge status-${complaint.status}`;
        statusBadge.textContent = complaint.status;
        document.getElementById('hiddenComplaintId').value = complaint.id;
        document.getElementById('updateStatus').value = complaint.status;
        document.getElementById('adminResponse').value = complaint.response || '';
    } else {
        document.getElementById('complaintIdModal').textContent = complaint.id;
        document.getElementById('modalOrderId').textContent = complaint.orderId;
        document.getElementById('modalDate').textContent = new Date(complaint.date).toLocaleString();
        document.getElementById('modalType').textContent = complaint.type.charAt(0).toUpperCase() + complaint.type.slice(1);
        document.getElementById('modalDetails').textContent = complaint.details;
        const statusBadge = document.getElementById('modalStatus');
        statusBadge.className = `status-badge status-${complaint.status}`;
        statusBadge.textContent = complaint.status;
        document.getElementById('modalUpdated').textContent = complaint.updated ? new Date(complaint.updated).toLocaleString() : 'Not updated yet';
        document.getElementById('modalResponse').textContent = complaint.response || 'No response yet.';
    }
    targetModal.show();
}


async function handleAdminLogin(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const response = await fetch('adminpanel.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();

    if (result.success) {
        window.location.href = 'adminpanel.php';
    } else {
        alert('Invalid username or password.');
    }
}


async function renderAdminComplaintsTable() {
    const adminComplaintsTable = document.getElementById('adminComplaintsTable');
    if (!adminComplaintsTable) return;

    const response = await fetch('adminpanel.php?action=get_all_complaints');
    if(response.status === 403) {
        window.location.href = 'complaint.php?login=required';
        return;
    }
    const complaints = await response.json();

    adminComplaintsTable.innerHTML = '';
    complaints.forEach(complaint => {
        const row = document.createElement('tr');
        const formattedDate = new Date(complaint.date).toLocaleDateString();
        const statusBadge = `<span class="status-badge status-${complaint.status}">${complaint.status}</span>`;

        row.innerHTML = `
            <td>${complaint.id}</td>
            <td>${complaint.orderId}</td>
            <td>${complaint.name}</td>
            <td>${complaint.type}</td>
            <td>${statusBadge}</td>
            <td>${formattedDate}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary edit-complaint" data-id="${complaint.id}">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </td>
        `;
        adminComplaintsTable.appendChild(row);
    });

    document.querySelectorAll('.edit-complaint').forEach(button => {
        button.addEventListener('click', function() {
            showComplaintDetails(this.getAttribute('data-id'), true);
        });
    });
}


async function handleAdminUpdate(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const response = await fetch('adminpanel.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();

    if (result.success) {
        alert('Complaint updated successfully!');
        const adminComplaintModal = bootstrap.Modal.getInstance(document.getElementById('adminComplaintModal'));
        adminComplaintModal.hide();
        renderAdminComplaintsTable(); 
    } else {
        alert('Error updating complaint.');
    }
}