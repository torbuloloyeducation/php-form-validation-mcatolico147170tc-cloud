<?php
// Initialize variables for form fields and errors
$name = $email = $gender = $phone = $website = $password = $confirm_password = "";
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = $passwordErr = $confirmErr = $termsErr = "";
$success = false;
$attempts = 0;

// Helper function to sanitize input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get attempt counter from hidden field or start at 0
    $attempts = isset($_POST['attempts']) ? (int)$_POST['attempts'] : 0;
    $attempts++; // Increment attempt counter
    
    // Validate Name (required)
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }
    
    // Validate Email (required)
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Validate Gender (required)
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    
    // Exercise 1: Validate Phone Number (required)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }
    
    // Exercise 2: Validate Website (optional but must be valid URL if provided)
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }
    
    // Exercise 3: Validate Password (required, min 8 chars)
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        }
    }
    
    // Exercise 3: Validate Confirm Password (required, must match)
    if (empty($_POST["confirm_password"])) {
        $confirmErr = "Please confirm your password";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirmErr = "Passwords do not match";
        }
    }
    
    // Exercise 4: Validate Terms Checkbox (required)
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
    
    // If no errors, show success
    if (empty($nameErr) && empty($emailErr) && empty($genderErr) && 
        empty($phoneErr) && empty($websiteErr) && empty($passwordErr) && 
        empty($confirmErr) && empty($termsErr)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation Lab</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="url"] {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        input[type="radio"] { margin-right: 10px; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .attempts { background: #e7f3ff; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>Registration Form</h1>
    
    <!-- Exercise 5: Display submission attempt counter -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="attempts">
            Submission attempt: <?= $attempts ?>
        </div>
    <?php endif; ?>
    
    <!-- Success message -->
    <?php if ($success): ?>
        <div class="success">
            <h2>Registration Successful!</h2>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Gender:</strong> <?= $gender ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <?php if (!empty($website)): ?>
                <p><strong>Website:</strong> <a href="<?= $website ?>" target="_blank"><?= $website ?></a></p>
            <?php endif; ?>
            <p>Thank you for registering!</p>
        </div>
    <?php else: ?>
        <!-- The Form -->
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name *:</label>
                <input type="text" id="name" name="name" value="<?= $name ?>">
                <?php if (!empty($nameErr)): ?>
                    <div class="error"><?= $nameErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email *:</label>
                <input type="email" id="email" name="email" value="<?= $email ?>">
                <?php if (!empty($emailErr)): ?>
                    <div class="error"><?= $emailErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Gender Field -->
            <div class="form-group">
                <label>Gender *:</label>
                <input type="radio" id="male" name="gender" value="Male" <?= $gender == "Male" ? "checked" : "" ?>>
                <label for="male" style="display: inline; font-weight: normal;">Male</label>
                <input type="radio" id="female" name="gender" value="Female" <?= $gender == "Female" ? "checked" : "" ?>>
                <label for="female" style="display: inline; font-weight: normal;">Female</label>
                <input type="radio" id="other" name="gender" value="Other" <?= $gender == "Other" ? "checked" : "" ?>>
                <label for="other" style="display: inline; font-weight: normal;">Other</label>
                <?php if (!empty($genderErr)): ?>
                    <div class="error"><?= $genderErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Exercise 1: Phone Number Field -->
            <div class="form-group">
                <label for="phone">Phone Number *:</label>
                <input type="tel" id="phone" name="phone" value="<?= $phone ?>">
                <?php if (!empty($phoneErr)): ?>
                    <div class="error"><?= $phoneErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Exercise 2: Website Field -->
            <div class="form-group">
                <label for="website">Website (optional):</label>
                <input type="url" id="website" name="website" value="<?= $website ?>">
                <?php if (!empty($websiteErr)): ?>
                    <div class="error"><?= $websiteErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Exercise 3: Password Fields -->
            <div class="form-group">
                <label for="password">Password * (min 8 characters):</label>
                <input type="password" id="password" name="password" value="">
                <?php if (!empty($passwordErr)): ?>
                    <div class="error"><?= $passwordErr ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *:</label>
                <input type="password" id="confirm_password" name="confirm_password" value="">
                <?php if (!empty($confirmErr)): ?>
                    <div class="error"><?= $confirmErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Exercise 4: Terms Checkbox -->
            <div class="form-group">
                <label>
                    <input type="checkbox" name="terms" <?= isset($_POST['terms']) ? "checked" : "" ?>>
                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> *
                </label>
                <?php if (!empty($termsErr)): ?>
                    <div class="error"><?= $termsErr ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Hidden field for attempt counter (Exercise 5) -->
            <input type="hidden" name="attempts" value="<?= $attempts ?>">
            
            <button type="submit">Register</button>
        </form>
    <?php endif; ?>
</body>
</html>