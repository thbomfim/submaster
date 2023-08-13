-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 09/08/2023 às 05:21
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `estacao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `caracoroa`
--

CREATE TABLE `caracoroa` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `did` int(100) NOT NULL,
  `cc` int(1) NOT NULL,
  `codigo` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_acc`
--

CREATE TABLE `fun_acc` (
  `id` int(100) NOT NULL,
  `gid` int(100) NOT NULL DEFAULT 0,
  `fid` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_acoes`
--

CREATE TABLE `fun_acoes` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `who` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `acao` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_albums`
--

CREATE TABLE `fun_albums` (
  `id` int(50) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `cmt` varchar(200) NOT NULL,
  `time` varchar(100) NOT NULL,
  `vis` varchar(100) NOT NULL,
  `pontos` int(10) NOT NULL DEFAULT 0,
  `senha` varchar(255) NOT NULL,
  `atul` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_announcements`
--

CREATE TABLE `fun_announcements` (
  `id` int(100) NOT NULL,
  `antext` varchar(200) NOT NULL,
  `clid` int(100) NOT NULL,
  `antime` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_ban`
--

CREATE TABLE `fun_ban` (
  `id` int(100) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `tipoban` varchar(100) NOT NULL,
  `tempo` varchar(100) NOT NULL,
  `motivo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_buddies`
--

CREATE TABLE `fun_buddies` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL DEFAULT 0,
  `tid` int(100) NOT NULL DEFAULT 0,
  `agreed` char(1) NOT NULL DEFAULT '0',
  `reqdt` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_chat`
--

CREATE TABLE `fun_chat` (
  `id` int(99) NOT NULL,
  `chatter` int(100) NOT NULL DEFAULT 0,
  `who` int(100) NOT NULL DEFAULT 0,
  `timesent` int(50) NOT NULL DEFAULT 0,
  `msgtext` varchar(255) NOT NULL DEFAULT '',
  `rid` int(99) NOT NULL DEFAULT 0,
  `exposed` char(1) NOT NULL DEFAULT '0',
  `para` varchar(255) NOT NULL,
  `acao` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_chonline`
--

CREATE TABLE `fun_chonline` (
  `lton` int(15) NOT NULL DEFAULT 0,
  `uid` int(100) NOT NULL DEFAULT 0,
  `rid` int(99) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_clubmembers`
--

CREATE TABLE `fun_clubmembers` (
  `id` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `clid` int(100) NOT NULL,
  `accepted` char(1) NOT NULL DEFAULT '0',
  `points` int(100) NOT NULL DEFAULT 0,
  `joined` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_clubs`
--

CREATE TABLE `fun_clubs` (
  `id` int(100) NOT NULL,
  `owner` int(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `rules` blob NOT NULL,
  `logo` varchar(200) NOT NULL,
  `plusses` int(100) NOT NULL DEFAULT 0,
  `created` int(100) NOT NULL DEFAULT 0,
  `subdono` varchar(255) NOT NULL DEFAULT '0',
  `tipo` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_cmt_a`
--

CREATE TABLE `fun_cmt_a` (
  `id` int(100) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `did` varchar(100) NOT NULL,
  `texto` varchar(100) NOT NULL,
  `cor` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_downloads`
--

CREATE TABLE `fun_downloads` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `visitas` varchar(255) NOT NULL,
  `categoria` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_fas`
--

CREATE TABLE `fun_fas` (
  `id` int(100) NOT NULL,
  `vid` int(100) NOT NULL,
  `uid` int(100) NOT NULL,
  `star` int(100) NOT NULL,
  `perm` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_fcats`
--

CREATE TABLE `fun_fcats` (
  `id` int(50) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `position` int(50) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_forums`
--

CREATE TABLE `fun_forums` (
  `id` int(50) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `position` int(50) NOT NULL DEFAULT 0,
  `cid` int(100) NOT NULL DEFAULT 0,
  `clubid` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_fotos`
--

CREATE TABLE `fun_fotos` (
  `id` int(50) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `cmt` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `did` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_gbook`
--

CREATE TABLE `fun_gbook` (
  `id` int(100) NOT NULL,
  `gbowner` int(100) NOT NULL DEFAULT 0,
  `gbsigner` int(100) NOT NULL DEFAULT 0,
  `gbmsg` blob NOT NULL,
  `dtime` int(100) NOT NULL DEFAULT 0,
  `cor` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_ignore`
--

CREATE TABLE `fun_ignore` (
  `id` int(10) NOT NULL,
  `name` int(99) NOT NULL DEFAULT 0,
  `target` int(99) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_log`
--

CREATE TABLE `fun_log` (
  `id` int(255) NOT NULL,
  `msg` mediumtext NOT NULL,
  `data` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_log`
--

INSERT INTO `fun_log` (`id`, `msg`, `data`) VALUES
(1, '%1% entrou no painel da equipe!', '1691469234'),
(2, '%1% está alterando as categorias do f�rum!', '1691469443'),
(3, '%1% está adicionando uma nova categoria no f�rum do site!', '1691469453'),
(4, '%1% entrou no painel da equipe!', '1691469458'),
(5, '%1% está alterando as sub. cats do f�rum!', '1691469460'),
(6, '%1%, está alterando as salas de chat do site!', '1691469463'),
(7, '%1% entrou no painel da equipe!', '1691469466'),
(8, '%1% está alterando as configurações do site!', '1691469468'),
(9, '%1% está limpando dados do site!', '1691469477'),
(10, '%1% está iniciando a busca de usuários por IP!', '1691469501'),
(11, '%1% vai modificar o perfil de algum usuário!', '1691469505'),
(12, '%1% vai modificar o perfil de algum usuário!', '1691469509'),
(13, '%1% entrou no painel da equipe!', '1691469519');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_mequipe`
--

CREATE TABLE `fun_mequipe` (
  `id` int(100) NOT NULL,
  `shout` varchar(150) NOT NULL DEFAULT '',
  `shouter` int(100) NOT NULL DEFAULT 0,
  `shtime` int(100) NOT NULL DEFAULT 0,
  `cor` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_mpot`
--

CREATE TABLE `fun_mpot` (
  `id` int(10) NOT NULL,
  `ddt` varchar(20) NOT NULL DEFAULT '',
  `dtm` varchar(20) NOT NULL DEFAULT '',
  `ppl` int(20) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_mpot`
--

INSERT INTO `fun_mpot` (`id`, `ddt`, `dtm`, `ppl`) VALUES
(1, '29/07/23', '10:38:04', 1),
(2, '29/07/23', '10:38:17', 1),
(3, '29/07/23', '10:38:19', 1),
(4, '29/07/23', '10:38:32', 1),
(5, '29/07/23', '11:02:10', 1),
(6, '08/08/23', '01:33:29', 1),
(7, '08/08/23', '01:33:42', 1),
(8, '08/08/23', '01:33:44', 1),
(9, '08/08/23', '01:33:51', 1),
(10, '08/08/23', '01:33:54', 1),
(11, '08/08/23', '01:33:57', 1),
(12, '08/08/23', '01:37:23', 1),
(13, '08/08/23', '01:37:33', 1),
(14, '08/08/23', '01:37:38', 1),
(15, '08/08/23', '01:37:40', 1),
(16, '08/08/23', '01:37:43', 1),
(17, '08/08/23', '01:37:46', 1),
(18, '08/08/23', '01:37:48', 1),
(19, '08/08/23', '01:37:57', 1),
(20, '08/08/23', '01:38:04', 1),
(21, '08/08/23', '01:38:21', 1),
(22, '08/08/23', '01:38:25', 1),
(23, '08/08/23', '01:38:29', 1),
(24, '08/08/23', '01:38:33', 1),
(25, '08/08/23', '01:38:39', 1),
(26, '08/08/23', '01:42:01', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_notificacoes`
--

CREATE TABLE `fun_notificacoes` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL DEFAULT 0,
  `touid` int(100) NOT NULL DEFAULT 0,
  `unread` char(1) NOT NULL DEFAULT '1',
  `timesent` int(100) NOT NULL DEFAULT 0,
  `starred` char(1) NOT NULL DEFAULT '0',
  `reported` char(1) NOT NULL DEFAULT '0',
  `cor` varchar(100) NOT NULL,
  `num` varchar(100) NOT NULL,
  `folderid` int(100) NOT NULL,
  `title` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_online`
--

CREATE TABLE `fun_online` (
  `id` int(10) NOT NULL,
  `userid` int(100) NOT NULL DEFAULT 0,
  `actvtime` int(100) NOT NULL DEFAULT 0,
  `place` varchar(50) NOT NULL DEFAULT '',
  `placedet` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_online`
--

INSERT INTO `fun_online` (`id`, `userid`, `actvtime`, `place`, `placedet`) VALUES
(2, 1, 1691469721, 'Página principal', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_parceiros`
--

CREATE TABLE `fun_parceiros` (
  `id` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_posts`
--

CREATE TABLE `fun_posts` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `tid` int(100) NOT NULL DEFAULT 0,
  `uid` int(100) NOT NULL DEFAULT 0,
  `dtpost` int(100) NOT NULL DEFAULT 0,
  `reported` char(1) NOT NULL DEFAULT '0',
  `quote` int(100) NOT NULL DEFAULT 0,
  `cor` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_private`
--

CREATE TABLE `fun_private` (
  `id` int(100) NOT NULL,
  `text` blob NOT NULL,
  `byuid` int(100) NOT NULL DEFAULT 0,
  `touid` int(100) NOT NULL DEFAULT 0,
  `unread` char(1) NOT NULL DEFAULT '1',
  `timesent` int(100) NOT NULL DEFAULT 0,
  `starred` char(1) NOT NULL DEFAULT '0',
  `reported` varchar(2) NOT NULL DEFAULT '0',
  `cor` varchar(100) DEFAULT NULL,
  `num` varchar(100) DEFAULT NULL,
  `folderid` int(100) DEFAULT NULL,
  `title` blob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_rooms`
--

CREATE TABLE `fun_rooms` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `static` char(1) NOT NULL,
  `mage` int(10) NOT NULL,
  `chposts` int(100) NOT NULL,
  `perms` int(10) NOT NULL,
  `censord` char(1) NOT NULL DEFAULT '1',
  `freaky` char(1) NOT NULL DEFAULT '0',
  `lastmsg` int(100) NOT NULL,
  `clubid` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_search`
--

CREATE TABLE `fun_search` (
  `id` int(20) NOT NULL,
  `svar1` varchar(50) NOT NULL DEFAULT '',
  `svar2` varchar(50) NOT NULL DEFAULT '',
  `svar3` varchar(50) NOT NULL DEFAULT '',
  `svar4` varchar(50) NOT NULL DEFAULT '',
  `svar5` varchar(50) NOT NULL DEFAULT '',
  `stime` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_ses`
--

CREATE TABLE `fun_ses` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `uid` varchar(30) NOT NULL DEFAULT '',
  `expiretm` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_ses`
--

INSERT INTO `fun_ses` (`id`, `uid`, `expiretm`) VALUES
('PHP_SESSID:6CFEC92FD8E12298509D9724D3529637E578A354', '1', 1691471521);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_settings`
--

CREATE TABLE `fun_settings` (
  `id` int(10) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_settings`
--

INSERT INTO `fun_settings` (`id`, `name`, `value`) VALUES
(1, 'sesexp', '30'),
(2, 'Mon/09/Aug/2010 - 15:56', '15'),
(3, '4ummsg', '[b].vempostar. bora faturar .$. [topic=67].clik.[/topic] [topic=68].clik.[/topic][/b]'),
(4, 'Counter', '33948'),
(5, 'pmaf', ''),
(6, 'reg', '1'),
(7, 'fview', '0'),
(8, 'lastbpm', '2023-08-08'),
(9, 'banco', '2012-06-08'),
(10, 'cassino', '22'),
(18, 'vat', '1'),
(19, 'vdica', 'Qual o nome do site?'),
(20, 'vresposta', 'estaçãowap'),
(21, 'vultimo', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_shouts`
--

CREATE TABLE `fun_shouts` (
  `id` int(100) NOT NULL,
  `shout` varchar(150) NOT NULL DEFAULT '',
  `shouter` int(100) NOT NULL DEFAULT 0,
  `shtime` int(100) NOT NULL DEFAULT 0,
  `cor` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_smilies`
--

CREATE TABLE `fun_smilies` (
  `id` int(100) NOT NULL,
  `scode` varchar(15) NOT NULL DEFAULT '',
  `imgsrc` varchar(200) NOT NULL DEFAULT '',
  `hidden` char(1) NOT NULL DEFAULT '0',
  `cat` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_spam`
--

CREATE TABLE `fun_spam` (
  `id` int(255) NOT NULL,
  `txt` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_topics`
--

CREATE TABLE `fun_topics` (
  `id` int(100) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `fid` int(100) NOT NULL DEFAULT 0,
  `authorid` int(100) NOT NULL DEFAULT 0,
  `text` blob NOT NULL,
  `pinned` char(1) NOT NULL DEFAULT '0',
  `closed` char(1) NOT NULL DEFAULT '0',
  `crdate` int(100) NOT NULL DEFAULT 0,
  `views` int(100) NOT NULL DEFAULT 0,
  `reported` char(1) NOT NULL DEFAULT '0',
  `lastpost` int(100) NOT NULL DEFAULT 0,
  `moved` char(1) NOT NULL DEFAULT '0',
  `pollid` int(100) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_trofeus`
--

CREATE TABLE `fun_trofeus` (
  `id` int(11) NOT NULL,
  `who` varchar(255) DEFAULT NULL,
  `motivo` varchar(155) DEFAULT NULL,
  `hora` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fun_users`
--

CREATE TABLE `fun_users` (
  `id` int(100) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `pass` varchar(60) NOT NULL DEFAULT '',
  `tempon` varchar(50) NOT NULL DEFAULT '0',
  `birthday` varchar(50) NOT NULL DEFAULT '',
  `sex` char(1) NOT NULL DEFAULT '',
  `location` varchar(100) NOT NULL DEFAULT '',
  `perm` char(1) NOT NULL DEFAULT '0',
  `posts` int(100) NOT NULL DEFAULT 0,
  `plusses` int(100) NOT NULL DEFAULT 0,
  `signature` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `recados` int(2) DEFAULT 0,
  `browserm` varchar(50) NOT NULL DEFAULT '',
  `ipadd` varchar(30) NOT NULL DEFAULT '',
  `lastact` int(100) NOT NULL DEFAULT 0,
  `regdate` int(100) NOT NULL DEFAULT 0,
  `chmsgs` int(100) NOT NULL DEFAULT 0,
  `shield` char(1) NOT NULL DEFAULT '0',
  `budmsg` varchar(100) NOT NULL DEFAULT '',
  `lastpnreas` varchar(100) NOT NULL DEFAULT '',
  `lastplreas` varchar(100) NOT NULL DEFAULT '',
  `shouts` int(100) NOT NULL DEFAULT 0,
  `hvia` char(1) NOT NULL DEFAULT '1',
  `lastvst` int(100) NOT NULL DEFAULT 0,
  `vip` varchar(100) NOT NULL DEFAULT '0',
  `banco` int(100) NOT NULL DEFAULT 0,
  `shopssid` varchar(100) NOT NULL DEFAULT '0',
  `specialid` varchar(100) NOT NULL DEFAULT '0',
  `tottimeonl` varchar(100) NOT NULL DEFAULT '0',
  `humor` varchar(100) NOT NULL DEFAULT '0',
  `visitas` int(100) NOT NULL DEFAULT 0,
  `numpm` varchar(100) DEFAULT NULL,
  `rperm` varchar(255) NOT NULL DEFAULT '0',
  `ruser` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fun_users`
--

INSERT INTO `fun_users` (`id`, `name`, `pass`, `tempon`, `birthday`, `sex`, `location`, `perm`, `posts`, `plusses`, `signature`, `avatar`, `email`, `recados`, `browserm`, `ipadd`, `lastact`, `regdate`, `chmsgs`, `shield`, `budmsg`, `lastpnreas`, `lastplreas`, `shouts`, `hvia`, `lastvst`, `vip`, `banco`, `shopssid`, `specialid`, `tottimeonl`, `humor`, `visitas`, `numpm`, `rperm`, `ruser`) VALUES
(1, 'admin', '25d55ad283aa400af464c76d713c07ad', '540', '2000-03-16', 'M', 'rede', '0', 0, 21, '', '', '', 0, 'Mozilla/5.0', '::1', 1691469721, 1690637799, 0, '0', '', '', '', 0, '1', 0, '0', 0, '0', '0', '540', '0', 1, NULL, '0', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `loja`
--

CREATE TABLE `loja` (
  `id` int(100) NOT NULL,
  `url` varchar(50) NOT NULL DEFAULT '0',
  `valor` int(5) NOT NULL DEFAULT 0,
  `cat` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `presentes`
--

CREATE TABLE `presentes` (
  `id` int(100) NOT NULL,
  `pid` int(50) NOT NULL DEFAULT 0,
  `uid` int(100) NOT NULL DEFAULT 0,
  `eid` int(100) NOT NULL DEFAULT 0,
  `msg` varchar(80) NOT NULL DEFAULT '0',
  `data` int(50) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `virtual_pet`
--

CREATE TABLE `virtual_pet` (
  `uid` int(10) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `rodjen` int(20) NOT NULL,
  `tezina` int(10) NOT NULL,
  `ime` varchar(30) NOT NULL,
  `ziv` int(1) NOT NULL,
  `nahranjen` int(20) NOT NULL,
  `boja` varchar(15) NOT NULL,
  `igra` int(20) NOT NULL,
  `kupanje` int(20) NOT NULL,
  `smrt` int(20) NOT NULL,
  `raspolozenje` int(2) NOT NULL,
  `broj` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitantes`
--

CREATE TABLE `visitantes` (
  `id` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `vid` varchar(255) NOT NULL,
  `hora` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `caracoroa`
--
ALTER TABLE `caracoroa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_acc`
--
ALTER TABLE `fun_acc`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_acoes`
--
ALTER TABLE `fun_acoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_albums`
--
ALTER TABLE `fun_albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `fun_announcements`
--
ALTER TABLE `fun_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_ban`
--
ALTER TABLE `fun_ban`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_buddies`
--
ALTER TABLE `fun_buddies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Índices de tabela `fun_chat`
--
ALTER TABLE `fun_chat`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_chonline`
--
ALTER TABLE `fun_chonline`
  ADD PRIMARY KEY (`lton`),
  ADD UNIQUE KEY `username` (`uid`);

--
-- Índices de tabela `fun_clubmembers`
--
ALTER TABLE `fun_clubmembers`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_clubs`
--
ALTER TABLE `fun_clubs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_cmt_a`
--
ALTER TABLE `fun_cmt_a`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_downloads`
--
ALTER TABLE `fun_downloads`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_fas`
--
ALTER TABLE `fun_fas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_fcats`
--
ALTER TABLE `fun_fcats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `fun_forums`
--
ALTER TABLE `fun_forums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `fun_fotos`
--
ALTER TABLE `fun_fotos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_gbook`
--
ALTER TABLE `fun_gbook`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_ignore`
--
ALTER TABLE `fun_ignore`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_log`
--
ALTER TABLE `fun_log`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_mequipe`
--
ALTER TABLE `fun_mequipe`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_mpot`
--
ALTER TABLE `fun_mpot`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_notificacoes`
--
ALTER TABLE `fun_notificacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_online`
--
ALTER TABLE `fun_online`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Índices de tabela `fun_parceiros`
--
ALTER TABLE `fun_parceiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_posts`
--
ALTER TABLE `fun_posts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_private`
--
ALTER TABLE `fun_private`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_rooms`
--
ALTER TABLE `fun_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `fun_search`
--
ALTER TABLE `fun_search`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_ses`
--
ALTER TABLE `fun_ses`
  ADD UNIQUE KEY `id` (`id`,`uid`);

--
-- Índices de tabela `fun_settings`
--
ALTER TABLE `fun_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `fun_shouts`
--
ALTER TABLE `fun_shouts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_smilies`
--
ALTER TABLE `fun_smilies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `scode` (`scode`);

--
-- Índices de tabela `fun_spam`
--
ALTER TABLE `fun_spam`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_topics`
--
ALTER TABLE `fun_topics`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_trofeus`
--
ALTER TABLE `fun_trofeus`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fun_users`
--
ALTER TABLE `fun_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Índices de tabela `loja`
--
ALTER TABLE `loja`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `presentes`
--
ALTER TABLE `presentes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `visitantes`
--
ALTER TABLE `visitantes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `caracoroa`
--
ALTER TABLE `caracoroa`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_acc`
--
ALTER TABLE `fun_acc`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_acoes`
--
ALTER TABLE `fun_acoes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_albums`
--
ALTER TABLE `fun_albums`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_announcements`
--
ALTER TABLE `fun_announcements`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_ban`
--
ALTER TABLE `fun_ban`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_buddies`
--
ALTER TABLE `fun_buddies`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_chat`
--
ALTER TABLE `fun_chat`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_clubmembers`
--
ALTER TABLE `fun_clubmembers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_clubs`
--
ALTER TABLE `fun_clubs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_cmt_a`
--
ALTER TABLE `fun_cmt_a`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_downloads`
--
ALTER TABLE `fun_downloads`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_fas`
--
ALTER TABLE `fun_fas`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_fcats`
--
ALTER TABLE `fun_fcats`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_forums`
--
ALTER TABLE `fun_forums`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_fotos`
--
ALTER TABLE `fun_fotos`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_gbook`
--
ALTER TABLE `fun_gbook`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_ignore`
--
ALTER TABLE `fun_ignore`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_log`
--
ALTER TABLE `fun_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `fun_mequipe`
--
ALTER TABLE `fun_mequipe`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_mpot`
--
ALTER TABLE `fun_mpot`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `fun_notificacoes`
--
ALTER TABLE `fun_notificacoes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_online`
--
ALTER TABLE `fun_online`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `fun_parceiros`
--
ALTER TABLE `fun_parceiros`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_posts`
--
ALTER TABLE `fun_posts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_private`
--
ALTER TABLE `fun_private`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_rooms`
--
ALTER TABLE `fun_rooms`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_search`
--
ALTER TABLE `fun_search`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_settings`
--
ALTER TABLE `fun_settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `fun_shouts`
--
ALTER TABLE `fun_shouts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_smilies`
--
ALTER TABLE `fun_smilies`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_spam`
--
ALTER TABLE `fun_spam`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_topics`
--
ALTER TABLE `fun_topics`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_trofeus`
--
ALTER TABLE `fun_trofeus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fun_users`
--
ALTER TABLE `fun_users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `loja`
--
ALTER TABLE `loja`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `presentes`
--
ALTER TABLE `presentes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitantes`
--
ALTER TABLE `visitantes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
