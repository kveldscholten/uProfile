<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);
$row = db_fetch_assoc($erg);

$sessionu_abf = db_fetch_assoc(db_query('SELECT * FROM prefix_user WHERE id = ' . $_SESSION['authid']));

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

$tpl = new tpl('uprofil/info.htm');

$geb_tag = date('d', strtotime($row['gebdatum']));
$geb_mon = date('n', strtotime($row['gebdatum']));

function sternzeichen_generieren($geb_tag, $geb_mon) {
    $sternzeichen[1] = "Widder";
    $sternzeichen[2] = "Stier";
    $sternzeichen[3] = "Zwillinge";
    $sternzeichen[4] = "Krebs";
    $sternzeichen[5] = "Löwe";
    $sternzeichen[6] = "Jungfrau";
    $sternzeichen[7] = "Waage";
    $sternzeichen[8] = "Skorpion";
    $sternzeichen[9] = "Schütze";
    $sternzeichen[10] = "Steinbock";
    $sternzeichen[11] = "Wassermann";
    $sternzeichen[12] = "Fische";
    if (($geb_mon == 3 && $geb_tag >= 21) || ($geb_mon == 4 && $geb_tag <= 20)) {
        $sternz = 1;
    } elseif (($geb_mon == 4 && $geb_tag >= 21) || ($geb_mon == 5 && $geb_tag <= 20)) {
        $sternz = 2;
    } elseif (($geb_mon == 5 && $geb_tag >= 21) || ($geb_mon == 6 && $geb_tag <= 21)) {
        $sternz = 3;
    } elseif (($geb_mon == 6 && $geb_tag >= 22) || ($geb_mon == 7 && $geb_tag <= 22)) {
        $sternz = 4;
    } elseif (($geb_mon == 7 && $geb_tag >= 23) || ($geb_mon == 8 && $geb_tag <= 23)) {
        $sternz = 5;
    } elseif (($geb_mon == 8 && $geb_tag >= 24) || ($geb_mon == 9 && $geb_tag <= 23)) {
        $sternz = 6;
    } elseif (($geb_mon == 9 && $geb_tag >= 24) || ($geb_mon == 10 && $geb_tag <= 23)) {
        $sternz = 7;
    } elseif (($geb_mon == 10 && $geb_tag >= 24) || ($geb_mon == 11 && $geb_tag <= 22)) {
        $sternz = 8;
    } elseif (($geb_mon == 11 && $geb_tag >= 23) || ($geb_mon == 12 && $geb_tag <= 21)) {
        $sternz = 9;
    } elseif (($geb_mon == 12 && $geb_tag >= 22) || ($geb_mon == 1 && $geb_tag <= 21)) {
        $sternz = 10;
    } elseif (($geb_mon == 1 && $geb_tag >= 21) || ($geb_mon == 2 && $geb_tag <= 19)) {
        $sternz = 11;
    } elseif (($geb_mon == 2 && $geb_tag >= 20) || ($geb_mon == 3 && $geb_tag <= 20)) {
        $sternz = 12;
    }
    return $sternzeichen[$sternz];
}

