<?php

include("core.php");
$a = $_GET["a"];
$sid = $_GET["sid"];
$rid = $_GET["rid"];
$rpw = $_GET["rpw"];
if(is_logado($sid)==false)
{
$url = "index.php";
header("Location: ".$url);
}
else if($a=="enviar")
{
$user = $_POST["usuario"];
$pasta = "m/";
$nome = $_FILES['arquivo']['name'];
$nome = str_replace("H", "", $nome);
$nome = str_replace("h", "", $nome);
$file_ext = explode('.', $nome);
$file_ext = strtolower($file_ext[count($file_ext) - 1]);
if(arquivo($file_ext)=="1")
{
$url = "chat.php?rid=$rid&rpw=$rpw&sid=$sid";
header("Location: ".$url);
exit();
}
move_uploaded_file($_FILES['arquivo']['tmp_name'], $pasta.$nome);
$uid = getuid_sid($sid);
$pdo->query("INSERT INTO fun_chat SET  chatter='".$uid."', who='".$who."', para='".$user."', timesent='".time()."', msgtext='[url=m/".$nome."]".$nome."[/url]', rid='".$rid."';");
$url = "chat.php?rid=$rid&rpw=$rpw&sid=$sid";
header("Location: ".$url);
}
else{

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

echo "<head>";

echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";

echo "<body>";
echo "<p align=\"center\">";
echo "<b>Enviar mensagem multimidia</b><br/>";
echo "</p>";
echo "<form action=\"chatmulti.php?a=enviar&rid=$rid&sid=$sid&rpw=$rpw\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Para:<br/><select name=\"usuario\">";
echo "<option value=\"0\">Todos</option>";
$inside = $pdo->query("SELECT DISTINCT * FROM fun_chonline WHERE rid='".$rid."' and uid IS NOT NULL");

while($ins = $inside->fetch())
{
$unick = getnick_uid2($ins[1]);
echo "<option value=\"$ins[1]\">$unick</option>";
}
echo "</select><br/>";
echo "Arquivo:<br/> <input name=\"arquivo\" size=\"12\" type=\"file\"/><br/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form><br/>";
echo "<p align=\"center\"><a href=\"chat.php?rid=$rid&rpw=$rpw&sid=$sid\">&#171;Voltar para sala</a></p>";
}
?>