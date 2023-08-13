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

$sid = $_GET["sid"];
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Voc� n�o est� logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

$a = $_GET["a"];
adicionar_online(getuid_sid($sid),"Noticias","");
if($a=="ver")
{ 
$id = $_GET["idc"];
$url = file_get_contents("http://wap.terra.com.br/noticias/?state=noticias_auto&idc=".$id);
$url = str_replace("\n", "", $url);
$url = str_replace("state", "a", $url);
$url = str_replace(" &#187;", ":", $url);
$url = str_replace("noticia_auto", "ler&sid=$sid&", $url);
$url = explode('<p><b>', $url);
$url = explode('</p><p>', $url[1]);

echo "<p align=\"center\">";
echo "<b>$url[0]</b><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"noticias.php?sid=$sid\">Notícias</a><br>";
}
else if($a=="ler")
{
$idc = $_GET["idc"];
$id = $_GET["id"];
$url = file_get_contents("http://wap.terra.com.br/noticias/?state=noticia_auto&id=".$id."&idc=".$idc);
$url = str_replace("\n", "", $url);
$url = explode('<card id="categoria" title="Not&#237;cias">', $url);
$url = explode('<br/></p> <p>', $url[1]);
echo "$url[0]";
echo "<p align=\"center\">";
echo "<a href=\"noticias.php?sid=$sid\">Notícias</a><br>";
}
else
{
echo "<p align=\"center\">";
echo "<b>Not�cias Gerais</b><br/>";
echo "</p>";
echo "<p>";
echo "<a href=\"?a=ver&idc=513&sid=$sid\">&#187;Brasil</a><br/>";
echo "<a href=\"?a=ver&idc=517&sid=$sid\">&#187;Ciências</a><br/>";
echo "<a href=\"?a=ver&idc=514&sid=$sid\">&#187;Mundo</a><br/>";
echo "<a href=\"?a=ver&idc=518&sid=$sid\">&#187;Tecnologia</a><br/>";
echo "<a href=\"?a=ver&idc=1335&sid=$sid\">&#187;Trânsito</a><br/>";
echo "<a href=\"?a=ver&idc=516&sid=$sid\">&#187;Popular</a><br/>";
echo "<a href=\"?a=ver&idc=4867&sid=$sid\">&#187;Moda</a><br/>";
echo "<a href=\"?a=ver&idc=16967&sid=$sid\">&#187;Educação</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
}
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
?>

