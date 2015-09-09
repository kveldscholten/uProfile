<?php

defined('main') or die('no direct access');

if ($allgAr['forum_usergallery'] == 0) {
    exit();
}

$uid = escape($menu->get(2), 'integer');
$img_per_site = $allgAr['gallery_imgs_per_site'];
$img_per_line = $allgAr['gallery_imgs_per_line'];

$abf = 'SELECT * FROM prefix_user WHERE id = ' . $uid;
$erg = db_query($abf);
$row = db_fetch_assoc($erg);

# user gallery zeigen
$uname = db_result(db_query("SELECT name FROM prefix_user WHERE id = " . $uid), 0, 0);

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

$tpl = new tpl('uprofil/fotos');
$tpl->set('uid', $uid);
$tpl->set('uname', $uname);

# bild loeschen...
if ($menu->getA(4) == 'd' AND is_numeric($menu->getE(4)) AND loggedin() AND (is_siteadmin() OR $uid == $_SESSION['authid'])) {
    $delid = escape($menu->getE(4), 'integer');
    $x = @db_result(db_query("SELECT endung FROM prefix_usergallery WHERE uid = " . $uid . " AND id = " . $delid), 0, 0);
    if (!empty($x)) {
        @unlink('include/images/usergallery/img_thumb_' . $delid . '.' . $x);
        @unlink('include/images/usergallery/img_' . $delid . '.' . $x);
        @db_query("DELETE FROM prefix_usergallery WHERE uid = " . $uid . " AND id = " . $delid);
    }
}

# bild hochladen
if (!empty($_FILES['file']['name']) AND is_writeable('include/images/usergallery') AND loggedin() AND $uid == $_SESSION['authid'] AND substr(ic_mime_type($_FILES['file']['tmp_name']), 0, 6) == 'image/') {
    require_once('include/includes/func/gallery.php');
    $size = @getimagesize($_FILES['file']['tmp_name']);
    $fende = preg_replace("/.+\.([a-zA-Z]+)$/", "\\1", $_FILES['file']['name']);
    $fende = strtolower($fende);
    if (!empty($_FILES['file']['name']) AND $size[0] > 10 AND $size[1] > 10 AND ($size[2] == 2 OR $size[2] == 3 OR $size[2] == 1) AND ($fende == 'gif' OR $fende == 'jpg' OR $fende == 'jpeg' OR $fende == 'png')) {
        $name = $_FILES['file']['name'];
        $tmp = explode('.', $name);
        $tm1 = count($tmp) - 1;
        $endung = escape($tmp[$tm1], 'string');
        unset($tmp[$tm1]);
        $name = escape(implode('', $tmp), 'string');
        $besch = escape($_POST['text'], 'string');
        $id = db_result(db_query("SHOW TABLE STATUS FROM `" . DBDATE . "` LIKE 'prefix_usergallery'"), 0, 'Auto_increment');
        $bild_url = 'include/images/usergallery/img_' . $id . '.' . $endung;
        if (@move_uploaded_file($_FILES['file']['tmp_name'], $bild_url)) {
            @chmod($bild_url, 0777);
            db_query("INSERT INTO prefix_usergallery (uid,name,endung,besch) VALUES (" . $uid . ",'" . $name . "','" . $endung . "','" . $besch . "')");
            $bild_thumb = 'include/images/usergallery/img_thumb_' . $id . '.' . $endung;
            create_thumb($bild_url, $bild_thumb, '120');
            @chmod($bild_thumb, 0777);
            echo '<div id="contentBigTop"><div class="HeadText"><a href="">Hochgeladen</a></div></div>';
            echo '<div id="contentBig">';
            echo '<div class="contenText">';
            echo '<b>Datei ' . $name . '.' . $endung . ' erfolgreich hochgeladen</b><br />';
            $page = $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);
            echo 'Bildlink: <a target="_blank" href="http://' . $page . '/' . $bild_url . '">http://' . $page . '/' . $bild_url . '</a><br />';
            echo 'Oder klein: <a target="_blank" href="http://' . $page . '/' . $bild_thumb . '">http://' . $page . '/' . $bild_thumb . '</a>';
            echo '</div>';
            echo '</div>';
        }
    }
}

# bilder abfragen
$limit = $img_per_site;
$page = ($menu->getA(3) == 'p' ? $menu->getE(3) : 1 );
$MPL = db_make_sites($page, '', $limit, 'index.php?user-fotos-' . $uid, "usergallery WHERE uid = " . $uid);
$anfang = ($page - 1) * $limit;
$erg = db_query("SELECT name, besch, endung, id FROM prefix_usergallery WHERE uid = " . $uid . " ORDER BY id DESC LIMIT " . $anfang . "," . $limit);

$tpl->set('imgperline', $allgAr['gallery_imgs_per_line']);
$tpl->set('MPL', $MPL);
$tpl->out(0);
$class = 'Cnorm';
$i = 0;
if (db_num_rows($erg) > 0) {
    while ($row = db_fetch_assoc($erg)) {
        $class = ( $class == 'Cmite' ? 'Cnorm' : 'Cmite' );
        $row['class'] = $class;
        $row['besch'] = unescape($row['besch']);
        if (loggedin() AND (is_siteadmin() OR $uid == $_SESSION['authid'])) {
            $row['besch'] .= '<a href="index.php?user-fotos-' . $uid . '-p' . $page . '-d' . $row['id'] . '"><img src="include/images/icons/del.gif" border="0" alt="l&ouml;schen" title="l&ouml;schen" /></a>';
        }
        $row['width'] = round(100 / $img_per_line);
        if ($i <> 0 AND ($i % $img_per_line ) == 0) {
            echo '</tr><tr>';
        }
        $tpl->set_ar_out($row, 1);
        $i++;
    }
    if ($i % $img_per_line <> 0) {
        $anzahl = $img_per_line - ($i % $img_per_line);
        for ($x = 1; $x <= $anzahl; $x++) {
            echo '<td class="' . $class . '"></td>';
        }
    }
}
$tpl->out(2);

# bild hochladen
if (is_writeable('include/images/usergallery') AND loggedin() AND $uid == $_SESSION['authid']) {
    $tpl->out(3);
}

$design->footer();

?>