<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);
$row = db_fetch_assoc($erg);

$friendcheckAbf = @db_result(db_query("SELECT * FROM prefix_friendscheck WHERE uid = " . $_SESSION['authid'] . " AND fid = " . $uid), 0);
$friendcheckFID = @db_result(db_query("SELECT * FROM prefix_friendscheck WHERE fid = " . $_SESSION['authid'] . " AND uid = " . $uid), 0);
$friendAbf = @db_result(db_query("SELECT * FROM prefix_friends WHERE uid = " . $_SESSION['authid'] . " AND fid = " . $uid), 0);
$friendAnzahl = db_count_query('SELECT count(id) FROM prefix_friends WHERE uid = ' . $_SESSION['authid']);
$UGAnzahl = db_count_query('SELECT count(uid) FROM prefix_usergallery WHERE uid = ' . $uid);

if (file_exists($row['avatar'])) {
    $avatar = '<img src="' . $row['avatar'] . '" width="145" height="145" border="0">';
} else {
    $avatar = '<img src="include/images/userprofil/avatar/nouser.png" width="145" height="145" border="0">';
}

if (file_exists($row['titelbild'])) {
    $titelbild = 'background-image: url(' . $row['titelbild'] . ');';
} else {
    $titelbild = 'background-color: #185687;';
}

if ($row['geschlecht'] == 2) {
    $langHGeschlecht = 'Freundin';
} else {
    $langHGeschlecht = 'Freund';
}


if (loggedin()) {
    if ($friendAbf > 0) {
        $friendAddButton = '<div class="HeaderButton friendDel" onclick="location.href = \'?user-fcheck-del-' . $uid . '\';">Freunde</div>';
        $PNButton = '<div class="HeaderButton PN" onclick="location.href = \'?forum-privmsg-new=0&empfid=' . $uid . '\';">Nachricht senden</div>';
        $MoreButton = '<div class="HeaderButton BHMore">...</div>';
    } else if ($friendcheckAbf > 0) {
        $friendAddButton = '<div class="HeaderButton friendAddCheck" onclick="location.href = \'?user-fcheck-back-' . $uid . '\';">Freundschaftsanfrage versendet</div>';
        $PNButton = '<div class="HeaderButton PN" onclick="location.href = \'?forum-privmsg-new=0&empfid=' . $uid . '\';">Nachricht senden</div>';
        $MoreButton = '<div class="HeaderButton BHMore">...</div>';
    } else if ($friendcheckFID > 0) {
        $friendAddButton = '<div class="HeaderButton friendAddCheck" onclick="location.href = \'?user-fcheck-' . $_SESSION['authid'] . '\';">Freundschaftsanfrage erhalten</div>';
        $PNButton = '<div class="HeaderButton PN" onclick="location.href = \'?forum-privmsg-new=0&empfid=' . $uid . '\';">Nachricht senden</div>';
        $MoreButton = '<div class="HeaderButton BHMore">...</div>';
    } else if ($_SESSION['authid'] == $uid) {
        $friendAddButton = '';
        $PNButton = '';
        $MoreButton = '';
    } else {
        $friendAddButton = '<div class="HeaderButton friendAddCheck" onclick="location.href = \'?user-fcheck-add-' . $uid . '\';">' . $langHGeschlecht . ' hinzufügen</div>';
        $PNButton = '<div class="HeaderButton PN" onclick="location.href = \'?forum-privmsg-new=0&empfid=' . $uid . '\';">Nachricht senden</div>';
        $MoreButton = '<div class="HeaderButton BHMore">...</div>';
    }


    if ($uid == $_SESSION['authid']) {
        $friendsCheckCount = db_count_query('SELECT count(uid) FROM prefix_friendscheck WHERE fid = ' . $uid);
        if ($friendsCheckCount > 0) {
            $friendsCheckDiv = '<div class="fCheckText">' . $friendsCheckCount . '</div>';
        } else {
            $friendsCheckDiv = '';
        }

        $friendsCheck = '<div class="button fCheck" onclick="location.href = \'?user-fcheck-' . $uid . '\';">
              <div class="fCheckIcon">
                ' . $friendsCheckDiv . '
              </div>
              </div>';
        
        if ($friendAnzahl > 0) {
            $optionsClass = 'nOptions2';
        } else {
            $optionsClass = 'nOptions';
        }
            $options = '<div id="mOptions" class="button ' . $optionsClass . '">
              <div class="pfeilDownIcon"></div>
              </div>';
    }
}

if (db_count_query('SELECT count(uid) FROM prefix_usergallery WHERE uid = ' . $uid) > '0' or $_SESSION['authid'] == $uid) {
    $fotos = '<div class="button foto" onclick="location.href = \'?user-fotos-' . $uid . '\';">
              <div class="buttonText">Fotos</div>
              </div>';
}

if ($UGAnzahl > 0 OR $_SESSION['authid'] == $uid) {
    $friendClass = 'friend2';
} else {
    $friendClass = 'friend';
}
if (db_count_query('SELECT count(uid) FROM prefix_friends WHERE uid = ' . $uid)) {
    $friends = '<div class="button ' . $friendClass . '" onclick="location.href = \'?user-friends-' . $uid . '\';">
              <div class="buttonText">Freunde</div>
              </div>';
}

$arHeader = array(
    "UID" => $row['id'],
    'NAME' => $row['name'],
    'TITELBILD' => $titelbild,
    'sFriendschek' => $friendsCheck,
    'sOpstions' => $options,
    'AVATA' => $avatar,
    'FRIENDADDBUTTON' => $friendAddButton,
    'PNBUTTON' => $PNButton,
    'MOREBUTTON' => $MoreButton,
    'AVATA' => $avatar,
    'sFotos' => $fotos,
    'sFriends' => $friends,
);

$tpl = new tpl('uprofil/header.htm');

$tpl->set_ar_out($arHeader, 0);

?>