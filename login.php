<?php
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use a prepared statement to safely fetch the user
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Securely verify the entered password against the hash in the database
        if (password_verify($password, $admin['password'])) {
            // If the password is correct, create the session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        }
    }

    // If the username doesn't exist or the password is wrong, show an error
    $error = "Invalid username or password";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Liora Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .admin-login-container {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
        }
        
        .admin-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
        }
        
        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }
        
        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .back-link a:hover {
            color: #3b82f6;
        }
        
        @media (max-width: 480px) {
            .admin-login-container {
                padding: 2rem 1.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2 class="login-title">Admin Access</h2>
            <p class="login-subtitle">Sign in to manage your store</p>
        </div>
        
        <?php if (isset($error)) { ?>
            <div class="error">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php } ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Enter admin username" required>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Sign In to Dashboard
            </button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i>
                Back to Store
            </a>
        </div>
    </div>
</body>
</html>