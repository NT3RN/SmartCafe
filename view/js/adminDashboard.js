document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    setupTabNavigation();
    setupFormValidation();
    setupLogout();
}

function setupTabNavigation() {
    let adminTab = document.getElementById("adminTab");
    if (adminTab) {
        adminTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('adminSection');
        });
    }

    let managerTab = document.getElementById("managerTab");
    if (managerTab) {
        managerTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('managerSection');
        });
    }

    let customerTab = document.getElementById("customerTab");
    if (customerTab) {
        customerTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('customerSection');
        });
    }

    let profileTab = document.getElementById("profileTab");
    if (profileTab) {
        profileTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('profileSection');
        });
    }
}

function switchTab(sectionId) {
    document.querySelectorAll('.admin-section').forEach(s => s.style.display = 'none');
    let active = document.getElementById(sectionId);
    if (!active) return;
    active.style.display = 'block';
    
    if (sectionId === 'adminSection'){
        loadAdmins();
    } 
    else if (sectionId === 'managerSection'){
        loadManagers();
    } 
    else if (sectionId === 'customerSection') {
        loadCustomers();
    }
    else if (sectionId === 'profileSection') {
        loadProfile();
    }
}

function setupFormValidation() {
    let adminForm = document.getElementById('addAdminForm');
    if (adminForm) {
        adminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (basicUserFormCheck(this, false)) {
                addAdmin(this);
            }
        });
    }
    
    let managerForm = document.getElementById('addManagerForm');
    if (managerForm) {
        managerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (basicUserFormCheck(this, true)) {
                addManager(this);
            }
        });
    }
    
    let customerForm = document.getElementById('addCustomerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (basicUserFormCheck(this, false)) {
                addCustomer(this);
            }
        });
    }

    let profileForm = document.getElementById('updateProfileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateProfileForm(this)) {
                updateProfile(this);
            }
        });
    }
}

function validateProfileForm(form) {
    let valid = true;
    
    form.querySelectorAll('.error-message').forEach(span => span.textContent = '');

    let username = form.querySelector('input[name="username"]');
    let email = form.querySelector('input[name="email"]');
    let currentPassword = form.querySelector('input[name="current_password"]');
    let newPassword = form.querySelector('input[name="new_password"]');
    let securityQuestion = form.querySelector('select[name="security_question"]');
    let securityAnswer = form.querySelector('input[name="security_answer"]');

    if (!/^[A-Za-z0-9_]{4,}$/.test(username.value.trim())) {
        document.getElementById('profile-username-error').textContent =
            'Username must be at least 4 characters, only letters, numbers, underscores, and no spaces';
        valid = false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        document.getElementById('profile-email-error').textContent =
            'Enter a valid email address';
        valid = false;
    }

    if (!currentPassword.value.trim()) {
        document.getElementById('profile-current-password-error').textContent =
            'Current password is required';
        valid = false;
    }

    if (newPassword.value.trim() && !/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(newPassword.value.trim())) {
        document.getElementById('profile-new-password-error').textContent =
            'New password must be at least 8 characters and include at least one letter and one number';
        valid = false;
    }

    if (!securityQuestion.value.trim()) {
        document.getElementById('profile-sq-error').textContent =
            'Please select a security question';
        valid = false;
    }

    if (!securityAnswer.value.trim()) {
        document.getElementById('profile-sa-error').textContent =
            'Security answer cannot be empty';
        valid = false;
    }

    return valid;
}

function basicUserFormCheck(form, isManager) {
    let valid = true;
    
    form.querySelectorAll('.error-message').forEach(span => span.textContent = '');

    let username = form.querySelector('input[name="username"]');
    let email = form.querySelector('input[name="email"]');
    let password = form.querySelector('input[name="password"]');
    let securityQuestion = form.querySelector('select[name="security_question"]');
    let securityAnswer = form.querySelector('input[name="security_answer"]');

    let formType = 'admin';
    if (form.id === 'addManagerForm') formType = 'manager';
    else if (form.id === 'addCustomerForm') formType = 'customer';

    if (!/^[A-Za-z0-9_]{4,}$/.test(username.value.trim())) {
        document.getElementById(`${formType}-username-error`).textContent =
            'Username must be at least 4 characters, only letters, numbers, underscores, and no spaces';
        valid = false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        document.getElementById(`${formType}-email-error`).textContent =
            'Enter a valid email address';
        valid = false;
    }

    if (!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password.value.trim())) {
        document.getElementById(`${formType}-password-error`).textContent =
            'Password must be at least 8 characters and include at least one letter and one number';
        valid = false;
    }

    if (!securityQuestion.value.trim()) {
        document.getElementById(`${formType}-sq-error`).textContent =
            'Please select a security question';
        valid = false;
    }

    if (!securityAnswer.value.trim()) {
        document.getElementById(`${formType}-sa-error`).textContent =
            'Security answer cannot be empty';
        valid = false;
    }

    if (isManager) {
        let salary = form.querySelector('input[name="salary"]');
        if (!salary.value.trim() || parseFloat(salary.value) <= 0) {
            document.getElementById('manager-salary-error').textContent =
                'Salary must be greater than 0';
            valid = false;
        }
    }

    return valid;
}

function setupLogout() {
    let logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        location.href = '../../controller/logout.php';
    }
}

function loadProfile() {
    fetch('../../controller/adminController.php?action=profile')
        .then(response => response.json())
        .then(profile => {
            populateProfile(profile);
        })
        .catch(error => {
            console.error('Error loading profile:', error);
        });
}

