<?php

#   Copyright by: Manuel
#   Support: www.ilch.de

defined('main') or die('no direct access');

switch ($menu->get(1)) {
    default : $userDatei = 'user/memb_list';
        break;
    case 'regist' : case 1 : $userDatei = 'user/regist';
        break;
    case 'confirm' : $userDatei = 'user/confirm';
        break;
    case 'login' : case 2 : $userDatei = 'user/login';
        break;
    case 'logout' : case 3 : $userDatei = 'user/logout';
        break;
    case 'mail' : case 4 : $userDatei = 'user/mail';
        break;
    case 'usergallery' : $userDatei = 'user/usergallery';
        break;
    case 'usergallery_upload' : $userDatei = 'user/usergallery_upload';
        break;
    case 'profil' : case 8 : $userDatei = 'user/profil_edit';
        break;
    case 'remind' : case 13 : $userDatei = 'user/password_reminder';
        break;
    
    // uProfil
    case 'details' : case 6 : $userDatei = 'uprofil/default';
        break;
    case 'info' : $userDatei = 'uprofil/info';
        break;
    case 'fotos' : $userDatei = 'uprofil/fotos';
        break;
    case 'friends' : $userDatei = 'uprofil/friends';
        break;

    // Settings
    case 'privatsphaere' : $userDatei = 'uprofil/settings/privatsphaere';
        break;
    case 'blockierung' : $userDatei = 'uprofil/settings/blockierung';
        break;

    // Check
    case 'allgcheck' : $userDatei = 'uprofil/check/allgcheck';
        break;
    case 'gbcheck' : $userDatei = 'uprofil/check/gbcheck';
        break;
    case 'fcheck' : $userDatei = 'uprofil/check/fcheck';
        break;
}

require_once('include/contents/' . $userDatei . '.php');

?>