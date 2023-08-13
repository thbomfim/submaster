<?php

function nome_arquivo($texto)
{
$texto = str_replace("H", "_", $texto);
$texto = str_replace("h", "_", $texto);
return $texto;
}


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

$a = $_GET["a"];
$sid = $_GET["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
$pmid = $_GET["pmid"];
$id = $_GET["id"];
$did = $_GET["did"];
$vit = $_GET["vit"];

if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
$uid = getuid_sid($sid);

if($a=="new")
{
adicionar_online(getuid_sid($sid),"Criando um album","");
echo "<p align=\"center\">";
$meus = $pdo->query("SELECT COUNT(*) FROM fun_albums WHERE uid='".$uid."'")->fetch();
if($meus[0]==0)
{
$pdo->query("INSERT INTO fun_albums SET uid='".$uid."', nome='".$uid."', logo='images/logo.gif', cmt='Nenhuma', time='".$time."', atul='".time()."'");
echo "<b>Parabêns agora você tem seu álbum pessoal!</b><br/>";
}
else
{
echo "<b>Desculpe, mais você já possue um álbum!</b><br>";
}
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}

else if($a=="cmt")
{
adicionar_online(getuid_sid($sid),"Comentarios do álbum","");
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_cmt_a WHERE did='".$did."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
echo "<p>";
if($num_items>0)
{

//changable sql
$sql = "
SELECT id, uid, did, texto, cor, time  FROM fun_cmt_a WHERE did='".$did."' ORDER BY id DESC
LIMIT $limit_start, $items_per_page
";
echo "<p>";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
$nick = getnick_uid($item[1]);
$text = scan_msg($item[3], $sid);
$tmstamp = $item[5];
$tmdt = date("d/m/Y - H:i:s", $tmstamp);
$lnk = "<a href=\"index.php?action=perfil&who=$item[1]&sid=$sid\">$nick</b></a>: <font color=\"$item[4]\">$text</font><br/>$tmdt";
if(candelcmta($uid, $item[0]))
{
$dlnk = "<a href=\"album.php?a=apagar_cmt&vit=$item[0]&sid=$sid&did=$item[2]\">[X]</a>";
}else{
$dlnk = "";
}


echo "$lnk $dlnk<br/><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
// Build Previous Link 
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"?a=cmt&page=$ppage&sid=$sid&did=$did\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"?a=cmt&page=$npage&sid=$sid&did=$did\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/><br/>";
}else{
echo "<b>Não há comentarios neste album!</b><br/>";
}
echo "<br/><a href=\"album.php?a=cmt2&sid=$sid&did=$did\"><img src=\"teks/cmt.gif\" alt=\"*\"/>";
echo "Adicionar comentario</a><br/>";
echo "<a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";

}
else if($a=="cmt2")
{
adicionar_online(getuid_sid($sid),"Escrevendo comentario em album","");
echo "<p align=\"left\">";
echo "<form action=\"album.php?sid=$sid&a=cmt3&did=$did\" method=\"post\">";
echo "Texto: <input name=\"texto\" maxlength=\"250\"/><br/>";
echo "Cor: <select name=\"cor\">";
echo "<option value=\"black\">preto</option>";
echo "<option value=\"blue\">azul</option>";
echo "<option value=\"red\">vermelho</option>";
echo "<option value=\"green\">verde</option>";
echo "<option value=\"yellow\">amarelo</option>";
echo "<option value=\"orange\">laranja</option>";
echo "<option value=\"deeppink\">pink</option>";
echo "<option value=\"purple\">roxo</option>";
echo "<option value=\"silver\">prata</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Gravar\"/>";
echo "</form>";
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";

}
else if($a=="cmt3")
{
adicionar_online(getuid_sid($sid),"Adicionando comentario em album","");
echo "<p align=\"center\">";
$texto = $_POST["texto"];
$cor = $_POST["cor"];
if($texto=="")
{
echo "<b>Digite algum texto!</b><br/>";
}
else
{
$time = time();
$pdo->query("INSERT INTO fun_cmt_a SET uid='".$uid."', did='".$did."', texto='".$texto."', cor='".$cor."', time='".$time."'");
$pdo->query("UPDATE fun_albums SET time='".$time."' WHERE id='".$did."'");
echo "<b>Comentario enviado com sucesso!</b><br/>";
}
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}

else if($a=="avatar")
{
adicionar_online(getuid_sid($sid),"Mudando avatar","");
echo "<p align=\"center\">";
if(candelfoto($uid, $vit))
{
$foto = $pdo->query("SELECT url FROM fun_fotos WHERE id='".$vit."'")->fetch();
$url ="fotos/$foto[0]";
$pdo->query("UPDATE fun_users SET avatar='".$url."' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"ok\"/>Avatar atualizado com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao mudar avatar!<br/>";
}
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";

}
else if($a=="apagar")
{
adicionar_online(getuid_sid($sid),"Apagando foto de album","");
echo "<p align=\"center\">";
if(candelfoto($uid, $vit))
{
$url = $pdo->query("SELECT url FROM fun_fotos WHERE id='".$vit."'")->fetch();
$urls = "fotos/".$url[0];
if(file_exists($urls))
{
unlink($urls);
}
$pdo->query("DELETE FROM fun_fotos WHERE id='".$vit."'");
echo "<img src=\"images/ok.gif\" alt=\"ok\"/>Foto apagada com sucesso!<br/>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao apagar foto!<br/>";
}
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}

else if($a=="apagar_cmt")
{
adicionar_online(getuid_sid($sid),"Apagando comentario de album","");
echo "<p align=\"center\">";
if(candelcmta($uid, $vit))
{
$pdo->query("DELETE FROM fun_cmt_a WHERE id='".$vit."'");
echo "<img src=\"images/ok.gif\" alt=\"ok\"/>Comentario apagado com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao apagar o comentario!<br/>";
}
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}
else if($a=="albapagar")
{
adicionar_online(getuid_sid($sid),"Apagando album","");
echo "<p align=\"center\">";
if(candelalbum($uid, $vit))
{
$pdo->query("DELETE FROM fun_albums WHERE id='".$vit."'");
$pdo->query("DELETE FROM fun_fotos WHERE did='".$vit."'");
$pdo->query("DELETE FROM fun_cmt_a WHERE did='".$vit."'");
echo "<img src=\"images/ok.gif\" alt=\"ok\"/>álbum excluido com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao apagar album!<br/>";
}
echo "<br><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}

else if($a=="capa")
{
adicionar_online(getuid_sid($sid),"Mudando capa do álbum","");

echo "<p align=\"center\">";

$vit = $_GET["vit"];

$a = $pdo->query("SELECT url FROM fun_fotos WHERE id='".$vit."'")->fetch();
$c = $pdo->query("UPDATE fun_albums SET logo='".$a[0]."', atul='".time()."' WHERE id='".$vit."'");
if($c)
{
echo "<img src=\"images/ok.gif\" alt=\"ok\"/>Capa do álbum alterada com sucesso!<br>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao mudar a capa do álbum!<br>";
}
echo "<br><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}

else if($a=="fotos")
{
adicionar_online(getuid_sid($sid),"Vendo fotos de album","");
echo "<p align=\"center\">";

if($page=="" || $page<=0)$page=1;
$timeout = 600;
$timeon = time()-$timeout;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_fotos WHERE did='".$did."'")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 4;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($num_items>0)
{
$sql = "
SELECT id, uid, url, cmt, time, did  FROM fun_fotos WHERE did='".$did."' ORDER BY id DESC
LIMIT $limit_start, $items_per_page
";
echo "<p>";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
$text = htmlspecialchars($item[3]);
$tmstamp = $item[4];
$tmdt = date("d/m/Y - H:i:s", $tmstamp);
$lnk = "<img src=\"fotos/$item[2]\" width=\"100x100\"alt=\"*\"/><br/>$text<br/>$tmdt";
if(candelfoto($uid, $item[0]))
{
$dlnk = "<a href=\"album.php?a=apagar&vit=$item[0]&sid=$sid&did=$item[5]\">[X]</a> <a href=\"album.php?a=avatar&vit=$item[0]&sid=$sid&did=$item[5]\">[AVATAR]</a> <a href=\"?a=capa&sid=$sid&vit=$item[0]\">[CAPA]</a>";
}else
{
$dlnk = "";
}
echo "$lnk <br/> $dlnk<br/><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
// Build Previous Link 
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"?a=fotos&page=$ppage&sid=$sid&did=$did\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"?a=fotos&page=$npage&sid=$sid&did=$did\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/><br/>";
}else{
echo "<b>Não há fotos neste album!</b><br/>";
}
echo "<br>";
$item = $pdo->query("SELECT id, uid FROM fun_albums WHERE id='".$did."'")->fetch();
if($item[1]==getuid_sid($sid))echo "<a href=\"?a=upload&sid=$sid&did=$did\"><img src=\"images/add.gif\">Enviar Fotos</a><br>";
echo "<a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}
else if($a=="upload")
{
adicionar_online(getuid_sid($sid),"Adicionando foto no album","");

echo "<p align=\"left\">";
echo "<img src=\"images/point.gif\">Não é premitidas fotos de nudez, em posições sexuais e pornograficas!.<br>";
echo "<img src=\"images/point.gif\">Extenções permitidas: JPG, JPEG, PNG, GIF E BMP.<br>";
echo "<img src=\"images/point.gif\">A equipe está liberada para moderar qualquer álbum sendo protegido por senha ou não!.<br>";
echo "<form action=\"album.php?sid=$sid&a=upfotos&did=$did\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Arquivo: <input type=\"file\" name=\"foto\"/><br/>";
echo "Descrição: <input type=\"text\" name=\"cmt\"/><br/>";
echo "<input type=\"submit\" value=\"Upload\"/>";
echo "</form><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}
else if($a=="upfotos")
{
adicionar_online(getuid_sid($sid),"Upload de fotos","");
echo "<p align=\"center\">";

$cmt = $_POST["cmt"];
$new_name = $_FILES['foto']['name'];
$clean_name = str_replace(" ", "_", str_replace("%20", "_", strtolower($new_name) ) );
$file_ext = explode('.', $clean_name);  
$file_ext = strtolower($file_ext[count($file_ext) - 1]);
$kbsize = (round($_FILES['foto']['size']/1024));
$clean_name = nome_arquivo($clean_name);
$dono = $pdo->query("SELECT uid FROM fun_albums WHERE id='".$did."'")->fetch();
if($dono[0] != $uid)
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Você não é dono desse álbum!<br/>";
}
else if(file_exists("fotos/$clean_name")) 
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Arquivo já existe no álbum!<br/>";
}
else if($kbsize > 1024)
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Tamanho da imagem ultrapassa limite de 1024KBs(<b>1MB</b>)!<br/>";
}else if(arquivo_extfoto($file_ext)=="1")
{
//log
$msg = "%$uid% tentou enviar um arquivo com a extenção .".strtoupper($file_ext).", para seu álbum!";
addlog($msg);
echo "<img src=\"images/notok.gif\" alt=\"*\">Não é permitido arquivos de fotos com extenção .".strtoupper($file_ext)."!<br/>";
}else
{
$nome_real = rand(0,9).rand(100,200).rand(1000,9999).".".$file_ext;
move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/".$nome_real);
$time = time();
$pdo->query("INSERT INTO fun_fotos SET uid='".$uid."', url='".$nome_real."', cmt='".$cmt."', time='".$time."', did='".$did."'");
$pdo->query("UPDATE fun_albums SET atul='".time()."' WHERE id='".$did."'");
echo "<img src=\"images/ok.gif\" alt=\"*\">Foto enviada com sucesso!<br/>";
}
echo "<br/><a href=\"album.php?a=ver&sid=$sid&id=$did\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}

else if($a=="ver")
{
adicionar_online(getuid_sid($sid),"Visitando album","");
echo "<p align=\"center\">";
$album = $pdo->query("SELECT id, uid, nome, logo, cmt, time, vis, pontos, senha, atul FROM fun_albums WHERE id='".$id."'")->fetch();
if($album[0]==0)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>álbum não existe, ou foi apagado!";
echo "<br><br>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
exit;
}
$senha = $_POST["senha"];
if($senha==$album[8] or $album[8]=="" or $album[1]==$uid or ismod($uid))
{
echo "<b>álbum de ".getnick_uid($album[2])."</b><br/>";

if(empty($album[3]) or !file_exists("fotos/".$album[3]))
{
echo "<img src=\"images/logo.png\" alt=\"logo\">";
}
else
{
echo "<img src=\"fotos/$album[3]\" alt=\"*\" width=\"150x150\"/><br/>";
}
echo "</p>";
echo "<p align=\"left\">";
$nick = getnick_uid($album[1]);
echo "Descrição: <b>".htmlspecialchars($album[4])."</b><br>";
echo "Dono: <b><a href=\"index.php?action=perfil&who=$album[1]&sid=$sid\">$nick</a></b><br/>";
$vis = $album[6]+1;
$pontos = "0";
$valor = "0";
$pdo->query("UPDATE fun_albums SET vis='".$vis."', pontos='".$pontos."', valor='".$valor."' WHERE id='".$id."'");
echo "Visitas: <b>$vis</b><br/><br/>";
$data = date("d/m/Y - H:i:s", $album[9]);
echo "última atualização: $data<br><br>";
$fotos = $pdo->query("SELECT COUNT(*) FROM fun_fotos WHERE did='".$album[0]."'")->fetch();
echo "<a href=\"album.php?a=fotos&sid=$sid&did=$album[0]\"><img src=\"teks/galeria.gif\" alt=\"*\"/>";
echo "Fotos($fotos[0])</a><br/><br/>";
$cmt = $pdo->query("SELECT COUNT(*) FROM fun_cmt_a WHERE did='".$album[0]."'")->fetch();
echo "<a href=\"album.php?a=cmt&sid=$sid&did=$album[0]\"><img src=\"teks/cmt.gif\" alt=\"*\"/>Comentários($cmt[0])</a><br/>";
if($uid=="$album[1]" OR ismod($uid))
{
echo "<a href=\"album.php?a=editar&sid=$sid&id=$id\"><img src=\"images/cp.png\" alt=\"*\"/>";
echo "Editar album</a><br/>";
}else
{
}
}
else
{
echo "<b>Digite a senha</b><br>";
echo "<form action=\"\" method=\"post\">";
echo "Senha: <input name=\"senha\" type=\"text\"><br>";
echo "<input type=\"submit\" value=\"Entrar\"></form>";
}
echo "<br/><a href=\"album.php?sid=$sid&a=albums\"><img src=\"images/0a.gif\" alt=\"*\"/>álbums</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}
else if($a=="editar")
{
adicionar_online(getuid_sid($sid),"Editando album","");
$album = $pdo->query("SELECT id, uid, nome, logo, cmt, time, senha FROM fun_albums WHERE id='".$id."'")->fetch();
echo "<p align=\"center\">";
if($uid=="$album[1]" OR ismod($uid))
{
echo "<p align=\"center\">";
echo "<b>Definições gerais</b>";
echo "<form action=\"album.php?a=editar2&sid=$sid&id=$id\" method=\"post\">";
echo "Descrição: <input name=\"cmt\" value=\"$album[4]\" maxlength=\"300\"/><br/>";
echo "Senha (deixe em branco para desativar): <input name=\"senha\" type=\"text\" value=\"$album[6]\"><br>";
echo "<input type=\"submit\" value=\"Editar album\"/>";
echo "</form><p align=\"center\"><br/>";
echo "<a href=\"album.php?a=albapagar&sid=$sid&vit=$id\"><img src=\"teks/pro.png\" alt=\"*\"/>";
echo "Apagar album</a><br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Esse álbum não é seu!<br/>";
echo "<br />";
}
echo "<a href=\"album.php?a=ver&sid=$sid&id=$id\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
echo "</p>";
}

else if($a=="editar2")
{
adicionar_online(getuid_sid($sid),"Editando album","");
$album = $pdo->query("SELECT id, uid, nome, logo, cmt, time FROM fun_albums WHERE id='".$id."'")->fetch();
echo "<p align=\"center\">";
if($uid=="$album[1]" OR ismod($uid))
{
$senha = $_POST["senha"];
$cmt = $_POST["cmt"];
$time = time();
$pdo->query("UPDATE fun_albums SET nome='".$uid."', cmt='".$cmt."', time='".$time."', senha='".$senha."' WHERE id='".$id."'");
echo "<b>álbum atualizado com sucesso!</b><br/>";
}else
{
echo "<b>Esse álbum não é seu!</b><br/>";
}
echo "<br><a href=\"album.php?a=ver&sid=$sid&id=$id\"><img src=\"images/0a.gif\" alt=\"*\"/>";
echo "Voltar para o álbum</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}

else if($a=="albums")
{
adicionar_online(getuid_sid($sid),"Vendo �lbums","");
echo "<p align=\"center\">";

if($page=="" || $page<=0)$page=1;
$vip = 'tek';
$timeout = 600;
$timeon = time()-$timeout;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_albums ")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 6;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($num_items>0)
{

//changable sql
$sql = "
SELECT id, uid, nome, logo, cmt, time  FROM fun_albums ORDER BY time DESC
LIMIT $limit_start, $items_per_page
";
echo "<p>";
$items = $pdo->query($sql);
while ($item = $items->fetch())
{
$lnk = "<a href=\"album.php?id=$item[0]&sid=$sid&a=ver\"><img src=\"images/0a.gif\" alt=\"*\"/>".getnick_uid($item[2])."</a><br/>";
$fotos = $pdo->query("SELECT COUNT(*) FROM fun_fotos WHERE did='".$item[0]."'")->fetch();
$nick = getnick_uid($item[1]);

echo "$lnk Fotos: $fotos[0]<br/><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
// Build Previous Link 
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"?a=albums&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"?a=albums&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/><br/>";
}else{
echo "<b>Nenhum álbum no momento!</b><br/><br/>";
}
$na = $pdo->query("SELECT COUNT(*) FROM fun_albums WHERE uid='".$uid."'")->fetch();
if($na[0]<1)
{
echo "<a href=\"album.php?a=new&sid=$sid\"><img src=\"teks/arrow.gif\" alt=\"*\"/>";
echo "Novo álbum</a><br/>";
}

echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";

echo "</p>";
}
echo "</body>";
echo "</html>";
?>