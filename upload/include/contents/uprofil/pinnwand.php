<?php

defined('main') or die('no direct access');

$uid = intval($menu->get(2));

$limit = 5;  // Limit
$page = ($menu->getA(3) == 'p' ? $menu->getE(3) : 1 );
$anfang = ($page - 1) * $limit;
$MPL = db_make_sites($page, 'WHERE uid = ' . $uid, $limit, '?user-details-' . $uid, 'usergbook');

$abfGB = 'SELECT * FROM prefix_usergbook WHERE uid = ' . $uid . ' ORDER BY datetime DESC LIMIT ' . $anfang . ',' . $limit;
$ergGB = db_query($abfGB);

$GBAnzahl = db_count_query('SELECT count(uid) FROM prefix_usergbook WHERE uid = ' . $uid);

$tpl = new tpl('uprofil/pinnwand.htm');

if (loggedin()) {
    if ($_SESSION['authid'] == $uid) {
        $textareaStatus = 'Schreib, was Du gerade machst.';
    } else {
        $textareaStatus = 'Hinterlasse eine Nachricht.';
    }

    // gibt die Smilies zurück
    function getPinnwadSmilies() {
        global $lang;
        $i = 0;
        $b = '<script language="JavaScript" type="text/javascript">function moreSmilies () { var x = window.open("about:blank", "moreSmilies", "width=250,height=200,status=no,scrollbars=yes,resizable=yes"); ';
        $a = '';
        $erg = db_query('SELECT emo, ent, url FROM `prefix_smilies`');
        while ($row = db_fetch_object($erg)) {

            $b .= 'x.document.write ("<a href=\"javascript:opener.put(\'' . addslashes(addslashes($row->ent)) . '\')\">");';
            $b .= 'x.document.write ("<img style=\"border: 0px; padding: 5px;\" src=\"include/images/smiles/' . $row->url . '\" title=\"' . $row->emo . '\"></a>");';

            if ($i < 15) {
                # float einbauen
                $a .= '<a href="javascript:put(\'' . addslashes($row->ent) . '\')">';
                $a .= '<img style="margin: 4px;" src="include/images/smiles/' . $row->url . '" border="0" title="' . $row->emo . '"></a>';
            }
            $i++;
        }
        $b .= ' x.document.write("<br /><br /><center><a href=\"javascript:window.close();\">' . $lang['close'] . '</a></center>"); x.document.close(); }</script>';
        if ($i > 15) {
            $a .= '<center><a href="javascript:moreSmilies();">' . $lang['more'] . '</a></center>';
        }
        $a = $b . $a;
        return ($a);
    }

    $tpl->set_ar_out(array('TEXTAREASTATUS' => $textareaStatus, 'SMILIES' => getPinnwadSmilies(), 'UID' => $uid), 0);
}

