<?php
include("config.php");
include("core.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


echo "<head>";

echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>";
echo "</head>";

echo "<body>";

$sid = $_GET["sid"];
$a = $_GET["a"];

$page = $_GET["page"];

$time = time();

if(is_logado($sid)==false)

{



      echo "<p align=\"center\">";

echo "Voce nao esta logado!<br/><br/>";

      echo "<a href=\"../index.php\">Login</a>";

      echo "</p>";

      exit();

}

adicionar_online(getuid_sid($sid),"Vendo fãs","");

$uid = getuid_sid($sid);

if($a=="ver")


{
$id = $_GET["id"];
if($page=="" || $page<=0)$page=1;

$noi = $pdo->query("SELECT COUNT(**) FROM fun_fas WHERE vid='".$id."'")->fetch();

    $num_items = $num_items = $noi[0]; //changable

$items_per_page= 5;

    $num_pages = ceil($num_items/$items_per_page);

    if($page>$num_pages)$page= $num_pages;

    $limit_start = ($page-1)*$items_per_page;

if($num_items>0)

    {



    //changable sql

        $sql = "

    SELECT id, uid, vid, star, perm FROM fun_fas WHERE vid='".$id."' ORDER BY id desc

            LIMIT $limit_start, $items_per_page

    ";

    echo "<p>";
echo "</p><p align=\"center\">";
echo "<b>Fãs</b><br/>";
echo "</p><p align=\"left\">";

    $items = $pdo->query($sql);

    while ($item = $items->fetch())

    {

          if(isonline($item[1]))

  {

    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";



  }else{

    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";

  }
  $nick = getnick_uid($item[1]);


      $lnk = "<br/><a href=\"index.php?action=perfil&who=$item[1]&sid=$sid\">$iml$nick</a>";

      echo "$lnk<br/>";
if($item[3]=="1")
{
$star = "<img src=\"images/star.gif\" alt=\"*\"/>";
}else if($item[3]=="2")
{
$star = "<img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/>";
}else if($item[3]=="3")
{
$star = "<img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/>";
}else if($item[3]=="4")
{
$star = "<img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/>";
}else if($item[3]=="5")
{
$star = "<img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/><img src=\"images/star.gif\" alt=\"*\"/>";
}else{
$star = "Nenhuma";
}


      echo "Estrelas: $star<br/>";
if($id=="$uid"){
echo "<a href=\"fas.php?a=star&id=$item[1]&tek=a&sid=$sid\">[+]</a> <a href=\"fas.php?a=star&id=$item[1]&tek=b&sid=$sid\">[-]</a> <a href=\"fas.php?a=remover&id=$item[1]&sid=$sid\">[X]</a>";
}
echo"<br/>";
}

    echo "</p>";

    echo "<p align=\"center\">";

    // Build Previous Link

if($page > 1){

    $prev = ($page - 1);

    echo "<a href=\"fas.php?a=ver&page=$prev&sid=$sid&id=$id\">&#171;Voltar</a> ";

}else{

echo "";

}

echo "$page/$num_pages";

// Build Next Link

if($page < $num_pages){

    $next = ($page + 1);

    echo " <a href=\"fas.php?a=ver&page=$next&sid=$sid&id=$id\">Mais&#187;</a><br/>";

    if($num_pages>2)
    {

        $rets = "<form action=\"fas.php\" method=\"get\">";
      $rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"OK\"/>";
        $rets .= "<input type=\"hidden\" name=\"a\" value=\"$a\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";
        $rets .= "</form>";

        echo $rets;
    }
}else{

echo "";

}

}else{
echo "</p><p align=\"center\">";
echo "<b>Fãs</b><br/>";
echo "<b>Este usuario nao possui fãs!</b><br/>";

}
  $nick = getnick_uid($id);
  if($id!="$uid"){
  $aaa = $pdo->query("SELECT COUNT(*) FROM fun_fas WHERE vid='".$id."' AND uid='".$uid."'")->fetch();
  if($aaa[0]=="0")
  {
  echo "<br/><a href=\"fas.php?a=entrar&id=$id&sid=$sid\">Virar fã de $nick</a><br/>";
  }}
echo "<p align=\"center\"><a href=\"../index.php?action=perfil&who=$id&sid=$sid\">Perfil de $nick</a><br/>";
}else if($a=="entrar")
{
echo "</p><p align=\"center\">";
$id = $_GET["id"];
$tek = $_GET["tek"];
  if($id!="$uid"){
  $noi = $pdo->query("SELECT COUNT(*) FROM fun_fas WHERE vid='".$id."' AND uid='".$uid."'")->fetch();
  if(!$noi[0]=="0")
  {
  echo "<b>Você ja e fã desse usuario!</b><br/>";
  }else{
  $pdo->query("INSERT INTO fun_fas SET uid='".$uid."', vid='".$id."'");
  echo "<b>Fã adicionado com sucesso!</b><br/>";
  }}
}else if($a=="remover"){
$id = $_GET["id"];
$noi = $pdo->query("SELECT COUNT(*) FROM fun_fas WHERE vid='".$uid."' AND uid='".$id."'")->fetch();
   if($noi[0]=="0")
  {
echo "</p><p align=\"center\">";
  echo "<b>Você não e fã desse usuario!</b><br/>";
}else{
$pdo->query("DELETE FROM fun_fas WHERE vid='".$uid."' AND uid='".$id."'");
echo "<b>Fã apagado com sucesso!</b><br/>";
}}else if($a=="star"){
$id = $_GET["id"];
$tek = $_GET["tek"];
$noi = $pdo->query("SELECT COUNT(*) FROM fun_fas WHERE vid='".$uid."' AND uid='".$id."'")->fetch();
  if($noi[0]=="0")
  {
echo "</p><p align=\"center\">";
  echo "<b>Você ja e fã desse usuario!</b><br/>";
}else{
$star = $pdo->query("SELECT star FROM fun_fas WHERE vid='".$uid."' AND uid='".$id."'")->fetch();
  if($tek=="a")
  {
  if($star[0]=='5'){$tek="5";}else{
  $tek = $star[0]+1;
  }}else{
  if($star[0]=='0'){$tek="0";}else{
  $tek = $star[0]-1;
  }}
$pdo->query("UPDATE fun_fas SET star='".$tek."' WHERE vid='".$uid."' AND uid='".$id."'");
echo "<p align=\"center\">";
echo "<b>Estrelas editadas com sucesso!</b><br/>";
echo "<br/><a href=\"fas.php?a=ver&id=$uid&sid=$sid\">Voltar aos fãs</a>";
}


echo "</p>";

 echo "</body>";

 echo "</html>";





}


echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>P�gina principal</a>";?>

