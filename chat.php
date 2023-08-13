<?php

include("config.php");
include("core.php");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "<head>";
echo "<title>$stitle</title>";
echo "</head>";

echo "<body>";


$who = $_GET["who"];
$action = $_GET["action"];
$id = $_GET["id"];
$sid = $_GET["sid"];
$rid = $_GET["rid"];
$rpw = $_GET["rpw"];
$uid = getuid_sid($sid);

$uexist = isuser($uid);

if((is_logado($sid)==false)||!$uexist)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

$isroom = $pdo->query("SELECT COUNT(*) FROM fun_rooms WHERE id='".$rid."'")->fetch();
if($isroom[0]==0)
{
echo "<p align=\"center\">";
echo "Esta sala não existe!<br/>";
echo "<br/>";
echo "<a href=\"index.php?action=chat&sid=$sid\">Bate-Papo</a>";
echo "</p>";

exit();
}
$passworded = $pdo->query("SELECT pass FROM fun_rooms WHERE id='".$rid."'")->fetch();
if($passworded[0]!="")
{
if($rpw!=$passworded[0])
{

echo "<p align=\"center\">";
echo "Você não pode entrar nesta sala<br/>";
echo "<br/>";
echo "<a href=\"index.php?action=chat&sid=$sid\">Salas de Bate-Papo</a>";
echo "</p>";

exit();
}
}
if(!canenter($rid,$sid))
{

echo "<p align=\"center\">";
echo "Não pode entrar nesta sala!<br/>";
echo "<br/>";
echo "<a href=\"index.php?action=chat&sid=$sid\">Salas de Bate-Papo</a>";
echo "</p>";

exit();
}
addtochat($uid, $rid);
$timeto = 300;
$timenw = time();
$timeout = $timenw-$timeto;
$deleted = $pdo->query("DELETE FROM fun_chat WHERE timesent<".$timeout."");

