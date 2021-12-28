<?php
    require_once("./connect.php");
    $link = new mysqli($host, $db_user, $db_pass, $db_name);
    
    if(isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] != '') {
        $login = $_POST['login'];
        $password = sha1($_POST['password']);

        $query = "INSERT INTO `accounts` (login, password) VALUES ('$login', '$password')";
        $res = $link->query($query);
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Zamaon</title>
</head>
<body>
    <nav id="navbar">
        <a id="logo" href="index.php"></a>
        <div id="nav-container">
            <a class="nav-option" href="./account.php">Konto</a> 
            <a class="nav-option" href="./cart.php">Koszyk <?php
                session_start();

                if($_SESSION['cart']['total'] != 0) {    
                    echo "
                        <span class='badge'>".$_SESSION['cart']['total']."</span>
                    
                    ";
                }
            ?></a>
        </div>
    </nav>
    <section id="main">
    <form method='post' id='login'>
        <h1>Zarejestruj się</h1>
        <label for='loginInput'>Login: </label><br/>
        <input type='text' name='login' class='loginInput'> <br>
        <label for='password'>Hasło: </label> <br>
        <input type='password' name='password' class='loginInput'> <br>
        <br>
        <button action='submit' class='loginInput loginBtn'>Zarejestruj się</button>
    </form>


    </section>
</body>
</html>