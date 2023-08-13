<?php
include("core.php");
include("config.php");
$id = $_GET["id"];
$info = $pdo->query("SELECT url, visitas FROM fun_downloads WHERE id='".$id."'")->fetch();
if(empty($info[0]))
{
echo "Erro arquivo não encontrado!";
}
else{
$mais = $info[1] + 1;
$pdo->query("UPDATE fun_downloads SET visitas='".$mais."' WHERE id='".$id."'");
header("Location: downloads/".$info[0]);
}
exit();
?>
