<?php
//login config 
include("core.php");
$usuario = $_GET["usuario"];
$senha   = date("si").md5($_GET["senha"]).date("sm");
//username scape strigs
//$usuario = $pdo->quote($usuario);
//location
header("Location: login.php?usuario=".$usuario."&senha=".$senha."");
?>
