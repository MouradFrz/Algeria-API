<?php
session_start();
include_once '../utils/db.php';
if(isset($_SESSION['loggedin'])){
    $pdo = dbConnect::connect();
$stmt = $pdo->prepare('Select * from users where username=?');
$stmt->execute([$_SESSION['loggedin']]);
$user = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algeria API-DOCS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="panel login-panel">
        <form action="login.php" method="POST">
            <h1>Login</h1>
            <p class="text-danger error-message-l"></p>
            <label for="">Username</label>
            <input type="text" name="username" class="custom-input">
            <label for="">Password</label>
            <input type="password" name="password" class="custom-input">
            <button class="cbtn">Login</button>
        </form>
    </div>
    <div class="panel register-panel">
        <form action="register.php" method="POST">
            <h1>Register</h1>
            <p class="text-danger error-message"></p>
            <label for="">Username</label>
            <input type="text" name="username" class="custom-input">
            <label for="">Email</label>
            <input type="Email" name="email" class="custom-input">
            <label for="">Password</label>
            <input type="password" name="password" class="custom-input">
            <label for="">Confirm password</label>
            <input type="password" name="passwordConfirm" class="custom-input">
            <button class="cbtn">Register</button>
        </form>
    </div>
    <div class="unclick"></div>

    <div>
        <div class="container">
            <nav>
                <div class="logo">
                    <h1>Algeria-API</h1>
                </div>
                <div class="links">
                    <ul>
                        <?php if (isset($_SESSION['loggedin'])) { ?>
                            <li class="fw-bold m-0 fs-6"><i class="bi bi-person fw-lighter"></i>
                                <?= $_SESSION['loggedin'] ?> </li>
                                <?php } ?>
                                <?php if (!isset($_SESSION['loggedin'])) { ?>
                            <li><button class="cbtn login-trigger">Login</button></li>
                            <li><button class="cbtn register-trigger">Register</button></li>
                        <?php } else { ?>
                            <li><form action="logout.php" method="post"><button class="cbtn">Logout <i class="bi bi-box-arrow-right"></i></button></form></li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div>
        <div class="container">
            <main>
            <?php if (isset($_SESSION['loggedin'])) { ?>
                <div class="apikey">

                    <p>My API key : <span><?= $user['apikey']?></span></p>
                </div>
                <?php } ?>
                <div class="hero">
                    <h1>Algeria-API Docs</h1>
                    <p>Algeria-API allows you to access information about districts using their postal code</p>
                </div>

                <div class="how-to">
                    <h2>How to</h2>
                    <p>You first need to create an account to get an API key. <a style="cursor:pointer; color:blue">Register here</a></p>
                    <p>Then you send a GET request to : <span class="url">https://Algeria-api.com?key=<span class="key">{Your API key}</span>&code=<span class="code">{A valid postal code}</span></span></p>
                    <p>For example : <span class="url">https://Algeria-api.com?key=<span class="key">FW31KLH151FL1352</span>&code=<span class="code">25000</span></span></p>
                </div>
                <div class="result">
                    <h2>Result</h2>
                    <p>Here is an example of the returned object</p>
                    <div>
                        <pre>
[   
    {
        postal_code:25000,
        city:constantine,
        city_code:25
    }
]
</pre>
                    </div>
                </div>
                
            </main>
            <footer>
                <div class="container">
                    <p class="text-center">© 2022 <a href="">Yaou Mourad</a> - All Rights Reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        document.querySelector('.unclick').addEventListener('click', () => {
            document.querySelector('.unclick').classList.remove('show')
            document.querySelectorAll('.panel').forEach(e => {
                e.classList.remove('show')
            })
        })


        document.querySelectorAll('.login-trigger').forEach(e => {
            e.addEventListener('click', () => {
                document.querySelector('.unclick').classList.add('show')
                document.querySelector('.login-panel').classList.add('show')
            })
        })
        document.querySelectorAll('.register-trigger').forEach(e => {
            e.addEventListener('click', () => {
                document.querySelector('.unclick').classList.add('show')
                document.querySelector('.register-panel').classList.add('show')
            })
        })
    </script>
    <script>
        let error = 0
        <?php if (isset($_GET['err'])) { ?>
            error = <?= $_GET['err'] ?>;
        <?php } ?>
        let message = ''
        switch (error) {
            case 1:
                message = 'All fields should be filled';
                break
            case 2:
                message = 'Username Should contain only letters and numbers';
                break
            case 3:
                message = 'Email format invalid.';
                break
            case 4:
                message = "The passwords don't match";
                break
            case 5:
                message = 'Username or email already taken';
                break
            default:
                message = ''
        }

        if (message) {
            document.querySelector('.error-message').textContent = message
            document.querySelector('.register-trigger').click();
        }
    </script>
    <script>
        let lerror = 0
        <?php if (isset($_GET['lerr'])) { ?>
            lerror = <?= $_GET['lerr'] ?>;
        <?php } ?>
        let lmessage = ''
        switch (lerror) {
            case 1:
                lmessage = "Your account doesn't exist";
                break
            case 2:
                lmessage = 'Wrong password';
                break
            default:
                lmessage = ''
        }

        if (lmessage) {
            document.querySelector('.error-message-l').textContent = lmessage
            document.querySelector('.login-trigger').click();
        }
    </script>
</body>

</html>