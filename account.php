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
                require_once("./connect.php");
                $link = new mysqli($host, $db_user, $db_pass, $db_name);

                if(isset($_POST['login']) && $_POST['password']) {
                    $login = $_POST['login'];
                    $password = sha1($_POST['password']);
                    $query = "SELECT * FROM `accounts` WHERE login = '$login' AND password = '$password';";
                    $res = $link->query($query);
                    if($res->num_rows > 0) {
                        $row = mysqli_fetch_assoc($res);
                        $_SESSION['logged_in'] = TRUE;
                        $_SESSION['login'] = $login;
                        $_SESSION['UID'] = $row['id'];
                        unset($_SESSION['error']);
                        unset($_SESSION['tried_Login']);
                    }
                    else {
                        $_SESSION['error'] = "<br><br><span style='font-size: 0.7em;'>Błędna nazwa użytkownika lub hasło!</span> <br>";
                        $_SESSION['tried_Login'] = true;
                    }
                }

				if($_SESSION['cart']['total'] != 0) {    
					echo "<span class='badge'>".$_SESSION['cart']['total']."</span>";
				}
			echo "</a>";
            if(isset($_SESSION['login'])) {
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
    <section id="main" style='display: flex';>
        <?php
        $productsQuery = "SELECT * FROM Products";
        $productsRes = $link->query($productsQuery);
        $products = array();
        while($productsRow=mysqli_fetch_assoc($productsRes)) {
            $products += [$productsRow["id"] => $productsRow["Name"]]; 
            
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
                 echo "<div style='display: flex; align-items: center;'><h1>Witaj, ".$_SESSION['login']."</h1></div> <hr style='border: 1px solid var(--orange)'>
                <div class='orderHistory'><h4>Historia zamówień</h4>";
                
                $pastOrdersQuery = "SELECT * FROM `orders` WHERE accountId = ".$_SESSION['UID'];
                $pastOrders = $link->query($pastOrdersQuery);
               
                while ($pastOrder=mysqli_fetch_assoc($pastOrders)) {
                    $order = json_decode($pastOrder['items'], true);
                    echo "<div class='pastOrder'>";
                    foreach($order as $item) {
                        if(isset($item["ProductID"])) {
        
                        echo "<span style='display: block; margin: 0.5rem'>";
                        if($products[$item["ProductID"]] != NULL) {
                            echo $products[$item["ProductID"]];
                        }
                        else {
                            echo "Wycofany Produkt #".$item["ProductID"];
                        }
                        echo " x".$item["amount"]."</span>";
                        
                    }
                    }
                    echo "</div>";
                }
                echo "</div>";
            }
        ?>
        
    </section>
</body>
</html>