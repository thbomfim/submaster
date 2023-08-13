<?php
//include core.php and config.php files
include("core.php");
include("config.php");

//html code
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
//define gets
$a = $_GET["a"];
//define posts
$nome  	= $_POST["nome"];
$senha 	= $_POST["senha"];
$senha2 = $_POST["senha2"];
$dia 	= $_POST["dia"];
$mes 	= $_POST["mes"];
$ano 	= $_POST["ano"];
$sex 	= $_POST["sex"];
$localidade = $_POST["localidade"];
//can reg
echo "<p>";
if(!canreg())
{
echo "<img src=\"images/notok.gif\"/>Cadastros não disponiveis no momento, futuro usuário por favor tente novamente mais tarde!";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a></p>";
exit;
}
if(empty($nome))//empty name
{
	echo registro(1);
}
else if(empty($senha))//empty password
{
	echo registro(2);
}
else if(empty($senha2))//empty password 2
{
	echo registro(3);
}
else if($senha!=$senha2)//confirm password
{
	echo registro(6);
}
else if(strlen($nome)<5)//username verification 
{
	echo registro(7);
}
else if(strlen($senha)<8||strlen($senha2)<8)//password verification
{
	echo registro(8);
}
else if(isuser(getuid_nick($nome)))//is username
{
	echo registro(9);
}
else if(empty($dia)||empty($mes)||empty($ano))//empty niver
{
	echo registro(10);
}
else if(!is_numeric($dia)||!is_numeric($mes)||!is_numeric($ano))//verification niver
{
	echo registro(11);
}
else
{
$by 	 = "$ano-$mes-$dia";//niver format
$_POST   = array_map("trim", $_POST);//scape spacing in words
$comando = "INSERT INTO fun_users SET name='".$nome."', pass='".md5($senha)."', birthday='".$by."', sex='".$sex."', location='".$localidade."', plusses='21', ipadd='".ver_ip()."', regdate='".time()."'";
$pdo->query($comando);
//auto pm welcome
$msg = "Olá /reader, seja bem vindo ao $snome, estamos felizes por se cadastrar em nossa comunidade! Será um prazer trazer laser e diversão para vocẽ! Divirta-se postando no [b]forum[/b], compartilhe fotos no seu [b]Álbum[/b], poste sua opiniões no [b]mural[/b] e muito mais![br/]Até breve, Abraços da equipe!";
autopm($msg, getuid_nick($nome));
//fim
echo "<img src=\"images/ok.gif\">Cadastro realizado com sucesso!<br>";
echo "<p align=\"center\">";
echo "<a href=\"logar.php?usuario=$nome&senha=$senha\"><img src=\"images/home.gif\" alt=\"*\"/>ENTRAR</a>";
echo "</p>";
exit();
}
//form register.php
echo "<br />";
echo "<form action=\"\" method=\"POST\">";
echo "Usuário: <input name=\"nome\" maxlength=\"50\"><br>";
echo "Senha: <input name=\"senha\" maxlength=\"50\" type=\"PASSWORD\"><br>";
echo "Repita a senha: <input name=\"senha2\" maxlength=\"50\" type=\"PASSWORD\"><br>";
echo "Aniversário<small>(DD-MM-YYYY)</small>: <input name=\"dia\" style=\"-wap-input-format:'*N'\" maxlength=\"2\" size=\"2\"> <input name=\"mes\" style=\"-wap-input-format:'*N'\" maxlength=\"2\" size=\"2\"> <input name=\"ano\" style=\"-wap-input-format:'*N'\" maxlength=\"4\" size=\"4\"><br>";
echo "Sexo: <select name=\"sex\">";
echo "<option value=\"M\">Homem</option>";
echo "<option value=\"F\">Mulher</option>";
echo "<option value=\"G\">GLS</option></select>";
echo "<br>";
echo "Localidade: <input name=\"localidade\"><br>";
echo "<input value=\"Cadastrar\" type=\"submit\">";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a></p>";
?>