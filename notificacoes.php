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
$page = $_GET["page"];
$who = $_GET["who"];
$pmid = $_GET["pmid"];
if(is_logado($sid)==false)
{

echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
$uid = getuid_sid($sid);
if(is_banido($uid))
{
exit();
}

if($action=="main")
{
adicionar_online(getuid_sid($sid),"Notificaçães","");

echo "<p align=\"center\"><a href=\"notificacoes.php?action=main&sid=$sid&t=".time()."\"><img src=\"images/atualizar.gif\" alt=\"\"/>Atualizar</a><br/>";
echo "<form action=\"notificacoes.php\" method=\"get\">";
echo "Ver: <select name=\"view\">";
echo "<option value=\"all\">Todos</option>";
echo "<option value=\"urd\">Nao lidas</option>";
echo "</select>";
echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
echo " <input type=\"submit\" value=\"OK\"/>";
echo "</form>";
echo "</p>";
$view = $_GET["view"];
//////ALL LISTS SCRIPT <<
if($view=="")$view="all";
if($page=="" || $page<=0)$page=1;
$myid = getuid_sid($sid);
$doit=false;
$num_items = getnotcount($myid); //changable
$items_per_page= 7;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($num_items>0)
{
if($doit)
{
$exp = "&rwho=$myid";
}else
{
$exp = "";
}
//changable sql
if($view=="all")
{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_notificacoes b ON a.id = b.byuid
WHERE b.touid='".$myid."'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="snttt")
{
$sql = "SELECT
a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_notificacoes b ON a.id = b.touid
WHERE b.byuid='".$myid."'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="strttt")
{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_notificacoes b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.starred='1'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="urd")
{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_notificacoes b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.unread='1'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}

echo "<p><small>";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
if($item[3]=="1")
{
$iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";
}else{
if($item[4]=="1")
{
$iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";
}else{

$iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";
}
}

$lnk = "<a href=\"notificacoes.php?action=ler&notid=$item[1]&sid=$sid\">$iml ".getnick_uid($item[2])."</a>";
echo "$lnk<br/>";
}
echo "</small></p>";
echo "<p align=\"center\">";

$npage = $page+1;
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"notificacoes.php?action=main&page=$ppage&sid=$sid&view=$view$exp\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"notificacoes.php?action=main&page=$npage&sid=$sid&view=$view$exp\">Próxima&#187;</a>";
}

echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"notificacoes.php\" method=\"get\">";
$rets .= "Pular Página: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= " <input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";

$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
echo "<br/>";
}
echo "<br/>";


echo "</p>";
}else{
echo "<p align=\"center\">";
echo "Nenhuma notificação!";
echo "</p>";
}
////// UNTILL HERE >>



echo "<p align=\"center\">";

echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Principal</a>";
echo "</p>";
}
else if($action=="ler")
{
adicionar_online(getuid_sid($sid),"Lendo notificação","");

echo "<p>";
$pmid = $_GET["notid"];
$pminfo = $pdo->query("SELECT text, byuid, timesent,touid, reported, cor, id FROM fun_notificacoes WHERE id='".$pmid."'")->fetch();
if(getuid_sid($sid)==$pminfo[3])
{
$chread = $pdo->query("UPDATE fun_notificacoes SET unread='0' WHERE id='".$pmid."'");
}

if(($pminfo[3]==getuid_sid($sid))||($pminfo[1]==getuid_sid($sid)))
{

if(getuid_sid($sid)==$pminfo[3])
{
if(isonline($pminfo[1]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$ptxt = "De: ";

$bylnk = "<a href=\"index.php?action=perfil&who=$pminfo[1]&sid=$sid\">$iml".getnick_uid($pminfo[1])."</a>";

}else{
if(isonline($pminfo[3]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$ptxt = "Para: ";

$bylnk = "<a href=\"index.php?action=perfil&who=$pminfo[3]&sid=$sid\">$iml".getnick_uid($pminfo[3])."</a>";

}

echo "$ptxt $bylnk<br/>";
$tmstamp = $pminfo[2];
$tmdt = date("d m Y - H:i:s", $tmstamp);
echo "$tmdt<br/><br/>";
$pmtext = scan_msg($pminfo[0], $sid);
$pmtext = str_replace("/reader",getnick_uid($pminfo[3]), $pmtext);

echo "<div style=\"color:$pminfo[5]\">$pmtext</div>";
echo "</p>";
echo "<p align=\"center\">";
echo "</select><br/>";
$real = $pminfo[6]-1;
echo "</form>";

}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Notificação não encontrada!<br/>";
}
echo "<br/><a href=\"?action=apagar&sid=$sid&notid=$pmid\">Apagar Notificação</a><br><a href=\"fun_notificacoes.php?action=main&sid=$sid\">Voltar as notificações</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Principal</a>";
echo "</p>";
}
else if($action=="apagar")
{
$notid = $_GET["notid"];
echo "<p align=\"center\">";
if(is_numeric($notid))
{
$pdo->query("DELETE FROM fun_notificacoes WHERE id='".$notid."' AND touid='".$uid."'");
echo "<img src=\"images/ok.gif\">Notificação apagada com sucesso!<br/>";
}
else
{
echo "<img src=\"images/notok.gif\">Notificação não foi apagada!<br>";
}
echo "<br/><a href=\"notificacoes.php?action=main&sid=$sid\">Voltar as notificações</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página Principal</a>";
echo "</p>";
}
echo "</body>";
echo "</html>";
?>
