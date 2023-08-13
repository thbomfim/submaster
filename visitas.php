<?php

include("config.php");
include("core.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

echo "<head>";

echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
$sid = $_GET["sid"];
$who = $_GET["who"];
$page = $_GET["page"];
$uid = getuid_sid($sid);
if((is_logado($sid)==false)||($uid==0))
{
echo "<p align=\"center\">";
echo "Voc&#234; n&#227;o est&#225; logado<br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Vendo visitantes","");
if($page=="" || $page<=0)$page=1;
echo "<p align=\"center\"/><b>Visitas</b><br/></p>";
$noi = $pdo->query("SELECT COUNT(*) FROM visitantes WHERE uid='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT vid, hora FROM visitantes WHERE uid='".$who."' ORDER BY hora DESC LIMIT $limit_start, $items_per_page";

echo "<p>";
$items = $pdo->query($sql);

while ($item = $items->fetch())
{

$nick = getnick_uid($item[0]);
echo "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$nick</a><br/>Data: ".date("d/m - H:i", $item[1])."<br/><br/>";
}
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"visitas.php?page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"visitas.php?page=$npage&sid=$sid&who=$who\">Pr�xima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
echo "<p align=\"center\"><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
?>