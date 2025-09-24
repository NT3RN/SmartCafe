<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location:../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SmartCafe Admin Dashboard</title>
    <link rel="stylesheet" href="../css/adminDashboard.css"/>
    <link rel="icon" href="../../assets/logo.png"/>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../../assets/logo.png" alt="SmartCafe Logo" class="logo">
                <span>Smart Cafe Admin</span>
            </div>
            <nav>
                <ul>
                    <li><a href="#" id="adminTab">Admins</a></li>
                    <li><a href="#" id="managerTab">Managers</a></li>
                    <li><a href="#" id="customerTab">Customers</a></li>
                    <li><a href="#" id="profileTab">My Profile</a></li>
                    <li><a href="#" id="salesTab">Sales Report</a></li>
                    <li><button id="logoutBtn">Logout</button></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-main">
            <section id="adminSection" class="admin-section">
                <h2>Admin Management</h2>
                <form id="addAdminForm">
                    <input type="text" name="name" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="security_question" id="security_question" required>
                        <option name="security" value="What is your mother’s maiden name?">What is your mother’s maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    
                    <button type="submit">Add Admin</button>
                </form>
                <div id="adminTableContainer"></div>
            </section>
            <section id="managerSection" class="admin-section" style="display:none;">
                <h2>Manager Management</h2>
                <form id="addManagerForm">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="security_question" id="security_question" required>
                        <option name="security" value="What is your mother’s maiden name?">What is your mother’s maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <input type="number" name="salary" placeholder="Salary" required>
                    <button type="submit">Add Manager</button>
                </form>
                <div id="managerTableContainer"></div>
            </section>
            <section id="customerSection" class="admin-section" style="display:none;">
                <h2>Customer Management</h2>
                <form id="addCustomerForm">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="security_question" id="security_question" required>
                        <option name="security" value="What is your mother’s maiden name?">What is your mother’s maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <button type="submit">Add Customer</button>
                </form>
                <div id="customerTableContainer"></div>
            </section>
            <section id="profileSection" class="admin-section" style="display:none;">
                <h2>My Profile</h2>
                <div id="profileContainer"></div>
            </section>
            <section id="salesSection" class="admin-section" style="display:none;">
                <h2>Total Sales Report</h2>
                <div id="salesReportContainer"></div>
            </section>
        </main>
    </div>
    <script src="../js/adminDashboard.js"></script>
</body>
</html>