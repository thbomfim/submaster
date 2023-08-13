<?php


include("config.php");
include("core.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

	echo "<head>";

	echo "<title>$stitle</title>";
	echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/style.css\" />";
	echo "</head>";

	echo "<body>";

$action = $_GET["action"];
$sid = $_GET["sid"];
$page = $_GET["page"];

    if(is_logado($sid)==false)
    {
      
      echo "<p align=\"center\">";
      echo "Você não está logado!<br/><br/>";
      echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
    }

if(is_banido($uid))
    {

      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "Você está <b>Banido</b><br/>";
      $banto = $pdo->query("SELECT timeto FROM fun_penalties WHERE uid='".$uid."' AND penalty='1'")->fetch();
      $remain = $banto[0]- time();
      $rmsg = tempo_msg($remain);
      echo "Tempo para acabar sua punição: $rmsg<br/><br/>";
      echo "<a href=\"index.php\">Página inicial</a>";
      echo "</p>";
      exit();
    }
if($action=="tpc")
{
    adicionar_online(getuid_sid($sid),"Buscar","");
    
    echo "<p>";
echo "<form action=\"search.php?action=stpc&sid=$sid\" method=\"post\">";
    echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "em: <select name=\"sin\">";
    echo "<option value=\"1\">postagens</option>";
    echo "<option value=\"2\">texto do topico</option>";
    echo "<option value=\"3\">nome</option>";
    echo "</select><br/>";
    echo "Ordem: <select name=\"sor\">";
    echo "<option value=\"1\">novos</option>";
    echo "<option value=\"2\">antigos</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"buscar\"/>";
  echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu de busca</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="blg")
{
    adicionar_online(getuid_sid($sid),"Blogs search","");
    
    echo "<p>";
echo "<form action=\"search.php?action=sblg&sid=$sid\" method=\"post\">";
    echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "em: <select name=\"sin\">";
    echo "<option value=\"1\">Texto</option>";
    echo "<option value=\"2\">Nome</option>";
    echo "</select><br/>";
    echo "Ordem: <select name=\"sor\">";
    echo "<option value=\"1\">Nome</option>";
    echo "<option value=\"2\">Hora</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu de busca</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="clb")
{
    adicionar_online(getuid_sid($sid),"Buscar","");
    
    echo "<p>";
echo "<form action=\"search.php?action=sclb&sid=$sid\" method=\"post\">";
    echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "Em: <select name=\"sin\">";
    echo "<option value=\"1\">Descricao</option>";
    echo "<option value=\"2\">Nome</option>";
    echo "</select><br/>";
    echo "Ordem: <select name=\"sor\">";
    echo "<option value=\"1\">Nome</option>";
    echo "<option value=\"2\">Antigos</option>";
    echo "<option value=\"3\">Novos</option>";
    echo "</select><br/>";
    
    
echo "<input type=\"submit\" value=\"Buscar\"/>";
    echo "</form>";

    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu de busca</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="nbx")
{
    adicionar_online(getuid_sid($sid),"buscar","");
    
    echo "<p>";
echo "<form action=\"search.php?action=snbx&sid=$sid\" method=\"post\">";
    echo "Texto: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "Em: <select name=\"sin\">";
    echo "<option value=\"1\">Recebidos</option>";
	echo "<option value=\"2\">Enviados</option>";
    echo "<option value=\"3\">Nome</option>";
    echo "</select><br/>";
    echo "Ordem: <select name=\"sor\">";
    echo "<option value=\"1\">Novos</option>";
    echo "<option value=\"2\">Antigos</option>";
    echo "<option value=\"2\">Nome</option>";
    echo "</select><br/>";
    
    
echo "<input type=\"submit\" value=\"buscar\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu de busca</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="mbrn")
{
    adicionar_online(getuid_sid($sid),"buscar","");
    
    echo "<p>";
echo "<form action=\"search.php?action=smbr&sid=$sid\" method=\"post\">";
    echo "Nickname: <input name=\"stext\" maxlength=\"15\"/><br/>";
    echo "Ordem: <select name=\"sor\">";
    echo "<option value=\"1\">Nome</option>";
    echo "<option value=\"2\">Ultima vez ativo</option>";
    echo "<option value=\"3\">Data de cadastro</option>";
    echo "</select><br/>";
    echo "<input type=\"submit\" value=\"Buscar\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu de busca</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="stpc")
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    adicionar_online(getuid_sid($sid),"buscar","");
    
    echo "<p>";

        if(trim($stext)=="")
        {
            echo "<br/>digite o texto";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_posts";
            $cond = "text";
            $select_fields = "id, tid";
            if($sor=="1")
            {
              $ord_fields = "dtpost DESC";
            }else{
                $ord_fields = "dtpost";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_topics";
            $cond = "text";
            $select_fields = "name, id";
            if($sor=="1")
            {
              $ord_fields = "crdate DESC";
            }else{
                $ord_fields = "crdate";
            }
          }else if($sin=="3")
          {
            $where_table = "fun_topics";
            $cond = "name";
            $select_fields = "name, id";
            if($sor=="1")
            {
              $ord_fields = "crdate DESC";
            }else{
                $ord_fields = "crdate";
            }
          }
          $noi = $pdo->query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'")->fetch();
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    
    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = $pdo->query($sql);
          while($item = $items->fetch())
          {
            if($sin=="1")
            {
              $tname = htmlspecialchars(gettname($item[1]));
			  
              if($tname=="" || !canaccess(getuid_sid($sid),getfid_tid($item[1]))){
                $tlink = "Unreachable<br/>";
              }else{
              $tlink = "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$item[1]&go=$item[0]\">".$tname."</a><br/>";
              }
                echo  $tlink;
            }
            else
            {
              $tname = htmlspecialchars($item[0]);
              if($tname=="" || !canaccess(getuid_sid($sid),getfid_tid($item[1]))){
                $tlink = "Unreachable<br/>";
              }else{
              $tlink = "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$item[1]\">".$tname."</a><br/>";
              }
                echo  $tlink;
            }
          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
 $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"voltar\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
      
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"mais\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
      
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$page\" method=\"post\">";
      $rets .= "pular para pagina <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Ok\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu buscar</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="sblg")
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    adicionar_online(getuid_sid($sid),"Buscar","");
    
    echo "<p>";

    
    
        if(trim($stext)=="")
        {
            echo "<br/>digite o texto";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_blogs";
            $cond = "btext";
            $select_fields = "id, bname";
            if($sor=="1")
            {
              $ord_fields = "bname";
            }else{
                $ord_fields = "bgdate DESC";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_blogs";
            $cond = "bname";
            $select_fields = "id, bname";
            if($sor=="1")
            {
              $ord_fields = "bname";
            }else{
                $ord_fields = "bgdate DESC";
            }
          }
          $noi = $pdo->query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'")->fetch();
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = $pdo->query($sql);
          while($item = $items->fetch())
          {
              $tlink = "<a href=\"index.php?action=viewblog&sid=$sid&bid=$item[0]&go=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;
            
          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
 $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Voltar\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Mais\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$page\" method=\"post\">";
      $rets .= "Pular a pagina:  <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Ok\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu buscar</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="sclb")
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    adicionar_online(getuid_sid($sid),"Club search","");
    
    echo "<p>";

    
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for club";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_clubs";
            $cond = "description";
            $select_fields = "id, name";
            if($sor=="1")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "created";
            }else if($sor=="3"){
                $ord_fields = "created DESC";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_clubs";
            $cond = "name";
            $select_fields = "id, name";
            if($sor=="1")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "created";
            }else if($sor=="3"){
                $ord_fields = "created DESC";
            }
          }
          $noi = $pdo->query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'")->fetch();
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = $pdo->query($sql);
          while($item = $items->fetch())
          {
              $tlink = "<a href=\"index.php?action=gocl&sid=$sid&clid=$item[0]&go=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;

          }
          echo "<p align=\"center\">";
		  if($page>1)
    {
      $ppage = $page-1;
       $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Voltar\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Mais\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$page\" method=\"post\">";
      $rets .= "Pular a pagina:  <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Ok\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu buscar</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="snbx")
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    adicionar_online(getuid_sid($sid),"Inbox search","");
    
    echo "<p>";

        $myid = getuid_sid($sid);
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for message";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin==1)
          {
          $noi = $pdo->query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND touid='".$myid."'")->fetch();
		  }else if($sin==2)
		  {
			$noi = $pdo->query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND byuid='".$myid."'")->fetch();
          }else{
                $stext = getuid_nick($stext);
            $noi = $pdo->query("SELECT COUNT(*)  FROM fun_private  WHERE byuid ='".$stext."' AND touid='".$myid."'")->fetch();
          }
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
          
          if($sin=="1")
          {
            /*
            $where_table = "fun_blogs";
            $cond = "btext";
            $select_fields = "id, bname";*/
            
            if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            //echo $sql;
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
          }
		  else if($sin=="2")
		  {
			if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            //echo $sql;
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.touid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
		  }
		  else if($sin=="3")
          {
            
            if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY b.timesent
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
          }
          

          $items = $pdo->query($sql);
          while($item = $items->fetch())
          {
              if($item[3]=="1")
      {
        $iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";
      }else{
        if($item[4]=="1")
        {
            $iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";
        }else{

        $iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";
        }
      }

      $lnk = "<a href=\"inbox.php?action=readpm&pmid=$item[1]&sid=$sid\">$iml ".getnick_uid($item[2])."</a>";
      echo "$lnk<br/>";

          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Voltar\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
       $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Mais\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
              $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$page\" method=\"post\">";
      $rets .= "Pular a pagina:  <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Ok\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu buscar</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}

else if($action=="smbr")
{
	$stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    adicionar_online(getuid_sid($sid),"Club search","");
    
    echo "<p>";

    
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for club";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          
            $where_table = "fun_users";
            $cond = "name";
            $select_fields = "id, name";
            if($sor=="1")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "lastact DESC";
            }else if($sor=="3"){
                $ord_fields = "regdate";
            }
          
          $noi = $pdo->query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'")->fetch();
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = $pdo->query($sql);
          while($item = $items->fetch())
          {
              $tlink = "<a href=\"index.php?action=perfil&sid=$sid&who=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;

          }
          echo "<p align=\"center\">";
		  if($page>1)
    {
      $ppage = $page-1;
       $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Voltar\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Mais\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
        $rets = "<form action=\"search.php?action=$action&sid=$sid&page=$page\" method=\"post\">";
      $rets .= "Pular a pagina:  <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Ok\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Menu buscar</a><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}
  

else{
  adicionar_online(getuid_sid($sid),"Lost in search lol","");
    
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
  echo "</p>";
}
	echo "</body>";
	echo "</html>";
?>