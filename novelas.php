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
$a = $_GET["a"];
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
if($a=="ver")
{
adicionar_online(getuid_sid($sid),"Vendo resumo da novela","");
$id = $_GET["id"];
$id = str_replace(" ", "%20", $id);
$ver_url = file_get_contents("http://diversao.terra.com.br/tv/noticias/$id.html");
$ver_url = str_replace("<br><br><i>", "<br/><i>", $ver_url);
$exibir = explode("<div class=\"page fontsize p1 printing\" id=\"SearchKey_Text1\">", $ver_url);
$exibir = explode("</div>", $exibir[1]);
echo "<p>";
echo "$exibir[0]";
}
else
{
adicionar_online(getuid_sid($sid),"Novelas","");
echo "<p align=\"center\">";
echo "<b>Novelas</b><br></p>";
$url_content = file_get_contents("http://diversao.terra.com.br/tv/");
$url_content = str_replace("http://diversao.terra.com.br/tv/noticias/", "?a=ver&sid=$sid&id=", $url_content);
$url_content = str_replace(".html", "", $url_content);
$exibir = explode('<tr class="gal-thumbs">', $url_content);
$exibir = explode('</tr>', $exibir[1]);
echo "$exibir[0]";
}
echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
?>