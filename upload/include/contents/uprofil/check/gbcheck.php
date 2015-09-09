<?php

defined('main') or die('no direct access');

$fid = intval($menu->get(3));
$gbid = intval($menu->get(4));
$uid = $_SESSION['authid'];

switch ($menu->get(2)) {
    case 'add':
        $title = $allgAr['title'] . ' :: Users :: Status hinzugefügt';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Status hinzugefügt';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin()) {
            if (isset($_POST['txt']) AND trim($_POST['txt']) != "") {
                $txt = escape($_POST['txt'], 'textarea');

                db_query("INSERT INTO prefix_usergbook (`sid`,`uid`,`txt`,`datetime`) VALUES ('" . $_SESSION['authid'] . "', '" . $fid . "', '" . $txt . "', '" . time() . "')");

                wd('?user-details-' . $fid, $lang['insertsuccessful']);
            } else {
                wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten oder es wurden nicht alle Felder ausgefüllt.');
            }
        } else {
            wd('?user-details-' . $fid, 'Nur eingeloggte User können eine Nachricht auf der Pinnwand hinterlassen.');
        }

        $design->footer();
        break;

    case 'addk':
        $title = $allgAr['title'] . ' :: Users :: Kommentar hinzugefügt';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Kommentar hinzugefügt';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin()) {
            if (isset($_POST['ktxt']) AND trim($_POST['ktxt']) != "") {
                $ktxt = escape($_POST['ktxt'], 'textarea');

                db_query("INSERT INTO prefix_usergbook_koms (`uid`,`gbid`,`txt`,`datetime`) VALUES ('" . $_SESSION['authid'] . "', '" . $gbid . "', '" . $ktxt . "', '" . time() . "')");

                wd('?user-details-' . $fid, $lang['insertsuccessful']);
            } else {
                wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten oder es wurden nicht alle Felder ausgefüllt.');
            }
        } else {
            wd('?user-details-' . $fid, 'Nur eingeloggte User können eine Nachricht auf der Pinnwand hinterlassen.');
        }

        $design->footer();
        break;

    case 'del':
        $title = $allgAr['title'] . ' :: Users :: Eintrag gelöscht';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Eintrag gelöscht';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($menu->get(4)) AND $_SESSION['authid'] == $fid) {
            $did = escape($menu->get(4), 'integer');
            db_query("DELETE FROM prefix_usergbook WHERE id = " . $did);
            db_query("DELETE FROM prefix_usergbook_koms WHERE gbid = " . $did);

            wd('?user-details-' . $fid, 'Eintrag erfolgreich gelöscht.');
        } else {
            wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten');
        }

        $design->footer();
        break;

    case 'delk':
        $title = $allgAr['title'] . ' :: Users :: Eintrag gelöscht';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Eintrag gelöscht';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($menu->get(4)) AND $_SESSION['authid'] == $fid) {
            $did = escape($menu->get(4), 'integer');
            db_query("DELETE FROM prefix_usergbook_koms WHERE id = " . $did);

            wd('?user-details-' . $fid, 'Eintrag erfolgreich gelöscht.');
        } else {
            wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten');
        }

        $design->footer();
        break;
}

?>