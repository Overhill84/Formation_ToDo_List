<?php

$nom1 = "Pedrolito";
$nom2 = "L'arniboïte";
$nom3 = "zEc58a";

if(preg_match("#^[a-zA-Z'àâäéèêïôöëùûüçÀÂÉÈÔÙÛÇ\s-]+$#", $nom2))
{
    echo "c'est ok";

} else{
    echo "c'est pas okay";
}