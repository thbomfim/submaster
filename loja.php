<?php

//SCRIPT CRIADO POR �LVARO
//CONTATO: suporte@alvarowap.com
//CORRIGIDO POR Bebeto

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
$sid = $_GET["sid"];
$a = $_GET["a"];
$cat = $_GET["c"];
$uid = getuid_sid($sid);
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Loja $snome","");
function produto($id)
{
	global $pdo;
$produto = $pdo->query("SELECT url FROM loja WHERE id='".$id."'")->fetch();
$produto = "<img src=\"loja/".$produto[0]."\" alt=\"\" height=\"100x100\"/>";
return $produto;
}
function renamefile($n)
{
$n = strtolower($n);
$n = str_replace(" ", "_", $n); 
$n = str_replace("h", "_", $n); 
$n = str_replace("H", "_", $n);
return $n;
}
if($a==vupload)
{
echo "<p align=\"center\">";
$valor = trim($_POST["valor"]);
$cat = $_POST["cat"];
$p = $_FILES["file"]["name"];
$extencao = arquivo_ext($p);
$tamanho = round($_FILES["file"]["size"]/1024);
if(empty($cat)||empty($valor)||empty($p))
{
echo "<b>Nada pode ficar em branco!</b><br>";
}
else if(!is_numeric($valor))
{
echo "<b>Digite um valor válido!</b><br>";
}
else if(arquivo_extfoto($extencao)=="1")
{
echo "<b>Arquivo não á uma foto!</b><br>";
}
else if($tamanho > 1024)
{
echo "<b>Arquivo muito grande!</b><br>";
}
else
{
$pasta = "loja/";
$nome_real = renamefile($p);
$nome_real = time().time().".".$extencao;
$upload = move_uploaded_file($_FILES["file"]["tmp_name"], $pasta.$nome_real);
if($upload)
{
	$pdo->query("INSERT INTO loja SET url='$nome_real', valor='".$valor."', cat='".$cat."'");
	echo "<b>Presente enviado com sucesso!</b><br/>";
}else
{
	echo "<b>Erro no upload!</b><br>";
}

}
echo "<p align=\"center\">";
echo "<a href=\"?a=main&sid=$sid\">Loja $snome</a><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($a==admin)
{
if(isadmin($uid))
{
echo "<p>";
echo "<p align=\"center\">";
echo "<b>Adicionar Presente</b><br></p>";
echo "<form action=\"loja.php?a=vupload&sid=$sid\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Produto: <input type=\"file\" name=\"file\" size=\"15\"/><br/>";
echo "Valor (pontos): <input name=\"valor\"/><br>";
echo "Categoria: <select name=\"cat\">";
echo "<option value=\"1\">Animadas</option>";
echo "<option value=\"2\">Amizade</option>";
echo "<option value=\"3\">Amor</option>";
echo "<option value=\"4\">Datas Especiais</option>";
echo "<option value=\"5\">Personalizadas</option>";
echo "<option value=\"6\">Diversas</option>";
echo "<option value=\"7\">Eroticas</option>";
echo "<option value=\"8\">Religiao</option>";
echo "</select><br/>";
echo "<input type=\"hidden\" name=\"upload\" value=\"upload\"/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<p align=\"center\"><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>P�gina principal</a>";
}
}
//////////////comprar wapgrana
else if($a==wcomprar)
{
echo "<p align=\"center\">";
$pontos  = getplusses($uid);
$q       = $_POST["quantidade"];
$wapgrana=  wapgrana_uid($uid);
if(empty($q))
{
$q = 10;
}
$t = $q / 2 * 5;
if($soma > $pontos)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você não tem pontos suficientes para comprar <b>WAPGRANAS</b>!";
echo "<br />";
}
else
{
$c = $_GET["c"];
if(isset($c))
{
$wapgrana = $wapgrana + $q;
$pontos = $pontos - $t;
$pdo->query("UPDATE fun_users SET wapgrana='".$wapgrana."', plusses='".$pontos."' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Compra realizada com sucesso!";
echo "<br />";
}
else
{
echo "Você deseja comprar $q <b>WAPGRANAS</b> por $t $smoeda?";
echo "<br />";
echo "<br />";
echo "<a href=\"?a=wcomprar&c=1&sid=$sid\"><b>SIM</b></a> - <a href=\"index.php?action=main&sid=$sid\"><b>NÃO</b></a>";
echo "<br />";
exit();
}
}
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
/////////////ver saldo de wapgrana
else if($a==wapgrana)
{
echo "<p align=\"center\">";
echo "<b>WAPGranas</b>";
echo "<br />";
echo "<br />";
$nick = getnick_uid($uid);
echo "Olá $nick, você tem no momento um saldo de ".wapgrana_uid($uid)." <b>WAPGRANAS</b>!";
echo "<br />";
echo "Aqui você pode comprar <b>WAPGRANAS</b> com seus $smoeda normalmente... Digite abaixo a quatidade de <b>WAPGRANAS</b> que você deseja comprar!";
echo "<br />";
echo "<form action=\"?a=wcomprar&sid=$sid\" method=\"post\">";
echo "Quantidade: <input name=\"quantidade\" type=\"text\">";
echo "<br /><input name=\"\" type=\"submit\" value=\"OK\">";
echo "</form>";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
else if($a==apagarpresente)
{
$id = $_GET["id"];
$ids = $pdo->query("SELECT uid, eid, url FROM presentes WHERE id='".$id."'")->fetch();
if($ids[0]==$uid OR $ids[1]==$uid OR isadmin($uid))
{
$pdo->query("DELETE FROM presentes WHERE id='".$id."'");
echo "<p align=\"center\"><b>Presente apagado com sucesso!</b>";
}
else{
echo "<p align=\"center\"><b>Erro!</b>";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($a==presentes)
{
$who = $_GET["who"];
$page = $_GET["p"];
echo "<p align=\"center\"><b>Presentes de ".getnick_uid($who)."</b><br/></p>";
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM presentes WHERE uid='".$who."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT id, pid, eid, msg, data FROM presentes WHERE uid='".$who."' ORDER BY id DESC LIMIT $limit_start, $items_per_page ";
echo "<p><small>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
if(isadmin($uid) OR $who==$uid OR $item[2]==$uid)
{
$apagar = "<a href=\"loja.php?a=apagarpresente&id=$item[0]&sid=$sid\">[X]</a>";
}
else{
$apagar = "";
}
echo "".produto($item[1])."<br/><b>$item[3]</b><br/>Por: <a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">".getnick_uid($item[2])."</a><br/>Data: ".date("d/m/Y - H:i:s", $item[4])." $apagar<br/><br/>";
}
}
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"loja.php?a=presentes&p=$ppage&who=$who&sid=$sid\">&#171;Voltar</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"loja.php?a=presentes&p=$npage&who=$who&sid=$sid\">Mais&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"loja.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"p\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "<input type=\"hidden\" name=\"a\" value=\"presentes\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($a==apagar)
{
if(isadmin($uid))
{
$id = $_GET["id"];
$pdo->query("DELETE FROM loja WHERE id='".$id."'");
$pdo->query("DELETE FROM presentes WHERE pid='".$id."'");
echo "<p align=\"center\">";
echo "<b>Produto apagado!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
}
else if($a==comprar2)
{
$id = $_GET["id"];
$idu = $_POST["idu"];
$msg = $_POST["msg"];
$valor = $pdo->query("SELECT valor FROM loja WHERE id='".$id."'")->fetch();
if(getplusses(getuid_sid($sid))<$valor[0])
{
echo "<p align=\"center\">";
echo "<b>Voce precisa ter no minimo $valor[0] para comprar este item!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if(empty($msg))
{
echo "<p align=\"center\">";
echo "<b>Erro na mensagem!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($idu=="$uid")
{
echo "<p align=\"center\">";
echo "<b>Voce nao pode comprar um presente para voce mesmo!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if(empty($idu) OR $idu=="0" OR !isuser($idu))
{
echo "<p align=\"center\">";
echo "<b>Usuario não existe!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else{
$meu = $pdo->query("SELECT plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$ns = $meu[0] - $valor[0];
$pdo->query("UPDATE fun_users SET plusses='".$ns."' WHERE id='".$uid."'");
$pdo->query("INSERT INTO presentes SET data='".time()."', pid='".$id."', uid='".$idu."', eid='".$uid."', msg='".$msg."'");
echo "<p align=\"center\">";
echo "<b>Compra realizada com sucesso!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
}
else if($a==comprar)
{
$id = $_GET["id"];
$valor = $pdo->query("SELECT valor FROM loja WHERE id='".$id."'")->fetch();
echo "<p align=\"center\"><b>Comprar</b><br/><br/>".produto($id)."<br/>Valor: <b>".$valor[0]." pontos</b><br/></p>";
echo "<form action=\"loja.php?a=comprar2&id=$id&sid=$sid\" method=\"post\">ID do usuario: <input name=\"idu\"/><br/>Mensagem: <input name=\"msg\"/><br/><input type=\"submit\" value=\"Comprar\"/></form>";
echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else if($a==main)
{
echo "<p align=\"center\">";
echo "<b>Loja $snome</b></p>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='1'")->fetch();
echo "<a href=\"loja.php?c=1&sid=$sid\">&#187;Animadas($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='2'")->fetch();
echo "<a href=\"loja.php?c=2&sid=$sid\">&#187;Amizade($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='3'")->fetch();
echo "<a href=\"loja.php?c=3&sid=$sid\">&#187;Amor($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='4'")->fetch();
echo "<a href=\"loja.php?c=4&sid=$sid\">&#187;Datas Especiais($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='5'")->fetch();
echo "<a href=\"loja.php?c=5&sid=$sid\">&#187;Personalizadas($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='6'")->fetch();
echo "<a href=\"loja.php?c=6&sid=$sid\">&#187;Diversas($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='7'")->fetch();
echo "<a href=\"loja.php?c=7&sid=$sid\">&#187;Eroticas($sm[0])</a><br/>";
$sm = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='8'")->fetch();
echo "<a href=\"loja.php?c=8&sid=$sid\">&#187;Religiao($sm[0])</a><br/>";
echo "<p align=\"center\">";
if(isadmin($uid))
{
echo "<br/><a href=\"loja.php?a=admin&sid=$sid\">Add Presente</a>";
}
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else
{
$page = $_GET["p"];
echo "<p align=\"center\"><b>Lista de Presentes</b><br/></p>";
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM loja WHERE cat='".$cat."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
$sql = "SELECT id, url, valor FROM loja WHERE cat='".$cat."' ORDER BY id DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
if(isadmin($uid))
{
$apagar = "<a href=\"loja.php?a=apagar&id=$item[0]&sid=$sid\">[X]</a>";
}
else{
$apagar = "";
}
echo "<img src=\"loja/".$item[1]."\" alt=\"\" height=\"100x100\"/><br/>Valor: <b>$item[2] pontos</b><br/><a href=\"loja.php?a=comprar&id=$item[0]&sid=$sid\"><img src=\"images/loja.gif\"><b>COMPRAR</b></a> $apagar<br/><br/>";
}
}
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"loja.php?c=$cat&p=$ppage&sid=$sid\">&#171;Voltar</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"loja.php?c=$cat&p=$npage&sid=$sid\">Mais&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form action=\"loja.php\" method=\"get\">";
$rets .= "Pular a pagina: <input name=\"p\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"OK\"/>";
$rets .= "<input type=\"hidden\" name=\"c\" value=\"$cat\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
?>