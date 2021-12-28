<!DOCTYPE html>
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
    <section id="main" style=' display: flex; justify-content: center'>
		<div class="item">
			<?php
				$product = $_GET['product'];
				require_once("./connect.php");
				$link = new mysqli($host, $db_user, $db_pass, $db_name);
				$query = "SELECT * FROM `Products` WHERE id = '$product';";
				$res = $link->query($query);
            	while ($row=mysqli_fetch_assoc($res)) {
					$price = str_replace('.', ',',$row['Price']); 
					echo "
					<div class='productImgContainer'><img class='productImg' src=".$row['imageLink']."></div>
					<div class='productInfo'>
						<h2 class='productName'>".$row['Name']."</h2>
						<h4>".$price." zł</h4>
						<form action='addToCart.php' method='post' id='cartForm'>
							<input type='hidden' name='item' value='".$row['id']."'>
							<input type='number' name='amount' class='amount' value='1' min='1'>
							<button action='submit' id='addToCart'>
							Dodaj do koszyka</button>
						</form>
					</div>
					<div class='description'>
						".$row['Description']."
					</div>
					";

				}
			?>
		</div>		
    </section>
</body>
</html>