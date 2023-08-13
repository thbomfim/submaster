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
$sid = $_GET["sid"];
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
if(getplusses(getuid_sid($sid))<21)
{
echo "<p align=\"center\">";
echo "<b>Voce precisa ter no minimo 21 pontos para jogar no cassino!</b>";
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
exit();
}
adicionar_online(getuid_sid($sid),"Cassino","");
$info = $pdo->query("SELECT value FROM fun_settings WHERE id='10'")->fetch();
$n1 = rand(1,10);
$n2 = rand(1,10);
$n3 = rand(1,10);
$t = time();
$img[1] = "<img src=\"images/cassino/1.gif\" alt=\"\"/>";
$img[2] = "<img src=\"images/cassino/2.gif\" alt=\"\"/>";
$img[3] = "<img src=\"images/cassino/3.gif\" alt=\"\"/>";
$img[4] = "<img src=\"images/cassino/4.gif\" alt=\"\"/>";
$img[5] = "<img src=\"images/cassino/5.gif\" alt=\"\"/>";
$img[6] = "<img src=\"images/cassino/6.gif\" alt=\"\"/>";
$img[7] = "<img src=\"images/cassino/7.gif\" alt=\"\"/>";
$img[8] = "<img src=\"images/cassino/8.gif\" alt=\"\"/>";
$img[9] = "<img src=\"images/cassino/9.gif\" alt=\"\"/>";
$img[10] = "<img src=\"images/cassino/10.gif\" alt=\"\"/>";
echo "<p align=\"center\"><b>Cassino</b><br/>";
echo "<br/>Ache 3 imagens iguais e ganhe os <b>$info[0]</b> pontos acumulados!<br/><br/>";
if($a=="jogar")
{
echo "$img[$n1] $img[$n2] $img[$n3]";
}
else{
echo "$img[$n1] $img[$n1] $img[$n1]";
}
if($a=="jogar")
{
$ut = $_GET["ut"];
$erro = $ut+30;
if($erro<$t OR empty($ut))
{
echo "<br/><br/><b>Jogo expirou!</b><br/><a href=\"cassino.php?a=jogar&ut=$t&sid=$sid\">NOVO JOGO</a>";
}
else if($n1=="$n2" and $n1=="$n3")
{
$pontos = $pdo->query("SELECT plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$ns = $pontos[0] + $info[0];
$pdo->query("UPDATE fun_users SET plusses='".$ns."' WHERE id='".$uid."'");
$pdo->query("UPDATE fun_settings SET value='10' WHERE id='10'");
echo "<br/><br/><b>Parabens! Voc� ganhou todos os pontos acumulados!</b><br/><a href=\"cassino.php?a=jogar&ut=$t&sid=$sid\">JOGAR DE NOVO</a>";
}
else{
$pontos = $pdo->query("SELECT plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$ns = $pontos[0] - 1;
$pdo->query("UPDATE fun_users SET plusses='".$ns."' WHERE id='".$uid."'");
$pont = $pdo->query("SELECT value FROM fun_settings WHERE id='10'")->fetch();
$np = $pont[0] + 1;
$pdo->query("UPDATE fun_settings SET value='".$np."' WHERE id='10'");
echo "<br/><br/><b>Que pena! N�o foi dessa vez!</b><br/><a href=\"cassino.php?a=jogar&ut=$t&sid=$sid\">JOGAR DE NOVO</a>";
}
}
else{
echo "<br/><br/><a href=\"cassino.php?a=jogar&ut=$t&sid=$sid\">INICIAR JOGO</a>";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";

?>