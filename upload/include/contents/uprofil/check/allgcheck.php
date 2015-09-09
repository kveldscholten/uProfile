<?php

defined('main') or die('no direct access');

$bid = intval($menu->get(3));
$uid = $_SESSION['authid'];

switch ($menu->get(2)) {
    case 'block':
        $title = $allgAr['title'] . ' :: Users :: Blockiert';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Blockiert';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($bid)) {
            db_query('INSERT INTO prefix_userblock (uid, bid) VALUES (' . $uid . ', ' . $bid . ')');
            
            db_query("DELETE FROM prefix_friends WHERE uid = " . $uid . " AND fid = " . $bid);
            db_query("DELETE FROM prefix_friends WHERE uid = " . $bid . " AND fid = " . $uid);

            wd('?user-details-' . $uid, 'User wurde Blockiert.');
        } else {
            wd('?user-details-' . $bid, 'Es ist ein Fehler aufgetreten.');
        }

        $design->footer();
        break;
        
    case 'refuse':
        $title = $allgAr['title'] . ' :: Users :: Entblocken';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Entblocken';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($bid)) {
            db_query("DELETE FROM prefix_userblock WHERE uid = " . $uid . " AND bid = " . $bid);

            wd('?user-fcheck-' . $uid, 'User wurde entblockt.');
        } else {
            wd('?user-details-' . $bid, 'Es ist ein Fehler aufgetreten.');
        }

        $design->footer();
        break;
}

?>