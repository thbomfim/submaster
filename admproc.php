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
$action = $_GET["action"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);
if(!isadmin(getuid_sid($sid)))
{
echo "<p align=\"center\">";
echo "Você não é admin!<br/>";
echo "<br/>";
echo "<a href=\"index.php\">Página principal</a>";
echo "</p>";
exit();
}
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Admin CP","");
if($action=="general")
{
$xtm = $_POST["sesp"];
$fmsg = $_POST["fmsg"];
$areg = $_POST["areg"];
$pmaf = $_POST["pmaf"];
$fvw = $_POST["fvw"];
if($areg=="d")
{
$arv = 0;
}
else
{
$arv = 1;
}
$msg = "%$uid% atualizou todas as informações do site!";
addlog($msg);
echo "<p align=\"center\">";
$res = $pdo->query("UPDATE fun_settings SET value='".$fmsg."' WHERE name='4ummsg'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Mural atualizado com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao atualizar o mural!<br/>";
}
$res = $pdo->query("UPDATE fun_settings SET value='".$xtm."' WHERE name='sesxp'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Período da sessão atualizado!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao atualizar período da sessão!<br/>";
}
$res = $pdo->query("UPDATE fun_settings SET value='".$pmaf."' WHERE name='pmaf'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>PM antiflood atualizado para $pmaf segundos!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao atualizar antiflood!<br/>";
}
$res = $pdo->query("UPDATE fun_settings SET value='".$arv."' WHERE name='reg'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Cadastros atualizados!<br/>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao atualizar os cadastros!<br/>";
}
echo "<br/>";
echo "<a href=\"admincp.php?action=general&sid=$sid\">";
echo "Configurações</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////apagar spam
else if($action=="aspam")
{
$id = $_GET["id"];
$pdo->query("DELETE FROM fun_spam WHERE id='".$id."'");
echo "<p align=\"center\">";
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Palavra apagada com sucesso!";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="editsml")
{
echo "<p align=\"center\">";
$c = $_POST["codigo"];
$cat = $_POST["cat"];
$smid = $_GET["smid"];
if(empty($smid)||$smid==0)
{
echo "<b>Smilie não existe!</b><br>";
}
else if(empty($c))
{
echo "<b>Digite um código para o smilie!</b><br>";
}
else
{
$res = $pdo->query("UPDATE fun_smilies SET scode='".$c."', cat='".$cat."' WHERE id='".$smid."'");
if($res)
{
///log
$msg = "%$uid% atualizou as informações do smilie ID($smid)!";
addlog($msg);
echo "<b>Smilie atualizado com sucesso!</b><br>";
}
else
{
echo "<b>Erro, tente mais tarde!</b><br>";
}
}
echo "<br><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delclub")
{
$clid = $_GET["clid"];
echo "<p align=\"center\">";
$res = deleteClub($clid);
if($res)
{
////log
$msg = "%$uid% deletou uma comunidade ID($clid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Comunidade deletada com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!<br/>";
}
echo "<br/><br/><a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////adicionar nova categoria no forum
else if($action=="addcat")
{
$fcname = $_POST["fcname"];//nome
$fcpos = $_POST["fcpos"];//posicao
echo "<p align=\"center\">";
if(empty($fcname))
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Por favor, digite o nome da <b>categoria</b>!";
}
else if(empty($fcpos))
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Por favor, digite a posi��o da categoria!";
}
else if(!is_numeric($fcpos))
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Por favor, digite apenas n�meros na posi��o!";
}
else
{
$res = $pdo->query("INSERT INTO fun_fcats SET name='".$fcname."', position='".$fcpos."'");
if($res)
{
/////log
$msg = "%$uid% adicionou uma categoria NOME($fcname)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria(<b>".$fcname."</b>) foi adicionada com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao adicionar categoria!";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Categorias do Fórum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="addfrm")
{
$frname = $_POST["frname"];//nome
$frpos = $_POST["frpos"];//posicao
$fcid = $_POST["fcid"];//categoria do forum
$fnome = getfname($fcid);
echo "<p align=\"center\">";
if(empty($frname)||strlen($frname)<2)
{
echo "<b>Verifique o nome da subcat!</b><br>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a><br>";
echo "</p>";
exit();
}
else if(!is_numeric($frpos))
{
echo "<b>Verifique a posição da subcat!</b><br>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">Página principal</a><br>";
echo "</p>";
exit();
}
$res = $pdo->query("INSERT INTO fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."'");
if($res)
{
////log
$msg = "%$uid% adicionou uma subcat na categoria $fnome, NOME($frname)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Subcat adicionada com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao adicionar a subcat!";
}
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delsm")
{
$smid = $_GET["smid"];
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_smilies WHERE id='".$smid."'");
if($res)
{
$msg = "%$uid% apagou um smilie ID($smid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Smilie apagado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao apagar o smilie!";
}
echo "<br/><br/><a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="addchr")
{
$chrnm = $_POST["chrnm"];//nome
$chrpst = $_POST["chrpst"];//max de posts
$chrprm = $_POST["chrprm"];//permicao 
echo "<p align=\"center\">";
if(empty($chrnm))
{
echo "<b>Digite um nome para a sala de chat!</b>";
}
else
{
$res = $pdo->query("INSERT INTO fun_rooms SET clubid='0', name='".$chrnm."', static='1', pass='', mage='0', chposts='".$chrpst."', perms='".$chrprm."', censord='0' , freaky='0'");
if($res)
{
////log
$msg = "%$uid% adicionou uma nova sala de chat NOME($chrnm)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sala de chat criada com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao criar a sala!";
}
}
echo "<br/><br/><a href=\"admincp.php?action=chrooms&sid=$sid\">";
echo "Mod Chat</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////////Update profile
else if($action=="uprof")
{
$who = $_GET["who"];
$unick = $_POST["unick"];
$perm = $_POST["perm"];
$semail = $_POST["semail"];
$ubday = $_POST["ubday"];
$uloc = $_POST["uloc"];
$usex = $_POST["usex"];
echo "<p align=\"center\">";
$onk = $pdo->query("SELECT name FROM fun_users WHERE id='".$who."'")->fetch();
$exs = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE name='".$unick."'")->fetch();
if($onk[0]!=$unick)
{
if($exs[0]>0)
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Este nick j� est� sendo usado!<br/>";
}else
{
$res = $pdo->query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', birthday='".$ubday."', location='".$uloc."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$who."'");
if($res)
{
///log
$msg = "%$uid% acabou de editar o perfil de ".getnick_uid2($who)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$unick atualizado com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao atualizar $unick!<br/>";
}
}
}else
{
$res = $pdo->query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', birthday='".$ubday."', location='".$uloc."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$who."'");
if($res)
{
///log
$msg = "%$uid% acabou de editar o perfil de ".getnick_uid2($who)."!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"o\"/>$unick atualizado com sucesso!<br/>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao atualizar $unick!<br/>";
}
}
echo "<br/><a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////user password
else if($action=="upwd")
{
$npwd = $_POST["npwd"];
$who = $_GET["who"];
echo "<p align=\"center\">";
if((strlen($npwd)<4) || (strlen($npwd)>15))
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>A senha deve ter no mínimo 4 e no máximo 15 caracteres!<br/>";
}else
{
$pwd = md5($npwd);
$res = $pdo->query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$who."'");
if($res)
{
///log
$msg = "%$uid% mudou a senha de ".getnick_uid2($who)." para ".base64_encode($npwd)."[CODIFICADO]!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"o\"/>Senha modificada com sucesso!<br/>";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"x\"/>Erro ao modificar!<br/>";
}
}
echo "<br/><a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="edtfrm")
{
$fid = $_POST["fid"];
$frname = $_POST["frname"];
$frpos = $_POST["frpos"];
$fcid = $_POST["fcid"];
echo "<p align=\"center\">";
echo $frname;
echo "<br/>";
$res = $pdo->query("UPDATE fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."' WHERE id='".$fid."'");
if($res)
{
///log
$msg = "%$uid% atualizou uma subcat do fórum ID($fid), NOME($frname)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum atualizado com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao atualizar!";
}
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Forums</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="edtcat")
{
$fcid = $_POST["fcid"];
$fcname = $_POST["fcname"];
$fcpos = $_POST["fcpos"];
echo "<p align=\"center\">";
if(empty($fcname)||is_numeric($fcname)||strlen($fcname)<3)
{
echo "<b>Verifique o titulo da categoria!</b>";
}
else
{
$res = $pdo->query("UPDATE fun_fcats SET name='".$fcname."', position='".$fcpos."' WHERE id='".$fcid."'");
if($res)
{
///log
$msg = "%$uid% editou uma categoria do fórum ID($fcid), NOME($fcname)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria atualizada com sucesso!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao atualizar a categoria!";
}
}
echo "<br/><br/><a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delfrm")
{
$fid = $_POST["fid"];
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_forums WHERE id='".$fid."'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Subcat apagado com sucesso!";
///log
$msg = "%$uid% apagou uma subcat do fórum ID($fid)!";
addlog($msg);
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao deletar!";
}
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delpms")
{
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_private WHERE reported!='1' AND starred='0' AND unread='0'");
if($res)
{
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todos os torpedos, excerto n�o lidos e reportados, foram apagados!";
///log
$msg = "%$uid% apagou todos os torpedos do site!";
addlog($msg);
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!!";
}
echo "<br/><br/><a href=\"admincp.php?action=clrdta&sid=$sid\">";
echo "Limpar dados</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="clrmlog")
{
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM addlog($msg);");
if($res)
{
///log
$msg = "%$uid% apagou todos os logs(MOD) do site!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Modlogs deletados!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!!";
}
echo "<br/><br/><a href=\"admincp.php?action=clrdta&sid=$sid\">";
echo "Limpar dados</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delsht")
{
echo "<p align=\"center\">";
$altm = time()-(5*24*60*60);
$res = $pdo->query("DELETE FROM fun_shouts WHERE shtime<'".$altm."'");
if($res)
{
///log
$msg = "%$uid% apagou todos os recados do mural com mais de 5 dias!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todos os recados antigos foram apagados!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!!";
}
echo "<br/><br/><a href=\"admincp.php?action=clrdta&sid=$sid\">";
echo "Limpar dados</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delchr")
{
$chrid = $_POST["chrid"];
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_rooms WHERE id='".$chrid."'");
if($res)
{
///log
$msg = "%$uid% apagou uma sala do chat ID($chrid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Sala apagada com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro no banco de dados!";
}
echo "<br/><br/><a href=\"admincp.php?action=chrooms&sid=$sid\">";
echo "Mod Chat</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delu")
{
$who = $_GET["who"];
$uid = getuid_sid($sid);
$whn = getnick_uid2($who);
if($who=="1" OR isadmin($who))
{
exit();
}    
echo "<p align=\"center\">";
echo "<br/>";
$res = $pdo->query("DELETE FROM fun_buddies WHERE tid='".$who."' OR uid='".$who."'");
$res = $pdo->query("DELETE FROM fun_gbook WHERE gbowner='".$who."' OR gbsigner='".$who."'");
$res = $pdo->query("DELETE FROM fun_ignore WHERE name='".$who."' OR target='".$who."'");
$res = $pdo->query("DELETE FROM fun_penalties WHERE uid='".$who."' OR exid='".$who."'");
$res = $pdo->query("DELETE FROM fun_posts WHERE uid='".$who."'");
$res = $pdo->query("DELETE FROM fun_private WHERE byuid='".$who."' OR touid='".$who."'");
$res = $pdo->query("DELETE FROM fun_shouts WHERE shouter='".$who."'");
$res = $pdo->query("DELETE FROM fun_topics WHERE authorid='".$who."'");
$res = $pdo->query("DELETE FROM fun_chat WHERE chatter='".$who."'");
$res = $pdo->query("DELETE FROM fun_chat WHERE who='".$who."'");
$res = $pdo->query("DELETE FROM fun_chonline WHERE uid='".$who."'");
$res = $pdo->query("DELETE FROM fun_online WHERE userid='".$who."'");
$res = $pdo->query("DELETE FROM fun_ses WHERE uid='".$who."'");
deleteMClubs($who);
$res = $pdo->query("DELETE FROM fun_users WHERE id='".$who."'");
if($res)
{
///log
$msg = "%$uid% apagou o usuário $whn do site!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Usuário apagado com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro!";
}
echo "<br/><br/><a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////// Delete users posts
else if($action=="delxp")
{
$who = $_GET["who"];
if($who=="1")exit();    
echo "<p align=\"center\">";
echo "<br/>";
$res = $pdo->query("DELETE FROM fun_posts WHERE uid='".$who."'");
$res = $pdo->query("DELETE FROM fun_topics WHERE authorid='".$who."'");
if($res)
{
$pdo->query("UPDATE fun_users SET plusses='0' where id='".$who."'");
///log
$whn = getnick_uid2($who);
$msg = "%$uid% apagou todas as postagens do usuário $whn!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Todas as postagens foram deletadas!";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Erro ao deletar!";
}
echo "<br/><br/><a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="delcat")
{
$fcid = $_POST["fcid"];
echo "<p align=\"center\">";
$res = $pdo->query("DELETE FROM fun_fcats WHERE id='".$fcid."'");
if($res)
{
///log
$msg = "%$uid% apagou uma categoria do site ID($fcid)!";
addlog($msg);
echo "<img src=\"images/ok.gif\" alt=\"O\"/>Categoria apagada com sucesso!";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error ao apagar a categoria!";
}
echo "<br/><br/><a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="logadmin")
{
echo "<p align=\"center\">";
$uid = getuid_sid($sid);
if($uid == 1)
{
$pdo->query("TRUNCATE TABLE fun_log");
echo "<img src=\"images/ok.gif\" alt=\"*\">Todos os logs foram apagados!";
/////log
$msg = "%$uid% limpou todos os logs!";
addlog($msg);
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"*\">Você não tem permição para limpar os logs!";
}
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else
{
}
echo "</body>";
echo "</html>";
?>