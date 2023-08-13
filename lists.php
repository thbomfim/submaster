<?php
////includes core.php and config.php files
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

//logged
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
//banned normal
if(is_banido($uid))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Desculpe, mais voc� foi banido do site!";
echo "<br />";
echo "<br />";
$infos_ban = $pdo->query("SELECT tempo, motivo FROM fun_ban WHERE uid='".$uid."' AND (tipoban='1' OR tipoban='2')")->fetch();
echo "Tempo para acabar sua penalidade: " . tempo_msg($infos_ban[0]);
echo "<br />";
echo "Motivo da sua penalidade: <b>".htmlspecialchars($infos_ban[1])."</b>";
exit();
}
if($action=="members")
{
adicionar_online(getuid_sid($sid),"Vendo todo os usuários","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$num_items = regmemcount(); //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql

$sql = "SELECT id, name, regdate FROM fun_users ORDER BY name LIMIT $limit_start, $items_per_page";

echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$jdt = date("d-m-y", $item[2]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> Resgistrado em: $jdt";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=members&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=members&page=$npage&sid=$sid\">Próxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "	<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////List users by IP
if($action=="byip")
{
adicionar_online(getuid_sid($sid),"Mod CP","");
//////ALL LISTS SCRIPT <<
$who = $_GET["who"];
$whoinfo = $pdo->query("SELECT ipadd, browserm FROM fun_users WHERE id='".$who."'")->fetch();
if(ismod(getuid_sid($sid))){
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE ipadd='".$whoinfo[0]."' AND browserm='".$whoinfo[1]."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE ipadd='".$whoinfo[0]."' AND browserm='".$whoinfo[1]."' ORDER BY name  LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets .= "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
}else{
echo "<p align=\"center\">";
echo "Você não tem acesso a essa lista!";
echo "</p>";
}
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////////longon
else if($action=="longon")
{
adicionar_online(getuid_sid($sid),"Mais Tempo Online","");
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE tottimeonl>'0'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, tottimeonl FROM fun_users WHERE tottimeonl>'0' ORDER BY floor(tottimeonl/60) DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$num = $item[2]/86400;
$days = intval($num);
$num2 = ($num - $days)*24;
$hours = intval($num2);
$num3 = ($num2 - $hours)*60;
$mins = intval($num3);
$num4 = ($num3 - $mins)*60;
$secs = intval($num4);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a>: <b>$days</b> dias, <b>$hours</b> horas, <b>$mins</b> mins, <b>$secs</b> segundos";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=longon&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=longon&page=$npage&sid=$sid&view=$view\">Próximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular Página: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($action=="topp")
{
adicionar_online(getuid_sid($sid),"Top Forum Postadores","");
echo "<p align=\"center\">";
echo "<b>Nosso top postadores</b><br/><br/>";
$weekago = time();
$weekago -= 7*24*60*60;
$noi = $pdo->query("SELECT COUNT(DISTINCT uid) FROM fun_posts WHERE dtpost>'".$weekago."';")->fetch();
echo "<a href=\"lists.php?action=tpweek&sid=$sid\">Esta semana($noi[0])</a><br/>";
$noi = $pdo->query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;")->fetch();
echo "<a href=\"lists.php?action=tptime&sid=$sid\">Todo o tempo($noi[0])</a><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$num_items = regmemcount(); //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, posts FROM fun_users ORDER BY posts DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> Posts: $item[2]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=topp&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=topp&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Most online daily list
else if($action=="moto")
{
adicionar_online(getuid_sid($sid),"Máximo online diario","");
echo "<p align=\"center\">";
echo "Maximo de usuarios online nos ultimos 10 dias<br/>";
echo "";
echo "</p>";
//////ALL LISTS SCRIPT <<
//changable sql
$sql = "SELECT ddt, dtm, ppl FROM fun_mpot ORDER BY id DESC LIMIT 10";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "$item[0]($item[1]) Usuários: $item[2]";
echo "$lnk<br/>";
}
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Chatters
else if($action=="tchat")
{
adicionar_online(getuid_sid($sid),"Top Chat","");
echo "<p align=\"center\">";
echo "<b>Top Chat</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$num_items = regmemcount(); //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, chmsgs FROM fun_users ORDER BY chmsgs DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> Postagens: $item[2]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=tchat&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=tchat&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input name=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input name=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////requists
else if($action=="reqs")
{
adicionar_online(getuid_sid($sid),"Pedidos de amizade","");
echo "<p align=\"center\">";
global $max_buds;
$uid = getuid_sid($sid);
echo "Esses usuários fizeram um pedido de amizade para você!<br/>";
$remp = $max_buds - getnbuds($uid);
echo "Você pode adicionar ainda <b>$remp</b> amigos!";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$nor = $pdo->query("SELECT COUNT(*) FROM fun_buddies WHERE tid='".$uid."' AND agreed='0'")->fetch();
$num_items = $nor[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid  FROM fun_buddies WHERE tid='".$uid."' AND agreed='0' ORDER BY reqdt DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$rnick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$rnick</a>: <a href=\"genproc.php?action=bud&who=$item[0]&sid=$sid&todo=add\">Aceitar</a>, <a href=\"genproc.php?action=bud&who=$item[0]&sid=$sid&todo=del\">Rejeitar</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&view=$view\">Proxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Stastisticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////shouts
else if($action=="shouts")
{
adicionar_online(getuid_sid($sid),"Vendo recados","");
$who = $_GET["who"];
if($page=="" || $page<=0)$page=1;
if($who=="")
{
$noi = $pdo->query("SELECT COUNT(*) FROM fun_shouts")->fetch();
}else{
$noi = $pdo->query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='".$who."'")->fetch();
}
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
if($who =="")
{
$sql = "SELECT id, shout, shouter, shtime, cor  FROM fun_shouts ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}else{
$sql = "SELECT id, shout, shouter, shtime, cor  FROM fun_shouts  WHERE shouter='".$who."'ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$shnick = getnick_uid($item[2]);
$sht = scan_msg($item[1],$sid);
$shdt = date("d m y-H:i", $item[3]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">$shnick</a>: <span style=\"color:$item[4]\">$sht</span><br/>$shdt";
if(ismod(getuid_sid($sid)))
{
$dlsh = "<a href=\"modproc.php?action=delsh&sid=$sid&shid=$item[0]\">[x]</a>";
}else{
$dlsh = "";
}
echo "$lnk $dlsh<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=shouts&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=shouts&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ok\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////User Clubs
else if($action=="ucl")
{
$denick = getnick_uid2(addslashes($_GET["who"]));
adicionar_online(getuid_sid($sid),"Comunidades de $denick","");
$who = $_GET["who"];
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id  FROM fun_clubs  WHERE owner='".$who."' ORDER BY id LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$nom = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$item[0]."' AND accepted='1'")->fetch();
$clinfo = $pdo->query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'")->fetch();
$lnk = "<a href=\"index.php?action=gocl&clid=$item[0]&sid=$sid\">".htmlspecialchars($clinfo[0])."</a>($nom[0])<br/>".htmlspecialchars($clinfo[1])."<br/>";
echo $lnk;
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
if($num_pages>1){
echo "<br/>$page/$num_pages<br/>";
}
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$whonick = getnick_uid($who);
echo "<a href=\"index.php?action=perfil&who=$who&sid=$sid\">Perfil de $whonick</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////User Clubs
else if($action=="clm")
{
adicionar_online(getuid_sid($sid),"Comunidades que sou membro","");
$who = $_GET["who"];
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND accepted='1'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT  clid  FROM fun_clubmembers  WHERE uid='".$who."' AND accepted='1' ORDER BY joined DESC  LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$clnm = $pdo->query("SELECT name FROM fun_clubs WHERE id='".$item[0]."'")->fetch();
$lnk = "<a href=\"index.php?action=gocl&clid=$item[0]&sid=$sid\">".htmlspecialchars($clnm[0])."</a><br/>";
echo $lnk;
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
if($num_pages>1){
echo "<br/>$page/$num_pages<br/>";
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Popular clubs
else if($action=="pclb")
{
adicionar_online(getuid_sid($sid),"Comunidades populares","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubs")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT clid, COUNT(*) as notl FROM fun_clubmembers WHERE accepted='1' GROUP BY clid ORDER BY notl DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$clnm = $pdo->query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'")->fetch();
$lnk = "<a href=\"index.php?action=gocl&clid=$item[0]&sid=$sid\">".htmlspecialchars($clnm[0])."</a>($item[1])<br/>".htmlspecialchars($clnm[1])."<br/>";
echo $lnk;
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
if($num_pages>1){
echo "<br/>$page/$num_pages<br/>";
}
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clmenu&sid=$sid\">Comunidades</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Active clubs
else if($action=="aclb")
{
adicionar_online(getuid_sid($sid),"Vendo comunidades ativas","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubs")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT COUNT(*) as notp, b.clubid FROM fun_topics a INNER JOIN fun_forums b ON a.fid = b.id WHERE b.clubid >'0'  GROUP BY b.clubid ORDER BY notp DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$clnm = $pdo->query("SELECT name, description FROM fun_clubs WHERE id='".$item[1]."'")->fetch();
$lnk = "<a href=\"index.php?action=gocl&clid=$item[1]&sid=$sid\">".htmlspecialchars($clnm[0])."</a>($item[0] Topics)<br/>".htmlspecialchars($clnm[1])."<br/>";
echo $lnk;
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
if($num_pages>1){
echo "<br/>$page/$num_pages<br/>";
}
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clmenu&sid=$sid\">Comunidades</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Random clubs
else if($action=="rclb")
{
adicionar_online(getuid_sid($sid),"Vendo 5 comunidades aleatorias","");
//////ALL LISTS SCRIPT <<
$sql = "SELECT id, name, description FROM fun_clubs ORDER BY RAND()  LIMIT 5";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=gocl&clid=$item[0]&sid=$sid\">".htmlspecialchars($item[1])."</a><br/>".htmlspecialchars($item[2])."<br/>";
echo $lnk;
}
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clmenu&sid=$sid\">Comunidades</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////shouts
else if($action=="annc")
{
adicionar_online(getuid_sid($sid),"Lendo anuncios","");
$clid = $_GET["clid"];
//////ALL LISTS SCRIPT <<
$uid = getuid_sid($sid);
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_announcements WHERE clid='".$clid."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, antext, antime  FROM fun_announcements WHERE clid='".$clid."' ORDER BY antime DESC LIMIT $limit_start, $items_per_page";
$cow = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if($cow[0]==$uid)
{
$dlan = "<a href=\"genproc.php?action=delan&sid=$sid&anid=$item[0]&clid=$clid\">[X]</a>";
}else{
$dlan = "";
}
$annc = htmlspecialchars($item[1])."<br/>Adicionado em: <b>".date("d/m/y (H:i)", $item[2])."</b>";
$annc = getbbcode($annc);//libera o bbcode no anuncio
echo "$annc $dlan<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&clid=$clid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&clid=$clid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if($cow[0]==$uid)
{
$dlan = "<a href=\"index.php?action=annc&sid=$sid&clid=$clid\">Adicionar An�ncio!</a><br/><br/>";
echo $dlan;
}
echo "<a href=\"index.php?action=gocl&sid=$sid&clid=$clid\">";
echo "Voltar para a comunidade</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////clubs requests
else if($action=="clreq")
{
adicionar_online(getuid_sid($sid),"Solicitações pedentes de comunidade","");
$clid = $_GET["clid"];
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
//////ALL LISTS SCRIPT <<
if($cowner[0]==$uid)
{
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0' ORDER BY joined DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$shnick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$shnick</a>: <a href=\"genproc.php?action=acm&who=$item[0]&sid=$sid&clid=$clid\">aceitar</a>, <a href=\"genproc.php?action=dcm&who=$item[0]&sid=$sid&clid=$clid\">recusar</a><br/>";
echo "$lnk";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&clid=$clid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&clid=$clid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/><a href=\"genproc.php?action=accall&clid=$clid&sid=$sid\">Aceitar Todos</a>, ";
echo "<a href=\"genproc.php?action=denall&clid=$clid&sid=$sid\">Recusar Todos</a>";
echo "</p>";
}else{
echo "<p align=\"center\">Essa comunidade não é sua!</p>";
}
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=gocl&sid=$sid&clid=$clid\">";
echo "Voltar para a comunidade</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////clubs members
else if($action=="clmem")
{
adicionar_online(getuid_sid($sid),"Vendo membros de uma comunidade","");
$clid = $_GET["clid"];
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid, joined, points  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1' ORDER BY joined DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if($cowner[0]==$uid)
{
$oop = ": <a href=\"index.php?action=clmop&sid=$sid&who=$item[0]&clid=$clid\">Op��es</a>";
}else{
$oop = "";
}
$shnick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$shnick</a>$oop<br/>";
$lnk .= "Entrou: ".date("d/m/y", $item[1])." - Pontos: $item[2]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&clid=$clid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&clid=$clid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=gocl&sid=$sid&clid=$clid\">";
echo "Voltar para a comunidade</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////User topics

else if($action=="tbuid")
{
$who = $_GET["who"];
$denick = getnick_uid2($who);
adicionar_online(getuid_sid($sid),"Vendos topicos de $denick","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE authorid='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, crdate  FROM fun_topics  WHERE authorid='".$who."'ORDER BY crdate DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if(canaccess(getuid_sid($sid),getfid_tid($item[0])))
{
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$item[0]\">".htmlspecialchars($item[1])."</a> ".date("d m y-H:i:s",$item[2])."<br/>";
}else{
echo "Tópico Privado!<br/>";
}
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "	<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$unick = getnick_uid($who);
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$who\">";
echo "Perfil de $unick</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////User topics
else if($action=="uposts")
{
$who = $_GET["who"];
$denick = getnick_uid($who);
adicionar_online(getuid_sid($sid),"Vendo posts de $who","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, dtpost  FROM fun_posts  WHERE uid='".$who."'ORDER BY dtpost DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$tid = gettid_pid($item[0]);
$tname = gettname($tid);
if(canaccess(getuid_sid($sid),getfid_tid($tid)))
{
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid&go=$item[0]\">".htmlspecialchars($tname)."</a> ".date("d m y-H:i:s",$item[1])."<br/>";
}else{
echo "Private Post<br/>";
}
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
$unick = getnick_uid($who);
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$who\">";
echo "$unick's Profile</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Banned
else if($action=="banned")
{
adicionar_online(getuid_sid($sid),"Vendo usuarios banidos","");
echo "<p align=\"center\">";
echo "<b>Lista de Banidos</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT count(*) FROM fun_ban WHERE tipoban='1' OR tipoban='2'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid, tipoban, motivo FROM fun_ban WHERE tipoban='1' OR tipoban='2' ORDER BY tempo LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> (".htmlspecialchars($item[2]).")";
if($item[1]=="1")
{
//set ban type
$bt = "Ban Normal";
}else
{
//set ban type
$bt = "IP Ban";
}
echo "$lnk $bt<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=banned&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=banned&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estat�sticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Smilies :)
else if($action=="smilies")
{
adicionar_online(getuid_sid($sid),"Smilies","");
$c = addslashes($_GET["c"]);
if(empty($c)||$c==0)$c = 1;
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='".$c."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, scode, imgsrc FROM fun_smilies WHERE cat='".$c."' ORDER BY id DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
if(isadmin(getuid_sid($sid)))
{
$delsl = "<a href=\"admproc.php?action=delsm&sid=$sid&smid=$item[0]\">[X]</a> <a href=\"admincp.php?action=editsml&sid=$sid&smid=$item[0]\">[E]</a>";
}else{
$delsl = "";
}
echo "$item[1] &#187; ";
echo "<img src=\"$item[2]\" alt=\"$item[1]\"/> $delsl<br/>";
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=smilies&c=$c&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=smilies&c=$c&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"c\" value=\"$c\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"paginas.php?p=sml&sid=$sid\">";
echo "Categorias</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Buddies
else if($action=="buds")
{
adicionar_online(getuid_sid($sid),"Vendo lista de amigos","");
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
echo "Frase de Amizade: <br/>";
echo scan_msg_other(getbudmsg($uid), $sid);
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$num_items = getnbuds($uid); //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT a.lastact, a.name, a.id, b.uid, b.tid, b.reqdt FROM fun_users a INNER JOIN fun_buddies b ON (a.id = b.uid) OR (a.id=b.tid) WHERE (b.uid='".$uid."' OR b.tid='".$uid."') AND b.agreed='1' AND a.id!='".$uid."' GROUP BY 1,2  ORDER BY a.lastact DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if(isonline($item[2]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
$uact = "Local: ";
$plc = $pdo->query("SELECT place FROM fun_online WHERE userid='".$item[2]."'")->fetch();
$uact .= $plc[0];
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
$uact = "Última visita: ";
$ladt = date("d/m/y-H:i:s", $item[0]);
$uact .= $ladt;
}
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">$iml".getnick_uid($item[2])."</a>";
echo "$lnk<br/>";
$bs = date("d/m/y-H:i:s",$item[5]);
echo "Amigo desde: $bs<br/>";
echo "$uact<br/>";
$bmsg = scan_msg_other(getbudmsg($item[2]), $sid);
echo "$bmsg<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=buds&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=buds&page=$npage&sid=$sid&view=$view\">Proxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=chbmsg&sid=$sid\">";
echo "Trocar frase de amizade</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////meus recados
else if($action=="gbook")
{
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Vendo recados","");
$uid = getuid_sid($sid);

echo "<p align=\"center\">";
if($who!=$uid)
{
echo "<b>Recados de ".getnick_uid($who)."</b><br />";
}
else
{
echo "<b>Meus Recados</b>";
}
echo "</p>";

//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT gbowner, gbsigner, gbmsg, dtime, id, cor FROM fun_gbook WHERE gbowner='".$who."' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if(isonline($item[1]))
{
//set online icon
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
//set offline icon
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$snick = getnick_uid($item[1]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[1]&sid=$sid\">$iml$snick</a>";
$bs = date("d/m/y - H:i:s",$item[3]);
echo "$lnk<br/>";
if($who == $uid)
{
$delnk = "<a href=\"index.php?action=signgb&sid=$sid&who=$item[1]\">[RESPONDER]</a> <a href=\"genproc.php?action=delfgb&sid=$sid&mid=$item[4]\">[X]</a><br/>";
}
else
{
$delnk = "";
}
$text = scan_msg($item[2], $sid);
$text = getbbcode($text);//liberar bbcode
$text = "<font color=\"$item[5]\">$text</font>";
echo "$text<br/>Data: <small>$bs</small><br/>$delnk<br/>";
echo "";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=$action&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=$action&page=$npage&sid=$sid&who=$who\">Proxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
if(cansigngb($uid, $who))
{
echo "<a href=\"index.php?action=signgb&sid=$sid&who=$who\">";
echo "Novo Recado</a><br/>";
}
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

//////////////////////////////////Lista negra
else if($action=="ignl")
{
adicionar_online(getuid_sid($sid),"Lista negra","");
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
echo "<b>Lista negra</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_ignore WHERE name='".$uid."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT target FROM fun_ignore WHERE name='".$uid."' LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->fetch()>0)
{
while ($item = $items->fetch())
{
$tnick = getnick_uid($item[0]);
if(isonline($item[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$iml$tnick</a>";
echo "$lnk: ";
echo "<a href=\"genproc.php?action=ign&who=$item[0]&sid=$sid&todo=del\">Remover</a><br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=ignl&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=ignl&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=cpanel&sid=$sid\">";
echo "CPanel</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($action=="tshout")
{
adicionar_online(getuid_sid($sid),"Top mural","");
echo "<p align=\"center\">";
echo "<b>Top mural</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$num_items = regmemcount(); //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, shouts FROM fun_users ORDER BY shouts DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> recados: $item[2]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=tshout&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=tshout&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Gammers
else if($action=="bbcode")
{
adicionar_online(getuid_sid($sid),"Vendo BBcode","");
echo "<p align=\"center\">";
echo "<b>BBcode</b>";
echo "</p>";
echo "<p>";
echo "<br/>";
echo "[b]TEXTO[/b]: <b>TEXTO</b><br/>";
echo "[i]TEXTO[/i]: <i>TEXTO</i><br/>";
echo "[u]TEXTO[/u]: <u>TEXTO</u><br/>";
echo "[big]TEXTO[/big]: <big>TEXTO</big><br/>";
echo "[small]TEXTO[/small]: TEXTO<br/>";
echo "[cor=red]TEXTO[/cor]: <font color=\"red\">TEXTO</font><br/>";
echo "[url=<i>http://www.google.com.br</i>]<i>www.google.com.br</i>[/url]: <a href=\"http://www.google.com.br\">www.google.com.br</a><br/>";
echo "[topic=<i>1501</i>]<i>Nome do topico</i>[/topic]: <a href=\"index.php?action=viewtpc&tid=1501&sid=$sid\">Nome do tópico</a><br/>";
echo "[club=<i>1</i>]<i>Nome da comunidade</i>[/club]: <a href=\"index.php?action=gocl&clid=1501&sid=$sid\">Nome da comunidade</a><br/>";
echo "[br/]: para inserir uma linha";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=cpanel&sid=$sid\">";
echo "Configurações</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Staff
else if($action=="staff")
{
adicionar_online(getuid_sid($sid),"Lista da equipe","");
echo "<p align=\"center\">";
echo "";
echo "<b>Lista da equipe</b><br/>";
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE perm='2'")->fetch();
echo "<a href=\"lists.php?action=admns&sid=$sid\">Admins($noi[0])</a><br/>";
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE perm='1'")->fetch();
echo "<a href=\"lists.php?action=modr&sid=$sid\">Moderadores($noi[0])</a>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE perm>'0'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, perm FROM fun_users WHERE perm>'0' ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
if($item[2]=='1')
{
$tit = "Mod";
}else{
$tit = "Admin";
}
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> $tit";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=staff&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=staff&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatásticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Staff
else if($action=="admns")
{
adicionar_online(getuid_sid($sid),"Vendo lista de admins","");
echo "<p align=\"center\">";
echo "<b>Lista de admins</b><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE perm='2'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE perm='2' ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=admns&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=admns&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="modr")
{
adicionar_online(getuid_sid($sid),"Vendo lista de moderadores","");
echo "<p align=\"center\">";
echo "<b>Moderadores</b><br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT count(*) FROM fun_users WHERE perm='1'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name FROM fun_users WHERE perm='1' ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if( $items->rowCount()>0)
{
while ($item = $items->fetch())
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$item[1]</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=modr&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=modr&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($action=="tpweek")
{
adicionar_online(getuid_sid($sid),"Top postadores da semana","");
echo "<p align=\"center\">";
echo "Postadores da semana<br/>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$weekago = time();
$weekago -= 7*24*60*60;
$noi = $pdo->query("SELECT COUNT(DISTINCT uid)  FROM fun_posts WHERE dtpost>'".$weekago."';")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid, COUNT(*) as nops FROM fun_posts  WHERE dtpost>'".$weekago."'  GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$unick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$unick</a> Posts: $item[1]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=tpweek&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=tpweek&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Top Posters List
else if($action=="tptime")
{
adicionar_online(getuid_sid($sid),"Vendo top postadores","");
echo "<p align=\"center\">";
echo "Top Postadores";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT uid, COUNT(*) as nops FROM fun_posts   GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$unick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$unick</a> Posts: $item[1]";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=tptime&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=tptime&page=$npage&sid=$sid&view=$view\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Males List
else if($action=="males")
{
adicionar_online(getuid_sid($sid),"Lista de homens","");
echo "<p align=\"center\">";
echo "<b>Homens</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE sex='M'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, birthday FROM fun_users WHERE sex='M' ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$uage = getage($item[2]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> Idade: $uage";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=males&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=males&page=$npage&sid=$sid\">Próxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= " <input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Males List
else if($action=="fems")
{
adicionar_online(getuid_sid($sid),"Lista de mulheres","");
echo "<p align=\"center\">";
echo "<b>Mulheres</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE sex='F'")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, birthday FROM fun_users WHERE sex='F' ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$uage = getage($item[2]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> Idade: $uage";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=fems&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=fems&page=$npage&sid=$sid\">Próxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "	<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pśgina principal</a>";
echo "</p>";
}
//////////////////////////////////Today's Birthday'
else if($action=="bdy")
{
adicionar_online(getuid_sid($sid),"Vendo aniversáriantes","");
echo "<p align=\"center\">";
echo "<b>Aniversáriantes</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, birthday  FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$uage = getage($item[2]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">".getnick_uid($item[0])."</a> idade: $uage";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=bdy&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=bdy&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Browsers
else if($action=="brows")
{
adicionar_online(getuid_sid($sid),"Vendo listas de navegadores","");
echo "<p align=\"center\">";
echo "<b>Navegadores</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
$noi = $pdo->query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL ")->fetch();
if($page=="" || $page<=0)$page=1;
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT browserm, COUNT(*) as notl FROM fun_users    WHERE browserm!='' GROUP BY browserm ORDER BY notl DESC LIMIT $limit_start, $items_per_page";
//$moderatorz=mysql_query("SELECT tlphone, COUNT(*) as notl FROM users GROUP BY tlphone ORDER BY notl DESC LIMIT  ".$pagest.",5");
$cou = $limit_start;
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$cou++;
$lnk = "$cou-$item[0] <b>$item[1]</b>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"lists.php?action=brows&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"lists.php?action=brows&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"lists.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatísticas</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
echo "</body>";
echo "</html>";
?>