function populateProfile(profile) {
    document.getElementById('profile-user-id').textContent = profile.user_id;
    document.getElementById('profile-username-display').textContent = profile.username;
    document.getElementById('profile-email-display').textContent = profile.email;
    document.getElementById('profile-created-at').textContent = new Date(profile.created_at).toLocaleDateString();

    document.getElementById('profile-username').value = profile.username;
    document.getElementById('profile-email').value = profile.email;
    document.getElementById('profile-security-question').value = profile.security_question;
    document.getElementById('profile-security-answer').value = profile.security_answer;
}

function updateProfile(form) {
    let formData = new FormData(form);
    let data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    const params = new URLSearchParams(data).toString();
    
    fetch('../../controller/adminController.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            form.querySelectorAll('.error-message').forEach(span => span.textContent = '');
            form.querySelector('input[name="current_password"]').value = '';
            form.querySelector('input[name="new_password"]').value = '';
            alert('Profile updated successfully');
            loadProfile();
        } else {
            alert(data.error || 'Failed to update profile');
        }
    })
    .catch(error => {
        alert('Error updating profile');
    });
}

function loadAdmins() {
    fetch('../../controller/adminController.php')
        .then(response => response.json())
        .then(admins => {
            displayAdmins(admins);
        })
        .catch(error => {
            document.getElementById('adminTableContainer').innerHTML = 
                '<p style="color: red;">Error loading admins</p>';
        });
}

function displayAdmins(admins) {
    let html = '';
    
    if (admins.length === 0) {
        html = '<p>No admins found</p>';
    } else {
        html = `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        admins.forEach(admin => {
            html += `
                <tr>
                    <td>${admin.user_id}</td>
                    <td>${admin.username}</td>
                    <td>${admin.email}</td>
                    <td>${admin.created_at}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteAdmin(${admin.user_id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                </tbody>
            </table>
        `;
    }
    
    document.getElementById('adminTableContainer').innerHTML = html;
}

function addAdmin(form) {
    let formData = new FormData(form);
    
    fetch('../../controller/adminController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            form.reset();
            form.querySelectorAll('.error-message').forEach(span => span.textContent = '');
            loadAdmins();
            alert('Admin added successfully');
        } else {
            alert(data.error || 'Failed to add admin');
        }
    })
    .catch(error => {
        alert('Error adding admin');
    });
}

function deleteAdmin(userId) {
    if (!confirm('Are you sure you want to delete this admin?')) {
        return;
    }
    
    fetch('../../controller/adminController.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadAdmins();
            alert('Admin deleted successfully');
        } else {
            alert(data.error || 'Failed to delete admin');
        }
    })
    .catch(error => {
        alert('Error deleting admin');
    });
}


function loadManagers() {
    fetch('../../controller/managerController.php')
        .then(response => response.json())
        .then(managers => {
            displayManagers(managers);
        })
        .catch(error => {
            document.getElementById('managerTableContainer').innerHTML = 
                '<p style="color: red;">Error loading managers</p>';
        });
}

function displayManagers(managers) {
    let html = '';
    
    if (managers.length === 0) {
        html = '<p>No managers found</p>';
    } else {
        html = `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Salary</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        managers.forEach(manager => {
            html += `
                <tr>
                    <td>${manager.user_id}</td>
                    <td>${manager.username}</td>
                    <td>${manager.email}</td>
                    <td>$${parseFloat(manager.salary).toFixed(2)}</td>
                    <td>${manager.created_at}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteManager(${manager.user_id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                </tbody>
            </table>
        `;
    }
    
    document.getElementById('managerTableContainer').innerHTML = html;
}

function addManager(form) {
    let formData = new FormData(form);
    
    fetch('../../controller/managerController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            form.reset();
            form.querySelectorAll('.error-message').forEach(span => span.textContent = '');
            loadManagers();
            alert('Manager added successfully');
        } else {
            alert(data.error || 'Failed to add manager');
        }
    })
    .catch(error => {
        alert('Error adding manager');
    });
}

function deleteManager(userId) {
    if (!confirm('Are you sure you want to delete this manager?')) {
        return;
    }
    
    fetch('../../controller/managerController.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadManagers();
            alert('Manager deleted successfully');
        } else {
            alert(data.error || 'Failed to delete manager');
        }
    })
    .catch(error => {
        alert('Error deleting manager');
    });
}


function loadCustomers() {
    fetch('../../controller/customerController.php')
        .then(response => response.json())
        .then(customers => {
            displayCustomers(customers);
        })
        .catch(error => {
            document.getElementById('customerTableContainer').innerHTML = 
                '<p style="color: red;">Error loading customers</p>';
        });
}

function displayCustomers(customers) {
    let html = '';
    
    if (customers.length === 0) {
        html = '<p>No customers found</p>';
    } else {
        html = `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        customers.forEach(customer => {
            html += `
                <tr>
                    <td>${customer.user_id}</td>
                    <td>${customer.username}</td>
                    <td>${customer.email}</td>
                    <td>${customer.created_at}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteCustomer(${customer.user_id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                </tbody>
            </table>
        `;
    }
    
    document.getElementById('customerTableContainer').innerHTML = html;
}

function addCustomer(form) {
    let formData = new FormData(form);
    
    fetch('../../controller/customerController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            form.reset();
            form.querySelectorAll('.error-message').forEach(span => span.textContent = '');
            loadCustomers();
            alert('Customer added successfully');
        } else {
            alert(data.error || 'Failed to add customer');
        }
    })
    .catch(error => {
        alert('Error adding customer');
    });
}

function deleteCustomer(userId) {
    if (!confirm('Are you sure you want to delete this customer?')) {
        return;
    }
    
    fetch('../../controller/customerController.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCustomers();
            alert('Customer deleted successfully');
        } else {
            alert(data.error || 'Failed to delete customer');
        }
    })
    .catch(error => {
        alert('Error deleting customer');
    });
}