<?php

defined('main') or die('no direct access');

$fid = intval($menu->get(3));
$uid = $_SESSION['authid'];

switch ($menu->get(2)) {
    default :
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

            $tpl = new tpl('uprofil/fcheck.htm');

            $FAnzahl = db_count_query('SELECT count(id) FROM prefix_friendscheck WHERE fid = ' . $uid);
            $BAnzahl = db_count_query('SELECT count(id) FROM prefix_userblock WHERE uid = ' . $uid);

            $abfF = 'SELECT u.*, f.* FROM prefix_friendscheck f LEFT JOIN prefix_user u ON u.id = f.uid WHERE f.fid = ' . $uid . ' ORDER BY f.id ASC';
            $ergF = db_query($abfF);

            $abfBlock = 'SELECT u.*, f.* FROM prefix_userblock f LEFT JOIN prefix_user u ON u.id = f.bid WHERE f.uid = ' . $uid . ' ORDER BY f.id ASC';
            $ergBlock = db_query($abfBlock);

            $tpl->out(0);

            if ($FAnzahl > 0) {
                while ($rowF = db_fetch_assoc($ergF)) {
                    $rowF['FNAME'] = '<a href="?user-details-' . $rowF['uid'] . '">' . $rowF['name'] . '</a>';

                    if (file_exists($rowF['avatar'])) {
                        $rowF['AVATAR'] = '<img src="' . $rowF['avatar'] . '" width="80" height="80" border="0">';
                    } else {
                        $rowF['AVATAR'] = '<img src="include/images/userprofil/avatar/nouser.png" width="75" height="75" border="0">';
                    }

                    $rowF['OTHER'] = '<br /><br /><div class="friendButton friendAccept" onclick="location.href = \'?user-fcheck-accept-' . $rowF['uid'] . '\';">Annehmen</div>';
                    $rowF['OTHER'] .= '<div class="friendButton friendRefuse" onclick="location.href = \'?user-fcheck-refuse-' . $rowF['uid'] . '\';">Ablehnen</div>';

                    $tpl->set_ar_out($rowF, 1);
                }
            } else {
                echo '<br /><div id="contenText" align="center">Keine Freundschaftsanfragen vorhanden.</div>';
            }

            $tpl->out(2);
        } else {
            wd('index.php', 'Es ist ein Fehler aufgetreten.');
        }


        $design->footer();
        break;

    case 'add':
        $title = $allgAr['title'] . ' :: Users :: Freund hinzufügen';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Freund hinzufügen';
        $design = new design($title, $hmenu, 1);
        $design->header();

        $double = @db_result(db_query("SELECT * FROM prefix_friendscheck WHERE uid = " . $uid . " AND fid = " . $fid), 0);

        if (loggedin() AND is_numeric($uid)) {
            if ($double == 0) {
                db_query('INSERT INTO prefix_friendscheck (uid, fid) VALUES (' . $uid . ', ' . $fid . ')');

                wd('?user-details-' . $fid, 'Freundschaftsanfrage gesendet.');
            } else {
                wd('?user-details-' . $fid, 'Deine Freundschaftsanfrage steht noch offen oder du bist schon mit dieser Person befreundet.');
            }
        } else {
            wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten.');
        }

        $design->footer();
        break;

    case 'accept':
        $title = $allgAr['title'] . ' :: Users :: Freundschaftsanfrage angenommen';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Freundschaftsanfrage angenommen';
        $design = new design($title, $hmenu, 1);
        $design->header();

        $double = @db_result(db_query("SELECT * FROM prefix_friends WHERE uid = " . $uid . " AND fid = " . $fid), 0);

        if (loggedin() AND is_numeric($uid)) {
            if ($double == 0) {
                db_query('INSERT INTO prefix_friends (uid, fid) VALUES (' . $uid . ', ' . $fid . ')');
                db_query('INSERT INTO prefix_friends (uid, fid) VALUES (' . $fid . ', ' . $uid . ')');

                db_query("DELETE FROM prefix_friendscheck WHERE uid = " . $fid . " AND fid = " . $uid);

                wd('?user-fcheck-' . $uid, 'Freundschaftsanfrage angenommen.');
            } else {
                wd('?user-fcheck-' . $uid, 'Du bist schon mit dieser Person befreundet.');
                db_query("DELETE FROM prefix_friendscheck WHERE uid = " . $uid . " AND fid = " . $fid);
            }
        } else {
            wd('?user-fcheck-' . $uid, 'Es ist ein Fehler aufgetreten.');
        }

        $design->footer();
        break;

    case 'back':
        $title = $allgAr['title'] . ' :: Users :: Freundschaftsanfrage zurückgezogen';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Freundschaftsanfrage zurückgezogen';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($fid)) {
            db_query("DELETE FROM prefix_friendscheck WHERE uid = " . $uid . " AND fid = " . $fid);

            wd('?user-details-' . $fid, 'Deine Freundschaftsanfrage wurde zurückgezogen.');
        } else {
            wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten');
        }

        $design->footer();
        break;

    case 'refuse':
        $title = $allgAr['title'] . ' :: Users :: Freundschaftsanfrage abgelehnt';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Freundschaftsanfrage abgelehnt';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($fid)) {
            db_query("DELETE FROM prefix_friendscheck WHERE uid = " . $fid . " AND fid = " . $uid);

            wd('?user-fcheck-' . $uid, 'Du hast die Freundschaftsanfrage abgelehnt.');
        } else {
            wd('?user-fcheck-' . $uid, 'Es ist ein Fehler aufgetreten');
        }

        $design->footer();
        break;

    case 'del':
        $title = $allgAr['title'] . ' :: Users :: Freund entfernen';
        $hmenu = $extented_forum_menu . '<a class="smalfont" href="?user">Users</a><b> &raquo; </b> Freund entfernen';
        $design = new design($title, $hmenu, 1);
        $design->header();

        if (loggedin() AND is_numeric($fid)) {
            db_query("DELETE FROM prefix_friends WHERE uid = " . $uid . " AND fid = " . $fid);
            db_query("DELETE FROM prefix_friends WHERE uid = " . $fid . " AND fid = " . $uid);

            wd('?user-details-' . $fid, 'Freund wurde entfernt.');
        } else {
            wd('?user-details-' . $fid, 'Es ist ein Fehler aufgetreten');
        }

        $design->footer();
        break;
}

?>