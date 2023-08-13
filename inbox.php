<?php
//inludes core.php and config.php file
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
$uid = getuid_sid($sid);
//////////////user logged
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não esté logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
///////user banned
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
/////sendpm
if($action=="sendpm")
{
adicionar_online(getuid_sid($sid),"Enviando torpedo","");
echo "<p align=\"center\">";
$whonick = getnick_uid($who);
$uidnick = getnick_uid($uid);
if(!isuser($who))
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Usuário não encontrado no banco de dados!";
echo "<br />";
}
else if($who == $uid)
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Não é possível enviar torpedos para você mesmo!";
echo "<br />";
}
else 
{
echo "Olá $uidnick, não tente enviar spam ou propagandas de outros sites para $whonick, você poderá ser punido!";
echo "</p>";
echo "<form action=\"inbxproc.php?action=sendpm&who=$who&sid=$sid\" method=\"post\">";
echo "Texto: <input name=\"pmtext\" maxlength=\"500\"/><br/>";
echo "Cor: <select name=\"cor\">";
echo "<option value=\"#000000\">Padrão</option>";
echo "<option value=\"#ff0000\">Vermelho</option>";
echo "<option value=\"#00ff00\">Limão</option>";
echo "<option value=\"#ff00ff\">Pink</option>";
echo "<option value=\"#006600\">Verde</option>";
echo "<option value=\"#33ffff\">Aqua</option>";
echo "<option value=\"#ff9900\">Laranja</option>";
echo "<option value=\"#0000ff\">Azul Marinho</option>";
echo "<option value=\"#393939\">Cinza</option>";
echo "<option value=\"#ececec\">Prata</option>";
echo "<option value=\"#660000\">Chocolate</option>";
echo "<option value=\"#6600cc\">Lilas</option>";
echo "<option value=\"#666600\">Dourado</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////main pms full
else if($action=="main")
{
adicionar_online(getuid_sid($sid),"Vendo Torpedos","");
echo "<p align=\"center\">";
echo "<a href=\"inbox.php?action=main&sid=$sid&atl_".time()."\">";
echo "<img src=\"images/atualizar.gif\" alt=\"\"/>Atualizar</a>";
echo "<br />";
echo "<form action=\"inbox.php\" method=\"get\">";
echo "Ver: <select name=\"view\">";
echo "<option value=\"all\">Todos</option>";
echo "<option value=\"snt\">Enviados</option>";
echo "<option value=\"str\">Marcados</option>";
echo "<option value=\"urd\">Nao lidos</option>";
echo "</select>";
echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
echo "<br><input type=\"submit\" value=\"OK\"/>";
echo "</form>";
echo "</p>";
//////view get code and addslashes protector
$view = addslashes($_GET["view"] ?? '');
//////ALL LISTS SCRIPT <<
if($view==""||empty($view)||is_numeric($view))
{
////view in case: empty or no numeric 
$view="all";
}
if($page=="" || $page<=0)
{
$page=1;
}
$myid = getuid_sid($sid);
$doit=false;
$num_items = getpmcount($myid,$view); //changable
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
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="snt")
{
$sql = "SELECT
a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.touid
WHERE b.byuid='".$myid."'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="str")
{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
WHERE b.touid='".$myid."' AND b.starred='1'
ORDER BY b.timesent DESC
LIMIT $limit_start, $items_per_page
";
}else if($view=="urd")
{
$sql = "SELECT
a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
INNER JOIN fun_private b ON a.id = b.byuid
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
$iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";//novo torpedo
$nova = "<font style=\"color: #000;\">(Nova!)</font>";
}else
{
if($item[4]=="1")
{
$iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";//torpedo marcado
$nova = "";
}else
{
$iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";//torpedo antigo
$nova="";
}
}
$lnk = "<a href=\"inbox.php?action=readpm&pmid=$item[1]&sid=$sid\">$iml ".getnick_uid($item[2])."</a>$nova";
echo "$lnk<br/>";
}
echo "</small></p>";
echo "<p align=\"center\">";
$npage = $page+1;
echo "<a href=\"m.php?sid=$sid\">Torpedo Multimúdia</a><br/><br/>";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"inbox.php?action=main&page=$ppage&sid=$sid&view=$view$exp\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"inbox.php?action=main&page=$npage&sid=$sid&view=$view$exp\">Próxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"inbox.php\" method=\"get\">";
$rets .= "Pular para página: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "	<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
echo "<br/>";
}
echo "<br/>";
echo "<form action=\"inbxproc.php?action=proall&sid=$sid\" method=\"post\">";
echo "Apagar: <select name=\"pmact\">";
echo "<option value=\"red\">Lidos</option>";
echo "<option value=\"all\">Todos</option>";
echo "</select>";
echo "<br><input type=\"submit\" value=\"Apagar\"/>";
echo "</form>";
echo "</p>";
}
else
{
/////error case pm = 0
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">No momento você não tem novos torpedos!";
echo "</p>";
}
/////final links
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="readpm")
{
adicionar_online(getuid_sid($sid),"Lendo sms","");
echo "<p>";
$pminfo = $pdo->query("SELECT text, byuid, timesent,touid, reported, cor FROM fun_private WHERE id='".$pmid."'")->fetch();
if(getuid_sid($sid)==$pminfo[3])
{
$chread = $pdo->query("UPDATE fun_private SET unread='0' WHERE id='".$pmid."'");//marca como lida
}
if(($pminfo[3]==getuid_sid($sid))||($pminfo[1]==getuid_sid($sid)))
{
if(getuid_sid($sid)==$pminfo[3])
{
if(isonline($pminfo[1]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";//online
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";//off
}
$ptxt = "De: ";
$bylnk = "<a href=\"index.php?action=perfil&who=$pminfo[1]&sid=$sid\">$iml".getnick_uid($pminfo[1])."</a>";
}else
{
if(isonline($pminfo[3]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else
{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$ptxt = "Para: ";
$bylnk = "<a href=\"index.php?action=perfil&who=$pminfo[3]&sid=$sid\">$iml".getnick_uid($pminfo[3])."</a>";
}
echo "$ptxt $bylnk<br/>";
$tmstamp = $pminfo[2];
$tmdt = date("d/m/Y - H:i:s", $tmstamp);
echo "$tmdt<br/><br/>";
$pmtext = scan_msg($pminfo[0], $sid);
$pmtext = str_replace("/reader",getnick_uid($pminfo[3]), $pmtext);
if(isspam($pmtext))
{
////////reporta a pm
if(($pminfo[4]=="0") && ($pminfo[1]!=1))
{
$pdo->query("UPDATE fun_private SET reported='tk' WHERE id='".$pmid."'");
}
}
echo "<div style=\"color:$pminfo[5]\">$pmtext</div>";
echo "</p>";
echo "<p align=\"center\">";
echo "<form action=\"inbxproc.php?action=sendpm&who=$pminfo[1]&sid=$sid&pmid=$pmid\" method=\"post\">";
echo "Resposta: <input name=\"pmtext\" maxlength=\"500\"/><br/>";
echo "Cor: <select name=\"cor\">";
echo "<option value=\"#000000\">Padr�o</option>";
echo "<option value=\"#ff0000\">Vermelho</option>";
echo "<option value=\"#00ff00\">Limão</option>";
echo "<option value=\"#ff00ff\">Pink</option>";
echo "<option value=\"#006600\">Verde</option>";
echo "<option value=\"#33ffff\">Aqua</option>";
echo "<option value=\"#ff9900\">Laranja</option>";
echo "<option value=\"#0000ff\">Azul Marinho</option>";
echo "<option value=\"#393939\">Cinza</option>";
echo "<option value=\"#ececec\">Prata</option>";
echo "<option value=\"#660000\">Chocolate</option>";
echo "<option value=\"#6600cc\">Lilas</option>";
echo "<option value=\"#666600\">Dourado</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form><br/>";
$real = $pminfo[6]-1;
echo "<br/><form action=\"inbxproc.php?action=proc&sid=$sid\" method=\"post\">";
echo "Ação: <select name=\"pmact\">";
echo "<option value=\"del-$pmid\">Apagar</option>";
if(isstarred($pmid))
{
echo "<option value=\"ust-$pmid\">Desmarcar</option>";
}else{
echo "<option value=\"str-$pmid\">Marcar</option>";
}
echo "<option value=\"rpt-$pmid\">Reportar</option>";
echo "</select>";
echo "<br> <input type=\"submit\" value=\"IR\"/>";
echo "</form>";
echo "<br/><a href=\"inbox.php?action=dialog&sid=$sid&who=$pminfo[1]\">Ver Dialogo</a>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Torpedo não existe!";
}
echo "<br/><br/><a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////dialog page
else if($action=="dialog")
{
adicionar_online(getuid_sid($sid),"Vendo dialogo","");
$uid = getuid_sid($sid);
if($page=="" || $page<=0)$page=1;
$myid = getuid_sid($sid);
$pms = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE (byuid='".$uid."' AND touid='".$who."') OR (byuid='".$who."' AND touid='".$uid."') ORDER BY timesent")->fetch();
$num_items = $pms[0]; //changable
$items_per_page= 7;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($num_items>0)
{
echo "<p>";
$pms = $pdo->query("SELECT byuid, text, timesent FROM fun_private WHERE (byuid='".$uid."' AND touid='".$who."') OR (byuid='".$who."' AND touid='".$uid."') ORDER BY timesent LIMIT $limit_start, $items_per_page");
while($pm= $pms->fetch())
{
if(isonline($pm[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else
{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$bylnk = "<a href=\"index.php?action=perfil&who=$pm[0]&sid=$sid\">$iml".getnick_uid($pm[0])."</a>";
echo $bylnk;
$tmopm = date("d m y - h:i:s",$pm[2]);
echo " <small>$tmopm<br/>";
echo scan_msg($pm[1], $sid);
echo "</small>";
echo "<br/>--------------<br/>";
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"inbox.php?action=dialog&page=$ppage&sid=$sid&who=$who\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"inbox.php?action=dialog&page=$npage&sid=$sid&who=$who\">Pr�xima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"inbox.php\" method=\"get\">";
$rets .= "Pular para p�gina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= " <input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "</form>";
echo $rets;
}
}else
{
echo "<p align=\"center\">";
echo "Sem dialogos dispon�veis!";
echo "<br />";
}
//////fim link
echo "<br />";
echo "<a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
echo "</body>";
?>