<?php
session_start();
    require_once("./connect.php");
    $link = new mysqli($host, $db_user, $db_pass, $db_name);
    
    if(isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] != '') {
        $login = $_POST['login'];
        $password = sha1($_POST['password']);
        $test = "SELECT * FROM accounts WHERE login = '$login'";
        $testRes = $link->query($test);
        
        if($testRes->num_rows === 0) { 
            $query = "INSERT INTO `accounts` (login, password, id, isAdmin) VALUES ('$login', '$password', NULL, 0)";
            $res = $link->query($query);
            unset($_SESSION['registrationError']);
            //header('Location: account.php');
        }
        else {
            $_SESSION['registrationError'] = "</br><span style='font-size: 0.7em;'> Ta nazwa użytkownika jest już zajęta!</span>";
        }
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

                if($_SESSION['cart']['total'] != 0) {    
                    echo "
                        <span class='badge'>".$_SESSION['cart']['total']."</span>
                    
                    ";
                }
            echo "</a>";
            if(isset($_SESSION['logged_in'])) {
                $user = $_SESSION['login'];
                $isAdminQuery = "SELECT isAdmin FROM accounts WHERE login = '$user'";
                $isAdminRes = $link->query($isAdminQuery);
                $isAdminRow = mysqli_fetch_assoc($isAdminRes);
                $isAdmin = $isAdminRow['isAdmin'];
                if($isAdmin == true) {
                    echo "<a class='nav-option' href='./adminPanel.php'>Panel</a> ";
                }
                echo "<a class='nav-option' href='./logout.php'>Wyloguj</a>";
            }
            ?>
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
        <button action='submit' class='loginInput loginBtn'>Zarejestruj się</button> <br>
        <?php
            echo $_SESSION['registrationError'];
            $link->close();
        ?>
    </form>


    </section>
</body>
</html>