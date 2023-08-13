<?php
//includes core.php and config.php files
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
$signo = $_GET["signo"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);
$uexist = isuser($uid);
if((is_logado($sid)==false)||!$uexist)
{
echo "<p align=\"center\">";
echo "Você não esta logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
//banned normal 
if(is_banido($uid))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Desculpe, mais você foi banido do site!";
echo "<br />";
echo "<br />";
$infos_ban = $pdo->query("SELECT tempo, motivo FROM fun_ban WHERE uid='".$uid."' AND (tipoban='1' OR tipoban='2')")->fetch();
echo "Tempo para acabar sua penalidade: " . tempo_msg($infos_ban[0]);
echo "<br />";
echo "Motivo da sua penalidade: <b>".htmlspecialchars($infos_ban[1])."</b>";
exit();
}
adicionar_online(getuid_sid($sid),"Horoscopo","");
if($a=="ver")
{
$url = file_get_contents("http://mobile.br.msn.com/device/astro/$signo.aspx");
echo "<p>";
$url = explode("<td valign=\"top\" class=\"c2\"><span>", $url);
$url = explode("</span></td>", $url[1]);
$url = htmlentities($url[0], ENT_NOQUOTES, "UTF-8");
echo "$url";
echo "<br />";
echo "<p align=\"center\">";
echo "<a href=\"horoscopo.php?sid=$sid\">Horóscopo</a>";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else{
echo "<p align=\"center\">";
echo "<b>Horóscopo</b><br/>";
echo "</p>";
echo "<p>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=aries\">&#187;Áries</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=touro\">&#187;Touro</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=gemeos\">&#187;Gemeos</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=cancer\">&#187;Câncer</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=leao\">&#187;Leão</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=virgem\">&#187;Virgem</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=libra\">&#187;Libra</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=escorpiao\">&#187;Escorpião</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=sagitario\">&#187;Sagitário</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=capricornio\">&#187;Capricórnio</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=aquario\">&#187;Aquário</a><br/>";
echo "<a href=\"horoscopo.php?a=ver&sid=$sid&signo=peixes\">&#187;Peixes</a><br/>";
echo "<p align=\"center\"><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
}
?>
