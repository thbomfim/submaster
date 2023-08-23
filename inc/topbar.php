<?php
require_once("config.php");
require_once("core.php");
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= "$stitle"?></title>
    <link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/dist/css/style.css">
</head>

<body>
    <?php 
cleardata();//erases all old data
$action = $_GET["action"] ?? '';
$sid = $_GET["sid"] ?? '';
$page = $_GET["page"] ?? '';
$who = $_GET["who"] ?? '';
$uid = getuid_sid($sid) ?? '';
//////////////user logged
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Você não esté logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
///////user banned
if(is_banido($uid))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Desculpe, mais você foi banido do site!";
echo "<br />";
echo "<br />";
$infos_ban = $pdo->query("SELECT tempo, motivo FROM fun_ban WHERE uid='".$uid."' AND (tipoban='1' OR tipoban='2')")->fetch();
echo "Tempo para acabar sua penalidade: " . tempo_msg($infos_ban[0]);
echo "<br />";
echo "Motivo da sua penalidade: <b>".htmlspecialchars($infos_ban[1])."</b>";
exit();
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
<div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <?php
                #count pm
                $tmsg = getpmcount(getuid_sid($sid));
                $umsg = getunreadpm(getuid_sid($sid));
                ?>
                <a class="nav-link active" aria-current="page" href="inbox.php?action=main&sid=<?=$sid?>">Inbox<?="($umsg/$tmsg)"?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?action=cpanel&sid=<?=$sid?>">Configurações</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Menu Extra
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?action=stats&sid=<?=$sid?>">Estatisticas</a></li>
                    <?php $sml = $pdo->query("SELECT COUNT(*) FROM fun_smilies")->fetch(); ?>
                    <li><a class="dropdown-item" href="paginas.php?p=sml&sid=<?=$sid?>">smilies<?="($sml[0])"?></a></li>
                    <!--<li>
                        <hr class="dropdown-divider">
                    </li>-->
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li>
        </ul>
        <form class="d-flex" role="search" href="">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</div>
</nav>
