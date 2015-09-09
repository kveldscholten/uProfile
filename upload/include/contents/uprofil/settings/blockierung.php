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

    $tpl = new tpl('uprofil/settings/blockierung.htm');

    $FAnzahl = db_count_query('SELECT count(id) FROM prefix_friendscheck WHERE fid = ' . $uid);
    $BAnzahl = db_count_query('SELECT count(id) FROM prefix_userblock WHERE uid = ' . $uid);

    $abfF = 'SELECT u.*, f.* FROM prefix_friendscheck f LEFT JOIN prefix_user u ON u.id = f.uid WHERE f.fid = ' . $uid . ' ORDER BY f.id ASC';
    $ergF = db_query($abfF);

    $abfBlock = 'SELECT u.*, f.* FROM prefix_userblock f LEFT JOIN prefix_user u ON u.id = f.bid WHERE f.uid = ' . $uid . ' ORDER BY f.id ASC';
    $ergBlock = db_query($abfBlock);

    $tpl->out(0);

    if ($BAnzahl > 0) {
        while ($rowBlock = db_fetch_assoc($ergBlock)) {
            $rowBlock['FNAME'] = $rowBlock['name'];
            $rowBlock['OTHER'] = '<br /><br /><div class="friendButton blockURefuse" onclick="location.href = \'?user-allgcheck-refuse-' . $rowBlock['bid'] . '\';">Blockierung aufheben</div>';

            $tpl->set_ar_out($rowBlock, 1);
        }
    } else {
        echo '<br /><div id="contenText" align="center">Keine Blockierte User vorhanden.</div>';
    }
    $tpl->out(2);
} else {
    wd('index.php', 'Es ist ein Fehler aufgetreten.');
}


$design->footer();

?>