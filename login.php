<?php
///include core.php and config.php files
require_once("core.php");
require_once("config.php");

//html code
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
//define get
$usuario = $_GET["usuario"];
$senha   = $_GET["senha"];
//set id in get(usuario)
if(is_numeric($usuario))
{
//transform number in username
$usuario = getnick_uid2($usuario);
}
else
{
//get normal
$usuario = $_GET["usuario"];
}
//verification in user name
$v_usuario = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE name = '".$usuario."' ")->fetch();
if($v_usuario[0]==0 OR empty($v_usuario[0]))
{
echo "<p align=\"center\">";
echo "<img src=\"images/logo.png\" alt=\"\"><br />";
echo "<br />";
echo "<img src=\"images/notok.gif\">Usuário não existe!<br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
}
else
{
//verification in username and password if are correct
$v_senha = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE name = '".$usuario."' AND pass = '".substr($senha, 4, 32)."'")->fetch();
if($v_senha[0]==0 OR empty($v_senha[0]))
{
echo "<p align=\"center\">";
echo "<img src=\"images/logo.png\" alt=\"\"><br />";
echo "<br />";
echo "<img src=\"images/notok.gif\">A senha digitada por você não confere com a cadastrada!<br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
}
else
{
//delete sessions old
$pdo->query("DELETE FROM fun_ses WHERE (uid='".getuid_nick($usuario)."')");
$s = sha1($usuario.time());
$sid = strtoupper("php_sessid:".(str_shuffle($s)));
$tempo = time() + 1800;
///insert into in database sid session
$pdo->query("INSERT INTO fun_ses SET id = '".$sid."', uid = '".getuid_nick($usuario)."', expiretm = '".$tempo."' ");
echo "<p align=\"center\">";
echo "<img src=\"images/logo.png\" alt=\"\"><br />";
echo "<br />";
echo "<img src=\"images/ok.gif\"><b>Bem vindo $usuario</b>!<br />";
echo "<br /><a href=\"index.php?action=main&sid=$sid\"><b>Home1</b></a><br />";
echo "<br /><a href=\"page.php?page=main&sid=$sid\"><b>Home2</b></a><br />";
echo "<br />Salve esta pagina nos marcadores para logar automaticamente!";
echo "</p>";
}
}
//fim
?>
