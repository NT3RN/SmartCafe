<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/login.css">
    <title>Register - SmartCafe</title>
</head>
<body>
<?php
// ==============================
// Precompute safe values (no ?:)
// ==============================
$formErr   = '';
$nameErr   = '';
$emailErr  = '';
$passErr   = '';
$cpassErr  = '';
$sqErr     = '';
$saErr     = '';

$usernameVal = '';
$emailVal    = '';
$selectedSQ  = '';
$saVal       = '';

if (isset($_GET["formErr"])) { $formErr = htmlspecialchars($_GET["formErr"]); }
if (isset($_GET["nameErr"])) { $nameErr = htmlspecialchars($_GET["nameErr"]); }
if (isset($_GET["emailErr"])) { $emailErr = htmlspecialchars($_GET["emailErr"]); }
if (isset($_GET["passErr"])) { $passErr = htmlspecialchars($_GET["passErr"]); }
if (isset($_GET["cpassErr"])) { $cpassErr = htmlspecialchars($_GET["cpassErr"]); }
if (isset($_GET["sqErr"])) { $sqErr = htmlspecialchars($_GET["sqErr"]); }
if (isset($_GET["saErr"])) { $saErr = htmlspecialchars($_GET["saErr"]); }

if (isset($_GET['username'])) { $usernameVal = htmlspecialchars($_GET['username']); }
if (isset($_GET['email']))    { $emailVal    = htmlspecialchars($_GET['email']); }
if (isset($_GET['sq']))       { $selectedSQ  = $_GET['sq']; }
if (isset($_GET['sa']))       { $saVal       = htmlspecialchars($_GET['sa']); }

// Security questions list
$questions = array(
    "What is your mother’s maiden name?",
    "What was the name of your first pet?",
    "What city were you born in?"
);
?>
<div id="loginDiv">
    <form method="post" action="../controller/registerController.php">
        <h1>Create your Smart Cafe account</h1>

        <?php if ($formErr !== ''): ?>
            <div style="color:red; margin-bottom:10px;">
                <?php echo $formErr; ?>
            </div>
        <?php endif; ?>

        <div class="input-group">
            <input
                type="text"
                name="username"
                id="username"
                placeholder="Username"
                autocomplete="off"
                value="<?php echo $usernameVal; ?>"
            >
            <span style="color:red;">
                <?php
                if ($nameErr !== '') {
                    echo $nameErr;
                }
                ?>
            </span>
        </div>

        <div class="input-group">
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Email address"
                autocomplete="off"
                value="<?php echo $emailVal; ?>"
            >
            <span style="color:red;">
                <?php
                if ($emailErr !== '') {
                    echo $emailErr;
                }
                ?>
            </span>
        </div>

        <div class="input-group">
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Password"
                autocomplete="off"
            >
            <span style="color:red;">
                <?php
                if ($passErr !== '') {
                    echo $passErr;
                }
                ?>
            </span>
        </div>

        <div class="input-group">
            <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                placeholder="Confirm password"
                autocomplete="off"
            >
            <span style="color:red;">
                <?php
                if ($cpassErr !== '') {
                    echo $cpassErr;
                }
                ?>
            </span>
        </div>

        <div class="input-group">
            <select name="security_question" id="security_question">
                <option value="">-- Select a security question --</option>
                <?php
                for ($i = 0; $i < count($questions); $i++) {
                    $q = $questions[$i];
                    $qEsc = htmlspecialchars($q);
                    $isSelected = false;
                    if ($selectedSQ === $q) {
                        $isSelected = true;
                    }

                    if ($isSelected) {
                        echo '<option value="' . $qEsc . '" selected>' . $qEsc . '</option>';
                    } else {
                        echo '<option value="' . $qEsc . '">' . $qEsc . '</option>';
                    }
                }
                ?>
            </select>
            <span style="color:red;">
                <?php
                if ($sqErr !== '') {
                    echo $sqErr;
                }
                ?>
            </span>
        </div>

        <div class="input-group">
            <input
                type="text"
                name="security_answer"
                id="security_answer"
                placeholder="Security answer"
                autocomplete="off"
                value="<?php echo $saVal; ?>"
            >
            <span style="color:red;">
                <?php
                if ($saErr !== '') {
                    echo $saErr;
                }
                ?>
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
