<?php

session_start();

if($_SESSION['userID'] != 1) {
    header("Location: ../index.php");
}

if(!empty($_POST['provinceSelect'])) {
    if(isset($_SESSION['start'])) {
        header("Location: ../../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&province=".$_POST['provinceSelect']."&start=".$_SESSION['start']."&p=1");
    } else {
        header("Location: ../../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&province=".$_POST['provinceSelect']."&p=1");
    }
} else {
    if(isset($_SESSION['start'])) {
        header("Location: ../../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&start=".$_SESSION['start']."&p=1");
    } else {
        header("Location: ../../admin/admin.php?section=users&action=maillist&active=".$_SESSION['active']."&p=1");
    }
}