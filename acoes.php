<?php
//include core.php and config.php files
include("core.php");
include("config.php");
//html cod
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
//get 
$sid = $_GET["sid"];
$a = $_GET["a"];
$who = $_GET["who"];
$uid = getuid_sid($sid);
//islogged 
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
//isuser
if($who==""||$who==0||isuser($who)==false)
{
echo "<p align=\"center\">";
echo "Usuário não existe!<br><br>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

if($a=="a")
{
$p = $_GET["p"];
adicionar_online("Vendo ações", "", $sid);
echo "<p align=\"center\">";
$nick = getnick_uid($who);
echo "<b>Ações de $nick</b><br></p>";
if($p==""||$p<=0)$p=1;
$n = $pdo->query("SELECT COUNT(*) FROM fun_acoes WHERE who='".$who."'")->fetch();
$num_itens = $n[0];
$itens_per_page = 5;
$num_pages = ceil($num_itens/$itens_per_page);
if(($p>$num_pages) AND $p!=1)$p = $num_itens;
$limit_start = ($p-1)*$itens_per_page;
$sql = "SELECT id, uid, acao, who, date FROM fun_acoes WHERE who='".$who."' ORDER BY date DESC LIMIT $limit_start,$itens_per_page";

$reg = $pdo->query($sql);
while($acoes = $reg->fetch())
{ 
$n1 = getnick_uid($acoes[1]);
$n2 = getnick_uid($acoes[3]);
$acao = htmlspecialchars($acoes[2]);
$data = date("d/m/y - H:i:s", $acoes[4]);
echo "$n1 $acao $n2<br/><small>$data</small><br><br>";
}
echo "<p align=\"center\">";
if($p>1)
{
$np = $p-1;
echo "<a href=\"?a=a&sid=$sid&who=$who&p=$np\">&#171;Voltar</a> ";
}
if($p<$num_pages)
{
$pp = $p+1;
echo "<a href=\"?a=a&sid=$sid&who=$who&p=$pp\">Mais&#187;</a>";
}
echo "<br>$p/$num_pages<br><br>";
if($uid!=$who)
{
$n = getnick_uid($who);
echo "<a href=\"?a=enviar&sid=$sid&who=$who\">Enviar ação para $n</a><br>";
}
echo "</p>";
}
else if($a=="enviar2")
{
adicionar_online("Enviando ação", "", $sid);
$acao = $_POST["acao"];
echo "<p align=\"center\">";
if(empty($who)||$who==0||!isuser($who))
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Usuário não existe!<br>";
}
else if($uid==$who)
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Vocã não pode enviar ações para vocẽ mesmo!<br>";
}
else if(getplusses($uid)<149)
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Você deve ter 150 pontos para enviar uma ação!<br>";
}
else
{
$res = $pdo->query("INSERT INTO fun_acoes SET acao='".$acao."', who='".$who."', uid='".$uid."', date='".time()."'");
if($res)
{
$de = getnick_uid2($uid);
$sms = "Olá /reader, você recebeu uma ação do usuário[b] $de [/b], para visualiza-la vá até seu perfil![br/][small]Torpedo Altomático![/small]";
autopm($sms, $who);
echo "<img src=\"images/ok.gif\" alt=\"\"/>Ação enviada com sucesso!<br>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Erro, tente mais tarde!<br>";
}
}
}
else if($a=="enviar")
{
adicionar_online("Enviando ação", "", $sid);
echo "<p align=\"center\">";
echo "<b>Enviar Ações</b><br/></p>";
echo "<form action=\"?a=enviar2&sid=$sid&who=$who\" method=\"post\">";
echo "Ação: <select name=\"acao\">";
//nick  voce
echo "<option value=\"um abraço em\">Abraço</option>";
echo "<option value=\"cutucou\">Cutucar</option>";
echo "<option value=\"deu um pisão em\">Pisão</option>";
echo "<option value=\"deu tapa em\">Tapa</option>";
echo "<option value=\"deu um selino em\">Selinho</option>";
echo "<option value=\"beliscou\">Beliscar</option>";
echo "<option value=\"piscou para\">Piscar</option>";
echo "<option value=\"gritou com\">Gritar</option>";
echo "<option value=\"apertou a mão de\">Aperto de mão</option>";
echo "<option value=\"mandou uma cantada para\">Cantada</option>";
echo "<option value=\"puxou o cabelo de\">Puxar Cabelo</option>";
echo "<option value=\"deu uma rasteira em\">Rasteira</option>";
echo "<option value=\"mandou uma flor para\">Flor</option>";
echo "</select><br>";
echo "<input value=\"Enviar\" type=\"submit\"></form><br>";
}
else
{
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a></p>";
?>