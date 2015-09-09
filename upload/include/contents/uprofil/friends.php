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

//Header
include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

$tpl = new tpl('uprofil/friends.htm');

$FAnzahl = db_count_query('SELECT count(id) FROM prefix_friends WHERE uid = ' . $uid);

$limit = 6;  // Limit
$page = ($menu->getA(3) == 'p' ? $menu->getE(3) : 1 );
$anfang = ($page - 1) * $limit;
$MPL = db_make_sites($page, 'WHERE uid = ' . $uid, $limit, '?user-friends-' . $uid, 'friends');

$abfF = 'SELECT u.*, f.* FROM prefix_friends f LEFT JOIN prefix_user u ON u.id = f.fid WHERE f.uid = ' . $uid . ' ORDER BY f.id ASC LIMIT ' . $anfang . ',' . $limit;
$ergF = db_query($abfF);

if ($FAnzahl > 0) {
    $tpl->out(0);
    while ($rowF = db_fetch_assoc($ergF)) {
        $rowF['FNAME'] = '<a href="?user-details-' . $rowF['fid'] . '">' . $rowF['name'] . '</a>';

        if (file_exists($rowF['avatar'])) {
            $rowF['AVATAR'] = '<img src="' . $rowF['avatar'] . '" width="80" height="80" border="0">';
        } else {
            $rowF['AVATAR'] = '<img src="include/images/userprofil/avatar/nouser.png" width="75" height="75" border="0">';
        }

        $countFriends = db_count_query('SELECT count(id) FROM prefix_friends WHERE uid = ' . $rowF['fid']);
        if ($countFriends == 1) {
            $langFriends = 'Freund';
        } else {
            $langFriends = 'Freunde';
        }

        $rowF['OTHER'] = '<br /><a href="?user-friends-' . $rowF['fid'] . '">' . $countFriends . ' ' . $langFriends . '</a>';

        $tpl->set_ar_out($rowF, 1);
    }
    $tpl->out(2);
}

$design->footer();

?>