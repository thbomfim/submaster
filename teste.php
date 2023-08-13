<?php
include("config.php");
include("core.php");

$onn = $pdo->query("SELECT userid, place FROM fun_online WHERE userid='1'")->fetch();

echo print_r($onn);

echo "<br>";
if($onn[0] == "1") {
    echo "achei um registro esta localizado na $onn[1]";
}
else {
    echo"que peninha";
}
echo "<hr>";

$maxmem = $pdo->query("SELECT value FROM fun_settings WHERE id='2'")->fetch();
$result = $pdo->query("SELECT COUNT(*) FROM fun_online")->fetch();
echo "<pre>";
echo" var_dump($maxmem[0])";
echo "print_r($result[0])";
echo "</pre>";

?>
