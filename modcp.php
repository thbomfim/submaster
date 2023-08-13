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
$uid = getuid_sid($sid);
$who = $_GET["who"];

if(!ismod(getuid_sid($sid)))
{
echo "<p align=\"center\">";
echo "Você não é moderador!<br/>";
echo "<br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
exit();
}
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

if($action=="enviarpm")
{
echo "<p align=\"center\">";
$pmtou = $_POST["pmtou"];
$para = $_POST["para"];
$byuid = getuid_sid($sid);
$tm = time();
if(empty($pmtou))
{
echo "<b>Digite um texto!</b><br>";
exit;
}
if($para=="todos")
{
$pms = $pdo->query("SELECT id, name FROM fun_users WHERE lastact>'".$tm24."'");
$t = ".coletiva.[br/]$pmtou [br/][br/]Coletiva $snome, Responda em caso de dúvida!";
}
else if($para=="equipe")
{
$pms = $pdo->query("SELECT id, name FROM fun_users WHERE perm>'0'");
$t = ".coletiva.[br/]$pmtou [br/][br/]Coletiva $snome, Responda em caso de dúvida!";
}
else if($para=="vips")
{
$pms = $pdo->query("SELECT id, name FROM fun_users WHERE vip>'0'");
$t = ".coletiva.[br/] $pmtou [br/][br/]Coletiva $snome, Responda em caso de dúvida!";
}
$tm = time();
while($pm= $pms->fetch())
{
$pdo->query("INSERT INTO fun_private SET text='".$t."', byuid='".$byuid."', touid='".$pm[0]."', timesent='".$tm."'");
}
//log
$msg = "%$uid% enviou um Coletiva $snome para ".strtoupper($para)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"\">Torpedo coletivo enviado com sucesso!<br>";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////rules da equipe
if($action=="rules")
{
echo "<p align=\"center\">";
echo "<b>Regras da Equipe</b>";
echo "</p>";
echo "<p>";
echo " - A equipe deve sempre falar com os usuários do site, além de dar sempre boas vindas a um novo usuário!";
echo "<br />";
echo " - Não tenha preconceito com nenhum usuário do site, dependente de cor, raça, sexo, religião, etc...";
echo "<br />";
echo " - Nunca adicione smilies de enormes(largura, altura) no site, além deixa-lo bagunçado, desorganizado, demora no carregamento das páginas!";
echo "<br />";
echo " - A equipe não está autorizada de fazer fofoca de outros sites, em respectivos na WAP!";
echo "<br />";
echo " - Nunca dá status para ninguém sem consultar a equipe em geral, verifique sempre se o usuário se destaca no site!";
echo "<br />";
echo " - Ao enviar ".strtolower($smoeda)." para algum usuário do site, por favor especifique o motivo do benefício!";
echo "<br />";
echo " - Lembre-se disso, a EQUIPE é a forma do site, ela que vai fazer o site ter sucesso, por isso cumpra sempre as regras propostas aqui!";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////////add vip no site
else if($action=="addvip")
{
echo "<p align=\"center\">";
$a = addslashes($_GET["a"]);
if($a=="a")
{
$tipo = 1;
$log = "adicionou";
}
else if($a=="r")
{
$tipo = 0;
$log = "removeu";
}
else
{
$tipo = 0;
$log = "removeu";
}
$perm = $pdo->query("SELECT perm FROM fun_users WHERE id='".$who."'")->fetch();
if($perm[0]==0)
{
$res = $pdo->query("UPDATE fun_users SET vip='".$tipo."' WHERE id='".$who."'");
if($res)
{
//log
$whn = getnick_uid2($who);
$msg = "%$uid% $log status VIP de $whn!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"*\">VIP atualizado com sucesso!";
echo "<br />";
echo "</p>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Erro ao adicionar VIP";
echo "<br />";
echo "</p>";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Esse usuário é da equipe do site, e não pode ser VIP!";
echo "<br />";
echo "</p>";
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a><br></p>";
}
else if($action=="main")
{
//log
$msg = "%$uid% entrou no Mod CP!";
addlog($msg);
adicionar_online(getuid_sid($sid),"Mod CP","");
echo "<p align=\"center\">";
echo "<b>Reportados</b>";
echo "</p>";
echo "<p>";
$nrpm = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE reported='tk'")->fetch();
echo "<a href=\"modcp.php?action=rpm&sid=$sid\">&#187;Torpedos($nrpm[0])</a><br/>";
$nrps = $pdo->query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'")->fetch();
echo "<a href=\"modcp.php?action=rps&sid=$sid\">&#187;Portagens($nrps[0])</a><br/>";
$nrtp = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'")->fetch();
echo "<a href=\"modcp.php?action=rtp&sid=$sid\">&#187;T�picos($nrtp[0])</a><br>";
$logs = $pdo->query("SELECT COUNT(*) FROM fun_log")->fetch();
echo "<a href=\"modcp.php?action=log&sid=$sid\">&#187;Logs($logs[0])</a><br/>";
echo "<a href=\"modcp.php?action=rules&sid=$sid\">&#187;Regras da equipe</a><br>";
echo "<p align=\"center\">";
echo "<b>Coletiva EstaçãoWAP</b>";
echo "</p>";
echo "<form action=\"modcp.php?action=enviarpm&sid=$sid\" method=\"post\">";
echo "Texto: <input type=\"text\" name=\"pmtou\" size=\"15\" maxlength=\"500\" /><br/>Para: <select name=\"para\"><option value=\"equipe\">Equipe</option><option value=\"vips\">VIPs</option><option value=\"todos\">Todos</option></select><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "<br/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////logs
else if($action=="log")
{
echo "<p align=\"center\">";
echo "<b>Logs da Equipe</b>";
echo "</p>";
$page = $_GET["page"];
if($page=="" || $page<=0)$page=1;
$list = $pdo->query("SELECT COUNT(*) FROM fun_log ")->fetch();
$num_items = $list[0];
$items_per_page= 15;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$comando = $pdo->query("SELECT id, msg, data FROM fun_log ORDER BY data DESC LIMIT $limit_start, $items_per_page");
if($comando->rowCount() > 1)
{
echo "<p>";
while($a = $comando->fetch())
{
	$e = explode("%", $a[1]);
	$e = explode("%", $e[1]);
	$a[1] = str_replace("%$e[0]%", getnick_uid($e[0]), $a[1]);
$tempo = date("d/m/y - H:i:s", $a[2]);
echo $a[1];
echo "<br />";
echo "<small>Em: $tempo</small>";
echo "<br />";
echo "<br />";
}
echo "</p>";
echo "<p align=\"center\">";
if($page > 1)
{
$voltar = $page - 1;
echo "<a href=\"?action=log&page=$voltar&sid=$sid\">&#171;Anterior</a> ";
}
if($page < $num_pages)
{
$mais = $page + 1;
echo "<a href=\"?action=log&page=$mais&sid=$sid\">Próximo&#187;</a>";
}
echo "<br />";
echo "$page/$num_pages";
echo "<br />";
echo "</p>";
}
else
{
echo "Nenhum log encontrado!";
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////Reported PMs
else if($action=="rpm")
{
$page = $_GET["page"];
echo "<p align=\"center\">";
echo "<b>Torpedos reportados</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE reported ='tk'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT id, text, byuid, touid, timesent FROM fun_private WHERE reported='tk' ORDER BY timesent DESC LIMIT $limit_start, $items_per_page";
$items = $pdo->query($sql);
while ($item= $items->fetch())
{
$fromnk = getnick_uid($item[2]);
$tonick = getnick_uid($item[3]);
$dtop = date("d m y - H:i:s", $item[4]);
$text = scan_msg($item[1]);
$flk = "<a href=\"index.php?action=perfil&sid=$sid&who=$item[2]\">$fromnk</a>";
$tlk = "<a href=\"index.php?action=perfil&sid=$sid&who=$item[3]\">$tonick</a>";
echo "De: $flk para: $tlk<br/>hora: $dtop<br/>";
echo $text;
echo "<br/>";
if(isadmin(getuid_sid($sid)))
{
echo "<a href=\"modproc.php?action=hpm&sid=$sid&pid=$item[0]\">Apagar</a><br/><br/>";
}
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"modcp.php?action=$action&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"modcp.php?action=$action&page=$npage&sid=$sid\">Mais&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?action=main&sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////Reported Posts
else if($action=="rps")
{
$page = $_GET["page"];
echo "<p align=\"center\">";
echo "<b>Portagens reportadas</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_posts WHERE reported ='1'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT id, text, tid, uid, dtpost FROM fun_posts WHERE reported='1' ORDER BY dtpost DESC LIMIT $limit_start, $items_per_page";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
$poster = getnick_uid($item[3]);
$tname = htmlspecialchars(gettname($item[3]));
$dtop = date("d m y - H:i:s", $item[4]);
$text = scan_msg_other($item[1]);
$flk = "<a href=\"index.php?action=perfil&sid=$sid&who=$item[3]\">$poster</a>";
$tlk = "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$item[2]\">$tname</a>";
echo "Postagem: $flk<br/>em: $tlk<br/>hora: $dtop<br/>";
echo $text;
echo "<br/>";
echo "<a href=\"modproc.php?action=hps&sid=$sid&pid=$item[0]\">apagar</a><br/><br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"modcp.php?action=$action&page=$ppage&sid=$sid\">&#171;anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"modcp.php?action=$action&page=$npage&sid=$sid\">próxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?action=main&sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////Reported Topics
else if($action=="rtp")
{
$page = $_GET["page"];
echo "<p align=\"center\">";
echo "<b>Tópicos reportados</b>";
echo "</p>";
echo "<p>";
echo "<small>";
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE reported ='1'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT id, name, text, authorid, crdate FROM fun_topics WHERE reported='1' ORDER BY crdate DESC LIMIT $limit_start, $items_per_page";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
$poster = getnick_uid($item[3]);
$tname = htmlspecialchars($item[1]);
$dtop = date("d m y - H:i:s", $item[4]);
$text = scan_msg_other($item[2]);
$flk = "<a href=\"index.php?action=perfil&sid=$sid&who=$item[3]\">$poster</a>";
$tlk = "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$item[0]\">$tname</a>";
echo "Postagem: $flk<br/>em: $tlk<br/>hora: $dtop<br/>";
echo $text;
echo "<br/>";
echo "<a href=\"modproc.php?action=htp&sid=$sid&tid=$item[0]\">apagar</a><br/><br/>";
}
echo "</small>";
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"modcp.php?action=$action&page=$ppage&sid=$sid\">&#171;Voltar</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"modcp.php?action=$action&page=$npage&sid=$sid\">Mais&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"modcp.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br/><br/>";
echo "<a href=\"modcp.php?action=main&sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////////////////////////Mod a user
else if($action=="user")
{
$who = $_GET["who"];
echo "<p align=\"center\">";
$unick = getnick_uid($who);
echo "<b>Moderar $unick</b>";
echo "</p>";
echo "<p>";
//log
$msg = "%$uid% está moderando ".getnick_uid2($who)."!";
addlog($msg);
echo "<a href=\"modcp.php?action=penopt&sid=$sid&who=$who\">&#187;Penalidades</a><br/>";
echo "<a href=\"modcp.php?action=plusses&sid=$sid&who=$who\">&#187;$smoeda</a><br/><br/>";
if(is_banido($who))
{
echo "<a href=\"modproc.php?action=unbn&sid=$sid&who=$who\">&#187;Desbanir membro</a><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo getnick_uid($uid)." a partir de agora todas as suas ações ao moderar $unick serão gravadas!";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////Penalties Options
else if($action=="penopt")
{
$who = $_GET["who"];
echo "<p align=\"center\">";
$unick = getnick_uid($who);
echo "</p>";
echo "<p>";
//log
$msg = "%$uid% está verificando uma penalidade para ".getnick_uid2($uid);
addlog($msg);
$pen = array();
$pen[0]="Banir";
$pen[1]="Banir o IP";
echo "<form action=\"modproc.php?action=pun&sid=$sid\" method=\"post\">";
echo "*Penalidade: <select name=\"pid\">";
for($i=0;$i<count($pen);$i++)
{
echo "<option value=\"$i\">$pen[$i]</option>";
}
echo "</select><br/>";
echo "*Motivos: <input name=\"pres\" maxlength=\"100\"/><br/>";
echo "*Dias: <input name=\"pds\" format=\"*N\" maxlength=\"4\"/><br/>";
echo "*Horas: <input name=\"phr\" format=\"*N\" maxlength=\"4\"/><br/>";
echo "Minutos: <input name=\"pmn\" format=\"*N\" maxlength=\"2\"/><br/>";
echo "Segundos: <input name=\"psc\" format=\"*N\" maxlength=\"2\"/><br/>";
echo "<input type=\"submit\" value=\"OK\"/>";
echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "*Dados extremamente importantes!<br><br>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="plusses")
{
$who = $_GET["who"];
$wnick = getnick_uid($who);
echo "<p align=\"center\">";
echo "<b>Pontos de $wnick!</b>";
echo "</p>";
$msg = "%$uid% está verificando se vai add ou remover pontos de ".getnick_uid2($who)."!";
addlog($msg);
echo "<form action=\"modproc.php?action=plusses&who=$who&sid=$sid\" method=\"post\">";
echo "Ação: <select name=\"acao\">";

echo "<option value=\"0\">Remover</option>";

echo "<option value=\"1\">Adicionar</option>";
echo "</select><br />";
echo "Motivo: <input name=\"motivo\" type=\"text\"><br />";
echo "Pontos: <input name=\"pontos\" type=\"text\"><br />";
echo "<input type=\"submit\" value=\"Atualizar\">";
echo "</form>";
echo "<p align=\"center\">";
echo "<a href=\"modcp.php?action=main&sid=$sid\">Mod R/L</a>";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
else
{
}
echo "</body>";
echo "</html>";
?>
