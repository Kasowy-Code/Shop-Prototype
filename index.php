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
				if($_SESSION['cart']['total'] != 0) {    
					echo "
						<span class='badge'>".$_SESSION['cart']['total']."</span>
					
					";
				}
			echo "</a>";
            if(isset($_SESSION['logged_in']) && isset($_SESSION['login'])) {
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
        <?php
            $query = "SELECT * FROM `Products`";

            $res = $link->query($query);
            while ($row=mysqli_fetch_assoc($res)) {
				$price = str_replace('.', ',',$row['Price']); 
                echo "
				<form action='product.php' method='get'>
				<input type='hidden' name='product' value='".$row['id']."'>
				<button class='product' action='submit'>
                    <div class='imgContainer'><img class='productImg' src=".$row['imageLink']."></div>
                    <h2 class='productName'>".$row['Name']."</h2>
                    <p style='font-size: 1rem'>".$row['description_short']."</p>
                    <b style='font-size: 1rem'>".$price." zł</b>
                </button>
				</form>
				";
            }
            $link->close();
        ?>

    </section>

</body>
</html>