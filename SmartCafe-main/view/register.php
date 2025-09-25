<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/login.css">
    <title>Register - SmartCafe</title>
</head>
<body>
<div id="loginDiv">
    <form method="post" action="../controller/registerController.php">
        <h1>Create your Smart Cafe account</h1>

        <?php if (isset($_GET["formErr"])): ?>
            <div style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_GET["formErr"]); ?>
            </div>
        <?php endif; ?>

        <div class="input-group">
            <input type="text" name="username" id="username" placeholder="Username" autocomplete="off"
                   value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
            <span style="color:red;">
                <?php if (isset($_GET["nameErr"])) echo htmlspecialchars($_GET["nameErr"]); ?>
            </span>
        </div>

        <div class="input-group">
            <input type="email" name="email" id="email" placeholder="Email address" autocomplete="off"
                   value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
            <span style="color:red;">
                <?php if (isset($_GET["emailErr"])) echo htmlspecialchars($_GET["emailErr"]); ?>
            </span>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="off">
            <span style="color:red;">
                <?php if (isset($_GET["passErr"])) echo htmlspecialchars($_GET["passErr"]); ?>
            </span>
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" autocomplete="off">
            <span style="color:red;">
                <?php if (isset($_GET["cpassErr"])) echo htmlspecialchars($_GET["cpassErr"]); ?>
            </span>
        </div>

        <div class="input-group">
            <input type="text" name="security_question" id="security_question" placeholder="Security question (e.g, Your first pet?)" autocomplete="off"
                   value="<?php echo isset($_GET['sq']) ? htmlspecialchars($_GET['sq']) : '' ?>">
            <span style="color:red;">
                <?php if (isset($_GET["sqErr"])) echo htmlspecialchars($_GET["sqErr"]); ?>
            </span>
        </div>

        <div class="input-group">
            <input type="text" name="security_answer" id="security_answer" placeholder="Security answer" autocomplete="off"
                   value="<?php echo isset($_GET['sa']) ? htmlspecialchars($_GET['sa']) : '' ?>">
            <span style="color:red;">
                <?php if (isset($_GET["saErr"])) echo htmlspecialchars($_GET["saErr"]); ?>
            </span>
        </div>

        <input type="submit" name="submit" value="Create account">

        <div style="margin-top:12px;">
            <a href="login.php">You already have an account? Login</a>
        </div>
    </form>
</div>
</body>
</html>
