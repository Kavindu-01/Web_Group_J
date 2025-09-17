<?php
include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email already exists using prepared statement
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Email already exists";
        $check_stmt->close();
    } else {
        $check_stmt->close();
        
        // Insert new user using prepared statement
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: signin.php");
            exit();
        } else {
            $error = "Error creating account: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Liora Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5dc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .signup-container {
            background: white;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .signup-container h2 {
            margin-bottom: 30px;
            color: #2d3748;
            font-weight: 600;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 16px;
            background-color: #f8fafc;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #4299e1;
            outline: none;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .btn-primary {
            background: #2d3748;
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            margin-top: 20px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1a202c;
            transform: translateY(-1px);
        }

        .error {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 6px;
            font-weight: 500;
            background-color: #fed7d7;
            color: #9b2c2c;
            border: 1px solid #feb2b2;
        }

        .signup-container p {
            margin-top: 20px;
            color: #4a5568;
            font-size: 14px;
        }

        .signup-container p a {
            color: #4299e1;
            text-decoration: none;
            font-weight: 500;
        }

        .signup-container p a:hover {
            text-decoration: underline;
            color: #2b6cb0;
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .back-home:hover {
            background-color: white;
            color: #2d3748;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 30px 25px;
                margin: 10px;
            }
            
            .back-home {
                position: relative;
                top: auto;
                left: auto;
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">← Back to Home</a>
    <div class="signup-container">
        <h2>Create Account</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-primary">Create Account</button>
        </form>
        <p>Already have an account? <a href="signin.php">Sign In</a></p>
    </div>
    
    <!-- Floating Contact Buttons -->
    <div class="floating-contact-buttons">
        <a href="https://wa.me/94773768984" target="_blank" class="whatsapp-btn" title="Chat on WhatsApp">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.473 3.516"/>
            </svg>
        </a>
        <a href="mailto:kavindugimhan206@gmail.com" class="email-btn" title="Send Email">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </a>
    </div>

    <style>
        .floating-contact-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        .whatsapp-btn, .email-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        .whatsapp-btn {
            background: #25D366;
        }

        .whatsapp-btn:hover {
            background: #128C7E;
            transform: scale(1.1);
        }

        .email-btn {
            background: #EA4335;
        }

        .email-btn:hover {
            background: #D33B2C;
            transform: scale(1.1);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
            50% {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            }
            100% {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
        }

        @media (max-width: 768px) {
            .floating-contact-buttons {
                bottom: 20px;
                right: 20px;
            }
            
            .whatsapp-btn, .email-btn {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <!-- Enhanced Visual Effects Scripts -->
    <script src="Scripts/cursor.js"></script>
    <script src="Scripts/particles.js"></script>
</body>
</html>