<?php
//include core.php and config.php file
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
$p = $_GET["p"];
$sid = $_GET["sid"];
$id = $_GET["id"];
$cat = addslashes($_GET["c"]);
$uid = getuid_sid($sid);
if(is_logado($sid) == false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br><br>";
echo "<a href=\"index.php\">Login</a><br></p>";
exit;
}
if($a=="d")
{
if(ismod($uid))
{
echo "<p align=\"center\">";
$d = $pdo->query("DELETE FROM fun_downloads WHERE id='".$id."'");
if($d)
{
echo "<img src=\"images/ok.gif\" alt=\"\">Download deletado com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Download não apagado!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Você não faz parte da equipe!";
}
}
else if($a=="adds")
{
echo "<p align=\"center\">";
$c = $_POST["categoria"];
$n = $_POST["nome"];
$nome = $_FILES["file"]["name"];
$ext = arquivo_ext($nome);
$size = (round($_FILES['file']['size']/1024));
if(empty($_FILES["file"]["name"])||empty($n)) 
{
echo "<img src=\"images/notok.gif\" alt=\"\">Todos os campos são obrigatorios!";
}
else if($size>5120)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Arquivo é muito grande!";
}
else if(arquivo($ext)=="1")
{
echo "<img src=\"images/notok.gif\" alt=\"\">Não é permitido enviar arquivos com a extenção .$ext!";
//log
$msg = "%uid% tentou enviar um arquivo da extenção .$ext para a página de downloads do site!";
addlog($msg);
}
else
{
$pasta = "downloads/";
$new_name = "download_".$uid.time()."_".date("dmy").".".$ext;
$new_name = strtoupper($new_name);
$upload = move_uploaded_file($_FILES["file"]["tmp_name"], $pasta.$new_name);
if($upload)
{
$res = $pdo->query("INSERT INTO fun_downloads SET url='".$new_name."', nome='".$n."', visitas='0', categoria='".$c."', uid='".$uid."', data='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"\">Download enviado com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Erro sql tente mais tarde!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Arquivo não foi enviado!";
}
}
}
else if($a=="add")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Download</b><br></p>";
echo "<form action=\"?a=adds&sid=$sid\" method=\"post\" enctype=\"multipart/form-data\">Categoria: <select name=\"categoria\">";
echo "<option value=\"1\">Musicas</option>";
echo "<option value=\"2\">Imagens</option>";
echo "<option value=\"3\">Videos</option>";
echo "<option value=\"4\">Aplicativos</option>";
echo "<option value=\"5\">Jogos</option>";
echo "<option value=\"6\">Outros arquivos</option>";
echo "</select><br>";
echo "Nome: <input name=\"nome\"><br>";
echo "Arquivo: <input name=\"file\" type=\"file\"><br>";
echo "<input value=\"Enviar\" type=\"submit\"></form>";
}
else if($a=="a")
{
echo "<p>";
$total = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='".$cat."'")->fetch();
if($p=="" || $p<=0)$p=1;
$itens = $total[0];
$itens_p = 10;
$n_paginas = ceil($itens/$itens_p);
if(($p>$n_paginas)&&$p!=1)$p = $n_paginas;
$limite = ($p-1)*$itens_p;

$sql = "SELECT id, nome, url, visitas, uid, data FROM fun_downloads WHERE categoria='".$cat."' ORDER BY data DESC LIMIT $limite, $itens_p";
$sql = $pdo->query($sql);
if($sql->rowCount()>0)
{
while($d = $sql->fetch())
{
$data = date("d/m/y H:i:s", $d[5]);
$link = "Nome: $d[1]<br>Downloads: $d[3]<br>Por: ".getnick_uid($d[4])."<br>Data: $data<br><a href=\"baixar.php?id=$d[0]\">DOWNLOAD</a>";
if(ismod($uid))
{
$d = " <a href=\"?a=d&id=$d[0]&sid=$sid\">[X]</a>";
}
else
{
$d = "";
}
echo "<small>".$link.$d."</small><br><br>";
}
echo "<p align=\"center\">";
if($p>1)
{
$ppage = $p-1;
echo "<a href=\"?a=a&p=$ppage&sid=$sid&c=$cat\">&#171;Voltar</a> ";
}
if($p<$n_paginas)
{
$npage = $p+1;
echo "<a href=\"?a=a&p=$npage&sid=$sid&c=$cat\">Mais&#187;</a>";
}
echo "<br/>$p/$n_paginas<br/>";
}
echo "</p>";
}
else if($a=="t")
{
echo "<p align=\"center\">";
echo "<b>Top Downloads</b><br/></p>";
$total = $pdo->query("SELECT COUNT(*) FROM fun_downloads ORDER BY visitas")->fetch();
if($p=="" || $p<=0)$p=1;
$itens = $total[0];
$itens_p = 10;
$n_paginas = ceil($itens/$itens_p);
if(($p>$n_paginas)&&$p!=1)$p = $n_paginas;
$limite = ($p-1)*$itens_p;

$sql = "SELECT id, nome, url, visitas, uid, data FROM fun_downloads ORDER BY visitas DESC LIMIT $limite, $itens_p";
$sql = $pdo->query($sql);
if($sql->rowCount()>0)
{
while($d = $sql->fetch())
{
echo getnick_uid($d[4]).": $d[3] downloads<br>";
}
echo "<p align=\"center\">";
if($p>1)
{
$ppage = $p-1;
echo "<a href=\"?a=t&p=$ppage&sid=$sid&c=$cat\">&#171;Voltar</a> ";
}
if($p<$n_paginas)
{
$npage = $p+1;
echo "<a href=\"?a=t&p=$npage&sid=$sid&c=$cat\">Mais&#187;</a>";
}
echo "<br/>$p/$n_paginas<br/>";
}
echo "</p>";
}
else if($a=="m")
{
echo "<p align=\"center\">";
echo "<b>Mais Baixados</b><br/></p>";
$total = $pdo->query("SELECT COUNT(*) FROM fun_downloads ORDER BY visitas")->fetch();
if($p=="" || $p<=0)$p=1;
$itens = $total[0];
$itens_p = 10;
$n_paginas = ceil($itens/$itens_p);
if(($p>$n_paginas)&&$p!=1)$p = $n_paginas;
$limite = ($p-1)*$itens_p;

$sql = "SELECT id, nome, url, visitas, uid, data FROM fun_downloads ORDER BY visitas DESC LIMIT $limite, $itens_p";
$sql = $pdo->query($sql);
if($sql->rowCount()>0)
{
while($d = $sql->fetch())
{
$data = date("d/m/y H:i:s", $d[5]);
$link = "Nome: $d[1]<br>Downloads: $d[3]<br>Por: ".getnick_uid($d[4])."<br>Data: $data<br><a href=\"baixar.php?id=$d[0]\">DOWNLOAD</a>";
if(ismod($uid))
{
$d = " <a href=\"?a=d&id=$d[0]&sid=$sid\">[X]</a>";
}
else
{
$d = "";
}
echo "<small>".$link.$d."</small><br><br>";
}
echo "<p align=\"center\">";
if($p>1)
{
$ppage = $p-1;
echo "<a href=\"?a=m&p=$ppage&sid=$sid&c=$cat\">&#171;Voltar</a> ";
}
if($p<$n_paginas)
{
$npage = $p+1;
echo "<a href=\"?a=m&p=$npage&sid=$sid&c=$cat\">Mais&#187;</a>";
}
echo "<br/>$p/$n_paginas<br/>";
}
echo "</p>";
}
else
{
echo "<p align=\"center\">";
echo "<b>Downloads</b><br></p>";
$m = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='1'")->fetch();
echo "<a href=\"?a=a&c=1&sid=$sid\"><img src=\"images/music.gif\" alt=\"\"/>Músicas($m[0])</a><br>";
$i = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='2'")->fetch();
echo "<a href=\"?a=a&c=2&sid=$sid\"><img src=\"images/image.gif\" alt=\"\"/>Imagens($i[0])</a><br>";
$v = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='3'")->fetch();
echo "<a href=\"?a=a&c=3&sid=$sid\"><img src=\"images/video.gif\" alt=\"\"/>Vídeos($v[0])</a><br>";
$j = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='4'")->fetch();
echo "<a href=\"?a=a&c=4&sid=$sid\"><img src=\"images/jogos.gif\" alt=\"\"/>Jogos($j[0])</a><br>";
$a = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='5'")->fetch();
echo "<a href=\"?a=a&c=5&sid=$sid\"><img src=\"teks/servicos.gif\" alt=\"\"/>Aplicativos($a[0])</a><br>";
$o = $pdo->query("SELECT COUNT(*) FROM fun_downloads WHERE categoria='6'")->fetch();
echo "<a href=\"?a=a&c=6&sid=$sid\"><img src=\"images/outros.gif\" alt=\"\"/>Outros arquivos($o[0])</a><br>";
echo "<p align=\"center\">";
echo "<a href=\"?a=add&sid=$sid\">Add Download</a> - <a href=\"?a=m&sid=$sid\">Mais baixados</a> - <a href=\"?a=t&sid=$sid\">Top Downloads</a>";
echo "</p>";
}
echo "<p align=\"center\">";
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a><br/>";
?>
