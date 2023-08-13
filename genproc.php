<?php
include("core.php");
include("config.php");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
cleardata();//limpar dados do site
$action = $_GET["action"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
if($action=="newtopic")
{
$fid = $_POST["fid"];
$ntitle = $_POST["ntitle"];//nome
$tpctxt = $_POST["tpctxt"];//desc
if(!canaccess(getuid_sid($sid), $fid))
{
echo "<p align=\"center\">";
echo "Você não tem permição para ver esse forum!<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Criando novo tópico","");
echo "<p align=\"center\">";
$crdate = time();
if(empty($tpctxt) OR empty($ntitle))
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Verifique os campos em branco!";
}
else
{
$texst = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$ntitle."' AND fid='".$fid."'")->fetch();
if($texst[0]==0)
{
$res = false;
$ltopic = $pdo->query("SELECT crdate FROM fun_topics WHERE authorid='".$uid."' ORDER BY crdate DESC LIMIT 1")->fetch();
global $topic_af;
$antiflood = time()-$ltopic[0];
if($antiflood>$topic_af)
{
$res = $pdo->query("INSERT INTO fun_topics SET name='".$ntitle."', fid='".$fid."', authorid='".$uid."', text='".$tpctxt."', crdate='".$crdate."', lastpost='".$crdate."'");
if($res)
{
$tnm = htmlspecialchars($ntitle);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico <b>$tnm</b> criado com sucesso!";
$tid = $pdo->query("SELECT id FROM fun_topics WHERE name='".$ntitle."' AND fid='".$fid."'")->fetch();
echo "<br/><br/><a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid[0]\">";
echo "Ver Topico</a>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Aconteceu algum erro ao criar esse tópico!";
}
}
else
{
$af = $topic_af -$antiflood;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Controle anti-flood: $af segundos!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Tópico com esse nome já existe no fórum!";
}
}
$fname = getfname($fid);
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
///atualizar humor
else if($action=="humor")
{
$humor = $_POST["humor"];
echo "<p align=\"center\">";
$althu = $pdo->query("UPDATE fun_users SET humor='".$humor."' WHERE id='".$uid."'");
if($althu)
{
echo "<img src=\"images/ok.gif\" alt=\"\">Humor atualizado com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Erro!";
}
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\">";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////smilie preferencias
else if($action=="sml")
{
$a = $_GET["a"];
echo "<p align=\"center\">";
if($a=="nao")
{
$pdo->query("UPDATE fun_users SET hvia='0' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"*\">Você não vai ver mais smilies!";
}
else if($a=="sim")
{
$pdo->query("UPDATE fun_users SET hvia='1' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"*\">Smilies ativados com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Opção selecionada não é válida!";
}
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\">";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////editar comunidade
else if($action=="editcl")
{
echo "<p align=\"center\">";
$clid = $_GET["clid"];
$nome = $_POST["nome"];
$regras = $_POST["regras"];
$desc = $_POST["descricao"];
$tipo = $_POST["tipo"];
$subdono = $_POST["subdono"];
$logo = $_FILES["logo"]["name"];
$logo_ext = arquivo_ext($logo);
$size = (round($_FILES["logo"]["size"]/1024));
$_POST = array_map("htmlspecialchars", $_POST);
$outra = $pdo->query("SELECT COUNT(*) FROM fun_clubs WHERE name LIKE '".$nome."' AND id!='".$clid."'")->fetch();
if($outra[0]==0)
{
if(empty($nome)||empty($regras)||empty($desc))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Todos os campos são obrigatorios, em exessão ao logo!";
}
else if(!empty($logo))//Atualiza o logotipo se ele for acionado
{
if($size > 1024)
{
echo "<img src=\"images/notok.gif\" alt=\"\">O logo n�o pode ser maior que 1024KBs(<b>1MB</b>)!";
}
else if(arquivo_extfoto($logo_ext)=="1")
{
echo "<img src=\"images/notok.gif\" alt=\"\">Arquivos com a extenção <b>.$logo_ext</b> não são aceitos pelo site!";
}
else
{
$logo_antigo = $pdo->query("SELECT logo FROM fun_clubs WHERE id='".$clid."'")->fetch();
if(file_exists($logo_antigo[0]))
{
unlink($logo_antigo[0]);
}
$pasta = "other/";
$novo_nome = "comunidade_".time().".".$logo_ext;
move_uploaded_file($_FILES["logo"]["tmp_name"], $pasta.$novo_nome);
$pdo->query("UPDATE fun_clubs SET logo = '".$pasta.$novo_nome."' WHERE id='".$clid."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Logo da comunidade foi atualizado com sucesso!";
}
}
else
{
$pdo->query("UPDATE fun_clubs SET name='".$nome."', rules='".$regras."', description='".$desc."', tipo='".$tipo."', subdono='".$subdono."' WHERE id='".$clid."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Comunidade atualizada com sucesso!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\">Já existe uma comunidade com esse nome!";
}
echo "<br><br><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="post")
{
$tid = $_POST["tid"];
$tfid = $pdo->query("SELECT fid FROM fun_topics WHERE id='".$tid."'")->fetch();
if(!canaccess(getuid_sid($sid), $tfid[0]))
{
echo "<p align=\"center\">";
echo "Você não tem permição para ver esse topico!<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
exit();
}
$reptxt = $_POST["reptxt"];
$qut = $_POST["qut"];
adicionar_online(getuid_sid($sid),"Postando resposta","");
echo "<p align=\"center\">";
$crdate = time();
$fid = getfid($tid);
$res = false;
$closed = $pdo->query("SELECT closed FROM fun_topics WHERE id='".$tid."'")->fetch();
if(($closed[0]!='1')||(ismod($uid)))
{
$lpost = $pdo->query("SELECT dtpost FROM fun_posts WHERE uid='".$uid."' ORDER BY dtpost DESC LIMIT 1")->fetch();
global $post_af;
$antiflood = time()-$lpost[0];
if($antiflood>$post_af)
{
if(trim($reptxt)!="")
{
$res = $pdo->query("INSERT INTO fun_posts SET text='".$reptxt."', tid='".$tid."', uid='".$uid."', dtpost='".$crdate."', quote='".$qut."'");
}
if($res)
{
$usts = $pdo->query("SELECT posts, plusses FROM fun_users WHERE id='".$uid."'")->fetch();
$ups = $usts[0]+1;
$upl = $usts[1]+2;
$pdo->query("UPDATE fun_users SET posts='".$ups."', plusses='".$upl."' WHERE id='".$uid."'");
$pdo->query("UPDATE fun_topics SET lastpost='".$crdate."' WHERE id='".$tid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Resposta enviada com sucesso!";
echo "<br/><br/><a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid&go=last\">";
echo "Ver Topico</a>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao postar!";
}
}else
{
$af = $post_af -$antiflood;
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Antiflood Control: $af";
}
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico está fechado para postagens!";
}
$fname = getfname($fid);
echo "<br/><br/><a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "Voltar para $fname</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////editando pontos da comunidade
else if($action=="gcp")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
$giv = $_POST["giv"];
$pnt = $_POST["pnt"];
adicionar_online(getuid_sid($sid),"Moderando Comunidade","");
echo "<p align=\"center\">";
$whnick = getnick_uid($who);
$exs = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND clid=".$clid."")->fetch();
$cow = $pdo->query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."' AND id=".$clid."")->fetch();
if($exs[0]>0 && $cow[0]>0)
{
if(getplusses_cl($clid)<$pnt AND $giv == 1)
{
echo "<img src=\"images/notok.gif\" alt=\"*\">A comunidade n�o tem um saldo de pontos suficientes!";
}
else
{
$pontos_w = $pdo->query("SELECT points FROM fun_clubmembers WHERE uid='".$who."' AND clid='".$clid."'")->fetch();
if($pontos_w[0] == 0 || $pontos_w[0] < $pnt)
{
echo "<img src=\"images/notok.gif\" alt=\"*\">O usu�rio n�o tem pontos suficientes para ser retirado!";
}
else if($giv == 1)
{
$pnt = $pontos_w[0] + $_POST["pnt"];
}
else
{
$pnt = $pontos_w[0] - $_POST["pnt"];
}
$info = $pdo->query("SELECT plusses, name FROM fun_clubs WHERE id='".$clid."'")->fetch();
$pdo->query("UPDATE fun_clubmembers SET points='".$pnt."' WHERE uid='".$who."' AND clid='".$clid."'");
if($giv == 1)
{
$msg = "Olá /reader, você acaba de receber ".$_POST["pnt"]." pontos da comunidade $info[1], para resgatar para seu perfil vê no menu comunidades([b]Resgatar Pontos[/b])![br/][br/]Torpedo Automático!";
autopm($msg, $who);
$p = $info[0] - $_POST["pnt"];
$pdo->query("UPDATE fun_clubs SET plusses='".$p."' WHERE id='".$clid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todos os pontos foram atualizados com sucesso!";
}
else
{
$p = $info[0] + $_POST["pnt"];
$pdo->query("UPDATE fun_clubs SET plusses='".$p."' WHERE id='".$clid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Pontos foram atualizados com sucesso!";
}
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro, tente novamente!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="gpl")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
$pnt = $_POST["pnt"];
adicionar_online(getuid_sid($sid),"Resgatando pontos","");
echo "<p align=\"center\">";
$pontos_cl = $pdo->query("SELECT points FROM fun_clubmembers WHERE uid='".$who."' AND clid='".$clid."'")->fetch();
if($pontos_cl[0]>=$pnt)
{
$dsoma = getplusses(getuid_sid($sid)) + floor($pnt / 2);
$res = $pdo->query("UPDATE fun_users SET plusses='".$dsoma."' WHERE id='".$who."'");
}
if($res)
{
$p = $pontos_cl[0] - $pnt;
$pdo->query("UPDATE fun_clubmembers SET points='".$p."' WHERE uid='".$who."' AND clid='".$clid."'");
echo "<img src=\"images/ok.gif\">Pontos atualizados com sucesso!<br>";
}
else
{
echo "<img src=\"images/notok.gif\">Erro, tente novamente!<br>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="mkroom")
{
$rname = $pdo->quote($_POST["rname"]);
$rpass = trim($_POST["rpass"]);
adicionar_online(getuid_sid($sid),"Chat","");
echo "<p align=\"center\">";
if ($rpass=="")
{
$cns = 1;
}else{
$cns = 0;
}
$prooms = $pdo->query("SELECT COUNT(*) FROM fun_rooms WHERE static='0'")->fetch();
if($prooms[0]<10)
{
$res = $pdo->query("INSERT INTO fun_rooms SET name='".$rname."', pass='".$rpass."', censord='".$cns."', static='0', lastmsg='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sala criada com sucesso!<br/><br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro!<br/><br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você só pode criar no máximo 10 salas!<br/><br/>";
}
echo "<a href=\"index.php?action=uchat&sid=$sid\"><img src=\"images/chat.gif\" alt=\"*\"/>salas de chat</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>Página principal</a>";
echo "</p>";
}
else if($action=="signgb")
{
$who = addslashes($_POST["who"]);
$uid = getuid_sid($sid);
adicionar_online(getuid_sid($sid),"Enviando recado","");
if(!cansigngb(getuid_sid($sid), $who))
{
echo "<p align=\"center\">";
echo "Você não pode enviar recados para esse usuário!<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a>";
echo "</p>";
exit();
}
$msgtxt = $_POST["msgtxt"];
$crdate = time();
$res = false;
$cor = $_POST["cor"];
echo "<p align=\"center\">";
if(trim($msgtxt)!="")
{
$res = $pdo->query("INSERT INTO fun_gbook SET gbowner='".$who."', gbsigner='".$uid."', dtime='".$crdate."', gbmsg='".$msgtxt."', cor='".$cor."'");
}
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Recado adicionado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar recado!";
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delan")
{
//$uid = getuid_sid($sid);
adicionar_online(getuid_sid($sid),"Apagando aviso","");
$clid = $_GET["clid"];
$anid = $_GET["anid"];
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
$pid = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
$exs = $pdo->query("SELECT COUNT(*) FROM fun_announcements WHERE id='".$anid."' AND clid='".$clid."'")->fetch();
if(($uid==$pid[0])&&($exs[0]>0))
{
$res = $pdo->query("DELETE FROM fun_announcements WHERE id='".$anid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Aviso apagado com sucesso da comunidade!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode remover esse anuncio!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="dlcl")
{
//$uid = getuid_sid($sid);
adicionar_online(getuid_sid($sid),"Apagando comunidade","");
$clid = $_GET["clid"];
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
$pid = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
if(ismod($uid) || $uid==$pid[0])
{
$res = deleteClub($clid);
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Comunidade apagada com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode apagar esta comunidade!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="reqjc")
{
$clid = $_GET["clid"];
adicionar_online(getuid_sid($sid),"Participando de uma Comunidade","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$isin = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'")->fetch();
if($isin[0]==0)
{
$tipo = $pdo->query("SELECT tipo FROM fun_clubs WHERE id = '".$clid."'")->fetch();
if($tipo[0]==0)
{
$res = $pdo->query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid."', accepted='1', points='0', joined='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Parabêns você já está participando dessa comunidade!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro MYSQL desconhecido, tente novamente mais tarde!";
}
}
else if($tipo[0]==1)
{
$res = $pdo->query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid."', accepted='0', points='0', joined='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Foi enviado uma solicita��o para o dono da comunidade, aguarde o torpedo de confirmação!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro desconhecido, tente novamente mais tarde!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>A solicitação para entrar nessa comunidade ainda está pedente, por favor aguarde!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="unjc")
{
//$uid = getuid_sid($sid);
$clid = $_GET["clid"];
adicionar_online(getuid_sid($sid),"Saindo de uma comunidade","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$isin = $pdo->query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'")->fetch();
if($isin[0]>0){
$res = $pdo->query("DELETE FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Você saiu da comunidade com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode sair dessa comunidade!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//explsar membro de comunidade
else if($action=="dcm")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Moderando comunidade","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner, name FROM fun_clubs WHERE id='".$clid."'")->fetch();
if($cowner[0]==$uid)
{
if($cowner[0]==$who)
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>O dono da comunidade não pode ser expulso!";
}
else
{
$res = $pdo->query("DELETE FROM fun_clubmembers  WHERE clid='".$clid."' AND uid='".$who."'");
if($res)
{
$pm = "Olá /reader, você foi expulso da comunidade [b]$cowner[1][/b]![br/]Torpedo Altomático!";
autopm($pm, $who);
echo "<img src=\"images/ok.gif\" alt=\"\"/>Usuário expulso da comunidade com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Erro no banco de dados!";
}
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Você não pode fazer isso!!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//aceitar membro na comunidade
else if($action=="acm")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Moderando Comunidade","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner, name FROM fun_clubs WHERE id='".$clid."'")->fetch();
if($cowner[0]==$uid){
$res = $pdo->query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."' AND uid='".$who."'");
if($res)
{
$pm = "Ol� /reader, agora você já está participando da comunidade [b]$cowner[1][/b]![br/]Torpedo Automático!";
autopm($pm, $who);
echo "<img src=\"images/ok.gif\" alt=\"\"/>Usuário adicionado com sucesso na comunidade!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Erro no banco de dados!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Você não pode fazer isso!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//aceitar todos os membros da comunidade
else if($action=="accall")
{
$clid = $_GET["clid"];
adicionar_online(getuid_sid($sid),"Adicionando membros na comunidade","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
if($cowner[0]==$uid){
$res = $pdo->query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Todos os membros foram adicionados!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode fazer isso!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="denall")
{
//$uid = getuid_sid($sid);
$clid = $_GET["clid"];
adicionar_online(getuid_sid($sid),"Adicionando membros","");
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
$cowner = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
if($cowner[0]==$uid){
$res = $pdo->query("DELETE FROM fun_clubmembers WHERE accepted='0' AND clid='".$clid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Todos membros rejeitados!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Comunidade não é sua!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////////shout
else if($action=="shout")
{
$shtxt = $_POST["shtxt"];
adicionar_online(getuid_sid($sid),"Enviando recado no mural","");
echo "<p align=\"center\">";
$cor = $_POST["cor"];
$text = scan_msg($shtxt, $sid);
$nos = substr_count($text,"<img src=");
$flood = $pdo->query("SELECT MAX(shtime) FROM fun_shouts")->fetch();
$pmfl = $flood[0]+180;
$time8 = time();
if(empty($text))
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você deve digitar o recado!";
}
else if($nos>3)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você ultrapassou o limite de 3 smilies!";
}
else  if($pmfl>$time8)
{
$resta = $pmfl - time();
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Um recado acabou de ser adicionado, aguarde $resta segundos!";
}
else
{
$shtxt = $shtxt;
$shtm = time();
$res = $pdo->query("INSERT INTO fun_shouts SET shout='".$shtxt."', shouter='".$uid."', shtime='".$shtm."', cor='".$cor."'");
if($res)
{
$shts = $pdo->query("SELECT shouts from fun_users WHERE id='".$uid."'")->fetch();
$shts = $shts[0]+1;
$total = $pdo->query("SELECT recados FROM fun_users WHERE id='".$uid."'")->fetch();
$sm = $total[0] - 1;
$pdo->query("UPDATE fun_users SET shouts='".$shts."', recados='".$sm."' WHERE id='".$uid."'");
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Recado adicionado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="shout2")
{
$shtxt = $_POST["shtxt"];
adicionar_online(getuid_sid($sid),"mural de recados","");
if(ismod($uid))
{
echo "<p align=\"center\">";
$cor = $_POST["cor"];
$text = scan_msg($shtxt, $sid);
$nos = substr_count($text,"<img src=");
$flood = $pdo->query("SELECT MAX(shtime) FROM fun_mequipe")->fetch();
$pmfl = $flood[0]+180;
$time8 = time();
if($nos>4)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Limite maximo 4 smileys por recado!";
}else  if($pmfl>$time8)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Um recado acabou de ser adicionado aguarde!";
}else{
$shtxt = $shtxt;
//$uid = getuid_sid($sid);
$shtm = time();
$res = $pdo->query("INSERT INTO fun_mequipe SET shout='".$shtxt."', shouter='".$uid."', shtime='".$shtm."', cor='".$cor."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>recado adicionado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
}         echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////////Announce
else if($action=="annc")
{
$antx = $_POST["antx"];
$clid = $_GET["clid"];
adicionar_online(getuid_sid($sid),"Enviando Aviso","");
$cow = $pdo->query("SELECT owner FROM fun_clubs WHERE id='".$clid."'")->fetch();
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
if($cow[0]!=$uid)
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode fazer isso!";
}else{
$shtxt = $shtxt;
//$uid = getuid_sid($sid);
$shtm = time();
$res = $pdo->query("INSERT INTO fun_announcements SET antext='".$antx."', clid='".$clid."', antime='".$shtm."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>O aviso foi enviado para a comunidade com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delfgb")
{
$mid = $_GET["mid"];
adicionar_online(getuid_sid($sid),"Apagando recado","");
echo "<p align=\"center\">";
if(candelgb(getuid_sid($sid), $mid))
{
$res = $pdo->query("DELETE FROM fun_gbook WHERE id='".$mid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Recado apagado!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro no banco de dados!!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Você não pode apagar esse recado!<br>";
}
echo "<br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="rpost")
{
$pid = $_GET["pid"];
adicionar_online(getuid_sid($sid),"Reportando post","");
echo "<p align=\"center\">";
$pinfo = $pdo->query("SELECT reported FROM fun_posts WHERE id='".$pid."'")->fetch();
if($pinfo[0]=="0")
{
$str = $pdo->query("UPDATE fun_posts SET reported='1' WHERE id='".$pid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Postagem reportada com sucesso!";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro, tente mais tarde!";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Postagem ja reportada!";
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="rtpc")
{
$tid = $_GET["tid"];
adicionar_online(getuid_sid($sid),"Reportando topico","");
echo "<p align=\"center\">";
$pinfo = $pdo->query("SELECT reported FROM fun_topics WHERE id='".$tid."'")->fetch();
if($pinfo[0]=="0")
{
$str = $pdo->query("UPDATE fun_topics SET reported='1' WHERE id='".$tid."' ");
if($str)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topico reportado com sucesso";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Impossivel reportar no momento";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topico ja encontra se reportado";
}
echo "<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="bud")
{
$todo = $_GET["todo"];
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Enviando pedido de amizade","");
echo "<p align=\"center\">";
//$uid = getuid_sid($sid);
$unick = getnick_uid($uid);
$tnick = getnick_uid($who);
if($todo=="add")
{
if(budres($uid,$who)!=3){
if(arebuds($uid,$who))
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>$tnick ja e teu amigo<br/>";
}else if(budres($uid, $who)==0)
{
$res = $pdo->query("INSERT INTO fun_buddies SET uid='".$uid."', tid='".$who."', reqdt='".time()."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Pedido de amizade feito com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick a lista de amigos!<br/>";
}
}
else if(budres($uid, $who)==1)
{
$res = $pdo->query("UPDATE fun_buddies SET agreed='1' WHERE uid='".$who."' AND tid='".$uid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Amigo adicionado com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick a lista de amigos!<br/>";
}
}
else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick a lista de amigos!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick a lista de amigos!<br/>";
}
}else if($todo="del")
{
$res= $pdo->query("DELETE FROM fun_buddies WHERE (uid='".$uid."' AND tid='".$who."') OR (uid='".$who."' AND tid='".$uid."')");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Amigo apagado com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode apagar esse amigo!<br/>";
}
}
echo "<br>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////////Update buddy message
else if($action=="upbmsg")
{
adicionar_online(getuid_sid($sid),"Atualizando frase de amigo","");
$bmsg = $_POST["bmsg"];
$bmsg = trim(htmlspecialchars($bmsg));
echo "<p align=\"center\">";
//$uid = getuid_sid($sid);
$res = $pdo->query("UPDATE fun_users SET budmsg='".$bmsg."' WHERE id='".$uid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Frase de amigo editada com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao alterar a frase!<br/>";
}
echo "<br/>";
echo "<a href=\"lists.php?action=buds&sid=$sid\">";
echo "Lista de amigos</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////// add club
else if($action == "adicionarcl")
{
adicionar_online(getuid_sid($sid), "Adicionando Comunidade", "");
$nome = $_POST["nome"];
$descricao = $_POST["descricao"];
$regras = $_POST["regras"];
$logo = $_FILES["logo"]["name"];
$tipo_cl = $_POST["tipo"];
$logo_ext = arquivo_ext($logo);
$size = (round($_FILES["logo"]["size"]/1024));
echo "<p align=\"center\">";
//comandos mysql
$outra = $pdo->query("SELECT COUNT(*) FROM fun_clubs WHERE name LIKE '".$nome."'")->fetch();
$minhas = $pdo->query("SELECT COUNT(*) FROM fun_clubs WHERE owner = '".getuid_sid($sid)."'")->fetch();
$pontos = getplusses(getuid_sid($sid));
if($size < 1024)
{
if($minhas[0]<5)
{
if($pontos>349)
{
if($outra[0]==0)
{
if(trim($nome)==""||trim($descricao)==""||trim($regras)==""||trim($logo)=="")
{
echo "<img src=\"images/notok.gif\" alt=\"X\">Todos os campos são obrigatórios!";
}
else if(is_numeric($nome))
{
echo "<img src=\"images/notok.gif\" alt=\"X\">O nome da comunidade não pode conter apenas números!";
}
else if(arquivo_extfoto($logo_ext)=="1")
{
echo "<img src=\"images/notok.gif\" alt=\"X\">A imagem que você enviou não é válida, por favor tente novamente!";
}
else
{
echo "<img src=\"images/ok.gif\" alt=\"OK\">Comunidade $nome, criada com sucesso!";
$pasta = "other/";
$novo_nome = "COMU_".time().".".$logo_ext;
move_uploaded_file($_FILES["logo"]["tmp_name"], $pasta.$novo_nome);
//adiciona a comunidade
$pdo->query("INSERT INTO fun_clubs SET name='".$nome."', owner='".$uid."', description='".$descricao."', rules='".$regras."', logo='".$pasta.$novo_nome."', plusses='0', created='".time()."', subdono='0', tipo='".$tipo_cl."' ");
//add o usuario que criou ela como o dono dela
$clid = $pdo->query("SELECT id FROM fun_clubs WHERE name='".$nome."'")->fetch();
$pdo->query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid[0]."', accepted='1', points='50', joined='".time()."'");
//adiciona as salas de bate papo, e o forum
$fnm = $nome;
$cnm = $nome;
$pdo->query("INSERT INTO fun_forums SET name='".$fnm."', position='0', cid='0', clubid='".$clid[0]."'");
$pdo->query("INSERT INTO fun_rooms SET name='".$cnm."', pass='', static='1', mage='0', chposts='0', perms='0', censord='0', freaky='0', lastmsg='".time()."', clubid='".$clid[0]."'");
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\">Não é permitido criar uma comunidade clone(igual), por favor verifique o nome!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\">Você deve ter mais de 350 $smoeda para criar uma comunidade!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\">Você atingiu o limite máximo de comunidades por usuário!";
}
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\">A imagem enviada é maior que 1024KBs(<b>1MB</b>), tente outra!";
}
echo "<br/><br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////Add remove from ignoire list
else if($action=="ign")
{
adicionar_online(getuid_sid($sid),"Editando lista negra","");
$todo = $_GET["todo"];
$who = $_GET["who"];
echo "<p align=\"center\">";
//$uid = getuid_sid($sid);
$tnick = getnick_uid($who);
if($todo=="add")
{
if(ignoreres($uid, $who)==1)
{
$res= $pdo->query("INSERT INTO fun_ignore SET name='".$uid."', target='".$who."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick adicionado na lista negra!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error!<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Você não pode adicionar $tnick a lista negra!<br/>";
}
}else if($todo="del")
{
if(ignoreres($uid, $who)==2)
{
$res= $pdo->query("DELETE FROM fun_ignore WHERE name='".$uid."' AND target='".$who."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick removido com sucesso da lista negra!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error Updating Database<br/>";
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>$tnick não está na lista negra!<br/>";
}
}
echo "<br/><a href=\"lists.php?action=ignl&sid=$sid\">";
echo "Lista negra</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////atualizar perfil
else if($action=="uprof")
{
adicionar_online(getuid_sid($sid),"Editando perfil","");
$semail = $_POST["semail"];
$ubday = $_POST["ubday"];
$uloc = $_POST["uloc"];
$usex = $_POST["usex"];
echo "<p align=\"center\">";
$res = $pdo->query("UPDATE fun_users SET  WHERE id='".$uid."'");
$res = $pdo->query("UPDATE fun_users SET email='".$semail."',  birthday='".$ubday."', location='".$uloc."', sex='".$usex."' WHERE id='".$uid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Seu perfil foi atualizado com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro, tente novamente mais tarde!<br/>";
}
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////atualizar senha
else if($action=="upwd")
{
adicionar_online(getuid_sid($sid),"Atualizando senha","");
$npwd = $_POST["npwd"];
$cpwd = $_POST["cpwd"];
echo "<p align=\"center\">";
if($npwd!=$cpwd)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>As senhas não são identicas!<br/>";
}
else if((strlen($npwd)<8) || (strlen($npwd)>15))
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Senha deve deve ter de 8 a 15 caracteres!<br/>";
}
else
{
$pwd = md5($npwd);
$res = $pdo->query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$uid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Senha atualizada com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao atualizar a senha!<br/>";
}
}
echo "<br/><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
echo "Esta página que vocẽ tentou acessar não foi encontrada!<br/><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
echo "</body>";
echo "</html>";
?>
