<?php

include("config.php");
include("core.php");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


echo "<head>";

echo "<title>$stitle</title>";

echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>";
echo "</head>";
echo "<body>";

$action = addslashes($_GET["action"]);
$sid = addslashes($_GET["sid"]);
$page = addslashes($_GET["page"]);
addvisitor();
$uid = addslashes(getuid_sid($sid));
if((is_logado($sid)==false)||($uid==0))
{
echo "<p align=\"center\">";
echo "Voce nao esta logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Virtual pet","");
$tipo = $pdo->query("SELECT tipo FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
if($action=="main")
{
echo "<p align=\"center\"><b>Virtual Pet</b><br/>";
$dal = $pdo->query("SELECT COUNT(*) FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."' AND ziv='1'")->fetch();
 if($dal[0]==0)
 { 
 echo "<img src=\"images/logo.gif\" alt=\"\"/><br/><br/><a href=\"virtual_pet.php?action=usvoji&sid=$sid\">Criar Virtual Pet</a>";
$imal = $pdo->query("SELECT rodjen, smrt FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."' AND ziv='0'")->fetch();
$zivio = $imal[1] - $imal[0];
if($zivio==0)
{
echo "";
}
else{
     $nopl = $pdo->query("SELECT rodjen, smrt FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
	 $sage = $nopl[1]-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
if($uid=="1" OR $uid=="4")
{
echo "<br/><br/>Seu virtual pet morreu! Para ressuscita-lo clique <a href=\"virtual_pet.php?action=ressuscitarvp&sid=$sid\">aqui</a>!";
}
else{
echo "<br/><br/><b>Seu virtual pet morreu!</b>";
}
}
  } else {
echo "<img src=\"images/pets/".$tipo[0].".gif\" alt=\"\"/><br/>";

echo "</p>";
echo "<p align=\"left\">";
$virtual_pet = $pdo->query("SELECT ziv, ime, boja, tezina, rodjen, raspolozenje, broj FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
echo "Nome: <b>$virtual_pet[1]</b><br/>";

     $nopl = $pdo->query("SELECT rodjen FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";

echo "Idade: <b>$ofll1</b><br/>";
echo "Cor: <b>$virtual_pet[2]</b><br/>";
echo "Peso: <b>$virtual_pet[3] gramas</b><br/>";
echo "Felicidade: <b>$virtual_pet[5]%</b><br/>";
echo "Este e seu: <b>$virtual_pet[6]�</b> Virtual Pet<br/>";
echo "<br/>";
echo "<a href=\"virtual_pet.php?action=hrana&sid=$sid\">&#187;Alimentar</a><br/>";
echo "<a href=\"virtual_pet.php?action=igra&sid=$sid\">&#187;Brincar</a><br/>";
echo "<a href=\"virtual_pet.php?action=kupanje&sid=$sid\">&#187;Banho</a><br/>";
echo "</p><p>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=sta&sid=$sid\">Como funciona?</a><br/>";
$apagar = $pdo->query("SELECT ziv FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
if($apagar[0]=="1")
{
echo "<a href=\"virtual_pet.php?action=apagar&sid=$sid\">Apagar meu pet</a><br/>";
}
$ukupno = $pdo->query("SELECT COUNT(*) FROM virtual_pet WHERE ziv='1'")->fetch();
echo "Total de Pets: <a href=\"virtual_pet.php?action=statistika&sid=$sid\">$ukupno[0]</a><br/>";
$memid = $pdo->query("SELECT uid, ime FROM virtual_pet WHERE ziv='1' ORDER BY rodjen DESC LIMIT 0,1")->fetch();
$nick = getnick_uid($memid[0]);
echo "O virtual pet mais novo e <b>$memid[1]</b> criado por $nick";
     $nopl = $pdo->query("SELECT rodjen FROM virtual_pet WHERE uid='".addslashes($memid[0])."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
echo "($ofll1)<br/>";
$memid = $pdo->query("SELECT uid, ime FROM virtual_pet WHERE ziv='1' ORDER BY rodjen LIMIT 0,1")->fetch();
$nick = getnick_uid($memid[0]);
//echo "Random <b>$memid[1]</b> od $nick ";
     $nopl = $pdo->query("SELECT rodjen FROM virtual_pet WHERE uid='".addslashes($memid[0])."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
//echo "($ofll1)<br/>";
$memid = $pdo->query("SELECT uid, ime, raspolozenje FROM virtual_pet WHERE ziv='1' ORDER BY raspolozenje DESC LIMIT 0,1")->fetch();
$nick = getnick_uid($memid[0]);
echo "O virtual pet mais feliz e <b>$memid[1]</b> criado por $nick ($memid[2]%)<br/>";

    echo "</p>";    
    
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";
 
}
if($action=="ressuscitarvp")
{
$pdo->query("UPDATE virtual_pet SET nahranjen='".time()."', igra='".time()."', kupanje='".time()."', tezina='1000', ziv='1' WHERE uid='".addslashes($uid)."'");
echo "<p align=\"center\"><b>Seu virtual pet foi  ressuscitado com sucesso!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";
}
if($action=="usuario")
{
$id = addslashes($_GET["id"]);
echo "<p align=\"center\"><b>Virtual Pet</b><br/>";
$virtual_pet = $pdo->query("SELECT ziv, ime, boja, tezina, rodjen, raspolozenje, broj, tipo FROM virtual_pet WHERE uid='".addslashes($id)."'")->fetch();
echo "<img src=\"images/pets/".$virtual_pet[7].".gif\" alt=\"\"/><br/>";

echo "</p>";
echo "<p align=\"left\">";

echo "Nome: <b>$virtual_pet[1]</b><br/>";

     $nopl = $pdo->query("SELECT rodjen FROM virtual_pet WHERE uid='".addslashes($id)."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";

echo "Idade: <b>$ofll1</b><br/>";
echo "Cor: <b>$virtual_pet[2]</b><br/>";
echo "Peso: <b>$virtual_pet[3] gramas</b><br/>";
echo "Felicidade: <b>$virtual_pet[5]%</b><br/>";
echo "Numero: <b>$virtual_pet[6]�</b> Virtual Pet<br/>";
$uid = addslashes(getuid_sid($sid));
if($uid=="$id")
{
echo "<br/>";
echo "<a href=\"virtual_pet.php?action=hrana&sid=$sid\">&#187;Alimentar</a><br/>";
echo "<a href=\"virtual_pet.php?action=igra&sid=$sid\">&#187;Brincar</a><br/>";
echo "<a href=\"virtual_pet.php?action=kupanje&sid=$sid\">&#187;Banho</a><br/>";
echo "</p>";
}
  echo "<p align=\"center\">";
  echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";

}
if($action=="apagar")
{
echo "<p align=\"center\"><b>Tem certeza que deseja apagar seu virtual pet?</b><br/><br/>";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">NAO</a> <a href=\"virtual_pet.php?action=apagar2&sid=$sid\">SIM</a>";
}
if($action=="apagar2")
{
$pdo->query("UPDATE virtual_pet SET ziv='0' WHERE uid='".addslashes(getuid_sid($sid))."'");
echo "<p align=\"center\"><b>Seu virtual pet foi apagado com sucesso!</b><br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";
}
if($action=="statistika")
{
  		echo "<p align=\"center\"><b>Todos os pets</b><br/>"; 
  
  echo "<img src=\"images/logo.gif\" alt=\"\"/><br/>";
$ukupno = $pdo->query("SELECT COUNT(*) FROM virtual_pet WHERE ziv='1'")->fetch();
echo "Total de pets: $ukupno[0]";
echo "</p>";

    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
  $num_items = $ukupno[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

    $sql = "SELECT uid, ime, tezina, rodjen, boja, broj, raspolozenje, tipo FROM virtual_pet WHERE ziv='1' ORDER BY rodjen DESC LIMIT $limit_start, $items_per_page";

    echo "<p>";
    $items = $pdo->query($sql);
    if($items->rowCount()>0)
    {
    while ($item = $items->fetch())
    {
     $nopl = $pdo->query("SELECT rodjen FROM virtual_pet WHERE uid='".addslashes($item[0])."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
$nick = getnick_uid($item[0]);
      $lnk = "<img src=\"images/pets/".$item[7].".gif\" alt=\"\"/><br/>&#187;<b>$item[1]</b> ($item[7]) e o $item[5]� virtual pet de <b>$nick</b>, ele esta pesando $item[2] gramas e sua cor � $item[4], sua idade e $ofll1 e sua felicidade este em $item[6]%.</small>";
      echo "$lnk<br/><br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"virtual_pet.php?action=statistika&page=$ppage&sid=$sid\">&#171;Voltar</a> ";
    }
    
   if($page<$num_pages)
    {
      $npage = $page+1;
      echo " <a href=\"virtual_pet.php?action=statistika&page=$npage&sid=$sid\">Mais&#187;</a>";
    }
	echo "<br/>";
echo "$page/$num_pages<br/>";
    if($num_pages>2)
    {
                  $rets = "<form action=\"virtual_pet.php\" method=\"get\">";
      $rets .= "Pular a pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"OK\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "</form>";

        echo $rets;
}
    echo "</p>";    
    
    echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}

//////////////////////////////////////////


if($action=="usvoji")
{
addvisitor();
echo "<p align=\"center\">";
echo "<b>Criar Virtual Pet<br/></b></p>
<br/>";
echo "<form action=\"virtual_pet.php?action=set&sid=$sid\" method=\"post\">
Nome do pet: <input id=\"inputText\" type=\"text\" name=\"ime\" value=\"\"/><br/>
Pet: <select id=\"pet\" name=\"pet\">
<option value=\"cachorro\">Cachorro</option>
<option value=\"gato\">Gato</option>
<option value=\"peixe\">Peixe</option>
<option value=\"urso\">Urso</option>
<option value=\"panda\">Panda</option><option value=\"tartaruga\">Tartaruga</option>
<option value=\"vaca\">Vaca</option>
<option value=\"esquilo\">Esquilo</option>
<option value=\"macaco\">Macaco</option>
<option value=\"pintinho\">Pintinho</option>
</select><br/>
Cor: <select id=\"boja\" name=\"boja\">
<option value=\"Azul\">Azul</option>
<option value=\"Vermelho\">Vermelho</option>
<option value=\"Amarelo\">Amarelo</option>
<option value=\"Verde\">Verde</option>
<option value=\"Rosa\">Rosa</option>
</select><br/>
<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>
<input id=\"inputButton\" type=\"submit\" value=\"Criar\"/>
</form><br/>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  

}

////////////////////////////////////////////

if($action=="set")
{
$ime = addslashes($_POST["ime"]);
$boja = addslashes($_POST["boja"]);
$pet = addslashes($_POST["pet"]);

  
echo "<p align=\"center\">";

$uid = getuid_sid($sid);
$hrana = time() - (7*60*60);
  $exs = $pdo->query("SELECT COUNT(*) FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
    if($exs[0]>0)
    {
  $cc = $pdo->query("SELECT broj FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
  $cc = $cc[0]+1;
//$broj = mysql_query("UPDATE virtual_pet SET broj='".addslashes($cc)."' WHERE uid='".addslashes($uid)."'");
$res = $pdo->query("UPDATE virtual_pet SET rodjen='".time()."', tezina='500', ime='".addslashes($ime)."', ziv='1', nahranjen='".addslashes($hrana)."', boja='".addslashes($boja)."', tipo='".addslashes($pet)."', igra='".addslashes($hrana)."', kupanje='".addslashes($hrana)."', smrt='0', raspolozenje='5', broj='".addslashes($cc)."' WHERE uid='".addslashes($uid)."'");
    }
	else
	{
$res = $pdo->query("INSERT INTO virtual_pet SET uid='".addslashes($uid)."', rodjen='".time()."', tezina='500', ime='".addslashes($ime)."', ziv='1', nahranjen='".addslashes($hrana)."', boja='".addslashes($boja)."', tipo='".addslashes($pet)."', igra='".addslashes($hrana)."', kupanje='".addslashes($hrana)."', broj='1'");
    }
        if($res)
        {
echo "<b>Seu virtual pet foi criado com o nome $ime!</b><br/>";
echo "<br/><a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
        }else{
            echo "<b>Erro!</b><br/><br/>";
        }

    echo "</p>";    
    
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}

////////////////////////////////////////////
if($action=="hrana")
{

  
  
    
 echo "<p align=\"center\">";

$uid = getuid_sid($sid);
     $nopl = $pdo->query("SELECT nahranjen FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";

  echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_alimentar.gif\" alt=\"\"/><br/>";  
  echo "Seu virtual pet foi alimentando ha <b>$ofll1</b><br/>";
echo "<a href=\"virtual_pet.php?action=hrana2&sid=$sid\">Alimentar!</a><br/><br/>";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
    echo "</p>";    
    
 
  
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
}

if($action=="igra")
{

  
  
  echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_brincar.gif\" alt=\"\"/><br/>";  
  
  $uid = getuid_sid($sid);
     $nopl = $pdo->query("SELECT igra FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
  
  echo "Seu virtual pet brincou ha <b>$ofll1</b><br/>";
echo "<a href=\"virtual_pet.php?action=igra2&sid=$sid\">Brincar!</a><br/>";
echo "<br/><a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
    echo "</p>";    
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}

if($action=="kupanje")
{

  
  
    
echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_banho.gif\" alt=\"\"/><br/>";

$uid = getuid_sid($sid);
     $nopl = $pdo->query("SELECT kupanje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
	 $sage = time()-$nopl[0];
	 $oflls = ceil(($sage/(1*60))-1);
	 $ofllss = ceil($sage-($oflls*60));
	 $ofllh = ceil(($sage/(1*60*60))-1);
	 $ofllhh = ceil($oflls-($ofllh*60));
	 $oflld = ceil(($sage/(24*60*60))-1);
	 $oflldd = ceil($ofllh-($oflld*24));
  if ($sage <= "60") $ofll1 = "$sage segundos";
  if ($sage <= "3599" AND $sage > "60") $ofll1 = "$oflls minutos, $ofllss segundos";
  if ($sage <= "86399" AND $sage >= "3600") $ofll1 = "$ofllh horas, $ofllhh minutos";
  if ($sage >= "86400") $ofll1 = "$oflld dias, $oflldd horas";
  
  echo "Seu virtual pet tomou banho ha <b>$ofll1</b><br/>";
echo "<a href=\"virtual_pet.php?action=kupanje2&sid=$sid\">Banho!</a><br/>";
echo "<br/><a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
    echo "</p>";    
    
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}
////////////////////////////////////////////
if($action=="hrana2")
{
 
	  echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_alimentar.gif\" alt=\"\"/><br/>";
$uid = getuid_sid($sid);

$nopl = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
if($nopl[0] > "9999")
{
$res = $pdo->query("UPDATE virtual_pet SET ziv='0', smrt='".time()."' WHERE uid='".addslashes($uid)."'");
//echo "Seu virtual pet esta se alimentando!<br/>";
}
else if($nopl[0] < "300")
{
$res = $pdo->query("UPDATE virtual_pet SET ziv='0', smrt='".time()."' WHERE uid='".addslashes($uid)."'");
//echo "Ele esta muito leve, alimente ele mais um pouco!<br/>";
}
else if($nopl[0] > "299" AND $nopl[0] < "10000")
{
$nopl = $pdo->query("SELECT nahranjen FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$nopl1 = time() - $nopl[0];
   if($nopl1 < "28800")
   {
$res = $pdo->query("UPDATE virtual_pet SET nahranjen='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 250;
$res = $pdo->query("UPDATE virtual_pet SET tezina='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
//echo "Va&#353; virtual_pet je upravo jeo i udebljao se na $tezina g.<br/>";
}
   else if($nopl1 < "604800" AND $nopl1 > "28799")
   {
$res = $pdo->query("UPDATE virtual_pet SET nahranjen='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 0;
$res = $pdo->query("UPDATE virtual_pet SET tezina='".addslashes($tezina)."' WHERE uid='".addslashes($uid)."'");
//echo "Va&#353; virtual_pet je upravo jeo i zadr&#382;ao je te&#382;inu od $tezina g.<br/>";
}
  else if($nopl1 < "54000" AND $nopl1 > "604799")
   {
$res = $pdo->query("UPDATE virtual_pet SET nahranjen='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] - 100;
$res = $pdo->query("UPDATE virtual_pet SET tezina='".addslashes($tezina)."' WHERE uid='".addslashes($uid)."'");
//echo "Va&#353; virtual_pet je upravo jeo i smr&#353;ao je na $tezina g.<br/>";
}
  else if($nopl1 < "604800" AND $nopl1 > "53999")
   {
$res = $pdo->query("UPDATE virtual_pet SET nahranjen='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] - 100;
$res = $pdo->query("UPDATE virtual_pet SET tezina='".addslashes($tezina)."' WHERE uid='".addslashes($uid)."'");
//echo "Your pet weight is : $tezina g.<br/>";
}
   else
   {
$res = $pdo->query("UPDATE virtual_pet SET ziv='0', smrt='".time()."' WHERE uid='".addslashes($uid)."'");
//echo "virtual_pet the weird pet did not eat..perhaps he is full..<br/>";
}
echo "Seu virtual pet esta se alimentando!<br/>E recomendado que voce alimente ele a cada 8-12 horas ou uma vez por dia! Ele esta pesando $tezina gramas!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
  echo "<p align=\"center\">";
  echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";
echo "</p>";

}

if($action=="igra2")
{

echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_brincar.gif\" alt=\"\"/><br/>";

$uid = getuid_sid($sid);

$nopl = $pdo->query("SELECT igra FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$nopl1 = time() - $nopl[0];
   if($nopl1 < "600")
   {
$res = $pdo->query("UPDATE virtual_pet SET igra='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 0;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo " virtual_pet the administration satisfied this finish has remained at $rasp[0].<br/>";
}
   if($nopl1 < "172800" AND $nopl1 > "599")
   {
$res = $pdo->query("UPDATE virtual_pet SET igra='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 2;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "virtual_pet the administration is satisfied his status has increased to $rasp[0].<br/>";
}
   else if($nopl1 < "86400" AND $nopl1 > "172799")
   {
$res = $pdo->query("UPDATE virtual_pet SET igra='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 1;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "virtual_pet the administration is satisfied his status has increased to $rasp[0].<br/>";
}
  else if($nopl1 > "86399")
   {
$res = $pdo->query("UPDATE virtual_pet SET igra='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] - 1;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "virtual_pet the administration is satisfied his fun status has increased to $rasp[0].Play Nice with him.<br/>";
}
$tezina = $pdo->query("SELECT tezina FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] - 50;
$res = $pdo->query("UPDATE virtual_pet SET tezina='".addslashes($tezina)."' WHERE uid='".addslashes($uid)."'");
echo "Seu virtual pet esta brincando!<br/>A felicidade dele esta em $rasp[0]%<br/>";
    echo "</p>";    
    
  echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
  echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}

if($action=="kupanje2")
{
echo "<p align=\"center\">";
  echo "<img src=\"images/pets/".$tipo[0]."_banho.gif\" alt=\"\"/><br/>";
$uid = getuid_sid($sid);

$nopl = $pdo->query("SELECT kupanje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$nopl1 = time() - $nopl[0];
   if($nopl1 < "604799")
   {
$res = $pdo->query("UPDATE virtual_pet SET kupanje='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] - 1;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "Va&#353; virtual_pet the owner has been bathed $rasp[0].<br/>";
}
   if($nopl1 < "804800" AND $nopl1 > "21600")
   {
$res = $pdo->query("UPDATE virtual_pet SET kupanje='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 2;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "Va&#353; virtual_pet je upravo zadovoljno okupan i raspolo&#382;enje mu je poraslo na $rasp[0].<br/>";
}
   else if($nopl1 < "604800" AND $nopl1 > "86399")
   {
$res = $pdo->query("UPDATE virtual_pet SET igra='".time()."' WHERE uid='".addslashes($uid)."'");
$tezina = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
$tezina = $tezina[0] + 1;
$res = $pdo->query("UPDATE virtual_pet SET raspolozenje='".addslashes($tezina)."' WHERE uid='".addslashes(getuid_sid($sid))."'");
$rasp = $pdo->query("SELECT raspolozenje FROM virtual_pet WHERE uid='".addslashes(getuid_sid($sid))."'")->fetch();
//echo "i bathed him rise to the $rasp[0].<br/>";
}
   else if($nopl1 > "604799")
   {
$res = $pdo->query("UPDATE virtual_pet SET ziv='0', smrt='".time()."' WHERE uid='".addslashes($uid)."'");
//echo "your friend did not revive his sight.<br/>";
}
    echo "Seu virtual pet esta tomando banho!<br/>A felicidade dele esta em $rasp[0]%</p>";    
    
  echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/>";
  echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
  
}

if($action=="sta")
{
  echo "<p align=\"center\">";
echo "<img src=\"images/logo.gif\" alt=\"\"/>";
echo "<br/><b>Como funciona o virtual pet?</b><br/><br/>";
echo "Voce deve alimenta-lo, brincar, e dar banho em seu virtual pet, pelo menos uma vez por dia. Se seu pet passar de <b>5000 gramas</b> ele podera morrer.<br/>"; 
  echo "<p align=\"center\">";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">Virtual Pet</a><br/><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>P�gina principal</a>";  
}

?>