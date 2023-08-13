<?php
//include core.php and config.php files
include("config.php");
include("core.php");
//connect database
//html code
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
//get defines
$a = $_GET["a"];
$id = $_GET["id"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);
//is logged verification
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br>";
echo "<br><a href=\"index.php\">Login</a><br>";
echo "</p>";
exit();
}
//pedido de namoro form
if($a=="pedido")
{
adicionar_online($uid, "Fazendo pedido de namoro");
echo "<p align=\"center\">";
echo "Digite o <b>ID</b> da pessoa que voc� quer enviar o pedido de namoro!";
echo "<br />";
echo "<form action=\"?a=pedir&sid=$sid\" method=\"POST\">";
echo "ID: <input name=\"who\" value=\"\" type=\"\" size=\"5\"><input name=\"\" value=\"OK\" type=\"submit\">";
echo "</form>";
}
//pedir ok
else if($a=="pedir")
{
adicionar_online($uid, "Fazendo pedido de namoro");
echo "<p align=\"center\">";
$who = $_POST["who"];
$rperm = $pdo->query("SELECT rperm FROM fun_users WHERE id='".$uid."'")->fetch();
if(!isuser($who))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Error!";
}
else if($rperm[0]==1)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você já está namorando alguém!";
}
else if($rperm==2)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você já está casado(a) alguém!";
}
else
{
$nick = getnick_uid2($uid);
$txt = "Olá /reader, você acaba de receber uma solicitação de namoro feito por $nick! [br/]Para aceitar a solicitação por favor clique [relacionamento=$uid]aqui[/relacionamento]";
autopm($txt, $who);
echo "<img src=\"images/ok.gif\" alt=\"\">Pedido de namoro enviado com sucesso!";
}
}
//aceitar solicitacao de namoro
else if($a=="aceitar")
{
adicionar_online($uid, "Aceitando pedido de namoro");
$cid = $_GET["cid"];
echo "<p align=\"center\">";
$rperm = $pdo->query("SELECT rperm FROM fun_users WHERE id='".$uid."'")->fetch();
if(!isuser($cid))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Error!";
}
else if($rperm[0]==1)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você já está namorando alguém!";
}
else if($rperm==2)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você já está casado(a) alguém!";
}
else
{
$nick = getnick_uid2($uid);
$txt = "Olá /reader, $nick aceitou seu pedido de namoro!";
autopm($txt, $cid);
//atualiza a rpem dos 2 users
$pdo->query("UPDATE fun_users SET rperm='1' WHERE id='".$cid."'");
$pdo->query("UPDATE fun_users SET rperm='1' WHERE id='".$uid."'");
///add o id do usuario na coluna correta
$pdo->query("UPDATE fun_users SET ruser='".$uid."' WHERE id='".$cid."'");
$pdo->query("UPDATE fun_users SET ruser='".$cid."' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Pedido de namoro aceito com sucesso!";
}
}
//separacao
else if($a=="separar")
{
adicionar_online($uid, "Relacionamento");
echo "<p align=\"center\">";
$info = $pdo->query("SELECT ruser FROM fun_users WHERE id='".$uid."'")->fetch();
//tira todos os status e ruser dos 2 users
$pdo->query("UPDATE fun_users SET rperm='', ruser='' WHERE id='".$uid."'");
$pdo->query("UPDATE fun_users SET rperm='', ruser='' WHERE id='".$info[0]."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Separação realizada com sucesso!";
}
else
{
adicionar_online($uid, "Relacionamento");    
echo "<p align=\"center\">";
echo "<b>Relacionamento</b>";
echo "</p>";
echo "<br />";
echo "<a href=\"?a=pedido&sid=$sid\"><img src=\"images/coracao.gif\" alt=\"\">Enviar pedido</a><br />";
echo "<a href=\"?a=separar&sid=$sid\"><img src=\"images/coracao.gif\" alt=\"\">Separar</a>";
}
echo "<br />";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
?>