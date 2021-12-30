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
                $order = $_POST['order'];
                if(isset($_SESSION['UID'])) {
                    $UID = $_SESSION['UID'];
                }
                else {
                    $UID = 0;
                }
                json_encode($order);
                if(isset($order) && $order != ""){
                    $OrderQuery = "INSERT INTO `orders` (id, accountId, items) VALUES (NULL, ".$UID.",'$order')";
                    $res = $link->query($OrderQuery);
                }

				if(isset($_SESSION['cart'])) {
                $delete = $_POST['idToDelete'];
                 $_SESSION['cart']['total'] -= $_SESSION['cart'][$delete]['amount'];
                 unset($_SESSION['cart'][$delete]);
                 unset($_POST['idToDelete']);
                 unset($delete);
                    if($_SESSION['cart']['total'] != 0) {    
                        echo "
                            <span class='badge'>".$_SESSION['cart']['total']."</span>
                        
                        ";
                    }
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
    <section id="main" style="display: block; text-align: center;">
        <h1>Koszyk</h1>
        <div class="cartSpace">
        <?php
            //var_dump($_SESSION['cart']);
            foreach($_SESSION['cart'] as $item) {
                if($item['ProductID'] != "") {
                    $ids .= " OR id=".$item['ProductID'];
                }
            }
           
            $query = "SELECT * FROM `Products` WHERE id= NULL".$ids;
            $res = $link->query($query);
        	while ($row=mysqli_fetch_assoc($res)) {
                $price = str_replace('.', ',',$row['Price']); 
                echo "<div class='cartItem'>
                        <div class='imgContainer' style='height: 3rem;'>
                            <img class='productImg' src=".$row['imageLink'].">
                        </div>
                        <span class='itemName'>".$row['Name']."</span>
                        <span class='itemAmount'>Ilość: ".$_SESSION['cart'][$row['id']]['amount']."</span>
                        <span class='itemPrice'>".$price." zł</span>
                        <form method='post' action='' id='deleteForm'>
                            <input type='hidden' name='idToDelete' value='".$row['id']."'>
                            <button class='removeItem' action='submit'>X</button>
                        </form>
                    </div>";
                    $total += $row['Price'] * $_SESSION['cart'][$row['id']]['amount'];
            }
            if($total != 0) {
                $total = str_replace('.', ',',$total); 
                echo "<span class='total'>Łącznie: ".$total." zł</span>";
                echo "<form action='' method='post'>
                    <input type='hidden' name='order' value=".json_encode($_SESSION['cart']).">
                    <button action='submit' class='addToCart orderBtn'>Zamów!</button>
                </form>";
            }
            else {
                echo "<p>Wygląda na to, że nic tu nie ma.</p>";
                echo "<p>Kliknij <a href='./index.php' class='returnLink'>tutaj</a> aby wrócić na stronę główną</p>";
            }
        ?>
        </div>
        </section>
</body>
</html>