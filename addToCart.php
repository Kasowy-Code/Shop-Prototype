<?php
    $amount = $_POST['amount'];
    $product = $_POST['item'];
    session_start();
    
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if(isset($_SESSION['cart'][$product])) {
        $_SESSION['cart'][$product]['amount'] += $amount;
    }
    else {
        $_SESSION['cart'][$product] = array('ProductID' => $product, 'amount' => $amount);
    }
    $_SESSION['cart']['total'] += $amount;
    header("Location: ./cart.php");
?>