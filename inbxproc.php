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
$pmtext = $_POST["pmtext"];
$who = $_GET["who"];
$cor = $_POST["cor"];
$pmid = $_GET["pmid"];
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
/////sendpm page fim
if($action=="sendpm")
{
echo "<p align=\"center\">";
$whonick = getnick_uid($who);
$byuid = getuid_sid($sid);
$tm = time();
$lastpm = $pdo->query("SELECT MAX(timesent) FROM fun_private WHERE byuid='".$byuid."'")->fetch();
$numpm = $pdo->query("SELECT numpm FROM fun_users WHERE id='".$who."'")->fetch();
$pmfl = $lastpm[0]+flood_torpedos();
if($pmfl<$tm)
{
if((!isignored($byuid, $who)))
{
if($numpm[0]=="")
{
$num = "0" ;
$pdo->query("UPDATE fun_users SET numpm='".$num."' WHERE id='".$uid."'");
}else
{
$num = $numpm[0]+1;
$pdo->query("UPDATE fun_users SET numpm='".$num."' WHERE id='".$who."'");
}
$res = $pdo->query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='".$who."', timesent='".$tm."', cor='".$cor."', num='".$num."'");
}else
{
$res = true;
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>";
echo "Torpedo enviado com sucesso para $whonick!";
echo "<br />";
echo "<br />";
if(isspam($pmtext))
{
$idpm = $pdo->query("SELECT id FROM fun_private WHERE text='".$pmtext."' AND touid='".$who."'")->fetch();
$pdo->query("UPDATE fun_private SET reported='tk' WHERE id='".$idpm[0]."'");
}
$pmtext2 = scan_msg($pmtext, $sid);
echo "<div style=\"color:$cor\">$pmtext2</div>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
echo "Impossivel enviar para $whonick!";
echo "<br />";
echo "<br />";
}
}else{
$rema = $pmfl - $tm;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
echo "Flood control: $rema Segundos<br/><br/>";
} 
echo "<center>";
echo "<br />";
echo "<br />";
echo "<a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="sendto")
{
echo "<p align=\"center\">";
$pmtou = $_POST["pmtou"];
$who = getuid_nick($pmtou);
if($who==0)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Usuário não existe!<br/>";
}else{
$whonick = getnick_uid($who);
$byuid = getuid_sid($sid);
$tm = time();
$lastpm = $pdo->query("SELECT MAX(timesent) FROM fun_private WHERE byuid='".$byuid."'")->fetch();
$numpm = $pdo->query("SELECT numpm FROM fun_users WHERE id='".$who."'")->fetch();
$pmfl = $lastpm[0]+flood_torpedos();
if($pmfl<$tm)
{
if(!isspam($pmtext,$byuid))
{
if((!isignored($byuid, $who))&&(!istrashed($byuid)))
{
$num = $numpm[0]+1;
$res = $pdo->query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='".$who."', timesent='".$tm."', cor='".$cor."', num='".$num."'");
}else{
$res = true;
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>";
echo "Torpedo enviado com sucesso para $whonick<br/><br/>";
$pmtext2 = scan_msg($pmtext, $sid);
echo "<div style=\"color:$cor\">$pmtext</div>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
echo "Impossivel enviar para $whonick<br/><br/>";
}
}else{
$bantime = time() + (7*24*60*60);
echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
echo "Impossivel enviar para $whonick<br/><br/>";
echo "Voce enviou url de site proibido esta banido!";
$pdo->query("INSERT INTO fun_penalties SET uid='".$byuid."', penalty='1', exid='1', timeto='".$bantime."', pnreas='Banned: Automatic Ban for spamming for a crap site'");
$pdo->query("UPDATE fun_users SET plusses='0', shield='0' WHERE id='".$byuid."'");
$pdo->query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='2', timesent='".$tm."'");
}
}
else
{
$rema = $pmfl - $tm;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
echo "Flood control: $rema Segundos<br/><br/>";
}
}
echo "<br/><br/><a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="proc")
{
$pmact = $_POST["pmact"];
$pact = explode("-",$pmact);
$pmid = $pact[1];
$pact = $pact[0];
echo "<p align=\"center\">";
$pminfo = $pdo->query("SELECT text, byuid, touid, reported FROM fun_private WHERE id='".$pmid."'")->fetch();
if($pact=="del")
{
adicionar_online(getuid_sid($sid),"Apagando torpedos","");
if(getuid_sid($sid)==$pminfo[2])
{
if($pminfo[3]=="1")
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Torpedo ja reportado!";
}else{
$del = $pdo->query("DELETE FROM fun_private WHERE id='".$pmid."' ");
if($del)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Torpedo apagado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel no momento!";
}
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Este torpedo não é seu!";
}
}else if($pact=="str")
{
adicionar_online(getuid_sid($sid),"Marcando torpedo","");
if(getuid_sid($sid)==$pminfo[2])
{
$str = $pdo->query("UPDATE fun_private SET starred='1' WHERE id='".$pmid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Torpedo marcado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel marcar no momento!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Este torpedo não é seu!";
}
}else if($pact=="ust")
{
adicionar_online(getuid_sid($sid),"Desmarcando torpedo","");
if(getuid_sid($sid)==$pminfo[2])
{
$str = $pdo->query("UPDATE fun_private SET starred='0' WHERE id='".$pmid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Torpedo desmarcado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel desmarcar no momento!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Este torpedo não é seu!";
}
}else if($pact=="rpt")
{
adicionar_online(getuid_sid($sid),"Reportando torpedo","");
if(getuid_sid($sid)==$pminfo[2])
{
if($pminfo[3]=="0")
{
$str = $pdo->query("UPDATE fun_private SET reported='tk' WHERE id='".$pmid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Reportado com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel no momento!!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Torpedo já reportado!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Esse torpedo não é seu!";
}
}
echo "<br/><br/><a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="proall")
{
$pact = addslashes($_POST["pmact"]);
echo "<p align=\"center\">";
adicionar_online(getuid_sid($sid),"Apagando torpedos","");
$uid = getuid_sid($sid);
if($pact=="red")//apaga todos os torpedos lidos
{
$del = $pdo->query("DELETE FROM fun_private WHERE touid='".$uid."' AND reported !='1' and unread='0'");
if($del)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todos os torpedos lidos foram apagados!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel no momento!";
}
}
else if($pact=="all")//apaga todos os torpedos
{
$del = $pdo->query("DELETE FROM fun_private WHERE touid='".$uid."' AND reported !='1'");
if($del)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todos os torpedos foram apagados com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel no momento!";
}
}
echo "<br/><br/><a href=\"inbox.php?action=main&sid=$sid\">Voltar aos torpedos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="dpm")
{

}
echo "</body>";
echo "</html>";
?>