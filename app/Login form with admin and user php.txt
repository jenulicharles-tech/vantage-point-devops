<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: user.php');
    }
    exit();
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = $_POST['role'] ?? 'user';
 
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $con = mysqli_connect("localhost", "root", "", "acbt");
        if (!$con) {
            $error = "Database connection failed.";
        } else {
            $username_safe = mysqli_real_escape_string($con, $username);
            $query = mysqli_query($con, "SELECT * FROM student WHERE username='$username_safe'");
            $user = mysqli_fetch_array($query);
 
            if ($user && $user['password'] === $password) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['name']     = $user['name'];
                $_SESSION['role']     = $role;
                mysqli_close($con);
                if ($role === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: user.php');
                }
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
            mysqli_close($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
        :root {
            --bg:       #0c0e14;
            --surface:  #13161f;
            --border:   rgba(255,255,255,0.08);
            --accent:   #7f77dd;
            --accent2:  #534ab7;
            --text:     #f0eef8;
            --muted:    rgba(240,238,248,0.45);
            --err:      #e24b4a;
            --radius:   14px;
        }
 
        body {
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            padding: 2rem 1rem;
        }
 
        /* Subtle radial glow behind the card */
        body::before {
            content: '';
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(127,119,221,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
 
        .card {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            position: relative;
            z-index: 1;
        }
 
        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.7rem;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #afa9ec, #7f77dd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
 
        .subtitle {
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 2rem;
        }
 
        /* Role toggle */
        .role-toggle {
            display: flex;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 1.75rem;
            gap: 4px;
        }
 
        .role-toggle input[type="radio"] { display: none; }
 
        .role-toggle label {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0.75rem;
            border-radius: 7px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
 
        .role-icon {
            font-size: 14px;
            line-height: 1;
        }
 
        .role-toggle input[type="radio"]:checked + label {
            background: var(--accent2);
            color: #e8e5fc;
        }
 
        /* Form fields */
        .field {
            margin-bottom: 1rem;
        }
 
        .field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
 
        .input-wrap {
            position: relative;
        }
 
        .input-wrap input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 0.7rem 2.6rem 0.7rem 0.9rem;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s;
        }
 
        .input-wrap input::placeholder { color: var(--muted); }
 
        .input-wrap input:focus {
            border-color: var(--accent);
        }
 
        .toggle-pw {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--muted);
            font-size: 13px;
            user-select: none;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--text); }
 
        /* Remember + Forgot row */
        .extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.8rem;
        }
 
        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--muted);
            cursor: pointer;
        }
 
        .remember input[type="checkbox"] {
            accent-color: var(--accent);
            width: 14px;
            height: 14px;
        }
 
        .forgot {
            color: var(--accent);
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .forgot:hover { opacity: 0.75; }
 
        /* Submit button */
        .btn-submit {
            width: 100%;
            background: var(--accent2);
            color: #e8e5fc;
            border: none;
            border-radius: 9px;
            padding: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.02em;
        }
        .btn-submit:hover  { background: var(--accent); }
        .btn-submit:active { transform: scale(0.98); }
 
        /* Error message */
        .error-msg {
            background: rgba(226,75,74,0.12);
            border: 1px solid rgba(226,75,74,0.3);
            border-radius: 8px;
            color: #f09595;
            font-size: 0.82rem;
            padding: 0.6rem 0.85rem;
            margin-bottom: 1.25rem;
        }
 
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
            color: var(--muted);
            font-size: 0.75rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
 
        /* Social buttons */
        .social-row {
            display: flex;
            gap: 0.75rem;
        }
 
        .btn-social {
            flex: 1;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 0.6rem;
            color: var(--muted);
            font-size: 0.8rem;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: border-color 0.2s, color 0.2s;
            text-decoration: none;
        }
        .btn-social:hover { border-color: rgba(255,255,255,0.18); color: var(--text); }
 
        .social-icon { font-size: 14px; }
    </style>
</head>
<body>
 
<div class="card">
    <div class="logo">Portal</div>
    <p class="subtitle">Sign in to continue to your dashboard</p>
 
    <!-- Role Selector -->
    <div class="role-toggle">
        <input type="radio" name="role_display" id="role_user_btn" value="user" checked>
        <label for="role_user_btn">
            <span class="role-icon">&#128100;</span> User Login
        </label>
        <input type="radio" name="role_display" id="role_admin_btn" value="admin">
        <label for="role_admin_btn">
            <span class="role-icon">&#128737;</span> Admin Login
        </label>
    </div>
 
    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
 
    <form method="POST" action="login.php">
        <!-- Hidden role field synced with toggle -->
        <input type="hidden" name="role" id="role_field" value="user">
 
        <div class="field">
            <label>Username</label>
            <div class="input-wrap">
                <input type="text" name="username" placeholder="Enter your username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
            </div>
        </div>
 
        <div class="field">
            <label>Password</label>
            <div class="input-wrap">
                <input type="password" name="password" id="pw_field" placeholder="Enter your password" required autocomplete="current-password">
                <span class="toggle-pw" id="toggle_pw" title="Show/hide password">&#128065;</span>
            </div>
        </div>
 
        <div class="extras">
            <label class="remember">
                <input type="checkbox" name="remember" checked>
                Remember me
            </label>
            <a href="#" class="forgot">Forgot password?</a>
        </div>
 
        <button type="submit" class="btn-submit" id="submit_btn">Sign In as User</button>
    </form>
 
    <div class="divider">or continue with</div>
 
    <div class="social-row">
        <a href="#" class="btn-social">
            <span class="social-icon">f</span> Facebook
        </a>
        <a href="#" class="btn-social">
            <span class="social-icon">&#120143;</span> Twitter
        </a>
    </div>
</div>
 
<script>
    const radios    = document.querySelectorAll('input[name="role_display"]');
    const roleField = document.getElementById('role_field');
    const submitBtn = document.getElementById('submit_btn');
 
    radios.forEach(r => {
        r.addEventListener('change', () => {
            roleField.value = r.value;
            submitBtn.textContent = r.value === 'admin' ? 'Sign In as Admin' : 'Sign In as User';
        });
    });
 
    // Password toggle
    const pwField  = document.getElementById('pw_field');
    const togglePw = document.getElementById('toggle_pw');
    togglePw.addEventListener('click', () => {
        pwField.type = pwField.type === 'password' ? 'text' : 'password';
        togglePw.title = pwField.type === 'password' ? 'Show password' : 'Hide password';
    });
</script>
</body>
</html>