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

if(!ismod(getuid_sid($sid)))
{
echo "<p align=\"center\">";
echo "Você não é moderador<br/>";
echo "<br/>";
echo "<a href=\"index.php\">Página principal</a>";
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

if($action=="delp")
{
$pid = $_GET["pid"];//id
$tid = gettid_pid($pid);
$fid = getfid_tid($tid);
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_posts WHERE id='".$pid."'");
if($res)
{
$tname = $pdo->query("SELECT name FROM fun_topics WHERE id='".$tid."'")->fetch();
//log 
$msg = "%$uid% apagou a postagem de ID $pid no tópico $tname[0]/".getfname($fid)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem deletada com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error no banco de dados!";
}

echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid&page=1000\">";
echo "Ver tópico</a><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////editar postagem
else if($action=="edtpst")
{
$pid = $_GET["pid"];//id
$ptext = $_POST["ptext"];//texto
$tid = gettid_pid($pid);
$fid = getfid_tid($tid);

echo "<p align=\"center\">";
$res = $pdo->query("UPDATE fun_posts SET text='".$ptext."' WHERE id='".$pid."'");
if($res)
{
$tname = $pdo->query("SELECT name FROM fun_topics WHERE id='".$tid."'")->fetch();
//log 
$msg = "%$uid% editou postagem ID($pid) do tópico $tname[0]!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem editada com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid\">";
echo "Ver Tópico</a><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

/////////////////////////////editar topico
else if($action=="edttpc")
{
$tid = $_GET["tid"];
$ttext = $_POST["ttext"];
$fid = getfid_tid($tid);

echo "<p align=\"center\">";
$res = $pdo->query("UPDATE fun_topics SET text='".$ttext."' WHERE id='".$tid."'");
if($res)
{
//log 
$msg = "%$uid% editou o tópico ".gettname($tid)."/".getfname($fid)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico editado com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid\">";
echo "Ver Topico</a><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

//////////////////////////abrir e fexar topicos
else if($action=="clot")
{
$tid = $_GET["tid"];
$tdo = $_GET["tdo"];
$fid = getfid_tid($tid);
echo "<p align=\"center\">";
$res = $pdo->query("UPDATE fun_topics SET closed='"
.$tdo."' WHERE id='".$tid."'");
if($res)
{
if($tdo==1)
{
$msg = "fexado";
}
else
{
$msg = "aberto";
}
//log 
$m = "%$uid% deixou $msg o tópico ".gettname($tid)."/".getfname($fid)."!";
addlog($m);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico $msg!";
$tpci = $pdo->query("SELECT name, authorid FROM fun_topics WHERE id='".$tid."'")->fetch();
$tname = htmlspecialchars($tpci[0]);
$msg = "Olá /reader, seu tópico [topic=$tid]$tname"."[/topic] foi $msg"."[br/][br/]Torpedo Automático!";
autopm($msg, $tpci[1]);
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}

echo "<br/><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//desbanir membro
else if($action=="unbn")
{
$who = $_GET["who"];
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_ban WHERE (tipoban='1' OR tipoban='2') AND uid='".$who."'");
if($res)
{
$unick = getnick_uid($who);
$msg = "%$uid% desbaniu o usuário ".getnick_uid2($who);
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"*\">Penalidade de $unick removida com sucesso!";
echo "<br />";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Não foi possivel remover a penalidade!";
echo "<br />";
}
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\">";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////deletar recado do mural
else if($action=="delsh")
{
$shid = $_GET["shid"];
echo "<p align=\"center\">";
$e = $pdo->query("SELECT COUNT(*) FROM fun_shouts WHERE id='".$shid."'")->fetch();
if($e[0]==0 || $shid==0 || empty($shid))
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Esse recado não existe!";
}
else
{
$res = $pdo->query("DELETE FROM fun_shouts WHERE id ='".$shid."'");
if($res)
{
$msg = "%$uid% apagou recado ID($shid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Recado apagado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao apagar o recado!";
}
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

////////////////////destacar topico
else if($action=="pint")
{
$tid = $_GET["tid"];
$tdo = $_GET["tdo"];
$fid = getfid_tid($tid);
echo "<p align=\"center\">";
$pnd = getpinned($fid);
if($pnd<=5)//verifica se a cat tem mais de 5 topicos destacados
{
$res = $pdo->query("UPDATE fun_topics SET pinned='".$tdo."' WHERE id='".$tid."'");
if($res)
{
if($tdo==1)
{
$msg = "destacado";
}
else
{
$msg = "não destacado";
}
$m = "%$uid% deixou o tópico ".gettname($tid)." $msg!";
addlog($m);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico $msg!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você só pode usar essa opção em 5 tópicos por categoria!";
}
echo "<br/><br/>";

$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

/////////////////////////////apagar topico
else if($action=="delt")
{
$tid = $_GET["tid"];//id topico
$fid = getfid_tid($tid);

echo "<p align=\"center\">";
$nome_topico = gettname($tid);
$res = $pdo->query("DELETE FROM fun_topics WHERE id='".$tid."'");
if($res)
{
//log
$msg = "%$uid% apagou o tópico $nome_topico/".getfname($fid)."!";
addlog($msg);
$pdo->query("DELETE FROM fun_posts WHERE tid='".$tid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico apagado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br/><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

//////////////////////////////editar postagem
else if($action=="rentpc")
{
$tid = $_GET["tid"];//id topico
$tname = $_POST["tname"];
$fid = getfid_tid($tid);

echo "<p align=\"center\">";
$otname = gettname($tid);
if(trim($tname!=""))
{
$not = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$fid."'")->fetch();
if($not[0]==0)
{
$res = $pdo->query("UPDATE fun_topics SET name='"
.$tname."' WHERE id='".$tid."'");
if($res)
{
$msg = "%$uid% renomeou o tópico $otname para $tname/".getfname($fid)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico renomeado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico já existe!";
}
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Digite o nome do tópico!";
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid\">";
echo "Ver topico</a><br/>";
$fname = getfname($fid);
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

/////////////////////////////mover topico
else if($action=="mvt")
{
$tid = $_GET["tid"];
$mtf = $_POST["mtf"];
$fname = htmlspecialchars(getfname($mtf));

echo "<p align=\"center\">";

$not = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$mtf."'")->fetch();
if($not[0]==0)
{
$res = $pdo->query("UPDATE fun_topics SET fid='".$mtf."', moved='1' WHERE id='".$tid."'");
if($res)
{
$msg = "%$uid% moveu o tópico de $tid para o $fname!";
addlog($msg);
$tpci = $pdo->query("SELECT name, authorid FROM fun_topics WHERE id='".$tid."'")->fetch();
$tname = htmlspecialchars($tpci[0]);
$msg = "Olá /reader, seu tópico [topic=$tid]$tname"."[/topic] foi movido para $fname![br/][br/]Torpedo Automático!";
autopm($msg, $tpci[1]);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico movido com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico com esse nome já existe";
}

echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$mtf\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

///////////////////////////////apagar post
else if($action=="hps")
{
$pid = $_GET["pid"];
echo "<p align=\"center\">";
$info = $pdo->query("SELECT uid, tid FROM fun_posts WHERE id='".$pid."'")->fetch();
$res = $pdo->query("UPDATE fun_posts SET reported='2' WHERE id='".$pid."'");
if($res)
{
$msg = "%$uid% apagou a postagem ID($pid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem apagada com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br />";
echo "<br />";
$poster = getnick_uid($info[0]);
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$info[0]\">$poster perfil</a><br/>";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$info[1]\">Ver perfil</a><br/><br/>";
echo "<a href=\"modcp.php?action=main&sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

//////////////////////////////////////apagar topico
else if($action=="htp")
{
$pid = $_GET["tid"];
echo "<p align=\"center\">";
$info = $pdo->query("SELECT authorid FROM fun_topics WHERE id='".$pid."'")->fetch();
$res = $pdo->query("UPDATE fun_topics SET reported='2' WHERE id='".$pid."'");
if($res)
{
$msg = "%$uid% apagou o tópico ".gettname($pid)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico apagado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br />";
echo "<br />";
$poster = getnick_uid($info[0]);
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$info[0]\">$poster perfil</a><br/>";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$pid\">Ver Topico</a><br/><br/>";
echo "<a href=\"modcp.php?action=main&sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}

/////////////////////////////////punicoes 
else if($action=="pun")
{
$pid = $_POST["pid"];
$who = $_POST["who"];
$pres = $_POST["pres"];
$pds = $_POST["pds"];
$phr = $_POST["phr"];
$pmn = $_POST["pmn"];
$psc = $_POST["psc"];

if($who=="1" OR isadmin($who))
{
exit();
}    
echo "<p align=\"center\">";

$uip = "";
$ubr = "";
$pmsg = array();
$pmsg[0]="Banido";
$pmsg[1]="IP Banido";
$pin = $pid;
$pid = $pid + 1;
if($pid == 2)
{
//so vai registrar o ip
//se o ban for do respectivo
$uip = ver_ip_uid($who);
$ubr = getbr_uid($who);
}
if(trim($pres)=="")
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Digite o motivo do BAN!";
}else
{
$timeto = $pds*24*60*60;
$timeto += $phr*60*60;
$timeto += $pmn*60;
$timeto += $psc;
$ptime = $timeto + time();
$unick = getnick_uid($who);
$res = $pdo->query("INSERT INTO fun_ban SET uid='".$who."', tipoban='".$pid."', browser='".$ubr."', ip='".$uip."', tempo='".$ptime."', motivo='".$pres."'");
if($res)
{
$msg = "%$uid% baniu ".getnick_uid2($who)." por $timeto segundos, motivo: $pres";
addlog($msg);
//deleta as sessoes ativas
$pdo->query("DELETE FROM fun_ses WHERE uid='".$who."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>$unick foi $pmsg[$pin] por $timeto segundos com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="plusses")
{
$who = $_GET["who"];
$acao = $_POST["acao"];
$motivo = $_POST["motivo"];
$pontos = $_POST["pontos"];
$mypontos = getplusses($who);
$nick = getnick_uid($who);
echo "<p align=\"center\">";
if(isuser($who))
{
if(empty($motivo))
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você prescisa falar o motivo dos $smoeda!";
}
else if(!is_numeric($pontos) OR empty($pontos))
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Digite quantos $smoeda você deseja enviar!";
}
else
{
if($acao=="0")
{
$n = $mypontos - $pontos;
}
else if($acao=="1")
{
$n = $mypontos + $pontos;
}
$res = $pdo->query("UPDATE fun_users SET plusses='".$n."', lastplreas='".$motivo."' WHERE id='".$who."'");
if($res)
{
$msg = "%$uid% atualizou os $smoeda de ".getnick_uid2($who)." de $mypontos para $n!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"X\"/>Pontos de $nick atualizados de $mypontos para $n!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro MYSQL!";
}
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Usuário não existe!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"modcp.php?action=main&sid=$sid\">Mod R/L</a>";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
//////////////hpm
else if($action=="hpm")
{
$pid = $_GET["pid"];
echo "<p align=\"center\">";
$info = $pdo->query("SELECT byuid, touid FROM fun_private WHERE id='".$pid."'")->fetch();
$res = $pdo->query("UPDATE fun_private SET reported='2' WHERE id='".$pid."'");
if($res)
{
$msg = "%$uid% apagou um torpedo que estava reportado ID($pid)!";addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Torpedo apagado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br/><br/>";

echo "<a href=\"index.php?action=viewuser&amp;sid=$sid&amp;who=$info[0]\">Ver perfil de quem enviou</a><br/>";
echo "<a href=\"index.php?action=viewuser&amp;sid=$sid&amp;who=$info[1]\">ver perfil de quem reportou</a><br/><br/>";
echo "<a href=\"modcp.php?action=main&amp;sid=$sid\">";
echo "Mod R/L</a><br/>";
echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////////////Punish
else if($action=="pls")
{
$pid = $_POST["pid"];//acao
$who = $_POST["who"];
$pres = $_POST["pres"];//motivo
$pval = $_POST["pval"];//pontos

echo "<p align=\"center\">";

$unick = getnick_uid($who);
$opl = getplusses($who);
if($pid=="")
{
$npl = $opl[0] - $pval;
}
else
{
$npl = $opl[0] + $pval;
}
if($npl<0)
{
$npl=0;
}
if(trim($pres)=="")
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Digite o motivo dos pontos!";
}else
{
$res = $pdo->query("UPDATE fun_users SET lastplreas='".$pdo->quote($pres)."', plusses='".$npl."' WHERE id='".$who."'");
if($res)
{
$msg = "%$uid% atualizou os pontos de ".getnick_uid2($who)." de $opl para $npl!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Pontos atualizado de $opl[0] para $npl com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else
{
}
echo "</body>";
echo "</html>";
?>
