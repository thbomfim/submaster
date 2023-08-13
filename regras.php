<?php

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

	echo "<head>";
	include("config.php");
	echo "<title>$snome</title>";
	echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
	echo "</head>";
echo "<body>";
echo "<p align=\"center\"><b>Regras do Site</b><br/></p>";
echo "- é proibido toda ou qualquer forma de divulgação (spam), de outras comunidades com as mesmas características da comunidade $snome.<br/>";
echo "- Postagens no fórum fora do contexto que o tópico abordar, ou postagens nocivas ao site, serão apagadas pelos moderadores sem aviso prévio.<br/>";
echo "- Não são permitidos postagens ou arquivos com apologias, racismo ou pornografia infantil.<br/>";
echo "- Cadastros duplicados acarreta-rá no cancelamento de um dos cadastros.<br/>";
echo "- Nao sao permitidos nicks vulgares ou com ofensas a outros usuários.<br/>";
echo "- Obrigado, divirta-se!";
echo "<p align=\"center\">";
$sid = $_GET["sid"];
if(empty($sid))
{
echo "<a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página de entrada</a></p>";
}
else
{
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a></p>";
}
?>