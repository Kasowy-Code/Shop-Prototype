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
        <?php
        // var_dump($_SESSION['tried_Login']);
        if(isset($_POST['login']) && $_POST['password']) {
            $login = $_POST['login'];
            $password = sha1($_POST['password']);
            require_once("./connect.php");
            $link = new mysqli($host, $db_user, $db_pass, $db_name);
            $query = "SELECT * FROM `accounts` WHERE login = '$login' AND password = '$password';";
            $res = $link->query($query);
            if($res->num_rows > 0) {
                $_SESSION['logged_in'] = TRUE;
                unset($_SESSION['error']);
                unset($_SESSION['tried_Login']);
            }
            else {
                $_SESSION['error'] = "<br><br><span style='font-size: 0.7em;'>Błędna nazwa użytkownika lub hasło!</span> <br>";
                $_SESSION['tried_Login'] = true;
            }
        }
            if(!isset($_SESSION['logged_in'])) {
                echo "<form method='post' id='login'>
                    <h1>Zaloguj się</h1>
                    <label for='loginInput'>Login: </label><br/>
                    <input type='text' name='login' class='loginInput'> <br>
                    <label for='password'>Hasło: </label> <br>
                    <input type='password' name='password' class='loginInput'> <br>
                    <br>
                    <button action='submit' class='loginInput loginBtn'>Zaloguj</button>
                    ";
                    if ($_SESSION['tried_Login'] === true && $_SESSION['tried_Login'] !== NULL  ) {    
                       echo $_SESSION['error'];
                    }
                    echo "<p>Nie masz konta? <br><a class='returnLink' href='register.php'>Zarejestruj się!</a></p>";
                echo "</form>";
            }
            else {
                
            }
        ?>
        
    </section>
</body>
</html>