if ($action=="")
{

//start of main card

echo "<timer value=\"200\"/><p align=\"center\">";
adicionar_online($uid,"Batepapo","");
echo "<a href=\"chat.php?time=".time()."&sid=$sid&rid=$rid&rpw=$rpw\">Atualizar</a></small> - ";
echo "<a href=\"lists.php?action=smilies&sid=$sid\">Smilies</a><br/>";    
$unreadinbox = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE unread='1' AND touid='".$uid."'")->fetch();
$pmtotl = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE touid='".$uid."'")->fetch();
$unrd="(".$unreadinbox[0]."/".$pmtotl[0].")";
if ($unreadinbox[0]>0)
{
echo "<a href=\"inbox.php?action=main&sid=$sid\">Torpedos$unrd</a><br/>";
}

echo "<form action=\"chat.php?sid=$sid&rid=$rid&rpw=$rpw\" method=\"post\">";

echo "<p><img src=\"images/escrever.gif\" alt=\"x\"/>Mensagem:<input name=\"message\" type=\"text\" value=\"\" maxlength=\"255\"/><br/>";
echo "Para: <select name=\"para\"><option value=\"0\">Todos</option>";
$inside = $pdo->query("SELECT DISTINCT * FROM fun_chonline WHERE rid='".$rid."' and uid IS NOT NULL");

while($ins = $inside->fetch())
{
$unick = getnick_uid2($ins[1]);
echo "<option value=\"$ins[1]\">$unick</option>";
}
echo "</select><br/>";
echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
echo "<input type=\"submit\" value=\"Escrever\"/>";
echo "</form>";
echo "</small></p>";
$message=$_POST["message"];
$para=$_POST["para"];
$who = $_POST["who"];
$rinfo = $pdo->query("SELECT censord, freaky FROM fun_rooms WHERE id='".$rid."'")->fetch();
if (trim($message) != "")
{
$nosm = $pdo->query("SELECT COUNT(*) FROM fun_chat WHERE msgtext='".$message."'")->fetch();
if($nosm[0]==0){

$chatok = $pdo->query("INSERT INTO fun_chat SET  chatter='".$uid."', who='".$who."', para='".$para."', timesent='".time()."', msgtext='".$message."', rid='".$rid."';");
$lstmsg = $pdo->query("UPDATE fun_rooms SET lastmsg='".time()."' WHERE id='".$rid."'");

$hehe = $pdo->query("SELECT chmsgs, plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$totl = $hehe[0]+1;
$totlp = $hehe[1]+2;
$msgst= $pdo->query("UPDATE fun_users SET chmsgs='".$totl."' WHERE id='".$uid."'");
}
$message = "";
}

echo "<p>";
echo "<small>";
$chats = $pdo->query("SELECT chatter, who, timesent, msgtext, exposed, para FROM fun_chat WHERE rid='".$rid."' ORDER BY timesent DESC, id DESC");
$counter=0;

while($chat = $chats->fetch())
{
$canc = true;


if($counter<18)
{
//////good
if(isignored($chat[0],$uid)){
$canc = false;
}
//////////good
if($chat[0]!=$uid)
{
if($chat[1]!=0)
{
if($chat[1]!=$uid)
{
$canc = false;
}
}
}
if($chat[4]=='1' && ismod($uid))
{
$canc = true;
}
if($canc)
{

$chnick = getnick_uid($chat[0]);
$optlink = $iml.$chnick;
if(($chat[1]!=0)&&($chat[0]==$uid))
{
///out
$iml = "<img src=\"moods/out.gif\" alt=\"!\"/>";
$chnick = getnick_uid($chat[1]);

$optlink = "Privado para ".$chnick;
}
if($chat[1]==$uid)
{
///out
$iml = "<img src=\"moods/in.gif\" alt=\"!\"/>";
$chnick = getnick_uid($chat[0]);
$optlink = "Privado de ".$chnick;
}
if($chat[4]=='1')
{
///out
$iml = "<img src=\"images/pin.gif\" alt=\"!\"/>";
$chnick = getnick_uid($chat[0]);
$tonick = getnick_uid($chat[1]);
$optlink = "$iml por ".$chnick." para ".$tonick;
}

$ds= date("H.i.s", $chat[2]);
$text = scan_msg($chat[3], $sid);
$nos = substr_count($text,"<img src=");
if(isspam($text) || substr($chat[3],0,4)=="*mat")
{
$chatters = $pdo->query("SELECT COUNT(*) FROM fun_chonline where rid='".$rid."'")->fetch();
$chnick = getnick_uid($chat[0]);
if(isspam($text)){
echo "<b>Sistema: $chnick, não faça spam!</b><br/>";
}else
{
echo "<b>Sistema:<br/>$chnick entrou na sala com ".getbr_uid($chat[0])."</i></b><br/>";
}
}
else if($nos>5){
$chnick = getnick_uid($chat[0]);
echo "<b>Sistema: </b> $chnick você pode postar até 5 smilies por mensagem!";
}else
{

$tosay = scan_msg($chat[3], $sid);

if($rinfo[1]==1)
{
$tosay = htmlspecialchars($chat[3]);
$tosay = strrev($tosay);
}
if($chat[5]=="0" OR $chat[5]=="")
{
$epara = "Todos";
}
else{
$epara = "<a href=\"chat.php?action=say2&sid=$sid&who=$chat[5]&rid=$rid&rpw=$rpw\">".getnick_uid($chat[5])."</a>";
}
echo "<a href=\"chat.php?action=say2&sid=$sid&who=$chat[0]&rid=$rid&rpw=$rpw\">$optlink</a> para $epara: ";
echo $tosay."<br/>";
}
}

$counter++;
}
}

echo "</small>";
echo "</p>";
echo "</table></div></div>";

echo "<p align=\"center\">";
$chatters = $pdo->query("SELECT COUNT(*) FROM fun_chonline where rid='".$rid."'")->fetch();
echo "<br/><a href=\"chatmulti.php?sid=$sid&rid=$rid&rpw=$rpw\">Enviar Multimídia</a> - <a href=\"chat.php?action=inside&sid=$sid&rid=$rid&rpw=$rpw\">Membros na Sala($chatters[0])</a> - ";
echo "<a href=\"index.php?action=chat&sid=$sid\">Salas de Chat</a> - <a href=\"?action=sair&sid=$sid&rid=$rid\">Sair</a><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a></p>";
}

else if ($action=="say2")                  
{
adicionar_online($uid,"Escrevendo Privado","");
$who = $_GET["who"];      
$unick = getnick_uid($who);
echo "<p align=\"center\">";
echo "<b>Escrevendo privado para $unick</b><br>";
echo "<form action=\"chat.php?sid=$sid&rid=$rid\" method=\"post\">";
echo "<p>Mensagem:<input name=\"message\" type=\"text\" value=\"\" maxlength=\"255\"/><br/>";
echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<br>";
echo "<p align=\"center\">";
echo "<a href=\"chat.php?sid=$sid&rid=$rid\">&#171; Voltar para a sala</a></p>";
}
else if ($action=="inside")         
{
adicionar_online($uid,"Membros no Chat","");
echo "<p align=\"center\">";
echo "<b>Usuários na Sala</b><br><br>";
$inside= $pdo->query("SELECT DISTINCT * FROM fun_chonline WHERE rid='".$rid."' and uid IS NOT NULL");

while($ins = $inside->fetch())
{
$unick = getnick_uid($ins[1]);
$userl = "<a href=\"chat.php?action=say2&sid=$sid&who=$ins[1]&rid=$rid\">$unick</a>, ";
echo "$userl";
}
echo "<br>";
echo "<p align=\"center\">";
echo "<a href=\"chat.php?sid=$sid&rid=$rid\">&#171;Voltar para a sala</a></p>";
}
else if($action=="sair")
{
$pdo->query("DELETE FROM fun_chonline WHERE uid='".$uid."'");
echo "<script> window.location.href = \"index.php?action=chat&sid=$sid\"; </script>";//javascript
}
echo "</body>";
echo "</html>";
?>