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

$p = $_GET["p"];
$sid = $_GET["sid"];
$who = $_GET["who"];
$page = $_GET["page"];
$uid = getuid_sid($sid);

if((is_logado($sid)==false)||($uid==0))
{
echo "<p align=\"center\">";
echo "Voc&#234; n&#227;o est&#225; logado<br/>";
echo "Ou sua sess&#227;o expirou,fa&#231;a o login novamente<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
if($p=="suporte")
{
adicionar_online($uid, "Pedindo ajuda","");
echo "<p align=\"center\">";
echo "<b>Pedir Ajuda</b><br>";
if($_POST["a"]=="Enviar")
{
$txt = $_POST["prob"];
$ea = $pdo->query("INSERT INTO fun_suporte SET texto='".$txt."', por='".$uid."', data='".time()."'");
if($ea)
{
echo "<br><b>Pedido de ajuda enviado com sucesso!</b><br>";
}else
{
echo "<br><b>Erro ao pedir ajuda!</b><br>";
}
}
echo "<form action=\"\" method=\"post\">";
echo "Digite seu problema: <br/><textarea name=\"prob\"></textarea><br>";
echo "<input name=\"a\" value=\"Enviar\" type=\"submit\"></form><br>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">P�gina principal</a></p>";
}
//mudar humor
else if($p=="humor")
{
adicionar_online($uid, "Alterando humor", "");
echo "<p align=\"center\">";
echo "<b>Alterar humor</b>";
echo "</p>";
$humor = $pdo->query("SELECT humor FROM fun_users WHERE id='".$uid."'")->fetch();
echo "<form action=\"genproc.php?action=humor&sid=$sid\" method=\"POST\">";
echo "Humor: <select name=\"humor\" value=\"$humor[0]\">";
echo "<option value=\"\">Sem humor</option>";
echo "<option value=\"AMANDO\">Amando</option>";
echo "<option value=\"CARENTE\">Carente</option>";
echo "<option value=\"DOENTE\">Doente</option>";
echo "<option value=\"TPM\">TPM</option>";
echo "<option value=\"FELIZ\">Feliz</option>";
echo "<option value=\"ROMANTICO\">Romantico</option>";
echo "<option value=\"ZANGADO\">Zangado</option>";
echo "<option value=\"PAQUERANDO\">Paquerando</option>";
echo "<option value=\"PIRANDO\">Pirando</option>";
echo "<option value=\"TRISTE\">Triste</option>";
echo "<option value=\"NERVOSO\">Nervoso</option>";
echo "<option value=\"ESTRESSADO\">Estressado</option>";
echo "<option value=\"DEPRESSIVO\">Depressivo</option>";
echo "</select>";
echo "<br />";
echo "<input name=\"\" value=\"Atualizar\" type=\"submit\" />";
echo "</form>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
else if($p=="servicos")
{
echo "<p align=\"center\">";
echo "<b>Serviços</b><br/></p>";
echo "<a href=\"noticias.php?sid=$sid\">&#187;Not�cias</a><br>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a></p>";
}
else if($p=="sml")
{
echo "<p align=\"center\">";
echo "<b>Categorias de Smilies</b><br></p>";

$c1 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='1'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=1&sid=$sid\">&#187;Diversas($c1[0])</a><br>";

$c2 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='2'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=2&sid=$sid\">&#187;Datas especiais($c2[0])</a><br>";

$c3 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='3'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=3&sid=$sid\">&#187;Personalizadas($c3[0])</a><br>";

$c4 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='4'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=4&sid=$sid\">&#187;Terror/Halloween($c4[0])</a><br>";

$c5 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='5'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=5&sid=$sid\">&#187;Amor/Emoções($c5[0])</a><br>";

$c6 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='6'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=6&sid=$sid\">&#187;Times/Clubes($c6[0])</a><br>";

$c7 = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE cat='7'")->fetch();
echo "<a href=\"lists.php?action=smilies&c=7&sid=$sid\">&#187;Plaquinhas/Assinaturas($c7[0])</a><br><br>";

echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a></p>";
}
else if($p=="amigos")
{
adicionar_online(getuid_sid($sid),"Lista de amigos","");

$who = $_GET["who"];
$page = $_GET["page"];
echo "<p align=\"center\">";
echo "Mensagem de ".getnick_uid($who).":<br/>";
echo scan_msg_other(getbudmsg($who), $sid);
echo "</p>";
//////ALL LISTS SCRIPT <<

if($page=="" || $page<=0)$page=1;
$num_items = getnbuds($who); //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;

//changable sql
/*
$sql = "SELECT
a.name, b.place, b.userid FROM fun_users a
INNER JOIN fun_online b ON a.id = b.userid
GROUP BY 1,2
LIMIT $limit_start, $items_per_page
";
*/
$sql = "SELECT a.lastact, a.id, a.id, b.uid, b.tid, b.reqdt FROM fun_users a INNER JOIN fun_buddies b ON (a.id = b.uid) OR (a.id=b.tid) WHERE (b.uid='".$who."' OR b.tid='".$who."') AND b.agreed='1' AND a.id!='".$who."' GROUP BY 1,2  ORDER BY a.lastact DESC LIMIT $limit_start, $items_per_page";


echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{

if(isonline($item[2]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
$uact = "Esta em: ";
$plc = $pdo->query("SELECT place FROM fun_online WHERE userid='".$item[2]."'")->fetch();
$uact .= $plc[0];
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
$uact = "Ultima vez ativo: ";
$ladt = date("d m y-H:i:s", $item[0]);
$uact .= $ladt;
}
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">$iml".getnick_uid($item[1])."</a>";
echo "$lnk<br/>";
echo "<small>";
$bs = date("d m y-H:i:s",$item[5]);
echo "Amigo desde: $bs<br/>";
echo "$uact<br/>";
echo "Msg: ";
$bmsg = scan_msg_other(getbudmsg($item[2]), $sid);
echo "$bmsg<br/>";
echo "</small>";

}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"paginas.php?who=$who&p=amigos&page=$ppage&sid=$sid\">&#171;Voltar</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"paginas.php?who=$who&p=amigos&page=$npage&sid=$sid\">Mais&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"paginas.php\" method=\"get\">";
$rets .= "Pular a pagina: :<input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"p\" value=\"$p\"/>";        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";

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
?>
