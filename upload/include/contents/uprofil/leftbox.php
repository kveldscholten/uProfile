<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);

$abfUG = 'SELECT * FROM prefix_usergallery WHERE uid = ' . $uid . ' ORDER BY id DESC LIMIT 0, 4';
$ergUG = db_query($abfUG);

$abfF = 'SELECT u.*, f.* FROM prefix_friends f LEFT JOIN prefix_user u ON u.id = f.fid WHERE f.uid = ' . $uid . ' ORDER BY f.id ASC LIMIT 0, 4';
$ergF = db_query($abfF);

$row = db_fetch_assoc($erg);

if ($row['gebdatum'] != "0000-00-00") {
    $gebdatum = '<img src="include/images/userprofil/icons/icon_birthday.png"> ' . date('d. M Y', strtotime($row['gebdatum'])) . '<br />';
} else {
    $gebdatum = '';
}

if (empty($row['wohnort'])) {
    $wohnort = '';
} else {
    $wohnort = '<img src="include/images/userprofil/icons/icon_home.png"> aus ' . $row['wohnort'] . '<br />';
}

$geschlecht = array('0' => 'Unbekannt', '1' => 'Männlich', '2' => 'Weiblich');
$geschlecht = '<img src="include/images/userprofil/icons/icon_user.png"> ' . $geschlecht[$row['geschlecht']];

$UGAnzahl = db_count_query('SELECT count(uid) FROM prefix_usergallery WHERE uid = ' . $uid);
$FAnzahl = db_count_query('SELECT count(uid) FROM prefix_friends WHERE uid = ' . $uid);
$GBAnzahl = db_count_query('SELECT count(uid) FROM prefix_usergbook WHERE uid = ' . $uid);

$tpl = new tpl('uprofil/leftbox.htm');

$ar = array(
    'UID' => $row['id'],
    'GEBURTSTAG' => $gebdatum,
    'WOHNORT' => $wohnort,
    'GESCHLECHT' => $geschlecht,
    'UGBILDER' => $UGAnzahl,
    'JOINED' => '<img src="include/images/userprofil/icons/icon_calendar.png"> ' . date('d. M Y', $row['regist']) . '<br />',
    'SITELINK' => $MPL,
);

$tpl->set_ar_out($ar, 0);

if ($UGAnzahl > 0) {
    $tpl->set_out('UGBILDER', ' (' . $UGAnzahl . ')', 1);

    while ($rowUG = db_fetch_assoc($ergUG)) {
        if (empty($rowUG['txt'])) {
            $rowUG['txt'] = $rowUG['name'];
        } else {
            $rowUG['txt'] = $rowUG['txt'];
        }

        $rowUG['BILDER'] = '<div class="boxUserFotos"><a href="include/images/usergallery/img_' . $rowUG['id'] . '.' . $rowUG['endung'] . '" target="_blank"><img src="include/images/usergallery/img_thumb_' . $rowUG['id'] . '.' . $rowUG['endung'] . '" title="' . $rowUG['txt'] . '" width="80px" height="80px"></a></div>';
        $tpl->set_ar_out($rowUG, 2);
    }

    $tpl->out(3);
}

if ($FAnzahl > 0) {
    $tpl->set_out('ZFRIENDS', ' (' . $FAnzahl . ')', 4);

    while ($rowF = db_fetch_assoc($ergF)) {
        if (file_exists($rowF['avatar'])) {
            $rowF['FRIENDA'] = '<div class="boxFriends"><a href="?user-details-' . $rowF['fid'] . '" title="' . $rowF['name'] . '"><img src="' . $rowF['avatar'] . '" title="' . $rowF['name'] . '" width="80px" heigt="80px;" class="boxFriendsAvatar"><div class="boxFriendsName">' . $rowF['name'] . '</div></a></div>';
        } else {
            $rowF['FRIENDA'] = '<div class="boxFriends"><a href="?user-details-' . $rowF['fid'] . '" title="' . $rowF['name'] . '"><img src="include/images/userprofil/avatar/nouser.png" title="' . $rowF['name'] . '" width="80px" heigt="80px;" class="boxFriendsAvatar"><div class="boxFriendsName">' . $rowF['name'] . '</div></a></div>';
        }
        $tpl->set_ar_out($rowF, 5);
    }

    $tpl->out(6);
}

?>