$monate = array(1 => "Januar",
    2 => "Februar",
    3 => "M&auml;rz",
    4 => "April",
    5 => "Mai",
    6 => "Juni",
    7 => "Juli",
    8 => "August",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Dezember");

if ($row['gebdatum'] != "0000-00-00") {
    $gebdatum = '<div id="infoAngabe">Geburtsdatum</div> ' . $geb_tag . '. ' . $monate[$geb_mon] . '<br />';
    $gebjahr = '<div id="infoAngabe">Geburtsjahr</div> ' . date('Y', strtotime($row['gebdatum'])) . '<br />';
    $sternzeichen = '<div id="infoAngabe">Sternzeichen</div> ' . sternzeichen_generieren($geb_tag, $geb_mon) . ' <img src="include/images/userprofil/sternzeichen/' . sternzeichen_generieren($geb_tag, $geb_mon) . '.png" alt="' . sternzeichen_generieren($geb_tag, $geb_mon) . '" /><br />';
} else {
    $gebdatum = '';
    $gebjahr = '';
    $sternzeichen = '';
}

if (!empty($row['staat']) AND file_exists('include/images/flags/' . $row['staat'])) {
    $staat = '<img src="include/images/flags/' . $row['staat'] . '" alt="" />';

    $position_des_letzten_punktes = strrpos($row['staat'], '.');
    $row['staat'] = ', ' . substr($row['staat'], 0, $position_des_letzten_punktes);
} else {
    $staat = '';
}

if (empty($row['wohnort'])) {
    $wohnort = '';
} else {
    $wohnort = '<div id="infoAngabe">Aktueller Wohnort</div> <a href="https://www.google.de/maps/search/' . $row['wohnort'] . $row['staat'] . '" target="_blank">' . $row['wohnort'] . '</a> ' . $staat . '<div id="trennlinie"></div>';
}

if ($row['status'] == '1') {
    $status = 'Aktiv';
} else {
    $status = 'Inaktiv';
}

if ($row['homepage'] == !'') {
    $homepage = '<div id="trennlinie"></div> <div id="infoAngabe">Website</div> <a href="' . $row['homepage'] . '" target="_blank">' . $row['homepage'] . '</a>';
}

if ($row['geschlecht'] == '1') {
    $iconGeschlecht = '<img src="include/images/userprofil/icons/icon_male.png" alt="Männlich" />';
} else if ($row['geschlecht'] == '2') {
    $iconGeschlecht = '<img src="include/images/userprofil/icons/icon_female.png" alt="Weiblich" />';
} else {
    $iconGeschlecht = '';
}

$geschlecht = array('0' => 'Unbekannt', '1' => 'Männlich', '2' => 'Weiblich');
$geschlecht = $geschlecht[$row['geschlecht']] . ' ' . $iconGeschlecht;

if ($_SESSION['authid'] == $uid) {
    $edit = '<span><img src="include/images/userprofil/icons/icon_edit.png"> <a href="index.php?user-8">Bearbeten</a></span>';
} else {
    $edit = '';
}

if ($row['opt_mail'] > 0 AND $sessionu_abf['opt_mail'] > 0) {
    $email = '<div id="infoAngabe">E-Mail-Adresse</div> <a href="?user-mail-' . $uid . '">E-Mail senden</a><br />';
} else {
    $email = '';
}

if (loggedin() AND $row['opt_pm'] > 0 AND $sessionu_abf['opt_pm'] > 0) {
    $pn = '<div id="infoAngabe">Privat Nachricht</div> <a href="index.php?forum-privmsg-new=0&amp;empfid=' . $uid . '">senden</a><br />';
} else {
    $pn = '';
}

if ($row['icq'] == '' or $row['icq'] == '0') {
    $icq = '';
} else {
    $icq = '<div id="infoAngabe">ICQ-Messenger</div> <a href="http://www.icq.com/people/' . $row['icq'] . '/" target="_blank">ICQ-Nachricht senden</a><br />';
}

if (!empty($row['msn'])) {
    $msn = '<div id="infoAngabe">MSN-Messenger</div> ' . $row['msn'] . '<br />';
} else {
    $msn = '';
}

if (!empty($row['yahoo'])) {
    $yahoo = '<div id="infoAngabe">YAHOO-Messenger</div> ' . $row['yahoo'] . '<br />';
} else {
    $yahoo = '';
}

if (!empty($row['aim'])) {
    $aim = '<div id="infoAngabe">AIM-Messenger</div> ' . $row['aim'] . '<br />';
} else {
    $aim = '';
}

if (!empty($email) OR !empty($pn) OR !empty($icq) OR !empty($msn) OR !empty($yahoo) OR !empty($aim)) {
    $kontakt = '<div id="contentBigTop"><div class="HeadText"><a href="">KONTAKT</a>' . $edit . '</div></div>
<div id="contentBig">
    <div class="contenText">
        ' . $email . '
        ' . $pn . '
        ' . $icq . '
        ' . $msn . '
        ' . $yahoo . '
        ' . $aim . '
    </div>
</div>';
}

if (!empty($row['sig'])) {
    $signatur = '<div id="contentBigTop"><div class="HeadText"><a href="">SIGNATUR</a>' . $edit . '</div></div>
<div id="contentBig">
    <div class="contenText">
        ' . bbcode($row['sig']) . '
    </div>
</div>';
}

$ar = array(
    'JOINED' => date('d M Y', $row['regist']),
    'LASTAK' => date('d M Y - H:i', $row['llogin']),
    'POSTS' => $row['posts'],
    'postpday' => $postpday,
    'RANG' => userrang($row['posts'], $uid),
    'GEBURTSDATUM' => $gebdatum,
    'GEBURTSJAHR' => $gebjahr,
    'STERNZEICHEN' => $sternzeichen,
    'WOHNORT' => $wohnort,
    'GESCHLECHT' => $geschlecht,
    'SIGNATUR' => $signatur,
    'STATUS' => $status,
    'HOMEPAGE' => $homepage,
    'KONTAKT' => $kontakt,
    'EDIT' => $edit,
);

$tpl->set_ar_out($ar, 0);

$design->footer();

?>