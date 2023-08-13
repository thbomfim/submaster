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
$sid = $_GET["sid"];
$action = $_GET["action"];
$uid = getuid_sid($sid);
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
if($action=="enviar")
{
$texto = $_POST["texto"];
$id = $_POST["id"];
$pasta = "m/";
$nome = $_FILES["arquivo"]["name"];
$nome = strtolower($nome);
$ext = arquivo_ext($nome);
echo "<p align=\"center\">";
if(!isuser($id))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Usuário não existe, por favor verifique o ID!";
}
else if(empty($nome))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você deve selecionar algum arquivo para enviar!";
}
else if(empty($texto))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Digite o texto do torpedo!";
}
else if(arquivo($ext)=="1")
{
echo "<img src=\"images/notok.gif\" alt=\"\">Esse arquivo não é aceito pelo sistema, tente outro!";
}
else
{
$new_nome = strtoupper(md5($nome))."_MMS_ALTENTICATE_OK.".$ext;
move_uploaded_file($_FILES["arquivo"]["tmp_name"], $pasta.$new_nome);
$time = time();
$pdo->query("INSERT INTO fun_private SET text='[url=m/".$new_nome."]".$nome."[/url][br/]".$texto."', byuid='".$uid."', touid='".$id."', timesent='".$time."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Torpedo enviado com sucesso para ".getnick_uid($id)."!";
}
echo "<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\">"; 
echo "<img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
else
{
echo "<p align=\"center\">"; 
echo "<b>Torpedo Multimúdia</b><br></p>";
echo "<form action=\"m.php?action=enviar&sid=$sid\" method=\"post\" enctype=\"multipart/form-data\">";
echo "ID: <input name=\"id\"/><br/>";
echo "Mensagem: <input name=\"texto\"/><br/>";
echo "Arquivo: <input type=\"file\" name=\"arquivo\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "Atenç~so: Apenas alguns tipos de <b>arquivos</b> são aceitos pelo site, para saber melhor entre em contato com a <b>equipe</b>!";
echo "<br /><br /><a href=\"index.php?action=main&sid=$sid\">"; 
echo "<img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
}
?>
