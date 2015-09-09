<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);
$row = db_fetch_assoc($erg);

$title = $allgAr['title'] . ' :: Users :: Details von ' . $row['name'];
$hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Details von ' . $row['name'] . $extented_forum_menu_sufix;
$design = new design($title, $hmenu, 1);
$design->addheader('<link rel="stylesheet" type="text/css" href="include/includes/css/uprofil/uprofil.css">');
$design->addheader('<script type="text/javascript" src="include/includes/js/uprofil.js"></script>');
$design->addheader('<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>');
$design->addheader('<script type="text/javascript" src="include/includes/js/jquery.textareaAutoResize.js"></script>');
$design->addheader('<script type="text/javascript" src="include/includes/js/bbcode.js"></script>');
$design->header();

if (loggedin() AND $menu->get(2) == $_SESSION['authid']) {
    //Header
    include __DIR__ . DIRECTORY_SEPARATOR . '../header.php';

    $tpl = new tpl('uprofil/settings/privatsphaere.htm');

    $tpl->out(0);
}

$design->footer();

?>