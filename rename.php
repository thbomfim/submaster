<?php

include("config.php");
include("core.php");


echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";

echo "<body>";

$action = $_GET["action"];
$sid = $_GET["sid"];
$who = $_GET["who"];

if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

$uid = getuid_sid($sid);

if($action=="editar")
{
adicionar_online(getuid_sid($sid),"Trocando nick","");
echo "<p align=\"center\">";
$who = $_POST["who"];
$name = ltrim($_POST["name"]);
$nick = getnick_uid($uid);

$uinf = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE name='".$name."'")->fetch();
if(empty($name))
{
echo "<b>Digite um nick!</b><br/>";
}
else if($uinf[0]==0)
{
$pdo->query("UPDATE fun_users SET name='".$name."' WHERE id='".$uid."'");
echo "<b>Nick mudado com sucesso para $name!</b><br/>";
}
else
{
$pdo->query("UPDATE fun_users SET name='".$nick."' WHERE id='".$uid."'");
echo "<b>Este nick j� esta em uso!</b><br/>";
}
echo "<br>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}
else
{

adicionar_online(getuid_sid($sid),"Escrevendo novo nick","");
echo "<p>";
echo "<b>Atenção!</b> muito cuidado ao trocar seu nick caso venha ser mau interpretado pela a equipe você será banido!...<br/>apenas seu nick será mudado seus status, pms, posts, etc...permanecem os mesmos!<br/>";
echo "<form action=\"rename.php?action=editar&sid=$sid\" method=\"post\">";
$nick = getnick_uid2($uid);
echo "Novo nick: <input name=\"name\" value=\"$nick\" size=\"15\" maxlength=\"20\"/><br/>";
echo "<input type=\"submit\" value=\"Atualizar\"/>";
echo "</form><br>"; 
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}
echo "</body>";
echo "</html>";
?>