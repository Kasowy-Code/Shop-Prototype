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

                if(isset($_POST['ProductToChange'])) {
                    $SelectProductQuery = "SELECT * FROM Products WHERE id = ".$_POST['ProductToChange'];
                    $SelectedProductRes = $link->query($SelectProductQuery);
                    while ($SelectedProduct = mysqli_fetch_assoc($SelectedProductRes)) {
                        $product = [ "Name" => $SelectedProduct["Name"], "Price" => $SelectedProduct["Price"], "Description" => $SelectedProduct["Description"], "description_short" => $SelectedProduct["description_short"], "image" => $SelectedProduct["imageLink"], "id" => $SelectedProduct["id"]];
                    }
                }
                if(isset($_POST["addProduct"])) {
                    $Name = $_POST["Name"];
                    $Price = $_POST["Price"];
                    $Description = $_POST["Description"];
                    $description_short = $_POST["description_short"];
                    
                    $target_dir = "images/";
                    
                    $target_file = $target_dir . basename($_FILES["ProductImageUpload"]["name"]);
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    $check = getimagesize($_FILES["ProductImageUpload"]["tmp_name"]);
                    $uploadOk = 1;

                    if($check === false) {
                        $uploadOk = 0;
                    }
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        $uploadOk = 0;
                    }
                    if ($uploadOk != 0) {
                        if (move_uploaded_file($_FILES["ProductImageUpload"]["tmp_name"], $target_file)) {
                            //echo "The file ". htmlspecialchars( basename( $_FILES["ProductImageUpload"]["name"])). " has been uploaded.";
                        }
                    }
                    
                    
                    $AddProductQuery = "INSERT INTO Products (id, Name, Price, Description, description_short, imageLink) VALUES (NULL, '$Name', $Price, '$Description', '$description_short', '$target_file')";
                    $link->query($AddProductQuery);
                    
                }var_dump($_POST["ProductToChange"]);
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
                else {
                    header('Location: ./index.php');
                }
                echo "<a class='nav-option' href='./logout.php'>Wyloguj</a>";
            }
            else {
                header("Location: ./index.php");
            }
            ?>
        </div>
    </nav>
    <section id="main" style="display: flex; justify-content: left;">
            <div class="ProductEdit">
                <h3>Produkt: </h3>
                <div class="productWindow">
                    <?php
                        echo "<form action='' method='post' enctype='multipart/form-data' id='productForm'>";
                            if(!isset($_POST['ProductToChange'])) {
                                echo "   <input type='hidden' name='addProduct' value='true'>  
                                        <label for='ProductImageUpload' id='ImageUpload'> Zdjęcie produktu: <br>
                                            <div class='ImageUploadBtn productInput'>Dodaj zdjęcie</div>
                                            <input type='file' name='ProductImageUpload' id='ProductImageUpload'>
                                        </label>
                                        
                                        <label for='ProductName'>Nazwa Produktu: <br>
                                            <input type='text' name='Name' id='ProductName' class='productInput'> 
                                        </label> 
                                    
                                    
                                    
                                        <label for='priceInput'>Cena: <br>
                                            <input type='number' name='Price' class='productInput'> 
                                        </label>
                                
                                        <label for='description_shortInput'>Krótki opis: 
                                            <input type='text' name='description_short' id='description_shortInput' class='productInput'>
                                        </label>
                                    
                                    
                                        <label for='descriptionInput' id='descriptionInputLabel'>Opis: </br>
                                            <textarea name='Description' id='descriptionInput' class='productInput'></textarea>
                                        </label>
                                        <input type='submit' class='AddProductBtn productInput' value='Dodaj produkt'>";
                            }
                            else {
                                echo "   <input type='hidden' name='ProductToChange' value='".$product['id']."'>
                                        <label for='ProductImageUpload' id='ImageUpload'> Zdjęcie produktu: <br>
                                            <div class='imgContainer'><img class='productImg' src=".$product['image']."></div>
                                            <div class='ImageUploadBtn productInput'>Dodaj zdjęcie</div>
                                            <input type='file' name='ProductImageUpload' id='ProductImageUpload'>
                                        </label>
                                        
                                        <label for='ProductName'>Nazwa Produktu: <br>
                                            <input type='text' name='Name' id='ProductName' class='productInput' value='".$product["Name"]."'> 
                                        </label> 
                                    
                                    
                                    
                                        <label for='priceInput'>Cena: <br>
                                            <input type='number' name='price' class='productInput' value='".$product["Price"]."'> 
                                        </label>
                                
                                        <label for='description_shortInput'>Krótki opis: 
                                            <input type='text' name='description_short' id='description_shortInput' class='productInput' value='".$product["description_short"]."'>
                                        </label>
                                    
                                    
                                        <label for='descriptionInput' id='descriptionInputLabel'>Opis: </br>
                                            <textarea name='Description' id='descriptionInput' class='productInput'>".$product["Description"]."</textarea>
                                        </label>
                                        <input type='submit' class='AddProductBtn productInput' value='Zaktualizuj produkt'>";
                            }
                            
                        echo "</form>";
                        //unset($_POST["ProductToChange"]);
                    ?>
                </div>
            </div>
            <hr style="border: 1px solid var(--orange); margin: 0; padding: 0">
            <div class="ChooseProduct">
                        <?php
                        $ShowProductsQuery = "SELECT * FROM Products";
                        $ShowProductsRes = $link->query($ShowProductsQuery);
                        while($ShowProductsRow = mysqli_fetch_assoc($ShowProductsRes)) {
                            echo "<form action='' method='post' class='productShown'>
                                <input type='hidden' name='ProductToChange' value='".$ShowProductsRow["id"]."'>
                                <label for='Product' class='ProductLabel'>
                                    <input type='submit' style='display: none' id='Product'>
                                    <span>Nazwa: ".$ShowProductsRow["Name"]."</span> <span>Cena: ".$ShowProductsRow["Price"]."</span> <span>ID: ".$ShowProductsRow["id"]."</span> 
                                </label>
                                </form>";
                        }
                        ?>
            </div>
    </section>
</body>
</html>