if ($GBAnzahl > 0) {
    while ($rowGB = db_fetch_assoc($ergGB)) {
        $rowGB['ID'] = $rowGB['id'];
        $rowGB['UID'] = $uid;
        $rowGB['SID'] = $rowGB['sid'];
        $rowGB['ERSTELLER'] = db_result(db_query("SELECT name FROM prefix_user WHERE id = " . $rowGB['sid']), 0, 0);
        $rowGB['EINTRAG'] = bbcode($rowGB['txt']);
        $rowGB['DATETIME'] = date('d. M Y - H:i', $rowGB['datetime']);

        //////////////////POST KOMMETARE////////////////////////////////////
        $GBKAnzahl = db_count_query('SELECT count(id) FROM prefix_usergbook_koms WHERE gbid = ' . $rowGB['ID']);
        $abfGBK = 'SELECT * FROM (SELECT * FROM prefix_usergbook_koms WHERE gbid = ' . $rowGB['ID'] . ' ORDER BY datetime DESC LIMIT 3) a ORDER BY datetime ASC';
        $abfGBKALL = 'SELECT * FROM (SELECT * FROM prefix_usergbook_koms WHERE gbid = ' . $rowGB['ID'] . ' ORDER BY datetime DESC LIMIT 3,' . $GBKAnzahl . ') a ORDER BY datetime ASC';
        $ergGBK = db_query($abfGBK);
        $ergGBKALL = db_query($abfGBKALL);

        @$komsavatar = db_result(db_query("SELECT avatar FROM prefix_user WHERE id = " . $_SESSION['authid']), 0, 0);

        if (file_exists($komsavatar)) {
            $komsavatar = '<img src="' . $komsavatar . '" width="35" height="35" border="0">';
        } else {
            $komsavatar = '<img src="include/images/userprofil/avatar/nouser.png" width="35" height="35" border="0">';
        }

        $rowGB['KOMAVATAR'] = $komsavatar;

        if ($GBKAnzahl > 0) {
            while ($rowGBK = db_fetch_assoc($ergGBK)) {
                $komauthor = db_result(db_query("SELECT name FROM prefix_user WHERE id = " . $rowGBK['uid']), 0, 0);
                $komavatar = db_result(db_query("SELECT avatar FROM prefix_user WHERE id = " . $rowGBK['uid']), 0, 0);
                $komtime = date('d. M Y - H:i', $rowGBK['datetime']);

                if (file_exists($komavatar)) {
                    $komavatar = '<img src="' . $komavatar . '" width="35" height="35" border="0">';
                } else {
                    $komavatar = '<img src="include/images/userprofil/avatar/nouser.png" width="35" height="35" border="0">';
                }

                if ($_SESSION['authid'] == $uid) {
                    $komdel = '<div class="KomsDel"><span><a href="javascript:delPWKcheck(' . $uid . ',' . $rowGBK['id'] . ')"><img src="include/images/userprofil/icons/icon_remove.png"></a></div><span>';
                } else {
                    $komdel = '';
                }

                $rowGB['KOM'] .= '<div id="cKoms-Index">
                        <div class="KomsBild">' . $komavatar . '</div> 
                        <div class="KomsInfo">
                        <a href="?user-details-' . $rowGBK['uid'] . '">' . $komauthor . '</a> ' . bbcode($rowGBK['txt']) . '<br />
                        <div class="KomsDate">' . $komtime . '</div>
                        </div> 
                        ' . $komdel . '
                        <br clear="all">
                        </div> ';
            }

            //////ALLE KOMMENTARE/////////////
            if ($GBKAnzahl > 3) {
                while ($rowGBKALL = db_fetch_assoc($ergGBKALL)) {

                    $komauthorall = db_result(db_query("SELECT name FROM prefix_user WHERE id = " . $rowGBKALL['uid']), 0, 0);
                    $komavatarall = db_result(db_query("SELECT avatar FROM prefix_user WHERE id = " . $rowGBKALL['uid']), 0, 0);
                    $komtimeall = date('d. M Y - H:i', $rowGBKALL['datetime']);

                    if (file_exists($komavatarall)) {
                        $komavatarall = '<img src="' . $komavatarall . '" width="35" height="35" border="0">';
                    } else {
                        $komavatarall = '<img src="include/images/userprofil/avatar/nouser.png" width="35" height="35" border="0">';
                    }

                    if ($_SESSION['authid'] == $uid) {
                        $komdelall = '<div class="KomsDel"><span><a href="javascript:delPWKcheck(' . $uid . ',' . $rowGBKALL['id'] . ')"><img src="include/images/userprofil/icons/icon_remove.png"></a></div><span>';
                    } else {
                        $komdelall = '';
                    }
                    $GBAllAnzahl = $GBKAnzahl - 3;
                    $rowGB['VISITALL'] = '<div class="allKomss" onclick="toggle_visibility(\'komAll' . $rowGB['id'] . '\');"><img src="include/images/userprofil/icons/icon_comments-all.png" width="16" height="16" border="0" style="vertical-align: middle;"> ' . $GBAllAnzahl . ' weitere Kommentare anzeigen</div>';

                    $rowGB['KOMALL'] .= '<div id="cKoms-Index">
                            <div class="KomsBild">' . $komavatarall . '</div> 
                            <div class="KomsInfo">
                            <a href="?user-details-' . $rowGBKALL['uid'] . '">' . $komauthorall . '</a> ' . bbcode($rowGBKALL['txt']) . '<br />
                            <div class="KomsDate">' . $komtimeall . '</div>
                            </div> 
                            ' . $komdelall . '
                            <br clear="all">
                            </div> ';
                }
            } else {
                $rowGB['VISITALL'] = '';
            }
        } else {
            $rowGB['KOM'] = '';
            $rowGB['VISITALL'] = '';
        }
        /////////////////////////////ENDE POST KOMMENTARE//////////////////////////////////

        $savatar = db_result(db_query("SELECT avatar FROM prefix_user WHERE id = " . $rowGB['sid']), 0, 0);
        if (file_exists($savatar)) {
            $rowGB['SAVATAR'] = '<img src="' . $savatar . '" width="45" height="45" border="0">';
        } else {
            $rowGB['SAVATAR'] = '<img src="include/images/userprofil/avatar/nouser.png" width="45" height="45" border="0">';
        }

        if ($_SESSION['authid'] == $uid) {
            $rowGB['DEL'] = '<div class="PinnwandDel"><span><a href="javascript:delPWcheck(' . $uid . ',' . $rowGB['id'] . ')"><img src="include/images/userprofil/icons/icon_remove.png"></a></div><span>';
        } else {
            $rowGB['DEL'] = '';
        }

        $tpl->set_ar_out($rowGB, 1);
    }
}

$tpl->set_out('SITELINK', $MPL, 2);

?>