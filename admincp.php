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
echo "<a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\">Página principal</a>";
echo "</p>";
exit();
}
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Voce nao está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

adicionar_online(getuid_sid($sid),"Admin CP","");
if($action=="main")
{
$msg = "%$uid% entrou no painel da equipe!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Admin CP</b>";
echo "</p>";
echo "<p>";
echo "<b> - Fórum e Chat</b><br/>";
echo "<a href=\"admincp.php?action=cforum&sid=$sid\">&#187;Categorias do fórum</a><br/>";
echo "<a href=\"admincp.php?action=forums&sid=$sid\">&#187;Subcategorias do fórum</a><br/>";
echo "<a href=\"admincp.php?action=chrooms&sid=$sid\">&#187;Salas de chat</a><br/>";
echo "<br />";
echo "<b> - Configurações do Site</b><br />";
echo "<a href=\"admincp.php?action=general&sid=$sid\">&#187;Configurar site</a><br/>";
echo "<a href=\"admincp.php?action=clrdta&sid=$sid\">&#187;Limpar dados</a><br/>";
echo "<a href=\"parceiros.php?a=admin&sid=$sid\">&#187;Adicionar parceiro</a><br />";
echo "<a href=\"admincp.php?action=spam&sid=$sid\">&#187;Guardian Anti-Spam</a><br/>";
echo "<br />";
echo "<b> - Moderar Usuários</b><br />";
echo "<a href=\"admincp.php?action=ip&sid=$sid\">&#187;Buscar por IP</a><br/>";
echo "<a href=\"admincp.php?action=chuinfo&sid=$sid\">&#187;Modificar usuário</a><br/>";
echo "<br />";
echo "<b> - área dos Smilies</b><br />";
echo "<a href=\"addsml.php?sid=$sid\">&#187;Adicionar Smilies</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
$nick = getnick_uid($uid);
echo "Olá $nick, todas as suas ações no painel da equipe estão sendo registras em logs!";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////spam page
else if($action == "spam")
{
echo "<p align=\"center\">";
echo "<b>Guardian  Anti-Spam</b>";
echo "<br />";
echo "<br />";
echo "Olá, seja bem vindo ao <b>Guardian</b> é o anti-spam do site, aqui você pode ver as palavras que serão detectadas pelo Guardian!";
echo "<br />";
$total = "SELECT COUNT(*) FROM fun_spam WHERE id";
$total = $pdo->query($total);
$total = $total->fetch();
echo "Palavras prontas para serem detectadas: <b>".$total[0]." palavra(s)</b>!";
echo "<br />";
echo "<br />";
echo "<b>";
echo "<a href=\"admincp.php?action=sadd&sid=$sid\">Nova Palavra</a> - ";
echo "<a href=\"admincp.php?action=sver&sid=$sid\">Palavras Registradas(".$total[0].")</a>";
echo "</b>";
echo "<br />";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////ver spam
else if($action=="sver")
{
echo "<p align=\"center\">";
echo "<b>Nova Palavra</b>";
echo "</p>";
$c = "SELECT id, txt FROM fun_spam WHERE id";
$c = $pdo->query($c);
while ($txt = $c->fetch())
{
echo "Palavra: <b>$txt[1]</b> - <a href=\"admproc.php?action=aspam&id=$txt[0]&sid=$sid\">[X]</a>";
echo "<br />";
}
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////add spam
else if($action=="sadd")
{
echo "<p align=\"center\">";
echo "<b>Nova Palavra</b>";
echo "<br />";
echo "<br />";
$add = $_POST["a"];
if($add=="Adicionar")
{
$p = $_POST["p"];
$outras = $pdo->query("SELECT COUNT(*) FROM fun_spam WHERE txt='".$p."'")->fetch();
if(empty($p)||$p==""||is_numeric($p)||$outras[0]>0)
{
echo "<img src=\"images/notok.gif\" alt=\"\">N�o foi poss�vel adicionar a palavra!";
}
else
{
$pdo->query("INSERT INTO fun_spam SET txt='".$p."'");
echo "<img src=\"images/ok.gif\" alt=\"\">Palavra adicionada com sucesso!";
}
}
else
{
echo "Cuidado ao adicionar palavras no guardian, pois todas as palavras <b>serão</b> detectadas por ele!";
}
echo "<form action=\"\" method=\"post\">";
echo "Palavra: <input name=\"p\" type=\"text\"><br />";
echo "<input name=\"a\" type=\"submit\" value=\"Adicionar\">";
echo "</form>";
echo "<br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////editar smilies
else if($action=="editsml")
{
$smid = $_GET["smid"];
$msg = "%$uid% está editando os smilies!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Editar Smilie</b><br/><br/>";
$infos = $pdo->query("SELECT scode FROM fun_smilies WHERE id='".$smid."'")->fetch();
echo "<form action=\"admproc.php?action=editsml&sid=$sid&smid=$smid\" method=\"POST\">";
echo "Código: <input name=\"codigo\" value=\"$infos[0]\"><br>";
echo "Categoria: <select name=\"cat\"><br>";
echo "<option value=\"1\">Diversas</option>";
echo "<option value=\"2\">Datas especiais</option>";
echo "<option value=\"3\">Personalizadas</option>";
echo "<option value=\"4\">Terror/Halloween</option>";
echo "<option value=\"5\">Amor/Emoções</option>";
echo "<option value=\"6\">Times/Clubes</option>";
echo "<option value=\"7\">Plaquinhas/Assinaturas</option>";
echo "</select><br>";
echo "<input value=\"Atualizar\" type=\"submit\"></form><br>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////busca por ip //ok
else if($action=="ip")
{
$msg = "%$uid% está iniciando a busca de usuários por IP!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Buscar por IP</b>";
echo "</p>";
echo "<form action=\"admincp.php\" method=\"get\">";
echo "Digite o IP: <input name=\"ip\" format=\"*x\" maxlength=\"15\"/><input type=\"hidden\" name=\"sid\" value=\"$sid\"/><input type=\"hidden\" name=\"action\" value=\"verip\"/><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "<br />";
echo "<p align=\"center\">";
echo "Para buscar usuários pelo IP voce deve digita-lo completo, exemplo: (<b>127.0.0.1</b>)!";
echo "<br />";
echo "<br />";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
}
//////////////////////////////////ver ip resultados //ok
else if($action=="verip")
{
$page = $_GET["page"];
$ip = $_GET["ip"];
$msg = "%$uid% está vendo resultados da busca por IP($ip)!";
addlog($msg);
if($page=="" || $page<=0)$page=1;
$noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE ipadd='".$ip."' ")->fetch();
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
echo "<p align=\"center\">";
echo "<b>Resultados da Busca</b>";
echo "</p>";
//changable sql
$sql = "SELECT id FROM fun_users WHERE ipadd='".$ip."' ORDER BY regdate LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = $pdo->query($sql);
if($items->rowCount()>0)
{
while ($item = $items->fetch())
{
$nick = getnick_uid($item[0]);
$lnk = "<a href=\"index.php?action=perfil&who=$item[0]&sid=$sid\">$nick</a>";
echo "$lnk<br/>";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"admincp.php?action=$action&page=$ppage&sid=$sid&ip=$ip\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"admincp.php?action=$action&page=$npage&sid=$sid&ip=$ip\">Proximo&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////configuracoes do site //ok
else if($action=="general")
{
$msg = "%$uid% está alterando as configurações do site!";
addlog($msg);
$xtm = getsxtm();
$paf = flood_torpedos();
$fvw = flood_forum();
$fmsg = htmlspecialchars(mural_admin());
if(canreg())
{
$arv = "e";
}else
{
$arv= "d";
}
echo "<p align=\"center\">";
echo "<b>Configurações</b><br/>";
echo "</p>";
echo "<p>";
echo "<form action=\"admproc.php?action=general&sid=$sid\" method=\"post\">";
echo "Período da sessao: ";
echo "<input name=\"sesp\" format=\"*N\" maxlength=\"3\" size=\"3\ value=\"$xtm\"/>";
echo "<br/>PM antiflood: <input name=\"pmaf\" format=\"*N\" maxlength=\"3\" size=\"3\" value=\"$paf\"/>";
echo "<br/>Mural Admin: ";
echo "<textarea name=\"fmsg\"  maxlength=\"255\" value=\"$fmsg\" rows=\"5\" cols\"33\">";
echo "diga algo aqui";
echo"</textarea>";
echo "<br/>Cadastros: ";
echo "<select name=\"areg\" value=\"$arv\">";
echo "<option value=\"e\">Ativados</option>";
echo "<option value=\"d\">Desativado</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Salvar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////categorias do forum //ok
else if($action=="cforum")
{
$msg = "%$uid% está alterando as categorias do f�rum!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Categorias do Fórum</b><br></p>";
echo "<a href=\"admincp.php?action=addcat&sid=$sid\">&#187;Nova Categoria</a><br/>";
echo "<a href=\"admincp.php?action=edtcat&sid=$sid\">&#187;Modificar Categoria</a><br/>";
echo "<a href=\"admincp.php?action=delcat&sid=$sid\">&#187;Apagar Categoria</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////////editar salas de chat //ok
else if($action=="chrooms")
{
$msg = "%$uid%, está alterando as salas de chat do site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Salas de Chat</b></p>";
$noi = $pdo->query("SELECT COUNT(*) FROM fun_rooms")->fetch();
if($noi[0]>0)
{
echo "<form action=\"admproc.php?action=delchr&sid=$sid\" method=\"post\">";
$rss = $pdo->query("SELECT name, id FROM fun_rooms");
echo "Apagar Sala: <select name=\"chrid\">";
while($rs = $rss->fetch())
{
echo "<option value=\"$rs[1]\">$rs[0]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Apagar Sala\"/>";
echo "</form>";
echo "<br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=addchr&sid=$sid\">ADD SALA DE CHAT</a>";
echo "<br />";
echo "<br />";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////////sub cat do forum //ok
else if($action=="forums")
{
$msg = "%$uid% está alterando as sub. cats do f�rum!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Subcategorias Fórum</b><br></p>";
echo "<a href=\"admincp.php?action=addfrm&sid=$sid\">&#187;Add subcat</a><br/>";
echo "<a href=\"admincp.php?action=edtfrm&sid=$sid\">&#187;Editar subcat</a><br/>";
echo "<a href=\"admincp.php?action=delfrm&sid=$sid\">&#187;Apagar subcat</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////////////apagar dados antigos //ok
else if($action=="clrdta")
{
$msg = "%$uid% está limpando dados do site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Limpar Dados</b><br></p>";
echo "<a href=\"admproc.php?action=delpms&sid=$sid\">&#187;Apagar torpedos</a><br/>";
if($uid == 1){
echo "<a href=\"admproc.php?action=logadmin&sid=$sid\">&#187;Limpar os LOGS</a><br />";
}
echo "<a href=\"admproc.php?action=delsht&sid=$sid\">&#187;Apagar recados antigos do mural</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////////add categoria do forum //ok
else if($action=="addcat")
{
$msg = "%$uid% está adicionando uma nova categoria no f�rum do site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Adicionar Categoria</b><br/><br/>";
echo "O campo <b>posição</b> vai alterar nas <b>posições reais dos tópicos</b>, para baixo ou cima!";
echo "<br />";
echo "<br />";
echo "<form action=\"admproc.php?action=addcat&sid=$sid\" method=\"post\">";
echo "Titúlo: <input name=\"fcname\" maxlength=\"30\"/><br/>";
/* Aqui será gerado uma posicao altomatica logica: Posicao_Atual + 1 = Nova_Posicao */
$max_id = $pdo->query("SELECT MAX(position) FROM fun_fcats")->fetch();
$max_id = $max_id[0] + 1;
echo "Posição: <input name=\"fcpos\" format=\"*N\" size=\"3\" value=\"$max_id\" maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Categorias do Fórum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////////
else if($action=="addfrm")
{
$msg = "%$uid% está adicionando uma sub cat no forum do site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Add Subcat</b><br/><br/>";
echo "<form action=\"admproc.php?action=addfrm&sid=$sid\" method=\"post\">";
echo "Nome: <input name=\"frname\" maxlength=\"30\"/><br/>";
echo "Posição: <input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
$cforum = $pdo->query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "Categoria: <select name=\"fcid\">";
while ($fcat = $cforum->fetch())
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Subcats do Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////Add nova sala de chat //ok
else if($action=="addchr")
{
$msg = "%$uid% está adicionando uma nova sala de chat no site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Add Sala de Chat</b><br/><br/>";
echo "<form action=\"admproc.php?action=addchr&sid=$sid\" method=\"post\">";
echo "Titúlo: <input name=\"chrnm\" maxlength=\"30\"/><br/>";
echo "Minimo de postagens: <input name=\"chrpst\" format=\"*N\" maxlength=\"4\" size=\"4\"/><br/>";
echo "Permição: <select name=\"chrprm\">";
echo "<option value=\"0\">Todos</option>";
echo "<option value=\"1\">Mods</option>";
echo "<option value=\"2\">Admins</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "<form>";
echo "<br/><br/><a href=\"admincp.php?action=chrooms&sid=$sid\">";
echo "Salas de Chat</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
///////////////////////////////////////editando sub cats do forum
else if($action=="edtfrm")
{
$msg = "%$uid% está editando as sub cats do fórum!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Editar sub cat do F�rum</b><br/><br/>";
$forums = $pdo->query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?action=edtfrm&sid=$sid\" method=\"post\">";
echo "Forum: <select name=\"fid\">";
while($forum = $forums->fetch())
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select>";
echo "<br/>Nome:<input name=\"frname\" maxlength=\"30\"/><br/>";
echo "Posição:<input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
$cforum = $pdo->query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "Categoria: <select name=\"fcid\">";
while ($fcat = $cforum->fetch())
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Forums</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////apagando as subcats do forum
else if($action=="delfrm")
{
$msg = "%$uid% está querendo apagar alguma sub cat do fórum!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Apagar Subcats</b><br/><br/>";
$forums = $pdo->query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?action=delfrm&sid=$sid\" method=\"post\">";
echo "Selecione: <select name=\"fid\">";
while($forum = $forums->fetch())
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Apagar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=forums&sid=$sid\">";
echo "Forum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
else if($action=="edtcat")
{
$msg = "%$uid% está editando as categorias do fórum!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Editar Categoria</b><br/><br/>";
$cforum = $pdo->query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?action=edtcat&sid=$sid\" method=\"post\">";
echo "Editar: <select name=\"fcid\">";
while ($fcat = $cforum->fetch())
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "Titúlo: <input name=\"fcname\" maxlength=\"30\"/><br/>";
echo "Posição: <input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Categorias do Fórum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}else if($action=="delcat")
{
$msg = "%$uid% está modificando as categorias do site!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Apagar Categoria</b><br/><br/>";
$cforum = $pdo->query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?action=delcat&sid=$sid\" method=\"post\"/>";
echo "Selecione: <select name=\"fcid\">";
while ($fcat = $cforum->fetch())
{
echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
}
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Apagar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=cforum&sid=$sid\">";
echo "Categorias do Fórum</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
/////////////////////////////////user info
else if($action=="chuinfo")
{
$msg = "%$uid% vai modificar o perfil de algum usuário!";
addlog($msg);
echo "<p align=\"center\">";
echo "<b>Mod Avançado</b><br/><br/>";
echo "<form action=\"admincp.php?action=acui&sid=$sid\" method=\"post\">";
echo "ID do usuario: <input name=\"unick\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Buscar\"/>";
echo "</form>";
echo "<br/><br/><a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
//////////////////////////////////////Change User info
else if($action=="acui")
{
$tid = $_POST["unick"];
$unick = getnick_uid($tid);
$whn = getnick_uid2($tid);
echo "<p align=\"center\">";
if($tid == 1)
{
echo "<img src=\"images/notok.gif\" alt=\"*\"/>Usuário de ID 1 nao pode ser modificado!";
echo "<br />";
echo "<br />";
}
else if($tid==0||!isuser($tid))
{
echo "<img src=\"images/notok.gif\" alt=\"*\"/>Usuário nao existe!";
echo "<br />";
echo "<br />";
}else
{
//log
$msg = "%$uid% está alterando perfil de $whn!";
addlog($msg);
echo "</p>";
echo "<p>";
echo "<a href=\"admincp.php?action=chubi&sid=$sid&who=$tid\">&#187;Modificar perfil de $unick</a><br/>";
echo "<a href=\"admproc.php?action=delxp&sid=$sid&who=$tid\">&#187;Apagar portagens de $unick</a><br/>";
echo "<a href=\"admproc.php?action=delu&sid=$sid&who=$tid\">&#187;Apagar $unick</a><br/>";
echo "</p>";
echo "<p align=\"center\">";
}
echo "<a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Página principal</a>";
echo "</p>";
}
////////////////////////////////////////////editar perfil de user
else if($action=="chubi")
{
$who = $_GET["who"];
$whn = getnick_uid2($who);
$msg = "%$uid% está editando o perfil completo de $whn!";
addlog($msg);
$unick = getnick_uid($who);
$email = $pdo->query("SELECT email FROM fun_users WHERE id='".$who."'")->fetch();
$bdy = $pdo->query("SELECT birthday FROM fun_users WHERE id='".$who."'")->fetch();
$uloc = $pdo->query("SELECT location FROM fun_users WHERE id='".$who."'")->fetch();
$unick = $pdo->query("SELECT name FROM fun_users WHERE id='".$who."'")->fetch(); 
$sx = $pdo->query("SELECT sex FROM fun_users WHERE id='".$who."'")->fetch();
$perm = $pdo->query("SELECT perm FROM fun_users WHERE id='".$who."'")->fetch();
echo "<p>";
echo "<form action=\"admproc.php?action=uprof&sid=$sid&who=$who\" method=\"post\">";
echo "Nick: <input name=\"unick\" value=\"$unick[0]\"/><br/>";
echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br/>";
echo "Aniversário <small>(YYYY-MM-DD)</small>: <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br/>";
echo "Localidade: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br/>";
echo "Sexo: <select name=\"usex\" value=\"$sx[0]\">";
echo "<option value=\"M\">Masculino</option>";
echo "<option value=\"F\">Feminino</option>";
echo "<option value=\"G\">GLS</option>";
echo "</select><br/>";
echo "Privilégios: <select name=\"perm\" value=\"$perm[0]\">";
echo "<option value=\"0\">Usuário</option>";
echo "<option value=\"1\">Moderador</option>";
echo "<option value=\"2\">Adminstrador</option>";
echo "</select><br/>";
echo "<input type=\"submit\" value=\"Salvar\"/>";
echo "</form>";
echo "<br/>";
echo "<form action=\"admproc.php?action=upwd&sid=$sid&who=$who\" method=\"post\">";
echo "Senha: <input name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Mudar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"admincp.php?action=chuinfo&sid=$sid\">";
echo "Mod Avançado</a><br/>";
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>";
echo "Admin CP</a><br/>";
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
