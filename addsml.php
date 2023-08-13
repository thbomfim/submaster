<?php
include("core.php");
include("config.php");
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

$uid = getuid_sid($sid);

if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

if(!isadmin($uid))
{
echo "<p align=\"center\">";
echo "Você não é adminstrador!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

if($a=="add")
{
echo "<p align=\"center\">";
$file = $_FILES["sml"]["name"];
$codigo = $_POST["codigo"];
$cat = $_POST["cat"];
$pasta = "sml/";
$file = str_replace(" ", "_", str_replace("%20", "_", strtolower($file) ) );
$file = str_replace("h", "", $file);
$file = str_replace("H", "", $file);
$file_ext = explode('.', $file);  
$file_ext = strtolower($file_ext[count($file_ext) - 1]);
$kbsize = (round($_FILES['sml']['size']/1024));
$sml = $pdo->query("SELECT COUNT(*) FROM fun_smilies WHERE scode='".$codigo."'")->fetch();
if(empty($codigo)||strlen($codigo)<3)
{
echo "<b>Codigo do smilie deve conter mais de 4 caracteres e não pode ficar em branco!</b>";
}
else if($sml[0]>'0')
{
echo "<b>Smilie já existe!</b>";
}
else if(empty($_FILES['sml']['name']))
{
echo "<b>Selecione um arquivo para enviar!</b>";
}
else if($kbsize > 500)
{
echo "<b>Arquivo não pode ser maior que 500KB!</b>";
}
else if(arquivo_extfoto($file_ext)=="1")
{
echo "<b>Esse aquivo não é um smilie!</b>";
}
else
{
$new_name = "SML_COD_".rand(1,100).rand(100,200).rand(1000,9999).".".$file_ext;
$res = $pdo->query("INSERT INTO fun_smilies SET scode='".$codigo."', imgsrc='".$pasta.$new_name."', hidden='0', cat='".$cat."'");
if($res)
{
move_uploaded_file($_FILES["sml"]["tmp_name"], $pasta.$new_name);
echo "<b>Smilie adicionado com sucesso!</b><br>";
}
else
{
echo "<b>Erro!</b><br>";
}
}
}
else
{
echo "<p align=\"center\">";
echo "<b>Adicionar Smilies</b><br></p>";
echo "<form action=\"?a=add&sid=$sid\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Arquivo: <input name=\"sml\" type=\"file\"><br>";
echo "C�digo: <input name=\"codigo\"><br>";
echo "Categoria: <select name=\"cat\"><br>";
echo "<option value=\"1\">Diversas</option>";
echo "<option value=\"2\">Datas especiais</option>";
echo "<option value=\"3\">Personalizadas</option>";
echo "<option value=\"4\">Terror/Halloween</option>";
echo "<option value=\"5\">Amor/Emoções</option>";
echo "<option value=\"6\">Times/Clubes</option>";
echo "<option value=\"7\">Plaquinhas/Assinaturas</option>";
echo "</select><br>";
echo "<input value=\"Enviar\" type=\"submit\"></form>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
?>
