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
                    <li><a href="#" id="settingsTab">System Settings</a></li>
                    <li><button id="logoutBtn">Logout</button></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-main">
            <section id="adminSection" class="admin-section">
                <h2>Admin Management</h2>
                <form id="addAdminForm">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
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
                    <button type="submit">Add Customer</button>
                </form>
                <div id="customerTableContainer"></div>
            </section>
            <section id="settingsSection" class="admin-section" style="display:none;">
                <h2>System Settings</h2>
                <p>Settings management coming soon...</p>
            </section>
        </main>
    </div>
    <script src="../js/adminDashboard.js"></script>
</body>
</html>