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
$a = $_GET["a"];
if($a=="mp3")
{
echo "<p align=\"center\">";
echo "<b>Buscar MP3</b></p>";
echo "<p>";
echo "Olá visitante, aqui no $snome você baixa milhares de músicas da internet totalmente grátis!";
echo "<br />Rapidamente você faz uma busca pelo cantor/banda/musica e milhares de resultados est~zo na tela do seu CELULAR ou PC!";
echo "<br />O que você estź esperando? Faça já seu cadastro e aproveite!";
echo "<p align=\"center\">";
echo "<a href=\"index.php\"><img src=\"images/home.gif\">";
echo "Página principal</a>";
echo "</p>";
}
?>