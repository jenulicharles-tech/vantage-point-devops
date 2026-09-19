<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#0c0e14;
            font-family:Arial;
        }

        .card{
            width:400px;
            background:#13161f;
            padding:40px;
            border-radius:15px;
            color:white;
        }

        h1{
            margin-bottom:10px;
        }

        p{
            margin-bottom:25px;
            color:#cccccc;
        }

        .field{
            margin-bottom:20px;
        }

        .field label{
            display:block;
            margin-bottom:8px;
        }

        .field input{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            background:#1d2230;
            color:white;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            border-radius:8px;
            background:#323EDD;
            color:white;
            font-size:18px;
            cursor:pointer;
        }

        .error{
            background:red;
            padding:10px;
            margin-bottom:20px;
            border-radius:5px;
        }

    </style>
</head>
<body>

<div class="card">

    <h1>Portal</h1>
    <p>Sign in to continue</p>

    <?php
    if(isset($_GET['error'])){
        echo "<div class='error'>Invalid Username or Password</div>";
    }
    ?>

    <form method="POST" action="login.php">

        <div class="field">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Sign In</button>

    </form>

</div>

</body>
</html>