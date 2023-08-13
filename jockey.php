<?php
//	SCRIPT POR BEBETO
//	Contato: bebeto_apx@hotmail.com
//	Por favor não modificar
//include core.php and config.php files
include("config.php");
include("core.php");
//code html
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>";
echo "</head>";
echo "<body>";
//connect db
//get defines
$a = $_GET["a"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);
//user is logged verification
if(!is_logado($sid))
{
echo "<p align=\"center\">";
echo "Você não está logado!";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?\">Login</a>";
echo "</p>";
exit();
}
adicionar_online($uid, "Jockey club", "");
//menus and options
if($a == "corrida")
{
//define post
$cavalo = $_POST["cavalo"];
$aposta = $_POST["aposta"];
$pontos = getplusses($uid);
echo "<p align=\"center\">";
if($pontos < $aposta)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você deve ter mais que $aposta $smoeda para apostar!";
echo "<br />";
}
else
{
$rand = rand(1,5);
if($rand == $cavalo)
{
$aposta++;
$ns = $pontos + $aposta;
$pdo->query("UPDATE fun_users SET plusses='".$ns."', lastplreas='Ganhou $aposta pontos no jockey club!' WHERE id='".$uid."'");
echo "<img src=\"images/cavalo.gif\" alt=\"\">";
echo "<b>________$rand</b>";
echo "<br />";
echo "Parabêns seu cavalo ganhou a corrida, você ganhou $aposta pontos!";
echo "<br />";
}
else
{
$aposta++;
$ns = $pontos - $aposta;
$pdo->query("UPDATE fun_users SET plusses='".$ns."', lastplreas='Perdeu $aposta pontos no jockey club!' WHERE id='".$uid."'");
echo "<img src=\"images/cavalo.gif\" alt=\"\">";
echo "<b>________$rand</b>";
echo "<br />";
echo "Seu cavalo não ganhou a corrida, você perdeu $aposta pontos!";
echo "<br />";
}
}
}
else if($a == "cf")
{
echo "<p align=\"center\">";
echo "<b>Como funciona?</b>";
echo "<br />";
echo "<br />";
echo "No <b>jockey club</b> você aposta em um determinado valor de pontos em um cavalo da lista, caso o seu cavalo seja o campeão você ganha os pontos apostados na hora!";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
echo "<b>Jockey Club</b>";
echo "</p>";
echo "<form action=\"?a=corrida&sid=$sid\" method=\"POST\">";
echo "Cavalo: <select name=\"cavalo\">";
echo "<option value=\"1\">1</option>";
echo "<option value=\"2\">2</option>";
echo "<option value=\"3\">3</option>";
echo "<option value=\"4\">4</option>";
echo "<option value=\"5\">5</option>";
echo "</select><br />";
echo "Pontos: <select name=\"aposta\">";
echo "<option value=\"1\">1</option>";
echo "<option value=\"2\">2</option>";
echo "<option value=\"3\">3</option>";
echo "<option value=\"4\">4</option>";
echo "<option value=\"5\">5</option>";
echo "<option value=\"6\">6</option>";
echo "<option value=\"7\">7</option>";
echo "<option value=\"8\">8</option>";
echo "<option value=\"9\">9</option>";
echo "<option value=\"10\">10</option>";
echo "</select><br />";
echo "<input name=\"\" value=\"OK\" type=\"submit\">";
echo "</form>";
}
echo "<p align=\"center\">";
if(empty($a))
{
echo "<a href=\"?a=cf&sid=$sid\">Como funciona?</a>";
echo "<br />";
echo "<br />";
}
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
?>