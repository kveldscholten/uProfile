<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$limit = 5;  // Limit
$page = ($menu->getA(3) == 'p' ? $menu->getE(3) : 1 );
$anfang = ($page - 1) * $limit;
$MPL = db_make_sites($page, 'WHERE uid = ' . $uid, $limit, '?user-details-' . $uid, 'usergbook');

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);

$abfBlockUID = db_fetch_assoc(db_query('SELECT * FROM prefix_userblock WHERE uid = ' . $_SESSION['authid']));
$abfBlockBID = db_fetch_assoc(db_query('SELECT * FROM prefix_userblock WHERE bid = ' . $_SESSION['authid']));

if (db_num_rows($erg) AND ($abfBlockUID['bid'] != $uid AND $abfBlockBID['uid'] != $uid)) {
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

    //Header
    include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

    echo '<div id="uProfil">';
    
    //Left Boxen
    echo '<div id="box-left">';
    include __DIR__ . DIRECTORY_SEPARATOR . 'leftbox.php';
    echo '</div>';

    //Pinnwand
    echo '<div id="box-right">';
    include __DIR__ . DIRECTORY_SEPARATOR . 'pinnwand.php';
    echo '</div>';
    
    echo '</div>';

    $design->footer();
} else {
    $title = $allgAr['title'] . ' :: Users :: User nicht gefunden';
    $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a> ' . $extented_forum_menu_sufix;
    $design = new design($title, $hmenu, 1);
    $design->header();

    echo 'Der Benutzer wurde nicht gefunden bzw. die Seite wurde nicht richtig aufgerufen.<br />';
}

$design->footer();

?>