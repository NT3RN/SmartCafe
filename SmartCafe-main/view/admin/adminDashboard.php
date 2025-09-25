<?php
session_start();
if ((isset($_SESSION["email"])) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] === "Admin") {
    } else {
        header("Location:../login.php");
    }
} else {
    header("Location:../login.php");
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
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/logo.png" alt="SmartCafe Logo" class="logo">
            <span>SmartCafe</span>
        </div>
        <nav class="admin-navlist">
        <ul>
            <li><button>Overview</li>
            <li><button>Orders</li>
            <li><button>Products</li>
            <li><button>Categories</li>
    </nav>
    </aside>
    <div class="main">
        <header>
            <h1 id="pageTitle">Admin Dashboard</h1>
            <div>
                <span= id="adminUsername">Admin</span>
                <button id="logoutBtn">Logout</button>
            </div>
        </header>
        <main id="mainContent">

        </main>
    </div>
</body>

</html>