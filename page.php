<?php 

include("inc/topbar.php");

////////////////////////////////////////main page
    if ($page=="main") {
        ?>
<?php
    addvisitor();//add visit in site
    adicionar_online(getuid_sid($sid),"Página principal","");
    $uid = getuid_sid($sid);
    echo "<p align=\"center\">";
    echo "<img src=\"images/logo.png\" alt=\"*\"/><br />";
    echo "<div class=\"forum\">Mural Admin</div>";
    $mural = scan_msg(htmlspecialchars(mural_admin()), $sid);
    echo "$mural<br />";
    echo "</p>";
    echo "<br>";
    echo "<div class=\"forum\">forum</div>";
    echo "</p>";
    $fcats = $pdo->query("SELECT id, name FROM fun_fcats ORDER BY position, id");
    $iml = "<img src=\"images/1.gif\" alt=\"*\"/>";
    while($fcat= $fcats->fetch())
    {
    $catlink = "<a href=\"index.php?action=viewcat&sid=$sid&cid=$fcat[0]\">$iml$fcat[1]</a>";
    echo "$catlink<br />";
    $forums = $pdo->query("SELECT id, name FROM fun_forums WHERE cid='".$fcat[0]."' AND clubid='0' ORDER BY position, id, name");
    if(flood_forum()==0)
    {
    echo "";
    while($forum= $forums->fetch())
    {
    if(canaccess(getuid_sid($sid),$forum[0]))
    {
    }
    }
    echo "";
    }
    }
    echo "<br>";
    //$recados = $pdo->query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$uid."'")->fetch();
    //echo "<a href=\"lists.php?action=gbook&sid=$sid&who=$uid\"><img src=\"images/recados.gif\" alt=\"*\"/>Recados($recados[0])</a><br />";
    echo "<a href=\"index.php?action=forum&sid=$sid\"><img src=\"images/folder.gif\" alt=\"*\"/>Fórum</a><br />";
    $chs = $pdo->query("SELECT COUNT(*) FROM fun_clubs")->fetch();
    //echo "<a href=\"index.php?action=clmenu&sid=$sid\"><img src=\"images/comunidades.gif\" alt=\"*\"/>Comunidades($chs[0])</a><br />";
   // $chs = $pdo->query("SELECT COUNT(*) FROM fun_chonline")->fetch();
    //echo "<a href=\"index.php?action=chat&sid=$sid\"><img src=\"images/batepapo.gif\">Chat($chs[0])</a><br />";
    $mybuds = getnbuds($uid);
    $onbuds = getonbuds($uid);
    echo "<a href=\"lists.php?action=buds&sid=$sid\"><img src=\"images/amigos.gif\" alt=\"*\"/>Amigos($onbuds/$mybuds)</a>";
    $reqs = getnreqs($uid);
    if($reqs>0)
    {
    echo ": <a href=\"lists.php?action=reqs&sid=$sid\">$reqs</a>";
    }
    echo "<br />";
    //$alb = $pdo->query("SELECT COUNT(*) FROM fun_albums")->fetch();
    //echo "<a href=\"album.php?&a=albums&sid=$sid\"><img src=\"images/galeria.gif\" alt=\"*\"/>álbuns($alb[0])</a><br />";
   // $down = $pdo->query("SELECT COUNT(*) FROM fun_downloads")->fetch();
    //echo "<a href=\"downloads.php?sid=$sid\"><img src=\"images/downloads.gif\" alt=\"*\"/>Downloads($down[0])</a><br />";
    /////////////////menu admin
    if (isadmin(getuid_sid($sid)))
    {
    echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>Admin CP</a><br />";
    }
    /////////////menu mod
    if(ismod($uid))
    {
    $tnor = $pdo->query("SELECT COUNT(*) FROM fun_private WHERE reported='tk'")->fetch();
    $tot = $tnor[0];
    $tnor = $pdo->query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'")->fetch();
    $tot += $tnor[0];
    $tnor = $pdo->query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'")->fetch();
    $tot += $tnor[0];
    $tnol = $pdo->query("SELECT COUNT(*) FROM fun_log")->fetch();
    $tol = $tnol[0]; 
    echo "<a href=\"modcp.php?action=main&sid=$sid\"><img src=\"images/den.gif\" alt=\"*\"/>Mod R/L($tot/$tol)</a><br />";
    }
    echo "<p align=\"center\">";
    echo "<b>Mural de recados</b><br />";
    echo getshoutbox($sid);
    echo "<br /><br />";
    echo "Usuários online: <a href=\"index.php?action=online&sid=$sid\">".getnumonline()."</a><br />";
    $timeout = 600;
    $timeon = time()-$timeout;
    $noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'")->fetch();
    echo "Equipe online: <a href=\"index.php?action=stfol&sid=$sid\">".$noi[0]."</a><br />";
    $timeout2 = 600;
    $timeon2 = time()-$timeout2;
    $noi = $pdo->query("SELECT COUNT(*) FROM fun_users WHERE vip='1' AND lastact>'".$timeon2."'")->fetch();
    echo "VIPs online: <a href=\"index.php?action=vipol&sid=$sid\">".$noi[0]."</a><br />";
    $memid = $pdo->query("SELECT id, name  FROM fun_users ORDER BY regdate DESC LIMIT 0,1")->fetch();
    echo "Novo usuário: <b><a href=\"index.php?action=perfil&who=$memid[0]&sid=$sid\">".getnick_uid($memid[0])."</a></b><br /><br />";
    echo "<a href=\"index.php?action=sair&sid=$sid\"><img src=\"teks/hit.gif\" alt=\"*\"/>";
    echo "Sair</a>";
    echo "</p>";
    }
?>

<script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="bootstrap/popperjs/core/dist/umd/popper.js"></script>
</body>

</html>