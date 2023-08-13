<?php

include("config.php");
include("core.php");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


echo "<head>";

echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>";
echo "</head>";
echo "<body>";

$a = $_GET["a"];
$t = $_GET["t"];
$sid = $_GET["sid"];
$tt = time();
addvisitor();
$uid = getuid_sid($sid);
if((is_logado($sid)==false)||($uid==0))
{
echo "<p align=\"center\">";
echo "Voce nao esta logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
if(getplusses(getuid_sid($sid))<45)
{
echo "<p align=\"center\">";
echo "<b>Voce precisa ter no minimo 45 pontos para jogar no cores da sorte!</b>";
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
exit();
}
adicionar_online(getuid_sid($sid),"Jogando cores da sorte","");
if($a=="cf")
{
echo "<p align=\"center\"><b>Como funciona?</b><br/><br/>";
echo "No cores da sorte você pode ganhar de 5 a 25 pontos, para jogar e só clicar no nome de uma cor e você poderá ganhar ou perder pontos, os pontos entram na hora!";
echo "<br/><br/><a href=\"cds.php?sid=$sid\">Cores da sorte</a><br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($a=="jogar")
{
$erro = $t+30;
if($erro<$tt OR empty($t))
{
echo "<p align=\"center\"><b>Jogo expirou!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else{
$nj = rand(1,2);
$pontos = rand(5,25);
if($nj=="1")
{
$pont = $pdo->query("SELECT plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$npts = $pont[0] + $pontos;
$pdo->query("UPDATE fun_users SET plusses='".$npts."' WHERE id='".$uid."'");
echo "<p align=\"center\"><b>Parabêns, você ganhou $pontos pontos!</b>";
}
else{
$pont = $pdo->query("SELECT plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$npts = $pont[0] - $pontos;
$pdo->query("UPDATE fun_users SET plusses='".$npts."' WHERE id='".$uid."'");
echo "<p align=\"center\"><b>Que pena, você perdeu $pontos pontos!</b>";
}
echo "<br/><br/><a href=\"cds.php?sid=$sid\">NOVO JOGO</a>";
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
}
else{
echo "<p align=\"center\"><b>Cores da sorte</b><br/><br/>";
echo "<a href=\"cds.php?a=jogar&palavra=azul&t=$tt&sid=$sid\"><b style=\"color: blue\">AZUL</b></a>  <a href=\"cds.php?a=jogar&palavra=vermelho&t=$tt&sid=$sid\"><b style=\"color: red\">VERMELHO</b></a><br/><br/>";
echo "<a href=\"cds.php?a=jogar&palavra=preto&t=$tt&sid=$sid\"><b style=\"color: black\">PRETO</b></a>  <a href=\"cds.php?a=jogar&palavra=cinza&t=$tt&sid=$sid\"><b style=\"color: silver\">CINZA</b></a><br/><br/>";
echo "<a href=\"cds.php?a=jogar&palavra=verde&t=$tt&sid=$sid\"><b style=\"color: green\">VERDE</b></a>  <a href=\"cds.php?a=jogar&palavra=laranja&t=$tt&sid=$sid\"><b style=\"color: orange\">LARANJA</b></a><br/><br/>";
echo "<a href=\"cds.php?a=jogar&palavra=rosa&t=$tt&sid=$sid\"><b style=\"color: #ff1493\">ROSA</b></a>  <a href=\"cds.php?a=jogar&palavra=amarelo&t=$tt&sid=$sid\"><b style=\"color: #ffff00\">AMARELO</b></a><br/><br/>";
echo "<a href=\"cds.php?a=jogar&palavra=marron&t=$tt&sid=$sid\"><b style=\"color: brown\">MARRON</b></a>  <a href=\"cds.php?a=jogar&palavra=roxo&t=$tt&sid=$sid\"><b style=\"color: purple\">ROXO</b></a><br/><br/>";
echo "<a href=\"cds.php?a=cf&sid=$sid\">Como funciona</a><br/><br/>";
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
if($umsg>0)
{
echo "<a href=\"inbox.php?action=main&sid=$sid\">Torpedos($umsg/$tmsg)</a><br/>";
}
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
?>