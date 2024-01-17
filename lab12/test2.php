<?php
session_start();
function AddToCard($id_prod){
    if (!isset($_SESSION['count']))
    {
        $_SESSION['count'] = 1;
    } else {
        $_SESSION['count']++;
    }
    $nr = $_SESSION['count'];

    $prod[$nr]['id_prod'] = $id_prod;
    $prod[$nr]['ile_sztuk'] = 1;
    $prod[$nr]['data'] = time();

    $nr_0=$nr. '_0';
    $nr_1=$nr. '_1';
    $nr_2=$nr. '_2';
    $nr_4=$nr. '_4';

    $_SESSION[$nr_0] = $nr;
    $_SESSION[$nr_1] = $prod[$nr]['id_prod'];
    $_SESSION[$nr_2] = $prod[$nr]['ile_sztuk'];
    $_SESSION[$nr_4] = $prod[$nr]['data'];
}
//function EchoCard(){
//    $nr = 1;
//    $nr_0=$nr. '_0';
//    while (isset($_SESSION[$nr_0]))
//    {
//        $nr = $_SESSION[$nr_0];
//        $nr_1=$nr. '_1';
//        echo "nr id: . $_SESSION[$nr_1] . </br>";
//        $nr += 1;
//        $nr_0=$nr. '_0';
//
//    }
//}
function DisplayProductIds(){
    if(isset($_SESSION['count'])){
        for($i = 1; $i <= $_SESSION['count']; $i++){
            $id_prod_key = $i . '_1';
            $id_prod = $_SESSION[$id_prod_key];
            echo "Product ID: $id_prod <br>";
        }
    } else {
        echo "No products in the cart.";
    }
}
AddToCard(1);
AddToCard(2);
AddToCard(3);
AddToCard(4);

DisplayProductIds()
//EchoCard();
?>