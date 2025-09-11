<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <?php
    $loginError = '';
    if (isset($_POST['submit'])) {
        $errors = [];

        if (empty($_POST['email'])) {
            $errors[] = "Email is required";
        }
        if (empty($_POST['password'])) {
            $errors[] = "Password is required";
        }

        if (empty($errors)) {
            require_once('../model/dbConnect.php');

            $email = mysqli_real_escape_string($con, $_POST['email']);
            $password = mysqli_real_escape_string($con, $_POST['password']);

            $sql = "SELECT u.*, 
                        CASE 
                            WHEN c.customer_id IS NOT NULL THEN c.customer_id
                            WHEN m.manager_id IS NOT NULL THEN m.manager_id
                            WHEN a.admin_id IS NOT NULL THEN a.admin_id
                        END as role_specific_id
                        FROM Users u
                        LEFT JOIN Customers c ON u.user_id = c.user_id
                        LEFT JOIN Managers m ON u.user_id = m.user_id
                        LEFT JOIN Admins a ON u.user_id = a.user_id
                        WHERE u.email = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['role_specific_id'] = $user['role_specific_id'];

                    switch ($user['role']) {
                        case 'Admin':
                            header('Location: admin/adminDashboard.php');
                            break;
                        case 'Customer':
                            header('Location: customer/customerDashboard.php');
                            break;
                        case 'Manager':
                            header('Location: manager/managerDashboard.php');
                            break;
                        default:
                            echo "<p class='error'>Invalid user role!</p>";
                    }
                    exit();
                } else {
                    $loginError = "Invalid email or password!";
                }
            } else {
                $loginError = "Invalid email or password!";
            }

            $stmt->close();
            $con->close();
        }
    }
    ?>
    <div id="loginDiv">
        <form method="post" action="">
            <h1>Login to Smart Cafe</h1>
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter your email" required>
                <?php if (isset($_POST['submit']) && empty($_POST['email'])): ?>
                    <span class="error">Email is required</span>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
                <?php if (isset($_POST['submit']) && empty($_POST['password'])): ?>
                    <span class="error">Password is required</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($loginError)): ?>
                <div class="input-group">
                    <span class="error"><?php echo $loginError; ?></span>
                </div>
            <?php endif; ?>

            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</body>

</html>