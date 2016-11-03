<?php
	session_start();
    include('../scripts/connect.php');

    if(!isset($_SESSION['adminAccess'])) {
        $_SESSION['adminAccess'] = '1';
    }

	if(empty($_SESSION['userID']) or $_SESSION['userID'] != 1)
	{
        $_SESSION['redirect'] = '1';

		header("Location: ../index.php");
	}

    if(!empty($_REQUEST['section']) and $_REQUEST['section'] != 'goods' and $_REQUEST['section'] != 'categories' and $_REQUEST['section'] != 'users')
    {
        header("Location: admin.php");
    }

    if(empty($_REQUEST['section']) and !empty($_REQUEST['action']))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['action']) and $_REQUEST['action'] != 'add' and $_REQUEST['action'] != 'edit' and $_REQUEST['action'] != 'delete' and $_REQUEST['action'] != 'mail' and $_REQUEST['action'] != 'users' and $_REQUEST['action'] != 'news' and $_REQUEST['action'] != 'maillist' and $_REQUEST['action'] != 'addNews' and $_REQUEST['action'] != 'mail-history')
    {
        header("Location: admin.php?section=".$_REQUEST['section']);
    }

    if(!empty($_REQUEST['type']) and (empty($_REQUEST['action']) or empty($_REQUEST['section'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['type']) and $_REQUEST['type'] !='fa' and $_REQUEST['type'] != 'em' and $_REQUEST['type'] != 'ca')
    {
        header("Location: admin.php?section=".$_REQUEST['section']."&action=".$_REQUEST['action']);
    }

    if(!empty($_REQUEST['c']) and (empty($_REQUEST['section']) or empty($_REQUEST['action']) or empty($_REQUEST['type'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['s']) and (empty($_REQUEST['section']) or empty($_REQUEST['action']) or empty($_REQUEST['type']) or empty($_REQUEST['c'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['s2']) and (empty($_REQUEST['section']) or empty($_REQUEST['action']) or empty($_REQUEST['type']) or empty($_REQUEST['c']) or empty($_REQUEST['s'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['id']))
    {
        $cGoodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
        $cGood = $cGoodResult->fetch_assoc();

        if((!empty($cGood['s']) and $cGood['s'] != 0 and empty($_REQUEST['s'])) or (!empty($cGood['s2']) and $cGood['s2'] != 0 and empty($_REQUEST['s2'])))
        {
            header("Locattion: admin.php");
        }
    }

    if(!empty($_REQUEST['level']) and $_REQUEST['level'] !='1' and $_REQUEST['level'] != '2' and $_REQUEST['level'] != '3')
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['p']) and (empty($_REQUEST['section']) or empty($_REQUEST['action'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['p']) and ($_REQUEST['action'] != 'maillist' and $_REQUEST['action'] != 'users' and $_REQUEST['action'] != 'news' and $_REQUEST['action'] != 'mail-history'))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['user']) and (empty($_REQUEST['section']) or $_REQUEST['action'] != "users"))
    {
        header("Location: admin.php");
    }
    
    if(!empty($_REQUEST['news']) and (empty($_REQUEST['section']) or empty($_REQUEST['action'])))
    {
        header("Location: admin.php");
    }

    if(!empty($_REQUEST['user']))
    {
        $usersCountResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE id = '".$_REQUEST['user']."'");
        if($usersCountResult->num_rows == 0)
        {
            header("Location: admin.php");
        }
    }

    if($_REQUEST['action'] == "maillist" and empty($_REQUEST['active']))
    {
        header("Location: admin.php?section=users&action=maillist&active=true&p=1");
    }

    if(!empty($_REQUEST['active']) and $_REQUEST['active'] != 'true' and $_REQUEST['active'] != 'false')
    {
        header("Location: admin.php?section=users&action=maillist&active=true&p=1");
    }

    if(!empty($_REQUEST['start']) and strlen($_REQUEST['start']) != 1 )
    {
        header("Location: admin.php?section=users&action=maillist&active=true&p=1");
    }

    if(!empty($_REQUEST['province']) and $_REQUEST['province'] != '1' and $_REQUEST['province'] != '2' and $_REQUEST['province'] != '3' and $_REQUEST['province'] != '4' and $_REQUEST['province'] != '5' and $_REQUEST['province'] != '6' and $_REQUEST['province'] != '7' and $_REQUEST['province'] != '8') {
        header("Location: admin.php?section=users&action=maillist&active=true&p=1");
    }

    if(!empty($_REQUEST['section']))
    {
        $_SESSION['section'] = $_REQUEST['section'];
    }
    else
    {
        unset($_SESSION['section']);
    }

    if(!empty($_REQUEST['action']))
    {
        $_SESSION['action'] = $_REQUEST['action'];
    }
    else
    {
        unset($_SESSION['action']);
    }

    if(!empty($_REQUEST['type']))
    {
        $_SESSION['type'] = $_REQUEST['type'];
    }
    else
    {
        unset($_SESSION['type']);
    }

    if(!empty($_REQUEST['c']))
    {
        $_SESSION['c'] = $_REQUEST['c'];
    }
    else
    {
        unset($_SESSION['c']);
    }

    if(!empty($_REQUEST['s']))
    {
        $_SESSION['s'] = $_REQUEST['s'];
    }
    else
    {
        unset($_SESSION['s']);
    }

    if(!empty($_REQUEST['s2']))
    {
        $_SESSION['s2'] = $_REQUEST['s2'];
    }
    else
    {
        unset($_SESSION['s2']);
    }

    if(!empty($_REQUEST['id']))
    {
        $_SESSION['id'] = $_REQUEST['id'];
    }
    else
    {
        unset($_SESSION['id']);
    }

    if(!empty($_REQUEST['level']))
    {
        $_SESSION['level'] = $_REQUEST['level'];
    }
    else
    {
        unset($_SESSION['level']);
    }

    if(!empty($_REQUEST['p']))
    {
        $_SESSION['p'] = $_REQUEST['p'];
    }
    else
    {
        unset($_SESSION['p']);
    }

    if(!empty($_REQUEST['user']))
    {
        $_SESSION['user'] = $_REQUEST['user'];
    }
    else
    {
        unset($_SESSION['user']);
    }

    if(!empty($_REQUEST['news']))
    {
        $_SESSION['news'] = $_REQUEST['news'];
    }
    else
    {
        unset($_SESSION['news']);
    }

    if(!empty($_REQUEST['active']))
    {
        $_SESSION['active'] = $_REQUEST['active'];
    }
    else
    {
        unset($_SESSION['active']);
    }

    if(!empty($_REQUEST['c']))
    {
        $cResult = $mysqli->query("SELECT COUNT(id) from categories_new WHERE id = '".$_REQUEST['c']."'");
        $c = $cResult->fetch_array(MYSQLI_NUM);

        if($c[0] == 0)
        {
            header("Location: admin.php?section=".$_REQUEST['section']."&action=".$_REQUEST['action']."&type=".$_REQUEST['type']);
        }
    }

    if(!empty($_REQUEST['s']))
    {
        $sResult = $mysqli->query("SELECT COUNT(id) from subcategories_new WHERE id = '".$_REQUEST['s']."'");
        $s = $sResult->fetch_array(MYSQLI_NUM);

        if($s[0] == 0)
        {
            header("Location: admin.php?section=".$_REQUEST['section']."&action=".$_REQUEST['action']."&type=".$_REQUEST['type']."&c=".$_REQUEST['c']);
        }
    }

    if(!empty($_REQUEST['s2']))
    {
        $s2Result = $mysqli->query("SELECT COUNT(id) from subcategories2 WHERE id = '".$_REQUEST['s2']."'");
        $s2 = $s2Result->fetch_array(MYSQLI_NUM);

        if($s2[0] == 0)
        {
            header("Location: admin.php?section=".$_REQUEST['section']."&action=".$_REQUEST['action']."&type=".$_REQUEST['type']."&c=".$_REQUEST['c']."&s=".$_REQUEST['s']);
        }
    }

    if(!empty($_REQUEST['id']))
    {
        $gResult = $mysqli->query("SELECT COUNT(id) from catalogue_new WHERE id = '".$_REQUEST['id']."'");
        $g = $gResult->fetch_array(MYSQLI_NUM);

        if($g[0] == 0)
        {
            header("Location: admin.php?section=".$_REQUEST['section']."&action=".$_REQUEST['action']."&type=".$_REQUEST['type']."&c=".$_REQUEST['c']."&s=".$_REQUEST['s']."&s2=".$_REQUEST['s2']);
        }
    }

    if(!empty($_REQUEST['news']))
    {
        $newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news WHERE id = '".$_REQUEST['news']."'");
        $newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);

        if($newsCount[0] == 0)
        {
            header("Location: admin.php?section=users&action=news?p=1");
        }
    }

    if($_REQUEST['action'] == 'news' and empty($_REQUEST['news']) and empty($_REQUEST['p']))
    {
        header("Location: admin.php?section=users&action=news&p=1");
    }

    if($_REQUEST['action'] == 'mail-history' and empty($_REQUEST['p']))
    {
        header("Location: admin.php?section=users&action=mail-history&p=1");
    }

    if(!empty($_REQUEST['action']) and ($_REQUEST['action'] == 'maillist' or $_REQUEST['action'] == 'users' or $_REQUEST['action'] == 'news' or $_REQUEST['action'] == 'mail-history'))
    {
        if($_REQUEST['action'] == 'news')
        {
            if(empty($_REQUEST['news']))
            {
                $newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news");
                $newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);
                                                    
                if($newsCount[0] > 10)
                {
                    if($newsCount[0] % 10 != 0)
                    {
                        $numbers = intval(($newsCount[0] / 10) + 1);
                    }
                    else
                    {
                        $numbers = intval($newsCount[0] / 10);
                    }
                }
                else
                {
                    $numbers = 1;
                }

                if(!empty($_REQUEST['p']) and $_REQUEST['p'] > $numbers)
                {
                    header("Location: admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&p=1");
                }
            }
        }
        else
        {
            if(empty($_REQUEST['user']))
            {
                switch($_REQUEST['action'])
                {
                    case "maillist":
                        if($_REQUEST['active'] == 'true')
                        {
                            $addressCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE in_send = '1'");
                        }
                        
                        if($_REQUEST['active'] == 'false')
                        {
                            $addressCountResult = $mysqli->query("SELECT COUNT(id) FROM mail WHERE in_send = '0'");
                        }

                        $addressCount = $addressCountResult->fetch_array(MYSQLI_NUM);
                                                    
                        if($addressCount[0] > 10)
                        {
                            if($addressCount[0] % 10 != 0)
                            {
                                $numbers = intval(($addressCount[0] / 10) + 1);
                            }
                            else
                            {
                                $numbers = intval($addressCount[0] / 10);
                            }
                        }
                        else
                        {
                            $numbers = 1;
                        }

                        if(!empty($_REQUEST['p']) and $_REQUEST['p'] > $numbers)
                        {
                            header("Location: admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&p=1");
                        }
                        break;
                    case "mail-history":
                        $mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail_result");
                        $mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);
                                                    
                        if($mailCount[0] > 10)
                        {
                            if($mailCount[0] % 10 != 0)
                            {
                                $numbers = intval(($mailCount[0] / 10) + 1);
                            }
                            else
                            {
                                $numbers = intval($mailCount[0] / 10);
                            }
                        }
                        else
                        {
                            $numbers = 1;
                        }

                        if(!empty($_REQUEST['p']) and $_REQUEST['p'] > $numbers)
                        {
                            header("Location: admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&p=1");
                        }
                        break;
                    case "users":
                        $usersCountResult = $mysqli->query("SELECT COUNT(id) FROM users WHERE id <> '1'");
                        $usersCount = $usersCountResult->fetch_array(MYSQLI_NUM);
                                                    
                        if($usersCount[0] > 10)
                        {
                            if($usersCount[0] % 10 != 0)
                            {
                                $numbers = intval(($usersCount[0] / 10) + 1);
                            }
                            else
                            {
                                $numbers = intval($usersCount[0] / 10);
                            }
                        }
                        else
                        {
                            $numbers = 1;
                        }

                        if(!empty($_REQUEST['p']) and $_REQUEST['p'] > $numbers)
                        {
                            header("Location: admin.php?section=".$_SESSION['section']."&action=".$_SESSION['action']."&p=1");
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        if($_REQUEST['action'] == "maillist")
        {
            if(empty($_REQUEST['active']))
            {
                header("Location: admin.php?section=users&action=maillist&active=true&p=1");
            }
            else
            {
                if(empty($_REQUEST['p']))
                {
                    if(empty($_REQUEST['start']))
                    {
                        header("Location: admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=1");
                    }
                }
            }
        }

        if($_REQUEST['action'] == "users" and empty($_REQUEST['p']) and empty($_REQUEST['user']))
        {
            header("Location: admin.php?section=users&action=users&p=1");
        }

        
        $page = $_REQUEST['p'];
        $start = $page * 10 - 10;
    }

    if(!empty($_REQUEST['start'])) {
        $_SESSION['start'] = $_REQUEST['start'];
    } else {
        unset($_SESSION['start']);
    }

    if(!empty($_REQUEST['province'])) {
        $_SESSION['province'] = $_REQUEST['province'];
    } else {
        unset($_SESSION['province']);
    }

?>

<!doctype html>

<html id='admHtml'>

<head>

    <meta charset="windows-1251">
    
    <title>������ �����������������</title>
    
    <link rel='shortcut icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
	<link rel='icon' href='../pictures/icons/favicon.ico' type='image/x-icon'>
    <link rel='stylesheet' media='screen' type='text/css' href='../css/style.css'>
    <link rel='stylesheet' type='text/css' href='../js/shadowbox/source/shadowbox.css'>
    <?php
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)
		{
			echo "<link rel='stylesheet' media='screen' type='text/css' href='../css/styleOpera.css'>";
		}
	?>

	<script type='text/javascript' src='../js/menuAdmin.js'></script>
    <script type='text/javascript' src='../js/adminFunctions.js'></script>
    <script type='text/javascript' src='../js/footer.js'></script>
    <script type='text/javascript' src='../js/jquery-1.8.3.min.js'></script>
    <script type='text/javascript' src='../js/ajaxAdmin.js'></script>
    <script type='text/javascript' src='../js/shadowbox/source/shadowbox.js'></script>
    <script type='text/javascript' src='http://js.nicedit.com/nicEdit-latest.js'></script>

    <script type='text/javascript'>
        bkLib.onDomLoaded(function() {
            new nicEditor({fullPanel : true}).panelInstance('emailText');
        });
    </script>

</head>

<body id='admBody'>

	<header>
    	<div id='headerBlock'>
        	<a href='../index.php' class='noBorder'>
                <div id='logo'>
                    <img src='../pictures/system/logo.png' class='noBorder' />
                </div>
            </a>
            <div id='admTopMenu'>
                <div <?php if(empty($_REQUEST['section'])){echo "class='admTopSectionActive'";}else{echo "class='admTopSection' id='admTopSection1'";} ?>></div>
                <div class='space5'></div>
                <div <?php if($_REQUEST['section'] == 'goods'){echo "class='admTopSectionActive'";}else{echo "class='admTopSection' id='admTopSection2'";} ?>></div>
                <div class='space5'></div>
                <div <?php if($_REQUEST['section'] == 'categories'){echo "class='admTopSectionActive'";}else{echo "class='admTopSection' id='admTopSection3'";} ?>></div>
                <div class='space5'></div>
                <div <?php if($_REQUEST['section'] == 'users'){echo "class='admTopSectionActive'";}else{echo "class='admTopSection' id='admTopSection4'";} ?>></div>
            </div>
            <div id='admMenu'>
                <a href='admin.php' class='noBorder'>
                    <div id='admPoint1' <?php if(empty($_REQUEST['section'])){echo "class='admMenuPointActive'";}else{echo "class='admMenuPoint' onmouseover='admPointChange(\"1\", \"admPoint1\", \"admFont1\", \"admTopSection1\")' onmouseout='admPointChange(\"0\", \"admPoint1\", \"admFont1\", \"admTopSection1\")'";} ?>>
                        <span id='admFont1' <?php if(empty($_REQUEST['section'])){echo "class='admMenuRedFont'";}else{echo "class='admMenuFont'";} ?>>�������</span>
                    </div>
                </a>
                <div class='tableSpace'></div>
                <a href='admin.php?section=goods' class='noBorder'>
                    <div id='admPoint2' <?php if($_REQUEST['section'] == 'goods'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD2(\"1\", \"admGoodsWindow\")' onmouseout='admPointChangeAD2(\"0\", \"admGoodsWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD2(\"1\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")' onmouseout='admPointChangeD2(\"0\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")'";} ?>>
                        <span id='admFont2' <?php if($_REQUEST['section'] == 'goods'){echo "class='admMenuRedFont'";}else{echo "class='admMenuFont'";} ?>>������ <span style='font-size: 12px;'>&#9660;</span></span>
                    </div>
                </a>
                <div class='tableSpace'></div>
                <a href='admin.php?section=categories' class='noBorder'>
                    <div id='admPoint3' <?php if($_REQUEST['section'] == 'categories'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD3(\"1\", \"admCategoriesWindow\")' onmouseout='admPointChangeAD3(\"0\", \"admCategoriesWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD3(\"1\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")' onmouseout='admPointChangeD3(\"0\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")'";} ?>>
                        <span id='admFont3' <?php if($_REQUEST['section'] == 'categories'){echo "class='admMenuRedFont'";}else{echo "class='admMenuFont'";} ?>>������� <span style='font-size: 12px;'>&#9660;</span></span>
                    </div>
                </a>
                <div class='tableSpace'></div>
                <a href='admin.php?section=users' class='noBorder'>
                    <div id='admPoint4' <?php if($_REQUEST['section'] == 'users'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD4(\"1\", \"admDifferentWindow\")' onmouseout='admPointChangeAD4(\"0\", \"admDifferentWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD4(\"1\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")' onmouseout='admPointChangeD4(\"0\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")'";} ?>>
                        <span id='admFont4' <?php if($_REQUEST['section'] == 'users'){echo "class='admMenuRedFont'";}else{echo "class='admMenuFont'";} ?>>������ <span style='font-size: 12px;'>&#9660;</span></span>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <div id='underHeaderLine'>
        <div id='underHeaderLine1000'>
            <div class='underHeaderLineSection' style='width: 247px;'></div>
            <div class='underHeaderLineSection'></div>
            <div class='underHeaderLineSection' <?php if($_REQUEST['section'] == 'goods'){echo "onmouseover='admPointChangeAD2(\"1\", \"admGoodsWindow\")' onmouseout='admPointChangeAD2(\"0\", \"admGoodsWindow\")'";}else{echo "onmouseover='admPointChangeD2(\"1\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")' onmouseout='admPointChangeD2(\"0\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")'";} ?>></div>
            <div class='underHeaderLineSection' <?php if($_REQUEST['section'] == 'categories'){echo "onmouseover='admPointChangeAD3(\"1\", \"admCategoriesWindow\")' onmouseout='admPointChangeAD3(\"0\", \"admCategoriesWindow\")'";}else{echo "onmouseover='admPointChangeD3(\"1\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")' onmouseout='admPointChangeD3(\"0\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")'";} ?>></div>
            <div class='underHeaderLineSection' <?php if($_REQUEST['section'] == 'users'){echo "onmouseover='admPointChangeAD4(\"1\", \"admDifferentWindow\")' onmouseout='admPointChangeAD4(\"0\", \"admDifferentWindow\")'";}else{echo "onmouseover='admPointChangeD4(\"1\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")' onmouseout='admPointChangeD4(\"0\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")'";} ?>></div>
        </div>
    </div>

    <div id='admGoodsWindow' <?php if($_REQUEST['section'] == 'goods'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD2(\"1\", \"admGoodsWindow\")' onmouseout='admPointChangeAD2(\"0\", \"admGoodsWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD2(\"1\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")' onmouseout='admPointChangeD2(\"0\", \"admPoint2\", \"admFont2\", \"admTopSection2\", \"admGoodsWindow\")'";} ?>>
        <div id='admGoodsWindowInner'>
            <a href='admin.php?section=goods&action=add' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'add'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection1' onmouseover='admPointChange(\"1\", \"dropSection1\", \"dropFont1\")' onmouseout='admPointChange(\"0\", \"dropSection1\", \"dropFont1\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'add'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont1'";} ?> style='font-size: 14px;'>����������</span>
                </div>
            </a>
            <a href='admin.php?section=goods&action=edit' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'edit'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection2' onmouseover='admPointChange(\"1\", \"dropSection2\", \"dropFont2\")' onmouseout='admPointChange(\"0\", \"dropSection2\", \"dropFont2\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'edit'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont2'";} ?> style='font-size: 14px;'>��������������</span>
                </div>
            </a>
            <a href='admin.php?section=goods&action=delete' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'delete'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection3' onmouseover='admPointChange(\"1\", \"dropSection3\", \"dropFont3\")' onmouseout='admPointChange(\"0\", \"dropSection3\", \"dropFont3\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'goods' and $_REQUEST['action'] == 'delete'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont3'";} ?> style='font-size: 14px;'>��������</span>
                </div>
            </a>
        </div>
    </div>

    <div id='admCategoriesWindow' <?php if($_REQUEST['section'] == 'categories'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD3(\"1\", \"admCategoriesWindow\")' onmouseout='admPointChangeAD3(\"0\", \"admCategoriesWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD3(\"1\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")' onmouseout='admPointChangeD3(\"0\", \"admPoint3\", \"admFont3\", \"admTopSection3\", \"admCategoriesWindow\")'";} ?>>
        <div id='admCategoriesWindowInner'>
            <a href='admin.php?section=categories&action=add' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'add'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection2_1' onmouseover='admPointChange(\"1\", \"dropSection2_1\", \"dropFont2_1\")' onmouseout='admPointChange(\"0\", \"dropSection2_1\", \"dropFont2_1\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'add'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont2_1'";} ?> style='font-size: 14px;'>����������</span>
                </div>
            </a>
            <a href='admin.php?section=categories&action=edit' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'edit'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection2_2' onmouseover='admPointChange(\"1\", \"dropSection2_2\", \"dropFont2_2\")' onmouseout='admPointChange(\"0\", \"dropSection2_2\", \"dropFont2_2\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'edit'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont2_2'";} ?> style='font-size: 14px;'>��������������</span>
                </div>
            </a>
            <a href='admin.php?section=categories&action=delete' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'delete'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection2_3' onmouseover='admPointChange(\"1\", \"dropSection2_3\", \"dropFont2_3\")' onmouseout='admPointChange(\"0\", \"dropSection2_3\", \"dropFont2_3\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'categories' and $_REQUEST['action'] == 'delete'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont2_3'";} ?> style='font-size: 14px;'>��������</span>
                </div>
            </a>
        </div>
    </div>

    <div id='admDifferentWindow' <?php if($_REQUEST['section'] == 'users'){echo "class='admMenuPointActive' onmouseover='admPointChangeAD4(\"1\", \"admDifferentWindow\")' onmouseout='admPointChangeAD4(\"0\", \"admDifferentWindow\")'";}else{echo "class='admMenuPoint' onmouseover='admPointChangeD4(\"1\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")' onmouseout='admPointChangeD4(\"0\", \"admPoint4\", \"admFont4\", \"admTopSection4\", \"admDifferentWindow\")'";} ?>>
        <div id='admDifferentWindowInner'>
            <a href='admin.php?section=users&action=mail' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'mail'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection3_1' onmouseover='admPointChange(\"1\", \"dropSection3_1\", \"dropFont3_1\")' onmouseout='admPointChange(\"0\", \"dropSection3_1\", \"dropFont3_1\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'mail'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont3_1'";} ?> style='font-size: 14px;'>E-mail ��������</span>
                </div>
            </a>
            <a href='admin.php?section=users&action=maillist' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'maillist'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection3_2' onmouseover='admPointChange(\"1\", \"dropSection3_2\", \"dropFont3_2\")' onmouseout='admPointChange(\"0\", \"dropSection3_2\", \"dropFont3_2\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'maillist'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont3_2'";} ?> style='font-size: 14px;'>���������� ����</span>
                </div>
            </a>
            <a href='admin.php?section=users&action=users' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'users'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection3_3' onmouseover='admPointChange(\"1\", \"dropSection3_3\", \"dropFont3_3\")' onmouseout='admPointChange(\"0\", \"dropSection3_3\", \"dropFont3_3\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'users'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont3_3'";} ?> style='font-size: 14px;'>������������</span>
                </div>
            </a>
            <a href='admin.php?section=users&action=news' class='noBorder'>
                <div <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'news'){echo "class='dropSectionActive'";}else{echo "class='dropSection' id='dropSection3_4' onmouseover='admPointChange(\"1\", \"dropSection3_4\", \"dropFont3_4\")' onmouseout='admPointChange(\"0\", \"dropSection3_4\", \"dropFont3_4\")'";} ?>>
                    <span <?php if($_REQUEST['section'] == 'users' and $_REQUEST['action'] == 'news'){echo "class='admMenuRedFont'";}else{echo"class='admMenuFont' id='dropFont3_4'";} ?> style='font-size: 14px;'>�������</span>
                </div>
            </a>
        </div>
    </div>

    <div id='admContent' <?php if(($_REQUEST['action'] == "users" and empty($_REQUEST['user']) and $numbers == 1) or ($_REQUEST['action'] == 'news' and empty($_REQUEST['news']) and $numbers == 1) or ($_REQUEST['action'] == "maillist")) {echo " style='overflow: visible;'";} ?>>
        <center><span class='admMenuFont'>������ �����������������</span></center>
        <br /><br />

        <div id='statusField'>
            <?php
                if(isset($_SESSION['addGood']))
                {
                    switch($_SESSION['addGood'])
                    {
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� �������� ������.</span>";
                            break;
                        case "photo":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� ���������� ������.</span>";
                            break;
                        case "sketch":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��������� ������ ��� �������� �������.</span>";
                            break;
                        case "code":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ������� ������ ��� ��������� ������������� ��������.</span>";
                            break;
                        case "codeExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�������� ���� ������� ��� ����������.</span>";
                            break;
                        case "price":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� ���� ������ ��� ��������� ������������� ��������.</span>";
                            break;
                        case "position":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� ������� ������ � �������.</span>";
                            break;
                        case "description":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� �������� ������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������ � ������� ��������� ������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>����� ��� ������� �������� � �������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addGood']);
                }

                if(isset($_SESSION['editGood']))
                {
                    switch($_SESSION['editGood'])
                    {
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� �������� ������.</span>";
                            break;
                        case "photo":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ���������� ��������� ������ ���� �� ������� �������� ������.</span>";
                            break;
                        case "sketch":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ��������� ������ ���� �� ������� �������� ������.</span>";
                            break;
                        case "code":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ������� ������ ��� ��������� ������������� ��������.</span>";
                            break;
                        case "price":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� ���� ������ ��� ��������� ������������� ��������.</span>";
                            break;
                        case "position":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� ������� ������ � �������.</span>";
                            break;
                        case "description":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� �������� ������.</span>";
                            break;
                        case "codeExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�������� ���� ������� ��� ����������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ������ ��������� ������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>����� ��� ������� ��������������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editGood']);
                }

                if(isset($_SESSION['deleteGood']))
                {
                    switch($_SESSION['deleteGood'])
                    {
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������ ��������� ������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>����� ��� ������� �����.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['deleteGood']);
                }

                if(isset($_SESSION['addCategory']))
                {
                    switch($_SESSION['addCategory'])
                    {
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� �������.</span>";
                            break;
                        case "nameExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� �������.</span>";
                            break;
                        case "redPicture":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ����������� ��������� ������.</span>";
                            break;
                        case "blackPicture":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������ ����������� ��������� ������.</span>";
                            break;
                        case "subcategory":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� �������� ������� ��� ��������� ��������� ������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ��������� ��������� ������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� ���������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addCategory']);
                }

                if(isset($_SESSION['addSubcategory']))
                {
                    switch($_SESSION['addSubcategory'])
                    {
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� �������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������� ��������� ������.</span>";
                            break;
                        case "subcategoryChange":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������� ��������� ������. ������� ������ �� ��� ��������������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������ ������� ��������.</span>";
                            break;
                        case "goods":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������� ��������� ������. ������ �� �������� ������� �� ���� ����������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addSubcategory']);
                }

                if(isset($_SESSION['addSubcategory2']))
                {
                    switch($_SESSION['addSubcategory2'])
                    {
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� ����������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ���������� ��������� ������.</span>";
                            break;
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� ��������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addSubcategory2']);
                }

                if(isset($_SESSION['editCategory']))
                {
                    echo $_SESSION['editCategory'];
                    switch($_SESSION['editCategory'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� ���������������.</span>";
                            break;
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� ���������.</span>";
                            break;
                         case "nameExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��������� � ����� ������ ��� ����������.</span>";
                            break;
                        case "subcategory":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������.</span>";
                            break;
                        case "red":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������� �����������.</span>";
                            break;
                        case "black":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������ �����������.</span>";
                            break;
                        case "n+s+b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������ �����������.</span>";
                            break;
                        case "n+s+r+b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������ �����������.</span>";
                            break;
                         case "empty":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ����� ��� � �� ������� ����� �����������, ������� ��������� �� ���� ���������������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "n+s-r-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������ � �� ���� �������� ������� �����������.</span>";
                            break;
                        case "n+s-r-b+":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������ � �� ���� �������� ������� �����������.</span>";
                            break;
                        case "n+s-r+":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������.</span>";
                            break;
                        case "n+s-b+":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������.</span>";
                            break;
                        case "n+s-r+b+":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������.</span>";
                            break;
                        case "n+s+r-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������� �����������.</span>";
                            break;
                        case "n+s+r-b+":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ���� �������� ������� �����������.</span>";
                            break;
                        case "n+s-b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������ � �� ���� �������� ������ �����������.</span>";
                            break;
                        case "n+s-r+b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������ � �� ���� �������� ������ �����������.</span>";
                            break;
                        case "n+s-r-b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. �� ��� �������������� ������� ������ � ����������� �� ���� ��������.</span>";
                            break;
                        case "n+s+r-b-":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ��������� �������� ��������� ������. ����������� �� ���� ��������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editCategory']);
                }

                if(isset($_SESSION['editSubcategory']))
                {
                    switch($_SESSION['editSubcategory'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������ ������� ��������������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� �������.</span>";
                            break;
                        case "nameExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>������ � ����� ������ ��� ���������� � ��������� ���������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editSubcategory']);
                }

                if(isset($_SESSION['editSubcategory2']))
                {
                    switch($_SESSION['editSubcategory2'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� ��������������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ���������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� ����������.</span>";
                            break;
                        case "nameExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��������� � ����� ������ ��� ���������� � ��������� �������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editSubcategory2']);
                }

                if(isset($_SESSION['categoryDelete']))
                {
                    switch($_SESSION['categoryDelete'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� �������.</span>";
                            break;
                        case "category":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ��������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "subcategory":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ��������� ��������� ������. �� ���� ������� ��� ������� ���������.</span>";
                            break;
                        case "subcategory2":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ��������� ��������� ������. �� ���� ������� ��� ����������.</span>";
                            break;
                        case "goods":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ��������� ��������� ������. �� ���� ������� ������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['categoryDelete']);
                }

                if(isset($_SESSION['subcategoryDelete']))
                {
                    switch($_SESSION['subcategoryDelete'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������ ������� �����.</span>";
                            break;
                        case "subcategory":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "subcategory2":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ��������� ������. �� ���� ������� ��� ����������.</span>";
                            break;
                        case "goods":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ��������� ������. �� ���� ������� ������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['categoryDelete']);
                }

                if(isset($_SESSION['subcategory2Delete']))
                {
                    switch($_SESSION['subcategory2Delete'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��������� ������� �����.</span>";
                            break;
                        case "subcategory2":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ���������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "goods":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ���������� ��������� ������. �� ���� ������� ������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['categoryDelete']);
                }

                if(isset($_SESSION['addAddress']))
                {
                    switch($_SESSION['addAddress'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>����� ����� ������� ��������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������ ��������� ������. ���������� �����.</span>";
                            break;
                        case "email":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�������� ���� ����� ����� �������� ������.</span>";
                            break;
                        case "name":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ��� / �������� �����������.</span>";
                            break;
                        case "emailExists":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�������� ���� ����� ��� ���� � ���������� ����.</span>";
                            break;
                        case "empty":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� e-mail �����.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addAddress']);
                }

                if(isset($_SESSION['sendEmail']))
                {
                    switch($_SESSION['sendEmail'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>��� ������ ������� ����������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� ��� ������ ���� ������� ����������. � ��������� ������� ��������� ������.</span>";
                            break;
                        case "count":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� ��� ������ ���� ������� ����������. � ��������� ������� ��������� ������.</span>";
                            break;
                        case "text":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ����� ��������.</span>";
                            break;
                        case "theme":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ���� ��������.</span>";
                            break;
                        case "address":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ����� ����� ����������.</span>";
                            break;
                        case "addressFormat":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� ������� ����� ����� ����������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['sendEmail']);
                }

                if(isset($_SESSION['addressDelete']))
                {
                    switch($_SESSION['addressDelete'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>E-mail ����� ��� ������� ����� �� ���������� ����.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� e-mail ������ ��������� ������. ���������� �����.</span>";
                            break;
                        case "empty":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� ������� e-mail �����, ������� ������ �������.</span>";
                            break;
                        case "id":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>���������� ���� ������ �� ����������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addressDelete']);
                }

                if(isset($_SESSION['editUser']))
                {
                    switch($_SESSION['editUser'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������ ������ ������������ ���� ������� ��������. ������ � ������������ ����������.</span>";
                            break;
                        case "emailValidate":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ����� �������� e-mail �����.</span>";
                            break;
                        case "empty":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>���� ��� ��������� ����� �� ���������.</span>";
                            break;
                        case "notification":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>������ ������ ����������� ���� ��������, �� ������ � ������������ �� ���� ����������.</span>";
                            break;
                        case "noChanges":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� ���� ������������� �� ������ ���������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editUser']);
                }

                if(isset($_SESSION['deleteNews']))
                {
                    switch($_SESSION['deleteNews'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������� ���� ������� ������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������� ������� ��������� ������. ���������� �����.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['deleteNews']);
                }

                if(isset($_SESSION['editNews']))
                {
                    switch($_SESSION['editNews'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������� ������� ���������������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "partly":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� �������������� ������� ��������� ������. �� ��� ���� ���� ��������.</span>";
                            break;
                        case "nothing":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>������� �� ���� ���������������, ��� ��� �� ���� ������������� ���������.</span>";
                            break;
                        case "text":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� ����� �������.</span>";
                            break;
                        case "short":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� �������� ��������.</span>";
                            break;
                        case "header":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� ���������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['editNews']);
                }

                if(isset($_SESSION['addNews']))
                {
                    switch($_SESSION['addNews'])
                    {
                        case "ok":
                            echo "<div id='statusBlue'><div id='status'><span class='fBlue'>������� ������� ���������.</span>";
                            break;
                        case "failed":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������� ��������� ������. ���������� �����.</span>";
                            break;
                        case "partly":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>��� ���������� ������� ��������� ������. �� ��� ���� ���� ���������.</span>";
                            break;
                        case "text":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� ����� �������.</span>";
                            break;
                        case "short":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� �������� ��������.</span>";
                            break;
                        case "header":
                            echo "<div id='statusRed'><div id='status'><span class='fRed'>�� �� �������� ���������.</span>";
                            break;
                        default:
                            break;
                    }

                    echo "</div></div><div style='position: relative; float: left; width: 100%; height: 20px;'></div>";
                    unset($_SESSION['addNews']);
                }
            ?>
        </div>

        <?php
            if(empty($_REQUEST['section']))
            {
                $goodsQuantityResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new");
                $goodsQuantity =  $goodsQuantityResult->fetch_array(MYSQLI_NUM);

                $ordersQuantityResult = $mysqli->query("SELECT COUNT(id) FROM orders_date");
                $ordersQuantity = $ordersQuantityResult->fetch_array(MYSQLI_NUM);

                $usersQuantityResult = $mysqli->query("SELECT COUNT(id) FROM users");
                $usersQuantity = $usersQuantityResult->fetch_array(MYSQLI_NUM);

                echo "<span class='admMenuFont'>���������� ������� � ��������: </span><span class='admMenuRedFont'>".$goodsQuantity[0]."</span><br />";
                echo "<span class='admMenuFont'>���������� ����������� �������: </span><span class='admMenuRedFont'>".$ordersQuantity[0]."</span><br />";
                echo "<span class='admMenuFont'>���������� ������������������ �������������: </span><span class='admMenuRedFont'>".$usersQuantity[0]."</span><br /><br /><br /><br />";

                echo "<span class='admMenuFont'>��� ������ ������ �������� � ���� ����������� ��������. ��������:</span><br /><br />";
                echo "
                    <ul class='admUL'>
                        <span class='admMenuFont'>1. �������� � ��������:</span>
                        <br /><br />
                        <a href='admin.php?section=goods&action=add' class='noBorder'><li>���������� ����� �������</li></a>
                        <a href='admin.php?section=goods&action=edit' class='noBorder'><li>�������������� ������������ �������</li></a>
                        <a href='admin.php?section=goods&action=delete' class='noBorder'><li>�������� ������� �� ��������</li></a>
                        <br /><br />
                        <span class='admMenuFont'>2. �������� � ���������:</span>
                        <br /><br />
                        <a href='admin.php?section=categories&action=add' class='noBorder'><li>���������� ����� ��������</li></a>
                        <a href='admin.php?section=categories&action=edit' class='noBorder'><li>�������������� ������������ ��������</li></a>
                        <a href='admin.php?section=categories&action=delete' class='noBorder'><li>�������� ��������</li></a>
                        <br /><br />
                        <span class='admMenuFont'>3. </span><a href='admin.php?section=users&action=mail' class='noBorder'><span class='admULFont'>����������� �������� e-mail ��������</span></a>
                        <br /><br />
                        <span class='admMenuFont'>4. </span><a href='admin.php?section=users&action=maillist' class='noBorder'><span class='admULFont'>������ � ���������� ����� e-mail �������</span></a>
                        <br /><br />
                        <span class='admMenuFont'>5. </span><a href='admin.php?section=users&action=users' class='noBorder'><span class='admULFont'>���������� ���������� ������������������ �������������</span></a>
                        <br /><br />
                        <span class='admMenuFont'>6. ������ �������� � ���������������:</span>
                        <br /><br />
                        <a href='admin.php?section=users&action=news' class='noBorder'><li>��������, �������������� � �������� ��������</li></a>
                        <a href='admin.php?section=users&action=addNews' class='noBorder'><li>���������� �������</li></a>
                    </ul>
                ";
            }
            else
            {
                switch($_REQUEST['section'])
                {
                    case "goods":
                        if(!empty($_REQUEST['action']))
                        {
                            switch ($_REQUEST['action'])
                            {
                                case "add":
                                    echo "
                                        <span class='admMenuFont'>���������� ����� �������</span>
                                        <br /><br ><br />
                                        <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                            <label class='admLabel'>�������� ��� ������:</label>
                                            <br />
                                            <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������ -</option>
                                                <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['type']))
                                    {
                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                        echo "
                                            <form name='selectCategoryForm' id='selectCategoryForm' method='post' action='../scripts/admin/selectCategory.php'>
                                                <label class='admLabel'>�������� ���������:</label>
                                                <br />
                                                <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��������� -</option>
                                        ";

                                        while($category = $categoryResult->fetch_assoc())
                                        {
                                            echo "<option value='".$category['id']."' "; if($category['id'] == $_REQUEST['c']) {echo "selected";} echo ">".$category['name']."</option>";
                                        }

                                        echo "
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";
                                    }

                                    if(!empty($_REQUEST['c']))
                                    {
                                        $subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_REQUEST['c']."'");
                                        $subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

                                        if($subcategoriesCount[0] > 1)
                                        {
                                            $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                            echo "
                                                <form name='subcategorySelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory.php'>
                                                    <label class='admLabel'>�������� ������:</label>
                                                    <br />
                                                    <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                        <option value=''>- �������� ������ -</option>
                                            ";

                                            while($subcategory = $subcategoryResult->fetch_assoc())
                                            {
                                                echo "<option value='".$subcategory['id']."' "; if($subcategory['id'] == $_REQUEST['s']) {echo "selected";} echo ">".$subcategory['name']."</option>";
                                            }

                                            echo "
                                                    </select>
                                                </form>
                                                <br /><br />
                                            ";

                                            if(!empty($_REQUEST['s']))
                                            {
                                                $subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");
                                                $subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

                                                if($subcategories2Count[0] > 1)
                                                {
                                                    $subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                    echo "
                                                        <form name='subcategory2SelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    while($subcategories2 = $subcategories2Result->fetch_assoc())
                                                    {
                                                        echo "<option value='".$subcategories2['id']."'"; if($subcategories2['id'] == $_REQUEST['s2']) {echo " selected";} echo ">".$subcategories2['name']."</option>";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['s2']))
                                                    {
                                                        $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");
                                                        $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                                        echo "
                                                            <form name='addGoodForm' id='addGoodForm' method='post' action='../scripts/admin/addGood.php' enctype='multipart/form-data'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <input type='text' class='admInput' name='goodName' id='addGoodNameInput'"; if(!empty($_SESSION['goodName'])){echo "value='".$_SESSION['goodName']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>����� ������ (���� ����):</label>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                                <br />
                                                                <div id='goodCodeContainer'><input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' /></div>
                                                                <br /><br />
                                                                <label class='admLabel'>���� � ��������:</label>
                                                                <br />
                                                                <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>������� � �������:</label>
                                                                <br />
                                                                <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                    <option value=''>- ������� ������� ������ � ������� -</option>
                                                        ";

                                                        $j = 1;

                                                        while($j != $goodsCount[0] + 2)
                                                        {
                                                            echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $goodsCount[0] + 1) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                            $j++;
                                                        }

                                                        echo "
                                                                </select>
                                                                <br /><br />
                                                                <label class='admLabel'>��������:</label>
                                                                <br />
                                                                <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} echo "</textarea>
                                                                <br /><br />
                                                                <input type='submit' class='admSubmit' title='�������� ����� � �������' value='��������' />
                                                            </form>
                                                        ";

                                                        unset($_SESSION['goodName']);
                                                        unset($_SESSION['goodCode']);
                                                        unset($_SESSION['goodPrice']);
                                                        unset($_SESSION['goodPosition']);
                                                        unset($_SESSION['goodDescription']);

                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                        if($goodsResult->num_rows > 0)
                                                        {
                                                            echo "
                                                                <div id='admGoodBlock'>
                                                                    <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                    <br /><br />
                                                            ";

                                                            $counter = 0;

                                                            while($goods = $goodsResult->fetch_assoc())
                                                            {
                                                                if($counter % 2 == 0)
                                                                {
                                                                    echo "
                                                                        <div class='tableLine'>
                                                                            <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                    ";
                                                                }
                                                                else
                                                                {
                                                                    echo "
                                                                        <div class='tableLineGrey'>
                                                                            <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                    ";
                                                                }

                                                                echo "
                                                                        <div class='tableNumber'><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                        <div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                    </div>
                                                                ";

                                                                $counter++;
                                                            }

                                                            echo "</div>";
                                                        }
                                                        else
                                                        {
                                                            echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                        }
                                                    }
                                                }
                                                else
                                                {

                                                    $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."'");
                                                    $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                                    echo "
                                                        <form name='addGoodForm' id='addGoodForm' method='post' action='../scripts/admin/addGood.php' enctype='multipart/form-data'>
                                                            <label class='admLabel'>�������� ������:</label>
                                                            <br />
                                                            <input type='text' class='admInput' name='goodName' id='addGoodNameInput'"; if(!empty($_SESSION['goodName'])){echo "value='".$_SESSION['goodName']."'";} echo " />
                                                            <br /><br />
                                                            <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                            <br />
                                                            <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                            <br /><br />
                                                            <label class='admLabel'>����� ������ (���� ����):</label>
                                                            <br />
                                                            <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                            <br /><br />
                                                            <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                            <br />
                                                            <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                            <br /><br />
                                                            <label class='admLabel'>���� � ��������:</label>
                                                            <br />
                                                            <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} echo " />
                                                            <br /><br />
                                                            <label class='admLabel'>������� � �������:</label>
                                                            <br />
                                                            <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                <option value=''>- ������� ������� ������ � ������� -</option>
                                                    ";

                                                    $j = 1;

                                                    while($j != $goodsCount[0] + 2)
                                                    {
                                                        echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $goodsCount[0] + 1) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                        $j++;
                                                    }

                                                    echo "
                                                            </select>
                                                            <br /><br />
                                                            <label class='admLabel'>��������:</label>
                                                            <br />
                                                            <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} echo "</textarea>
                                                            <br /><br />
                                                            <input type='submit' class='admSubmit' title='�������� ����� � �������' value='��������' />
                                                        </form>
                                                    ";

                                                    unset($_SESSION['goodName']);
                                                    unset($_SESSION['goodCode']);
                                                    unset($_SESSION['goodPrice']);
                                                    unset($_SESSION['goodPosition']);
                                                    unset($_SESSION['goodDescription']);

                                                    if($subcategories2Count[0] == 1)
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                    }

                                                    if($subcategories2Count[0] == 0)
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY priority");
                                                    }
                                                    
                                                    if($goodsResult->num_rows > 0)
                                                    {
                                                        echo "
                                                            <div id='admGoodBlock'>
                                                                <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                <br /><br />
                                                        ";

                                                        $counter = 0;

                                                        while($goods = $goodsResult->fetch_assoc())
                                                        {
                                                            if($counter % 2 == 0)
                                                            {
                                                                echo "
                                                                    <div class='tableLine'>
                                                                        <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                ";
                                                            }
                                                            else
                                                            {
                                                                echo "
                                                                    <div class='tableLineGrey'>
                                                                        <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                ";
                                                            }

                                                            echo "
                                                                    <div class='tableNumber'><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                    <div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                </div>
                                                            ";

                                                            $counter++;
                                                        }

                                                        echo "</div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."'");
                                            $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                            echo "
                                                <form name='addGoodForm' id='addGoodForm' method='post' action='../scripts/admin/addGood.php' enctype='multipart/form-data'>
                                                    <label class='admLabel'>�������� ������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='goodName' id='addGoodNameInput'"; if(!empty($_SESSION['goodName'])){echo "value='".$_SESSION['goodName']."'";} echo " />
                                                    <br /><br />
                                                    <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                    <br />
                                                    <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                    <br /><br />
                                                    <label class='admLabel'>����� ������ (���� ����):</label>
                                                    <br />
                                                    <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                    <br /><br />
                                                    <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                    <br /><br />
                                                    <label class='admLabel'>���� � ��������:</label>
                                                    <br />
                                                    <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} echo " />
                                                    <br /><br />
                                                    <label class='admLabel'>������� � �������:</label>
                                                    <br />
                                                    <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                        <option value=''>- ������� ������� ������ � ������� -</option>
                                            ";

                                            $j = 1;

                                            while($j != $goodsCount[0] + 2)
                                            {
                                                echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $goodsCount[0] + 1) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                $j++;
                                            }

                                            echo "
                                                    </select>
                                                    <br /><br />
                                                    <label class='admLabel'>��������:</label>
                                                    <br />
                                                    <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} echo "</textarea>
                                                    <br /><br />
                                                    <input type='submit' class='admSubmit' title='�������� ����� � �������' value='��������' />
                                                </form>
                                            ";

                                            unset($_SESSION['goodName']);
                                            unset($_SESSION['goodCode']);
                                            unset($_SESSION['goodPrice']);
                                            unset($_SESSION['goodPosition']);
                                            unset($_SESSION['goodDescription']);

                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY priority");
                                            if($goodsResult->num_rows > 0)
                                            {
                                                echo "
                                                    <div id='admGoodBlock'>
                                                        <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                        <br /><br />
                                                ";

                                                $counter = 0;

                                                while($goods = $goodsResult->fetch_assoc())
                                                {
                                                    if($counter % 2 == 0)
                                                    {
                                                        echo "
                                                            <div class='tableLine'>
                                                                <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        echo "
                                                            <div class='tableLineGrey'>
                                                                <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                        ";
                                                    }

                                                    echo "
                                                            <div class='tableNumber'><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                            <div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                        </div>
                                                    ";

                                                    $counter++;
                                                }

                                                echo "</div>";
                                            }
                                            else
                                            {
                                                echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                            }
                                        }
                                    }
                                    break;
                                case "edit":
                                    echo "
                                        <span class='admMenuFont'>�������������� �������</span>
                                        <br /><br ><br />
                                        <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                            <label class='admLabel'>�������� ��� ������:</label>
                                            <br />
                                            <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������ -</option>
                                                <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['type']))
                                    {
                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                        echo "
                                            <form name='selectCategoryForm' id='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryE.php'>
                                                <label class='admLabel'>�������� ���������:</label>
                                                <br />
                                                <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��������� -</option>
                                        ";

                                        while($category = $categoryResult->fetch_assoc())
                                        {
                                            echo "<option value='".$category['id']."' "; if($category['id'] == $_REQUEST['c']) {echo "selected";} echo ">".$category['name']."</option>";
                                        }

                                        echo "
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";
                                    }

                                    if(!empty($_REQUEST['c']))
                                    {
                                        $subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_REQUEST['c']."'");
                                        $subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

                                        if($subcategoriesCount[0] > 1)
                                        {
                                            $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                            echo "
                                                <form name='subcategorySelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory.php'>
                                                    <label class='admLabel'>�������� ������:</label>
                                                    <br />
                                                    <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                        <option value=''>- �������� ������ -</option>
                                            ";

                                            while($subcategory = $subcategoryResult->fetch_assoc())
                                            {
                                                echo "<option value='".$subcategory['id']."' "; if($subcategory['id'] == $_REQUEST['s']) {echo "selected";} echo ">".$subcategory['name']."</option>";
                                            }

                                            echo "
                                                    </select>
                                                </form>
                                                <br /><br />
                                            ";

                                            if(!empty($_REQUEST['s']))
                                            {
                                                $subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");
                                                $subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

                                                if($subcategories2Count[0] > 1)
                                                {
                                                    $subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                    echo "
                                                        <form name='subcategory2SelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    while($subcategories2 = $subcategories2Result->fetch_assoc())
                                                    {
                                                        echo "<option value='".$subcategories2['id']."'"; if($subcategories2['id'] == $_REQUEST['s2']) {echo " selected";} echo ">".$subcategories2['name']."</option>";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['s2']))
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY name");

                                                        echo "
                                                            <form name='goodSelectForm' id='goodSelectForm' method='post' action='../scripts/admin/selectGood.php'>
                                                                <label class='admLabel'>�������� �����:</label>
                                                                <br />
                                                                <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ����� -</option>
                                                        ";

                                                        while($goods = $goodsResult->fetch_assoc())
                                                        {
                                                            echo "<option value='".$goods['id']."'"; if($goods['id'] == $_REQUEST['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['id']))
                                                        {
                                                            $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                            $good = $goodResult->fetch_assoc();

                                                            $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");
                                                            $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                                            echo "
                                                                <form name='editGoodForm' id='editGoodForm' method='post' action='../scripts/admin/editGood.php' enctype='multipart/form-data'>
                                                                    <label class='admLabel'>�������� ������:</label>
                                                                    <br />
                                                                    <input type='text' class='admInput' name='goodName' id='goodNameInput'"; if(!empty($_SESSION['goodName'])){echo " value='".$_SESSION['goodName']."'";} else {echo " value='".$good['name']."'";} echo " />
                                                                    <br /><br />
                                                                    <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                                    <br />
                                                                    <a href='../pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ����������</span></a>
                                                                    <br />
                                                                    <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                                    <br /><br />
                                                                    <label class='admLabel'>����� ������ (���� ����):</label>
                                                                    <br />
                                                                    <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                                    <br /><br />
                                                                    <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                                    <br />
                                                                    <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} else {echo " value='".$good['code']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                                    <br /><br />
                                                                    <label class='admLabel'>���� � ��������:</label>
                                                                    <br />
                                                                    <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} else {echo " value='".$good['price']."'";} echo " />
                                                                    <br /><br />
                                                                    <label class='admLabel'>������� � �������:</label>
                                                                    <br />
                                                                    <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                        <option value=''>- ������� ������� ������ � ������� -</option>
                                                            ";

                                                            $j = 1;

                                                            while($j != $goodsCount[0] + 1)
                                                            {
                                                                echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $good['priority']) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                                $j++;
                                                            }

                                                            echo "
                                                                    </select>
                                                                    <br /><br />
                                                                    <label class='admLabel'>��������:</label>
                                                                    <br />
                                                                    <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} else {echo str_replace("<br />", "\n", $good['description']);} echo "</textarea>
                                                                    <br /><br />
                                                                    <input type='submit' class='admSubmit' title='��������������� �����' value='�������������' />
                                                                </form>
                                                            ";

                                                            unset($_SESSION['goodName']);
                                                            unset($_SESSION['goodCode']);
                                                            unset($_SESSION['goodPrice']);
                                                            unset($_SESSION['goodPosition']);
                                                            unset($_SESSION['goodDescription']);

                                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                            if($goodsResult->num_rows > 0)
                                                            {
                                                                echo "
                                                                    <div id='admGoodBlock'>
                                                                        <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                        <br /><br />
                                                                ";

                                                                $counter = 0;

                                                                while($goods = $goodsResult->fetch_assoc())
                                                                {
                                                                    if($_REQUEST['id'] == $goods['id'])
                                                                    {
                                                                        echo "
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLineActive'>
                                                                                <div class='tableNumberActive'><span class='admFont'>".$goods['priority']."</span></div>
                                                                                <div class='tableNumberActive'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableNameActive'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                            </div>
                                                                        ";
                                                                    }
                                                                    else
                                                                    {
                                                                        if($counter % 2 == 0)
                                                                        {
                                                                            echo "
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLine'>
                                                                                    <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                            ";
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLineGrey'>
                                                                                    <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                            ";
                                                                        }

                                                                        echo "
                                                                                <div class='tableNumber'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                            </div>
                                                                        ";

                                                                        $counter++;
                                                                    }
                                                                }

                                                                echo "</div></a>";
                                                            }
                                                            else
                                                            {
                                                                echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if($subcategories2Count[0] == 1)
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY name");
                                                    }

                                                    if($subcategories2Count[0] == 0)
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");
                                                    }
                                                    
                                                    echo "
                                                        <form name='goodSelectForm' id='selectGood' method='post' action='../scripts/admin/selectGood.php'>
                                                            <label class='admLabel'>�������� �����:</label>
                                                            <br />
                                                            <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ����� -</option>
                                                    ";

                                                    while($goods = $goodsResult->fetch_assoc())
                                                    {
                                                        echo "<option value='".$goods['id']."'"; if($_REQUEST['id'] == $goods['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['id']))
                                                    {
                                                        $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                        $good = $goodResult->fetch_assoc();

                                                        if(!empty($_REQUEST['s2']))
                                                        {
                                                            $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");
                                                            $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
                                                        }
                                                        else
                                                        {
                                                            $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."'");
                                                            $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);
                                                        }

                                                        echo "
                                                            <form name='editGoodForm' id='editGoodForm' method='post' action='../scripts/admin/editGood.php' enctype='multipart/form-data'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <input type='text' class='admInput' name='goodName' id='goodNameInput'"; if(!empty($_SESSION['goodName'])){echo " value='".$_SESSION['goodName']."'";} else {echo " value='".$good['name']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                                <br />
                                                                <a href='../pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ����������</span></a>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>����� ������ (���� ����):</label>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                                <br />
                                                                <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} else {echo " value='".$good['code']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                                <br /><br />
                                                                <label class='admLabel'>���� � ��������:</label>
                                                                <br />
                                                                <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} else {echo " value='".$good['price']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>������� � �������:</label>
                                                                <br />
                                                                <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                    <option value=''>- ������� ������� ������ � ������� -</option>
                                                        ";

                                                        $j = 1;

                                                        while($j != $goodsCount[0] + 1)
                                                        {
                                                            echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $good['priority']) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                            $j++;
                                                        }

                                                        echo "
                                                                </select>
                                                                <br /><br />
                                                                <label class='admLabel'>��������:</label>
                                                                <br />
                                                                <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} else {echo str_replace("<br />", "\n", $good['description']);} echo "</textarea>
                                                                <br /><br />
                                                                <input type='submit' class='admSubmit' title='��������������� �����' value='�������������' />
                                                            </form>
                                                        ";

                                                        unset($_SESSION['goodName']);
                                                        unset($_SESSION['goodCode']);
                                                        unset($_SESSION['goodPrice']);
                                                        unset($_SESSION['goodPosition']);
                                                        unset($_SESSION['goodDescription']);

                                                        if(!empty($_REQUEST['s2']))
                                                        {
                                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                        }
                                                        else
                                                        {
                                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY priority");
                                                        }

                                                        if($goodsResult->num_rows > 0)
                                                        {
                                                            echo "
                                                                <div id='admGoodBlock'>
                                                                    <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                    <br /><br />
                                                            ";

                                                            $counter = 0;

                                                            while($goods = $goodsResult->fetch_assoc())
                                                            {
                                                                if($_REQUEST['id'] == $goods['id'])
                                                                    {
                                                                        echo "
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLineActive'>
                                                                                <div class='tableNumberActive'><span class='admFont'>".$goods['priority']."</span></div>
                                                                                <div class='tableNumberActive'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableNameActive'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                            </div>
                                                                        ";
                                                                    }
                                                                    else
                                                                    {
                                                                        if($counter % 2 == 0)
                                                                        {
                                                                            echo "
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLine'>
                                                                                    <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                            ";
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLineGrey'>
                                                                                    <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                            ";
                                                                        }

                                                                        echo "
                                                                                <div class='tableNumber'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                                <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                            </div>
                                                                        ";

                                                                        $counter++;
                                                                    }
                                                            }

                                                            echo "</div></a>";
                                                        }
                                                        else
                                                        {
                                                            echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");
                                            $subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

                                            if($subcategories2Count[0] > 1)
                                            {
                                                $subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                echo "
                                                    <form name='subcategory2SelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                        <label class='admLabel'>�������� ���������:</label>
                                                        <br />
                                                        <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ��������� -</option>
                                                ";

                                                while($subcategories2 = $subcategories2Result->fetch_assoc())
                                                {
                                                    echo "<option value='".$subcategories2['id']."'"; if($subcategories2['id'] == $_REQUEST['s2']) {echo " selected";} echo ">".$subcategories2['name']."</option>";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['s2']))
                                                {
                                                    $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY name");

                                                    echo "
                                                        <form name='goodSelectForm' id='goodSelectForm' method='post' action='../scripts/admin/selectGood.php'>
                                                            <label class='admLabel'>�������� �����:</label>
                                                            <br />
                                                            <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ����� -</option>
                                                    ";

                                                    while($goods = $goodsResult->fetch_assoc())
                                                    {
                                                        echo "<option value='".$goods['id']."'"; if($goods['id'] == $_REQUEST['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['id']))
                                                    {
                                                        $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                        $good = $goodResult->fetch_assoc();

                                                        $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");
                                                        $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                                        echo "
                                                            <form name='editGoodForm' id='editGoodForm' method='post' action='../scripts/admin/editGood.php' enctype='multipart/form-data'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <input type='text' class='admInput' name='goodName' id='goodNameInput'"; if(!empty($_SESSION['goodName'])){echo " value='".$_SESSION['goodName']."'";} else {echo " value='".$good['name']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                                <br />
                                                                <a href='../pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ����������</span></a>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>����� ������ (���� ����):</label>
                                                                <br />
                                                                <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                                <br /><br />
                                                                <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                                <br />
                                                                <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} else {echo " value='".$good['code']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                                <br /><br />
                                                                <label class='admLabel'>���� � ��������:</label>
                                                                <br />
                                                                <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} else {echo " value='".$good['price']."'";} echo " />
                                                                <br /><br />
                                                                <label class='admLabel'>������� � �������:</label>
                                                                <br />
                                                                <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                    <option value=''>- ������� ������� ������ � ������� -</option>
                                                        ";

                                                        $j = 1;

                                                        while($j != $goodsCount[0] + 2)
                                                        {
                                                            echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $good['priority']) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                            $j++;
                                                        }

                                                        echo "
                                                                </select>
                                                                <br /><br />
                                                                <label class='admLabel'>��������:</label>
                                                                <br />
                                                                <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} else {echo str_replace("<br />", "\n", $good['description']);} echo "</textarea>
                                                                <br /><br />
                                                                <input type='submit' class='admSubmit' title='��������������� �����' value='�������������' />
                                                            </form>
                                                        ";

                                                        unset($_SESSION['goodName']);
                                                        unset($_SESSION['goodCode']);
                                                        unset($_SESSION['goodPrice']);
                                                        unset($_SESSION['goodPosition']);
                                                        unset($_SESSION['goodDescription']);

                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                        if($goodsResult->num_rows > 0)
                                                        {
                                                            echo "
                                                                <div id='admGoodBlock'>
                                                                    <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                    <br /><br />
                                                            ";

                                                            $counter = 0;

                                                            while($goods = $goodsResult->fetch_assoc())
                                                            {
                                                                if($_REQUEST['id'] == $goods['id'])
                                                                {
                                                                    echo "
                                                                        <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLineActive'>
                                                                            <div class='tableNumberActive'><span class='admFont'>".$goods['priority']."</span></div>
                                                                            <div class='tableNumberActive'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableNameActive'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                        </div>
                                                                    ";
                                                                }
                                                                else
                                                                {
                                                                    if($counter % 2 == 0)
                                                                    {
                                                                        echo "
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLine'>
                                                                                <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                        ";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableLineGrey'>
                                                                                <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                        ";
                                                                    }

                                                                    echo "
                                                                            <div class='tableNumber'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                            <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']."&s2=".$goods['subcategory2']."&id=".$goods['id']."' class='noBorder'><div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                        </div>
                                                                    ";

                                                                    $counter++;
                                                                }
                                                            }

                                                            echo "</div></a>";
                                                        }
                                                        else
                                                        {
                                                            echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if($subcategories2Count[0] == 1)
                                                {
                                                    $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY name");
                                                }
                                                
                                                if($subcategories2Count[0] == 0)
                                                {
                                                    $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");
                                                }

                                                echo "
                                                    <form name='goodSelectForm' id='selectGood' method='post' action='../scripts/admin/selectGood.php'>
                                                        <label class='admLabel'>�������� �����:</label>
                                                        <br />
                                                        <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ����� -</option>
                                                ";

                                                while($goods = $goodsResult->fetch_assoc())
                                                {
                                                    echo "<option value='".$goods['id']."'"; if($_REQUEST['id'] == $goods['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['id']))
                                                {
                                                    $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                    $good = $goodResult->fetch_assoc();

                                                    if(!empty($_REQUEST['s2']))
                                                    {
                                                        $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");
                                                    }
                                                    else
                                                    {
                                                        $goodsCountResult = $mysqli->query("SELECT COUNT(id) FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."'");
                                                    }
                                                    
                                                    $goodsCount = $goodsCountResult->fetch_array(MYSQLI_NUM);

                                                    echo "
                                                        <form name='editGoodForm' id='editGoodForm' method='post' action='../scripts/admin/editGood.php' enctype='multipart/form-data'>
                                                            <label class='admLabel'>�������� ������:</label>
                                                            <br />
                                                            <input type='text' class='admInput' name='goodName' id='goodNameInput'"; if(!empty($_SESSION['goodName'])){echo " value='".$_SESSION['goodName']."'";} else {echo " value='".$good['name']."'";} echo " />
                                                            <br /><br />
                                                            <label class='admLabel'>���������� ������<br />(��� ������� 100*100 ��������):</label>
                                                            <br />
                                                            <a href='../pictures/catalogue/big/".$good['picture']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ����������</span></a>
                                                            <br />
                                                            <input type='file' class='admFile' name='goodPhoto' id='goodPhotoInput' />
                                                            <br /><br />
                                                            <label class='admLabel'>����� ������ (���� ����):</label>
                                                            <br />
                                                            <input type='file' class='admFile' name='goodSketch' id='goodSketchInput' />
                                                            <br /><br />
                                                            <label class='admLabel'>�������: (</label><span id='setCode' class='admULFont' style='font-size: 14px; cursor: pointer;'>���������� ������ ���������</span><label class='admLabel'>)</label>
                                                            <br />
                                                            <input type='text' class='admInput' name='goodCode' id='goodCodeInput'"; if(!empty($_SESSION['goodCode'])){echo " value='".$_SESSION['goodCode']."'";} else {echo " value='".$good['code']."'";} echo " onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                            <br /><br />
                                                            <label class='admLabel'>���� � ��������:</label>
                                                            <br />
                                                            <input type='number' class='admInput' name='goodPrice' id='goodPriceInput' step='0.001' min='0.001'"; if(!empty($_SESSION['goodPrice'])){echo " value='".$_SESSION['goodPrice']."'";} else {echo " value='".$good['price']."'";} echo " />
                                                            <br /><br />
                                                            <label class='admLabel'>������� � �������:</label>
                                                            <br />
                                                            <select class='admSelect' name='goodPosition' id='goodPositionSelect' size='1'>
                                                                <option value=''>- ������� ������� ������ � ������� -</option>
                                                    ";

                                                    $j = 1;

                                                    while($j != $goodsCount[0] + 1)
                                                    {
                                                        echo "<option value='".$j."'"; if((empty($_SESSION['goodPosition']) and $j == $good['priority']) or (!empty($_SESSION['goodPosition']) and $_SESSION['goodPosition'] == $j)) {echo " selected";} echo ">".$j."</option>";
                                                        $j++;
                                                    }

                                                    echo "
                                                            </select>
                                                            <br /><br />
                                                            <label class='admLabel'>��������:</label>
                                                            <br />
                                                            <textarea class='admTextarea' name='goodDescription' id='goodDescriptionInput'>"; if(!empty($_SESSION['goodDescription'])){echo $_SESSION['goodDescription'];} else {echo str_replace("<br />", "\n", $good['description']);} echo "</textarea>
                                                            <br /><br />
                                                            <input type='submit' class='admSubmit' title='��������������� �����' value='�������������' />
                                                        </form>
                                                    ";

                                                    unset($_SESSION['goodName']);
                                                    unset($_SESSION['goodCode']);
                                                    unset($_SESSION['goodPrice']);
                                                    unset($_SESSION['goodPosition']);
                                                    unset($_SESSION['goodDescription']);

                                                    if(!empty($_REQUEST['s2']))
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY priority");
                                                    }
                                                    else
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY priority");
                                                    }
                                                    
                                                    if($goodsResult->num_rows > 0)
                                                    {
                                                        echo "
                                                            <div id='admGoodBlock'>
                                                                <center><span class='admMenuFont'>������� ���������� ������� � ��������� �������</span></center>
                                                                <br /><br />
                                                        ";

                                                        $counter = 0;

                                                        while($goods = $goodsResult->fetch_assoc())
                                                        {
                                                            if($_REQUEST['id'] == $goods['id'])
                                                            {
                                                                echo "
                                                                    <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLineActive'>
                                                                        <div class='tableNumberActive'><span class='admFont'>".$goods['priority']."</span></div>
                                                                        <div class='tableNumberActive'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                        <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableNameActive'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                    </div>
                                                                ";
                                                            }
                                                            else
                                                            {
                                                                if($counter % 2 == 0)
                                                                {
                                                                    echo "
                                                                        <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLine'>
                                                                            <div class='tableNumberGrey'><span class='admFont'>".$goods['priority']."</span></div>
                                                                    ";
                                                                }
                                                                else
                                                                {
                                                                    echo "
                                                                        <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableLineGrey'>
                                                                            <div class='tableNumber'><span class='admFont'>".$goods['priority']."</span></div>
                                                                    ";
                                                                }

                                                                echo "
                                                                        <div class='tableNumber'></a><a href='../pictures/catalogue/big/".$goods['picture']."' class='noBorder' rel='lightbox'><img src='../pictures/catalogue/small/".$goods['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
                                                                        <a href='admin.php?section=goods&action=edit&type=".$goods['type']."&c=".$goods['category']."&s=".$goods['subcategory']; if(!empty($_REQUEST['s2'])) {echo "&s2=".$goods['subcategory2'];} echo "&id=".$goods['id']."' class='noBorder'><div class='tableName'><span class='admFont' title='�������: ".$goods['code']."; ����: $".$goods['price']."'>".$goods['name']."</span></div>
                                                                    </div>
                                                                ";

                                                                $counter++;
                                                            }
                                                        }

                                                        echo "</div></a>";
                                                    }
                                                    else
                                                    {
                                                        echo "<div id='admGoodBlock'><span class='admMenuFont'>��������� ������ ���� ����.</span></div>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case "delete":
                                    echo "
                                        <span class='admMenuFont'>�������� �������</span>
                                        <br /><br ><br />
                                        <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                            <label class='admLabel'>�������� ��� ������:</label>
                                            <br />
                                            <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������ -</option>
                                                <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['type']))
                                    {
                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                        echo "
                                            <form name='selectCategoryForm' id='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryE.php'>
                                                <label class='admLabel'>�������� ���������:</label>
                                                <br />
                                                <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��������� -</option>
                                        ";

                                        while($category = $categoryResult->fetch_assoc())
                                        {
                                            echo "<option value='".$category['id']."' "; if($category['id'] == $_REQUEST['c']) {echo "selected";} echo ">".$category['name']."</option>";
                                        }

                                        echo "
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";

                                        if(!empty($_REQUEST['c']))
                                        {
                                            $subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories_new WHERE category = '".$_REQUEST['c']."'");
                                            $subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

                                            if($subcategoriesCount[0] > 1)
                                            {
                                                $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                                echo "
                                                    <form name='subcategorySelectForm' id='subcategorySelectForm' method='post' action='../scripts/admin/selectSubcategory.php'>
                                                        <label class='admLabel'>�������� ������:</label>
                                                        <br />
                                                        <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ������ -</option>
                                                ";

                                                while($subcategory = $subcategoryResult->fetch_assoc())
                                                {
                                                    echo "<option value='".$subcategory['id']."' "; if($subcategory['id'] == $_REQUEST['s']) {echo "selected";} echo ">".$subcategory['name']."</option>";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['s']))
                                                {
                                                    $subcategories2CountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");
                                                    $subcategories2Count = $subcategories2CountResult->fetch_array(MYSQLI_NUM);

                                                    if($subcategories2Count[0] > 1)
                                                    {
                                                        $subcategory2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                        echo "
                                                            <form name='subcategory2SelectForm' id='subcategory2SelectForm' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                                <label class='admLabel'>�������� ���������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ��������� -</option>
                                                        ";

                                                        while($subcategory2 = $subcategory2Result->fetch_assoc())
                                                        {
                                                            echo "<option value='".$subcategory2['id']."' "; if($subcategory2['id'] == $_REQUEST['s2']) {echo "selected";} echo ">".$subcategory2['name']."</option>";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s2']))
                                                        {
                                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."' ORDER BY name");

                                                            echo "
                                                                <form name='goodSelectForm' id='goodSelectForm' method='post' action='../scripts/admin/selectGood.php'>
                                                                    <label class='admLabel'>�������� �����:</label>
                                                                    <br />
                                                                    <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                                        <option value=''>- �������� ����� -</option>
                                                            ";

                                                            while($goods = $goodsResult->fetch_assoc())
                                                            {
                                                                echo "<option value='".$goods['id']."'"; if($goods['id'] == $_REQUEST['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                            }

                                                            echo "
                                                                    </select>
                                                                </form>
                                                                <br /><br />
                                                            ";

                                                            if(!empty($_REQUEST['id']))
                                                            {
                                                                $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                                $good = $goodsResult->fetch_assoc();

                                                                echo "
                                                                    <form name='deleteGoodForm' id='dleteGoodForm' method='post' action='../scripts/admin/deleteGood.php'>
                                                                        <input type='submit' class='admSubmit' value='�������' />
                                                                    </form>
                                                                ";
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                        echo "
                                                            <form name='goodSelectForm' id='goodSelectForm' method='post' action='../scripts/admin/selectGood.php'>
                                                                <label class='admLabel'>�������� �����:</label>
                                                                <br />
                                                                <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ����� -</option>
                                                        ";

                                                        while($goods = $goodsResult->fetch_assoc())
                                                        {
                                                            echo "<option value='".$goods['id']."'"; if($goods['id'] == $_REQUEST['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['id']))
                                                        {
                                                            $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                            $good = $goodsResult->fetch_assoc();

                                                            echo "
                                                                <form name='deleteGoodForm' id='dleteGoodForm' method='post' action='../scripts/admin/deleteGood.php'>
                                                                    <input type='submit' class='admSubmit' value='�������' />
                                                                </form>
                                                            ";
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."' ORDER BY name");

                                                echo "
                                                    <form name='goodSelectForm' id='goodSelectForm' method='post' action='../scripts/admin/selectGood.php'>
                                                        <label class='admLabel'>�������� �����:</label>
                                                        <br />
                                                        <select class='admSelect' name='goodSelect' id='goodSelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ����� -</option>
                                                ";

                                                while($goods = $goodsResult->fetch_assoc())
                                                {
                                                    echo "<option value='".$goods['id']."'"; if($goods['id'] == $_REQUEST['id']) {echo " selected";} echo ">".$goods['name']."</option>";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['id']))
                                                {
                                                    $goodResult = $mysqli->query("SELECT * FROM catalogue_new WHERE id = '".$_REQUEST['id']."'");
                                                    $good = $goodsResult->fetch_assoc();

                                                    echo "
                                                        <form name='deleteGoodForm' id='dleteGoodForm' method='post' action='../scripts/admin/deleteGood.php'>
                                                            <input type='submit' class='admSubmit' value='�������' />
                                                        </form>
                                                    ";
                                                }
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                        else
                        {
                            echo "
                                <span class='admMenuFont'<br />�� ���������� � ������� ������ �������� ��� �������� �� ��������. ��� ����������� ������ ���������� ������� ���� �� ��������� ��������:</span>
                                <br /><br />
                                <ul class='admUL'>
                                    <a href='admin.php?section=goods&action=add' class='noBorder'><li>���������� ����� �������</li></a>
                                    <a href='admin.php?section=goods&action=edit' class='noBorder'><li>�������������� ������������ �������</li></a>
                                    <a href='admin.php?section=goods&action=delete' class='noBorder'><li>�������� ������� �� ��������</li></a>
                                </ul>
                            ";
                        }
                        break;
                    case "categories":
                        if(!empty($_REQUEST['action']))
                        {
                            switch($_REQUEST['action'])
                            {
                                case "add":
                                    echo "
                                        <span class='admMenuFont'>���������� ����� ��������</span>
                                        <br /><br ><br />
                                        <form name='selectLevelForm' id='selectLevelForm' method='post' action='../scripts/admin/selectLevel.php'>
                                            <label class='admLabel'>�������� ��� �������:</label>
                                            <br />
                                            <select class='admSelect' name='levelSelect' id='levelSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������� -</option>
                                                <option value='1' "; if($_REQUEST['level'] == '1') {echo "selected";} echo ">��������� (� ���������) - ������� 1</option>
                                                <option value='2' "; if($_REQUEST['level'] == '2') {echo "selected";} echo ">������ - ������� 2</option>
                                                <option value='3' "; if($_REQUEST['level'] == '3') {echo "selected";} echo ">��������� - ������� 3</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['level']))
                                    {
                                        echo "
                                            <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                                <label class='admLabel'>�������� ��� �������:</label>
                                                <br />
                                                <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��� ������� -</option>
                                                    <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";

                                        if(!empty($_REQUEST['type']))
                                        {
                                            if($_REQUEST['level'] == '1')
                                            {
                                                echo "
                                                    <form name='addCategoryForm' id='addCategoryForm' method='post' action='../scripts/admin/addCategory.php' enctype='multipart/form-data'>
                                                        <label class='admLabel'>�������� ���������:</label>
                                                        <br />
                                                        <input type='text' name='categoryName' id='categoryNameInput' class='admInput'"; if(isset($_SESSION['categoryName'])) {echo " value='".$_SESSION['categoryName']."'";} echo " />
                                                        <br /><br />
                                                        <label class='admLabel'>�������� ������� ����������� (������ 21*21 �������):</label>
                                                        <br />
                                                        <input type='file' name='categoryRedPicture' id='categoryRedPictureInput' class='admFile' />
                                                        <br /><br />
                                                        <label class='admLabel'>�������� ������ ����������� (������ 21*21 �������):</label>
                                                        <br />
                                                        <input type='file' name='categoryBlackPicture' id='categoryBlackPictureInput' class='admFile' />
                                                        <br /><br />
                                                        <label class='admLabel'>��������� ��� ��������</label>
                                                        <input type='checkbox' name='categoryCheckbox' value='1' />
                                                        <br /><br />
                                                        <input type='submit' class='admSubmit' value='��������' />
                                                    </form>
                                                ";

                                                unset($_SESSION['categoryName']);
                                            }
                                            else
                                            {
                                                $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                                echo "
                                                    <form name='selectCategoryForm' id='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                        <label class='admLabel'>�������� ���������:</label>
                                                        <br />
                                                        <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ��������� -</option>
                                                ";

                                                while($category = $categoryResult->fetch_assoc())
                                                {
                                                    echo "<option value='".$category['id']."' "; if($category['id'] == $_REQUEST['c']) {echo "selected";} echo ">".$category['name']."</option>";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if($_REQUEST['level'] == 2)
                                                {
                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        echo "
                                                            <form name='addSubcategoryForm' id='addSubcategoryForm' method='post' action='../scripts/admin/addSubcategory.php'>
                                                                <label class='admLabel'>�������� �������:</label>
                                                                <br />
                                                                <input type='text' name='subcategoryName' id='subcategoryNameInput' class='admInput'"; if(isset($_SESSION['subcategoryName'])) {echo " value='".$_SESSION['subcategoryName']."'";} echo " />
                                                                <br /><br />
                                                                <input type='submit' class='admSubmit' value='��������' />
                                                            </form>
                                                        ";

                                                        unset($_SESSION['subcategoryName']);
                                                    } 
                                                }
                                                else
                                                {
                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                                        echo "
                                                            <form name='selectSubcategoryForm' id='selectSubcategoryForm' method='post' action='../scripts/admin/selectSubcategoryC.php'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ������ -</option>
                                                        ";

                                                        while($subcategory = $subcategoryResult->fetch_assoc())
                                                        {
                                                            echo "<option value='".$subcategory['id']."' "; if($subcategory['id'] == $_REQUEST['s']) {echo "selected";} echo ">".$subcategory['name']."</option>";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s']))
                                                        {
                                                            echo "
                                                                <form name='addSubcategory2Form' id='addSubcategory2Form' method='post' action='../scripts/admin/addSubcategory2.php'>
                                                                    <label class='admLabel'>�������� ����������:</label>
                                                                    <br />
                                                                    <input type='text' name='subcategory2Name' id='subcategory2NameInput' class='admInput'"; if(isset($_SESSION['subcategory2Name'])) {echo " value='".$_SESSION['subcategory2Name']."'";} echo " />
                                                                    <br /><br />
                                                                    <input type='submit' class='admSubmit' value='��������' />
                                                                </form>
                                                            ";

                                                            unset($_SESSION['subcategory2Name']);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case "edit":
                                    echo "
                                        <span class='admMenuFont'>�������������� ��������:</span>
                                        <br /><br ><br />
                                        <form name='selectLevelForm' id='selectLevelForm' method='post' action='../scripts/admin/selectLevelCE.php'>
                                            <label class='admLabel'>�������� ��� �������:</label>
                                            <br />
                                            <select class='admSelect' name='levelSelect' id='levelSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������� -</option>
                                                <option value='1' "; if($_REQUEST['level'] == '1') {echo "selected";} echo ">��������� (� ���������) - ������� 1</option>
                                                <option value='2' "; if($_REQUEST['level'] == '2') {echo "selected";} echo ">������ - ������� 2</option>
                                                <option value='3' "; if($_REQUEST['level'] == '3') {echo "selected";} echo ">��������� - ������� 3</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['level']))
                                    {
                                        echo "
                                            <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                                <label class='admLabel'>�������� ��� �������:</label>
                                                <br />
                                                <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��� ������� -</option>
                                                    <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";

                                        if(!empty($_REQUEST['type']))
                                        {
                                            if($_REQUEST['level'] == '1')
                                            {
                                                $categoriesResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                                echo "
                                                    <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                        <label class='admLabel'>�������� ���������:</label>
                                                        <br />
                                                        <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ��������� -</option>
                                                ";

                                                while($categories = $categoriesResult->fetch_assoc())
                                                {
                                                    echo "
                                                        <option value='".$categories['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $categories['id']){echo " selected";} echo ">".$categories['name']."</option>
                                                    ";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['c']))
                                                {
                                                    $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE id = '".$_REQUEST['c']."'");
                                                    $category = $categoryResult->fetch_assoc();

                                                    echo "
                                                        <form name='editCategoryForm' id='editCategoryForm' method='post' action='../scripts/admin/editCategory.php' enctype='multipart/form-data'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <input type='text' name='categoryName' id='categoryNameInput' class='admInput'"; if(isset($_SESSION['categoryName'])) {echo " value='".$_SESSION['categoryName']."'";} else{echo " value='".$category['name']."'";} echo " />
                                                            <br /><br />
                                                            <label class='admLabel'>�������� ������� ����������� (������ 21*21 �������):</label>
                                                            <br />
                                                            <a href='../pictures/icons/".$category['picture_red']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ������� �����������</span></a>
                                                            <br />
                                                            <input type='file' name='categoryRedPicture' id='categoryRedPictureInput' class='admFile' />
                                                            <br /><br />
                                                            <label class='admLabel'>�������� ������ ����������� (������ 21*21 �������):</label>
                                                            <br />
                                                            <a href='../pictures/icons/".$category['picture']."' class='noBorder' rel='lightbox'><span class='admULFont' style='font-size: 14px;'>�������� ������ �����������</span></a>
                                                            <br />
                                                            <input type='file' name='categoryBlackPicture' id='categoryBlackPictureInput' class='admFile' />
                                                            <br /><br />
                                                            <input type='submit' class='admSubmit' value='�������������' />
                                                        </form>
                                                    ";

                                                    unset($_SESSION['categoryName']);
                                                }
                                            }
                                            else
                                            {
                                                if($_REQUEST['level'] == '2')
                                                {
                                                    $catArray = array();
                                                    $namesArray = array();

                                                    $categoriesResult = $mysqli->query("SELECT DISTINCT category FROM subcategories_new WHERE id < 1000 AND type = '".$_SESSION['type']."'");

                                                    while($categories = $categoriesResult->fetch_array(MYSQLI_NUM))
                                                    {
                                                        array_push($catArray, $categories[0]);
                                                    }

                                                    for($i = 0; $i < count($catArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT name FROM categories_new WHERE id = '".$catArray[$i]."'");
                                                        $category = $categoryResult->fetch_array(MYSQLI_NUM);

                                                        array_push($namesArray, $category[0]);
                                                    }

                                                    sort($namesArray);

                                                    echo "
                                                        <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    for($i = 0; $i < count($namesArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE name = '".$namesArray[$i]."'");
                                                        $category = $categoryResult->fetch_assoc();

                                                        echo "
                                                            <option value='".$category['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $category['id']){echo " selected";} echo ">".$category['name']."</option>
                                                        ";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        $subcategoriesResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                                        echo "
                                                            <form name='selectSubcategoryForm' method='post' action='../scripts/admin/selectSubcategoryC.php'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ������ -</option>
                                                        ";

                                                        while($subcategories = $subcategoriesResult->fetch_assoc())
                                                        {
                                                            echo "
                                                                <option value='".$subcategories['id']."'"; if(!empty($_REQUEST['s']) and $_REQUEST['s'] == $subcategories['id']){echo " selected";} echo ">".$subcategories['name']."</option>
                                                            ";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s']))
                                                        {
                                                            $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE id = '".$_REQUEST['s']."'");
                                                            $subcategory = $subcategoryResult->fetch_assoc();

                                                            echo "
                                                                <form name='editSubcategoryForm' id='editSubcategoryForm' method='post' action='../scripts/admin/editSubcategory.php'>
                                                                    <label class='admLabel'>�������� �������:</label>
                                                                    <br />
                                                                    <input type='text' name='subcategoryName' id='subcategoryNameInput' class='admInput'"; if(isset($_SESSION['subcategoryName'])) {echo " value='".$_SESSION['subcategoryName']."'";} else{echo " value='".$subcategory['name']."'";} echo " />
                                                                    <br /><br />
                                                                    <input type='submit' class='admSubmit' value='�������������' />
                                                                </form>
                                                            ";

                                                            unset($_SESSION['subcategoryName']);
                                                        }
                                                    }
                                                }

                                                if($_REQUEST['level'] == "3")
                                                {
                                                    $subArray = array();
                                                    $cleanSubArr = array();
                                                    $catArray = array();
                                                    $namesArray = array();

                                                    $subcategories2Result = $mysqli->query("SELECT DISTINCT subcategory FROM subcategories2");
                                                    while($subcategories2 = $subcategories2Result->fetch_array(MYSQLI_NUM))
                                                    {
                                                        array_push($subArray, $subcategories2[0]);
                                                    }

                                                    for($i = 0; $i < count($subArray); $i++)
                                                    {
                                                        $subcategoryResult = $mysqli->query("SELECT type FROM subcategories_new WHERE id = '".$subArray[$i]."'");
                                                        $subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

                                                        if($subcategory[0] == $_SESSION['type'])
                                                        {
                                                            array_push($cleanSubArr, $subArray[$i]);
                                                        }
                                                    }

                                                    for($i = 0; $i < count($cleanSubArr); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT category FROM subcategories_new WHERE id = '".$cleanSubArr[$i]."'");
                                                        $category = $categoryResult->fetch_array(MYSQLI_NUM);

                                                        $count = 0;

                                                        for($j = 0; $j < count($catArray); $j++)
                                                        {
                                                            if($catArray[$j] == $category[0])
                                                            {
                                                                $count++;
                                                            }
                                                        }

                                                        if($count == 0)
                                                        {
                                                            array_push($catArray, $category[0]);
                                                        }
                                                    }

                                                    for($i = 0; $i < count($catArray); $i++)
                                                    {
                                                        $categoryNameResult = $mysqli->query("SELECT name FROM categories_new WHERE id = '".$catArray[$i]."'");
                                                        $categoryName = $categoryNameResult->fetch_array(MYSQLI_NUM);

                                                        array_push($namesArray, $categoryName[0]);
                                                    }

                                                    sort($namesArray);

                                                    echo "
                                                        <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    for($i = 0; $i < count($namesArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE name = '".$namesArray[$i]."'");
                                                        $category = $categoryResult->fetch_assoc();

                                                        echo "
                                                            <option value='".$category['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $category['id']){echo " selected";} echo ">".$category['name']."</option>
                                                        ";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        $namesArray = array();

                                                        for($i = 0; $i < count($cleanSubArr); $i++)
                                                        {
                                                            $subcategoryResult = $mysqli->query("SELECT name FROM subcategories_new WHERE id = '".$cleanSubArr[$i]."' AND category = '".$_SESSION['c']."'");
                                                            $subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

                                                            if(!empty($subcategory))
                                                            {
                                                                array_push($namesArray, $subcategory[0]);
                                                            }
                                                        }

                                                        sort($namesArray);

                                                        echo "
                                                            <form name='selectSubcategoryForm' method='post' action='../scripts/admin/selectSubcategoryC.php'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ������ -</option>
                                                        ";

                                                        for($i = 0; $i < count($namesArray); $i++)
                                                        {
                                                            $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE name = '".$namesArray[$i]."'");
                                                            $subcategory = $subcategoryResult->fetch_assoc();

                                                            echo "
                                                                <option value='".$subcategory['id']."'"; if(!empty($_REQUEST['s']) and $_REQUEST['s'] == $subcategory['id']){echo " selected";} echo ">".$subcategory['name']."</option>
                                                            ";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s']))
                                                        {
                                                            $subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");

                                                            echo "
                                                                <form name='selectSubcategory2Form' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                                    <label class='admLabel'>�������� ���������:</label>
                                                                    <br />
                                                                    <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                                        <option value=''>- �������� ��������� -</option>
                                                            ";

                                                            while($subcategories2 = $subcategories2Result->fetch_assoc())
                                                            {
                                                                echo "
                                                                    <option value='".$subcategories2['id']."'"; if(!empty($_REQUEST['s2']) and $_REQUEST['s2'] == $subcategories2['id']){echo " selected";} echo ">".$subcategories2['name']."</option>
                                                                ";
                                                            }

                                                            echo "
                                                                    </select>
                                                                </form>
                                                                <br /><br />
                                                            ";

                                                            if(!empty($_REQUEST['s2']))
                                                            {
                                                                $subcategory2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE id = '".$_REQUEST['s2']."'");
                                                                $subcategory2 = $subcategory2Result->fetch_assoc();

                                                                echo "
                                                                    <form name='editSubcategory2Form' id='editSubcategory2Form' method='post' action='../scripts/admin/editSubcategory2.php'>
                                                                        <label class='admLabel'>�������� ����������:</label>
                                                                        <br />
                                                                        <input type='text' name='subcategory2Name' id='subcategory2NameInput' class='admInput'"; if(isset($_SESSION['subcategory2Name'])) {echo " value='".$_SESSION['subcategory2Name']."'";} else{echo " value='".$subcategory2['name']."'";} echo " />
                                                                        <br /><br />
                                                                        <input type='submit' class='admSubmit' value='�������������' />
                                                                    </form>
                                                                ";

                                                                unset($_SESSION['subcategory2Name']);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case "delete":
                                    echo "
                                        <span class='admMenuFont'>�������� ��������:</span>
                                        <br /><br ><br />
                                        <form name='selectLevelForm' id='selectLevelForm' method='post' action='../scripts/admin/selectLevelCE.php'>
                                            <label class='admLabel'>�������� ��� �������:</label>
                                            <br />
                                            <select class='admSelect' name='levelSelect' id='levelSelect' size='1' onchange='this.form.submit()'>
                                                <option value=''>- �������� ��� ������� -</option>
                                                <option value='1' "; if($_REQUEST['level'] == '1') {echo "selected";} echo ">��������� (� ���������) - ������� 1</option>
                                                <option value='2' "; if($_REQUEST['level'] == '2') {echo "selected";} echo ">������ - ������� 2</option>
                                                <option value='3' "; if($_REQUEST['level'] == '3') {echo "selected";} echo ">��������� - ������� 3</option>
                                            </select>
                                        </form>
                                        <br /><br />
                                    ";

                                    if(!empty($_REQUEST['level']))
                                    {
                                        echo "
                                            <form name='selectTypeForm' id='selectTypeForm' method='post' action='../scripts/admin/selectType.php'>
                                                <label class='admLabel'>�������� ��� �������:</label>
                                                <br />
                                                <select class='admSelect' name='typeSelect' id='typeSelect' size='1' onchange='this.form.submit()'>
                                                    <option value=''>- �������� ��� ������� -</option>
                                                    <option value='fa' "; if($_REQUEST['type'] == 'fa') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='em' "; if($_REQUEST['type'] == 'em') {echo "selected";} echo ">��������� ���������</option>
                                                    <option value='ca' "; if($_REQUEST['type'] == 'ca') {echo "selected";} echo ">���������� ��� ����</option>
                                                </select>
                                            </form>
                                            <br /><br />
                                        ";

                                        if(!empty($_REQUEST['type']))
                                        {
                                            if($_REQUEST['level'] == '1')
                                            {
                                                $categoriesResult = $mysqli->query("SELECT * FROM categories_new WHERE type = '".$_REQUEST['type']."' ORDER BY name");

                                                echo "
                                                    <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                        <label class='admLabel'>�������� ���������:</label>
                                                        <br />
                                                        <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                            <option value=''>- �������� ��������� -</option>
                                                ";

                                                while($categories = $categoriesResult->fetch_assoc())
                                                {
                                                    echo "
                                                        <option value='".$categories['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $categories['id']){echo " selected";} echo ">".$categories['name']."</option>
                                                    ";
                                                }

                                                echo "
                                                        </select>
                                                    </form>
                                                    <br /><br />
                                                ";

                                                if(!empty($_REQUEST['c']))
                                                {
                                                    $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE category = '".$_REQUEST['c']."'");

                                                    echo "
                                                        <form name='deleteCategoryForm' id='deleteCategoryForm' method='post' action='../scripts/admin/deleteCategory.php'>
                                                    ";
                                                    
                                                    if($goodsResult->num_rows != 0)
                                                    {
                                                        echo "
                                                            <label class='admLabel' onclick='checkboxClick(\"categoryDeleteCheckbox\")' style='cursor: pointer;'>������� ��������� ������ � ��������</label>
                                                            <input type='checkbox' name='categoryDeleteCheckbox' id='categoryDeleteCheckbox' value='1' style='margin-left: -90px; top: 6px; cursor: pointer;' />
                                                            <br /><br />
                                                        ";
                                                    }

                                                    echo "
                                                            <input type='submit' class='admSubmit' value='�������' />
                                                        </form>
                                                    ";
                                                }
                                            }
                                            else
                                            {
                                                if($_REQUEST['level'] == '2')
                                                {
                                                    $catArray = array();
                                                    $namesArray = array();

                                                    $categoriesResult = $mysqli->query("SELECT DISTINCT category FROM subcategories_new WHERE id < 1000 AND type = '".$_SESSION['type']."'");

                                                    while($categories = $categoriesResult->fetch_array(MYSQLI_NUM))
                                                    {
                                                        array_push($catArray, $categories[0]);
                                                    }

                                                    for($i = 0; $i < count($catArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT name FROM categories_new WHERE id = '".$catArray[$i]."'");
                                                        $category = $categoryResult->fetch_array(MYSQLI_NUM);

                                                        array_push($namesArray, $category[0]);
                                                    }

                                                    sort($namesArray);

                                                    echo "
                                                        <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    for($i = 0; $i < count($namesArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE name = '".$namesArray[$i]."'");
                                                        $category = $categoryResult->fetch_assoc();

                                                        echo "
                                                            <option value='".$category['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $category['id']){echo " selected";} echo ">".$category['name']."</option>
                                                        ";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        $subcategoriesResult = $mysqli->query("SELECT * FROM subcategories_new WHERE category = '".$_REQUEST['c']."' ORDER BY name");

                                                        echo "
                                                            <form name='selectSubcategoryForm' method='post' action='../scripts/admin/selectSubcategoryC.php'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ������ -</option>
                                                        ";

                                                        while($subcategories = $subcategoriesResult->fetch_assoc())
                                                        {
                                                            echo "
                                                                <option value='".$subcategories['id']."'"; if(!empty($_REQUEST['s']) and $_REQUEST['s'] == $subcategories['id']){echo " selected";} echo ">".$subcategories['name']."</option>
                                                            ";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s']))
                                                        {
                                                            $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory = '".$_REQUEST['s']."'");

                                                            echo "
                                                                <form name='deleteSubcategoryForm' id='deleteSubcategoryForm' method='post' action='../scripts/admin/deleteSubcategory.php'>
                                                            ";

                                                            if($goodsResult->num_rows != 0)
                                                            {
                                                                echo "
                                                                    <label class='admLabel' onclick='checkboxClick(\"subcategoryDeleteCheckbox\")' style='cursor: pointer;'>������� ������ ������ � ��������</label>
                                                                    <input type='checkbox' name='subcategoryDeleteCheckbox' id='subcategoryDeleteCheckbox' value='1' style='margin-left: -90px; top: 6px; cursor: pointer;' />
                                                                    <br /><br />
                                                                ";
                                                            }                                                         

                                                            echo "
                                                                    <input type='submit' class='admSubmit' value='�������' />
                                                                </form>
                                                            ";
                                                        }
                                                    }
                                                }

                                                if($_REQUEST['level'] == "3")
                                                {
                                                    $subArray = array();
                                                    $cleanSubArr = array();
                                                    $catArray = array();
                                                    $namesArray = array();

                                                    $subcategories2Result = $mysqli->query("SELECT DISTINCT subcategory FROM subcategories2");
                                                    while($subcategories2 = $subcategories2Result->fetch_array(MYSQLI_NUM))
                                                    {
                                                        array_push($subArray, $subcategories2[0]);
                                                    }

                                                    for($i = 0; $i < count($subArray); $i++)
                                                    {
                                                        $subcategoryResult = $mysqli->query("SELECT type FROM subcategories_new WHERE id = '".$subArray[$i]."'");
                                                        $subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

                                                        if($subcategory[0] == $_SESSION['type'])
                                                        {
                                                            array_push($cleanSubArr, $subArray[$i]);
                                                        }
                                                    }

                                                    for($i = 0; $i < count($cleanSubArr); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT category FROM subcategories_new WHERE id = '".$cleanSubArr[$i]."'");
                                                        $category = $categoryResult->fetch_array(MYSQLI_NUM);

                                                        $count = 0;

                                                        for($j = 0; $j < count($catArray); $j++)
                                                        {
                                                            if($catArray[$j] == $category[0])
                                                            {
                                                                $count++;
                                                            }
                                                        }

                                                        if($count == 0)
                                                        {
                                                            array_push($catArray, $category[0]);
                                                        }
                                                    }

                                                    for($i = 0; $i < count($catArray); $i++)
                                                    {
                                                        $categoryNameResult = $mysqli->query("SELECT name FROM categories_new WHERE id = '".$catArray[$i]."'");
                                                        $categoryName = $categoryNameResult->fetch_array(MYSQLI_NUM);

                                                        array_push($namesArray, $categoryName[0]);
                                                    }

                                                    sort($namesArray);

                                                    echo "
                                                        <form name='selectCategoryForm' method='post' action='../scripts/admin/selectCategoryC.php'>
                                                            <label class='admLabel'>�������� ���������:</label>
                                                            <br />
                                                            <select class='admSelect' name='categorySelect' id='categorySelect' size='1' onchange='this.form.submit()'>
                                                                <option value=''>- �������� ��������� -</option>
                                                    ";

                                                    for($i = 0; $i < count($namesArray); $i++)
                                                    {
                                                        $categoryResult = $mysqli->query("SELECT * FROM categories_new WHERE name = '".$namesArray[$i]."'");
                                                        $category = $categoryResult->fetch_assoc();

                                                        echo "
                                                            <option value='".$category['id']."'"; if(!empty($_REQUEST['c']) and $_REQUEST['c'] == $category['id']){echo " selected";} echo ">".$category['name']."</option>
                                                        ";
                                                    }

                                                    echo "
                                                            </select>
                                                        </form>
                                                        <br /><br />
                                                    ";

                                                    if(!empty($_REQUEST['c']))
                                                    {
                                                        $namesArray = array();

                                                        for($i = 0; $i < count($cleanSubArr); $i++)
                                                        {
                                                            $subcategoryResult = $mysqli->query("SELECT name FROM subcategories_new WHERE id = '".$cleanSubArr[$i]."' AND category = '".$_SESSION['c']."'");
                                                            $subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);

                                                            if(!empty($subcategory))
                                                            {
                                                                array_push($namesArray, $subcategory[0]);
                                                            }
                                                        }

                                                        sort($namesArray);

                                                        echo "
                                                            <form name='selectSubcategoryForm' method='post' action='../scripts/admin/selectSubcategoryC.php'>
                                                                <label class='admLabel'>�������� ������:</label>
                                                                <br />
                                                                <select class='admSelect' name='subcategorySelect' id='subcategorySelect' size='1' onchange='this.form.submit()'>
                                                                    <option value=''>- �������� ������ -</option>
                                                        ";

                                                        for($i = 0; $i < count($namesArray); $i++)
                                                        {
                                                            $subcategoryResult = $mysqli->query("SELECT * FROM subcategories_new WHERE name = '".$namesArray[$i]."'");
                                                            $subcategory = $subcategoryResult->fetch_assoc();

                                                            echo "
                                                                <option value='".$subcategory['id']."'"; if(!empty($_REQUEST['s']) and $_REQUEST['s'] == $subcategory['id']){echo " selected";} echo ">".$subcategory['name']."</option>
                                                            ";
                                                        }

                                                        echo "
                                                                </select>
                                                            </form>
                                                            <br /><br />
                                                        ";

                                                        if(!empty($_REQUEST['s']))
                                                        {
                                                            $subcategories2Result = $mysqli->query("SELECT * FROM subcategories2 WHERE subcategory = '".$_REQUEST['s']."'");

                                                            echo "
                                                                <form name='selectSubcategory2Form' method='post' action='../scripts/admin/selectSubcategory2.php'>
                                                                    <label class='admLabel'>�������� ���������:</label>
                                                                    <br />
                                                                    <select class='admSelect' name='subcategory2Select' id='subcategory2Select' size='1' onchange='this.form.submit()'>
                                                                        <option value=''>- �������� ��������� -</option>
                                                            ";

                                                            while($subcategories2 = $subcategories2Result->fetch_assoc())
                                                            {
                                                                echo "
                                                                    <option value='".$subcategories2['id']."'"; if(!empty($_REQUEST['s2']) and $_REQUEST['s2'] == $subcategories2['id']){echo " selected";} echo ">".$subcategories2['name']."</option>
                                                                ";
                                                            }

                                                            echo "
                                                                    </select>
                                                                </form>
                                                                <br /><br />
                                                            ";

                                                            if(!empty($_REQUEST['s2']))
                                                            {
                                                                $goodsResult = $mysqli->query("SELECT * FROM catalogue_new WHERE subcategory2 = '".$_REQUEST['s2']."'");

                                                                echo "
                                                                    <form name='deleteSubcategory2Form' id='deleteSubcategory2Form' method='post' action='../scripts/admin/deleteSubcategory2.php'>
                                                                ";

                                                                if($goodsResult->num_rows != 0)
                                                                {
                                                                    echo "
                                                                        <label class='admLabel' onclick='checkboxClick(\"subcategory2DeleteCheckbox\")' style='cursor: pointer;'>������� ��������� ������ � ��������</label>
                                                                        <input type='checkbox' name='subcategory2DeleteCheckbox' id='subcategory2DeleteCheckbox' value='1' style='margin-left: -90px; top: 6px; cursor: pointer;' />
                                                                        <br /><br />
                                                                    ";
                                                                }

                                                                echo "
                                                                        <input type='submit' class='admSubmit' value='�������' />
                                                                    </form>
                                                                ";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                        else
                        {
                            echo "
                                <span class='admMenuFont'<br />�� ���������� � ������� ������ �������� ��� ��������� �����. ��� ����������� ������ ���������� ������� ���� �� ��������� ��������:</span>
                                <br /><br />
                                <ul class='admUL'>
                                    <a href='admin.php?section=categories&action=add' class='noBorder'><li>���������� ����� ��������</li></a>
                                    <a href='admin.php?section=categories&action=edit' class='noBorder'><li>�������������� ������������ ��������</li></a>
                                    <a href='admin.php?section=categories&action=delete' class='noBorder'><li>�������� ��������</li></a>
                                </ul>
                            ";
                        }
                        break;
                    case "users":
                        if(!empty($_REQUEST['action']))
                        {
                            switch($_REQUEST['action'])
                            {
                                case "mail":
                                    echo "
                                        <span class='admMenuFont'>����������� e-mail ��������</span>
                                        <br /><br ><br />
                                        <form name='emailSendForm' id='emailSendForm' method='post' action='../scripts/admin/sendEmail.php' enctype='multipart/form-data'>
                                            <label class='admLabel'>���� ������:</label>
                                            <br />
                                            <input type='text' class='admInput' id='emailThemeInput' name='emailTheme'"; if(!empty($_SESSION['emailTheme'])) {echo " value='".$_SESSION['emailTheme']."'";} echo " />
                                            <br /><br />
                                            <input type='radio' name='emailType' id='sendAll' class='admRadio' onclick='hideField()' value='all'"; if(empty($_SESSION['emailType']) or $_SESSION['emailType'] == "all") {echo " checked='checked'";} echo " />
                                            <label class='admRadioLabel' for='sendAll' onclick='hideField()'>��������� �� ���� ������� �� ���������� ����</label>
                                            <br />
                                            <input type='radio' name='emailType' id='sendGroup' class='admRadio' onclick='addressGroup()' value='group'"; if($_SESSION['emailType'] == "group") {echo " checked='checked'";} echo " />
                                            <label class='admRadioLabel' for='sendOne' onclick='addressGroup()'>��������� ���� �� ��������� �������</label>
                                            <br />
                                            <input type='radio' name='emailType' id='sendOne' class='admRadio' onclick='addressField()' value='one'"; if($_SESSION['emailType'] == "one") {echo " checked='checked'";} echo " />
                                            <label class='admRadioLabel' for='sendOne' onclick='addressField()'>��������� ������ �������</label>
                                            <div id='addressField'>
                                    ";
                                    if($_SESSION['emailAddress'] == "one")
                                    {
                                        echo "<br /><br /><label class='admLabel'>������� ����� ����������:</label><br /><input type='text' class='admInput' name='emailAddress' id='addressFieldInput' value='".$_SESSION['emailAdress']."' />";
                                    }
                                    echo "
                                            </div>
                                            <br /><br />
                                            <label class='admLabel'>����� ��������:</label>
                                            <br />
                                            <textarea id='emailText' name='emailText' style='width: 100%;' rows='20'>"; if(!empty($_SESSION['emailText'])) {echo $_SESSION['emailText'];} echo "</textarea>
                                            <br /><br />
                                            <label class='admLabel'>���������� ����:</label>
                                            <br />
                                            <input type='file' name='emailFile[]' id='emailFile' class='admFile' multiple />
                                            <br /><br />
                                            <input type='submit' class='admSubmit' value='���������' />
                                            <input type='text' id='trigger' name='trigger' style='display: none;' readonly />
                                        </form>
                                    ";

                                    echo "
                                        <a href='admin.php?section=users&action=mail-history&p=1' class='noBorder'>
                                            <div class='adminMailButton' id='mailHistoryButton' onmouseover='buttonColor(\"1\", \"mailHistoryButton\", \"mailHistoryButtonText\")' onmouseout='buttonColor(\"0\", \"mailHistoryButton\", \"mailHistoryButtonText\")'>
                                                <span class='admWhiteText' id='mailHistoryButtonText'>������� ��������</span>
                                            </div>
                                        </a>
                                    ";

                                    unset($_SESSION['newAddress']);
                                    unset($_SESSION['emailTheme']);
                                    unset($_SESSION['emailText']);
                                    unset($_SESSION['emailType']);
                                    break;
                                case "mail-history":
                                    $mailResult = $mysqli->query("SELECT * FROM mail_result ORDER BY date DESC LIMIT ".$start.", 10");
                                    $mailCountResult = $mysqli->query("SELECT COUNT(id) FROM mail_result");
                                    $mailCount = $mailCountResult->fetch_array(MYSQLI_NUM);

                                    echo "
                                        <table>
                                            <tr>
                                                <td class='adminTDNumber' style='background-color: #dddddd;'>
                                                    <span class='admLabel'>�</span>
                                                </td>
                                                <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>���� ���������</span>
                                                </td>
                                                <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>����� ��������</span>
                                                </td>
                                                <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>����������</span>
                                                </td>
                                                <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>���� �����������</span>
                                                </td>
                                                <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>������������</span>
                                                </td>
                                                <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>����������</span>
                                                </td>
                                                <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                    <span class='admLabel'>�� ����������</span>
                                                </td>
                                            </tr>
                                    ";

                                    while($mail = $mailResult->fetch_assoc())
                                    {
                                        $count++;
                                        $number = $mailCount[0] - $_REQUEST['p'] * 10 + 11 - $count;

                                        if(substr_count($mail['send_to'], '@') == 1) {
                                            $from = $mail['send_to'];
                                        } else {
                                            if(strlen($mail['send_to']) == 1) {
                                                $locationResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$mail['send_to']."'");
                                                $location = $locationResult->fetch_array(MYSQLI_NUM);

                                                $from = $location[0];

                                                if($mail['send_to'] != 8) {
                                                    $from .= " �������";
                                                }
                                            } else {
                                                if($mail['send_to'] == "all") {
                                                    $from  = "���� ��������";
                                                } else {
                                                    $from = "";
                                                }
                                            }
                                        }

                                        echo "
                                            <tr>
                                                <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".$number."</span>
                                                </td>
                                                <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".$mail['subject']."</span>
                                                </td>
                                                <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admULFont' style='cursor: pointer;' onclick='showMailText(\"".$mail['id']."\")'>����� ��������</span>
                                                </td>
                                                <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".$from."</span>
                                                </td>
                                                <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".substr($mail['date'], 0, 10)."</span>
                                                </td>
                                                <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".$mail['count']."</span>
                                                </td>
                                                <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                                    <span class='admLabel'>".$mail['send']."</span>
                                                </td>
                                                <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd'";} echo ">
                                        ";

                                        if(($mail['count'] - $mail['send']) > 0) {
                                            echo "<span class='admULFont' style='cursor: pointer;' onclick='showFailedEmails(\"".$mail['id']."\")'>".($mail['count'] - $mail['send'])."</span>";
                                        } else {
                                            echo "<span class='admLabel'>0</span>";
                                        }

                                        echo "
                                                </td>
                                            </tr>
                                        ";
                                    }

                                    echo "
                                        </table>
                                        <div id='mailTextBlock'></div>
                                    ";

                                    if($numbers > 1)
                                    {
                                        if($numbers <= 7)
                                        {
                                            echo "
                                                <br /><br />
                                                <div id='admPageNumbers'>
                                            ";

                                            if($_REQUEST['p'] == 1)
                                            {
                                                echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                            }
                                            else
                                            {
                                                echo "<a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                            }

                                            for($i = 1; $i <= $numbers; $i++)
                                            {
                                                if($_REQUEST['p'] != $i)
                                                {
                                                    echo "<a href='admin.php?section=users&action=mail-history&p=".$i."' class='noBorder'>";
                                                }

                                                echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                if($_REQUEST['p'] != $i)
                                                {
                                                    echo "</a>";
                                                }
                                            }

                                            if($_REQUEST['p'] == $numbers)
                                            {
                                                echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                            }
                                            else
                                            {
                                                echo "<a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                            }

                                            echo "</div>";
                                        }
                                        else
                                        {
                                            if($_REQUEST['p'] < 5)
                                            {
                                                echo "
                                                    <br /><br />
                                                    <div id='admPageNumbers'>
                                                ";

                                                if($_REQUEST['p'] == 1)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                }

                                                for($i = 1; $i <= 5; $i++)
                                                {
                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "<a href='admin.php?section=users&action=mail-history&p=".$i."' class='noBorder'>";
                                                    }

                                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "</a>";
                                                    }
                                                }

                                                echo "<div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>";
                                                echo "<a href='admin.php?section=users&action=mail-history&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>";

                                                if($_REQUEST['p'] == $numbers)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                }

                                                echo "</div>";
                                            }
                                            else
                                            {
                                                $check = $numbers - 3;

                                                if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
                                                {
                                                    echo "
                                                        <br /><br />
                                                        <div id='admPageNumbers'>
                                                            <a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                            <a href='admin.php?section=users&action=mail-history&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                            <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                            <a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
                                                            <div class='admPageNumberBlockActive'><span class='admWhiteFont'>".$_REQUEST['p']."</span></div>
                                                            <a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
                                                            <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                            <a href='admin.php?section=users&action=mail-history&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>
                                                            <a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>
                                                        </div>
                                                    ";
                                                }
                                                else
                                                {
                                                    echo "
                                                        <br /><br />
                                                        <div id='admPageNumbers'>
                                                            <a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                            <a href='admin.php?section=users&action=mail-history&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                            <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                    ";

                                                    for($i = ($numbers - 4); $i <= $numbers; $i++)
                                                    {
                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "<a href='admin.php?section=users&action=mail-history&p=".$i."' class='noBorder'>";
                                                        }

                                                        echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "</a>";
                                                        }
                                                    }

                                                    if($_REQUEST['p'] == $numbers)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=mail-history&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                    }

                                                    echo "</div>";
                                                }
                                            }
                                        }
                                    }
                                    
                                    break;
                                case "maillist":
                                    $symbols = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '� ������� (�����, ������������� � �.�.)');

                                    echo "
                                        <div id='addressSearchResult'></div>
                                        <div class='container'>
                                        <div id='clientsList'>
                                        <span class='admMenuFont'>������ ������� ���������� ����</span>
                                        <br /><br ><br />
                                       <div id='activeFilters'>
                                        ";

                                    if($_REQUEST['active'] == "false")
                                    {
                                        echo "<a href='admin.php?section=users&action=maillist&active=true&p=1' class='noBorder'>";
                                    }

                                    echo "
                                        <div "; if($_REQUEST['active'] == "true"){echo "class='adminMailButtonActive'";} else {echo "class='adminMailButton' onmouseover='buttonColor(\"1\", \"activeEmails\", \"activeEmailsText\")' onmouseout='buttonColor(\"0\", \"activeEmails\", \"activeEmailsText\")'";} echo " id='activeEmails'>
                                            <span "; if($_REQUEST['active'] == "true"){echo "class='admRedText'";} else {echo "class='admWhiteText'";} echo " id='activeEmailsText'>��������</span>
                                        </div>
                                    ";

                                    if($_REQUEST['active'] == "false")
                                    {
                                        echo "</a>";
                                    }

                                    if($_REQUEST['active'] == "true")
                                    {
                                        echo "<a href='admin.php?section=users&action=maillist&active=false&p=1' class='noBorder'>";
                                    }

                                    echo "
                                        <div "; if($_REQUEST['active'] == "false"){echo "class='adminMailButtonActive'";} else {echo "class='adminMailButton' onmouseover='buttonColor(\"1\", \"inactiveEmails\", \"inactiveEmailsText\")' onmouseout='buttonColor(\"0\", \"inactiveEmails\", \"inactiveEmailsText\")'";} echo " style='margin-left: 10px;' id='inactiveEmails'>
                                            <span "; if($_REQUEST['active'] == "false"){echo "class='admRedText'";} else {echo "class='admWhiteText'";} echo " id='inactiveEmailsText'>����������</span>
                                        </div>
                                    ";

                                    if($_REQUEST['active'] == "true")
                                    {
                                        echo "</a>";
                                    }

                                    echo "
                                        <div style='clear: both;'></div>
                                        </div>
                                        <br />
                                        <div id='filters'>
                                        <form name='chooseStartForm' id='chooseStartForm' method='post' action='../scripts/chooseStartSymbol.php'>
                                            <label class='admLabel'>�������� ��� ������, ������������ �:</label>
                                            <br />
                                            <select class='admSelect' name='startSymbolSelect' onchange='this.form.submit()'>
                                                <option value=''>������ ������ �������</option>
                                                <option value='zero'"; if($_REQUEST['start'] == "zero") {echo " selected";} echo ">0</option>
                                    ";

                                    for($i = 1; $i < count($symbols) - 1; $i++)
                                    {
                                        echo "<option value='".$symbols[$i]."'"; if($_REQUEST['start'] == $symbols[$i]) {echo " selected";} echo ">".$symbols[$i]."</option>";
                                    }

                                    echo "
                                            </select>
                                        </form>
                                        <br /><br />
                                        <form id='selectProvinceForm' method='post' action='../scripts/admin/chooseProvince.php'>
                                            <label class='admLabel'>�������� ��� ������ �� ��������� �������:</label>
                                            <br />
                                            <select class='admSelect' name='provinceSelect' onchange='this.form.submit()'>
                                                <option value=''"; if(empty($_REQUEST['province'])) {echo " selected";} echo ">��� �������</option>
                                    ";

                                    $provincesResult = $mysqli->query("SELECT * FROM locations");
                                    while($provinces = $provincesResult->fetch_assoc()) {
                                        echo "
                                            <option value='".$provinces['id']."'"; if($_REQUEST['province'] == $provinces['id']) {echo " selected";} echo ">".$provinces['name']."</option>
                                        ";
                                    }

                                    echo "
                                            </select>
                                        </form>
                                        </div>
                                        <br /><br />
                                        <div id='searchFormBlock'>
                                            <form id='searchAddressForm' name='searchAddressForm' method='post'>
                                                <label class='admLabel'>����� e-mail ������:</label>
                                                <br />
                                                <input type='text' class='admInput' name='addressSearch' id='addressSearchInput' />
                                            </form>
                                        </div>
                                        </div>
                                    ";

                                    echo "
                                            <div id='newAddress'>
                                                <span class='admMenuFont'>���������� ������ � ���������� ����</span>
                                                <br /><br ><br />
                                                <form name='newAddressForm' id='newAddressForm' method='post' action='../scripts/admin/addAddress.php'>
                                                    <label class='admLabel'>������� ����� e-mail �����:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='newAddress' id='newAddressInput'"; if(!empty($_SESSION['newAddress'])) {echo " value='".$_SESSION['newAddress']."'";} echo " autofocus />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ��� / �������� �����������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='newName' id='newAddressInput'"; if(!empty($_SESSION['newName'])) {echo " value='".$_SESSION['newName']."'";} echo " />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ������� (�����������):</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='newPhone' id='newAddressInput'"; if(!empty($_SESSION['newPhone'])) {echo " value='".$_SESSION['newPhone']."'";} echo " />
                                                    <br /><br />
                                                    <label class='admLabel' for='newLocationSelect'>�������� �������:</label>
                                                    <br />
                                                    <select name='newLocation' id='newLocationSelect' class='admSelect'>
                                    ";

                                    $locationResult = $mysqli->query("SELECT * FROM locations ORDER BY id");
                                    while($location = $locationResult->fetch_assoc()) {
                                        echo "
                                            <option value=".$location['id']." "; if($location['id'] == 8) {echo "selected ";} echo ">".$location['name']."</option>
                                        ";
                                    }

                                    echo "
                                                    </select>
                                                    <br /><br />
                                                    <label class='admLabel'>������� (�����������):</label>
                                                    <br />
                                                    <textarea class='admTextarea' name='newNotes' id='newNotesInput'"; if(!empty($_SESSION['newNotes'])) {echo " value='".$_SESSION['newNotes']."'";} echo ">"; if(isset($_SESSION['newNotes'])) {echo $_SESSION['newNotes'];} echo "</textarea>
                                                    <br /><br />
                                                    <input type='submit' class='admSubmit' value='��������' style='right: 0;' />
                                                </form>
                                            </div>
                                        ";

                                    unset($_SESSION['newAddress']);
                                    unset($_SESSION['newName']);

                                    echo "
                                        <div style='clear: both;'></div>
                                        </div>
                                    ";

                                    if(!empty($_REQUEST['start']) or !empty($_REQUEST['province']))
                                    {
                                        if($_REQUEST['active'] == "true")
                                        {
                                            if($_REQUEST['start'] == "zero")
                                            {
                                                if(!empty($_REQUEST['province'])) {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' AND email LIKE '0%' AND location = '".$_REQUEST['province']."' ORDER BY email");
                                                } else {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' AND email LIKE '0%' ORDER BY email");
                                                }
                                            }
                                            else
                                            {
                                                if(!empty($_REQUEST['province'])) {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' AND email LIKE '".$_REQUEST['start']."%' AND location = '".$_REQUEST['province']."' ORDER BY email");
                                                } else {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' AND email LIKE '".$_REQUEST['start']."%' ORDER BY email");
                                                }
                                            }
                                        }

                                        if($_REQUEST['active'] == "false")
                                        {
                                            if($_REQUEST['start'] == "zero")
                                            {
                                                if(!empty($_REQUEST['province'])) {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '0' AND email LIKE '0%' AND location = '".$_REQUEST['province']."' ORDER BY disactivation_date DESC");
                                                } else {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '0' AND email LIKE '0%' ORDER BY disactivation_date DESC");
                                                }
                                            }
                                            else
                                            {
                                                if(!empty($_REQUEST['province'])) {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '0' AND email LIKE '".$_REQUEST['start']."%' AND location='".$_REQUEST['province']."' ORDER BY disactivation_date DESC");
                                                } else {
                                                    $mailResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '0' AND email LIKE '".$_REQUEST['start']."%' ORDER BY disactivation_date DESC");
                                                }
                                            }
                                        }

                                        echo "</div><div style='clear: both;'></div><div id='tableContainer'>";

                                        if($mailResult->num_rows > 0)
                                        {
                                            $count = 0;

                                            echo "<table id='clientsTable'>";

                                            if($_REQUEST['active'] == "true")
                                            {
                                                echo "
                                                    <tr>
                                                        <td class='adminTDNumber' style='background-color: #dddddd;'>
                                                            <span class='admLabel'>�</span>
                                                        </td>
                                                        <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>Email</span>
                                                        </td>
                                                        <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>���/�����������</span>
                                                        </td>
                                                        <td class='adminTDPhone' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDLocation' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDNotes' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDButtons' style='background-color: #dddddd;'>
                                                            <span class='admLabel'>��������</span>
                                                        </td>
                                                    </tr>
                                                ";

                                                while($address = $mailResult->fetch_assoc())
                                                {
                                                    $count++;
                                                    $addressNumber = $count;

                                                    $locationResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$address['location']."'");
                                                    $location = $locationResult->fetch_array(MYSQLI_NUM);

                                                    echo "
                                                        <tr>
                                                            <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <span class='admMenuFont'>".$addressNumber."</span>
                                                            </td>
                                                            <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <div id='emailBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"".$address['id']."\", \"emailBlock".$address['id']."\")' title='������������� e-mail'>".$address['email']."</span></div>
                                                            </td>
                                                            <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editName(\"".$address['id']."\", \"nameBlock".$address['id']."\")'>
                                                                <div id='nameBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� ��� / �������� �����������'>".$address['name']."</span></div>
                                                            </td>
                                                            <td class='adminTDPhone'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editPhone(\"".$address['id']."\", \"phoneBlock".$address['id']."\")'>
                                                                <div id='phoneBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ����� ��������'>".$address['phone']."</span></div>
                                                            </td>
                                                            <td class='adminTDLocation' "; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <div id='locationBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ��������������' onclick='editLocation(\"".$address['id']."\", \"".$address['location']."\", \"locationBlock".$address['id']."\")'>".$location[0]."</span></div>
                                                            </td>
                                                            <td class='adminTDNotes'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editNotes(\"".$address['id']."\", \"notesBlock".$address['id']."\")'>
                                                                <div id='notesBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� �������'>".$address['notes']."</span></div>
                                                            </td>
                                                            <td class='adminTDButtons'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <a href='../scripts/admin/deleteAddress.php?id=".$address['id']."' class='noBorder'><img id='mi".$address['id']."' src='../pictures/system/cross.png' class='noBorder' title='������� ����� �� ���������� ����' style='margin-top: 12px;' onmouseover='mailIcon(\"1\", \"mi".$address['id']."\")' onmouseout='mailIcon(\"0\", \"mi".$address['id']."\")' /></a>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }
                                            }
                                            
                                            if($_REQUEST['active'] == "false")
                                            {
                                                echo "
                                                    <tr>
                                                        <td class='adminTDNumber' style='background-color: #dddddd;'>
                                                            <span class='admLabel'>�</span>
                                                        </td>
                                                        <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>Email</span>
                                                        </td>
                                                        <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>���/�����������</span>
                                                        </td>
                                                        <td class='adminTDPhone' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDLocation' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDNotes' style='background-color: #dddddd; text-align: center;'>
                                                            <span class='admLabel'>�������</span>
                                                        </td>
                                                        <td class='adminTDDate' style='background-color: #dddddd;'>
                                                            <span class='admLabel'>���� �������</span>
                                                        </td>
                                                        <td class='adminTDButtons' style='background-color: #dddddd;'>
                                                            <span class='admLabel'>��������</span>
                                                        </td>
                                                    </tr>
                                                ";

                                                while($address = $mailResult->fetch_assoc())
                                                {
                                                    $count++;
                                                    $addressNumber = $count;

                                                    $locationResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$address['location']."'");
                                                    $location = $locationResult->fetch_array(MYSQLI_NUM);

                                                    echo "
                                                        <tr>
                                                            <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <span class='admMenuFont'>".$addressNumber."</span>
                                                            </td>
                                                            <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <div id='emailBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"".$address['id']."\", \"emailBlock".$address['id']."\")' title='������������� e-mail'>".$address['email']."</span></div>
                                                            </td>
                                                            <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editName(\"".$address['id']."\", \"nameBlock".$address['id']."\")'>
                                                                <div id='nameBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� ��� / �������� �����������'>".$address['name']."</span></div>
                                                            </td>
                                                            <td class='adminTDPhone'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editPhone(\"".$address['id']."\", \"phoneBlock".$address['id']."\")'>
                                                                <div id='phoneBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ����� ��������'>".$address['phone']."</span></div>
                                                            </td>
                                                            <td class='adminTDLocation' "; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <div id='locationBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ��������������' onclick='editLocation(\"".$address['id']."\", \"".$address['location']."\", \"locationBlock".$address['id']."\")'>".$location[0]."</span></div>
                                                            </td>
                                                            <td class='adminTDNotes'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editNotes(\"".$address['id']."\", \"notesBlock".$address['id']."\")'>
                                                                <div id='notesBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� �������'>".$address['notes']."</span></div>
                                                            </td>
                                                            <td class='adminTDDate'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <span class='admLabel'>".$address['disactivation_date']."</span>
                                                            </td>                                                            
                                                            <td class='adminTDButtons'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                                <a href='../scripts/admin/deleteAddress.php?id=".$address['id']."' class='noBorder'><img id='mi".$address['id']."' src='../pictures/system/cross.png' class='noBorder' title='������� ����� �� ���������� ����' style='margin-top: 12px;' onmouseover='mailIcon(\"1\", \"mi".$address['id']."\")' onmouseout='mailIcon(\"0\", \"mi".$address['id']."\")' /></a>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }
                                            }

                                            echo "</table>";
                                        }
                                        else
                                        {
                                            if($_REQUEST['start'] == "zero")
                                            {
                                                echo "<span class='admMenuFont'>�������, ������������ � ������� '0' � ���������� ���� ���.</span>";
                                            }
                                            else
                                            {
                                                echo "<span class='admMenuFont'>�������, ������������ � ������� '".$_REQUEST['start']."' � ���������� ���� ���.</span>";
                                            }
                                        }
                                    }
                                    
                                    if(!empty($_REQUEST['p']) and empty($_REQUEST['start']) and empty($_REQUEST['province']))
                                    {
                                        $count = 0;

                                        echo "
                                            </div>
                                            <div style='clear: both;'></div>
                                            <div id='tableContainer'>
                                                <table id='clientsTable'>
                                        ";

                                        if($_REQUEST['active'] == "true")
                                        {
                                            echo "
                                                <tr>
                                                    <td class='adminTDNumber' style='background-color: #dddddd;'>
                                                        <span class='admLabel'>�</span>
                                                    </td>
                                                    <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>Email</span>
                                                    </td>
                                                    <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>���/�����������</span>
                                                    </td>
                                                    <td class='adminTDPhone' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDLocation' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDNotes' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDButtons' style='background-color: #dddddd;'>
                                                        <span class='admLabel'>��������</span>
                                                    </td>
                                                </tr>
                                            ";

                                            $addressResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '1' ORDER BY email LIMIT ".$start.", 10");

                                            while($address = $addressResult->fetch_assoc())
                                            {
                                                $count++;
                                                $addressNumber = $_REQUEST['p'] * 10 - 10 + $count;

                                                $locationResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$address['location']."'");
                                                $location = $locationResult->fetch_array(MYSQLI_NUM);

                                                echo "
                                                    <tr>
                                                        <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <span class='admMenuFont'>".$addressNumber."</span>
                                                        </td>
                                                        <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <div id='emailBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"".$address['id']."\", \"emailBlock".$address['id']."\")' title='������������� e-mail'>".$address['email']."</span></div>
                                                        </td>
                                                        <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editName(\"".$address['id']."\", \"nameBlock".$address['id']."\")'>
                                                            <div id='nameBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� ��� / �������� �����������'>".$address['name']."</span></div>
                                                            <td class='adminTDPhone'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editPhone(\"".$address['id']."\", \"phoneBlock".$address['id']."\")'>
                                                                <div id='phoneBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ����� ��������'>".$address['phone']."</span></div>
                                                        </td>
                                                        <td class='adminTDLocation' "; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <div id='locationBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ��������������' onclick='editLocation(\"".$address['id']."\", \"".$address['location']."\", \"locationBlock".$address['id']."\")'>".$location[0]."</span></div>
                                                        </td>
                                                        <td class='adminTDNotes'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editNotes(\"".$address['id']."\", \"notesBlock".$address['id']."\")'>
                                                                <div id='notesBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� �������'>".$address['notes']."</span></div>
                                                        </td>
                                                        <td class='adminTDButtons'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <a href='../scripts/admin/deleteAddress.php?id=".$address['id']."' class='noBorder'><img id='mi".$address['id']."' src='../pictures/system/cross.png' class='noBorder' title='������� ����� �� ���������� ����' style='margin-top: 12px;' onmouseover='mailIcon(\"1\", \"mi".$address['id']."\")' onmouseout='mailIcon(\"0\", \"mi".$address['id']."\")' /></a>
                                                        </td>
                                                    </tr>
                                                ";
                                            }
                                        }

                                        if($_REQUEST['active'] == "false")
                                        {
                                            echo "
                                                <tr>
                                                    <td class='adminTDNumber' style='background-color: #dddddd;'>
                                                        <span class='admLabel'>�</span>
                                                    </td>
                                                    <td class='adminTDMail' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>Email</span>
                                                    </td>
                                                    <td class='adminTDName' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>���/�����������</span>
                                                    </td>
                                                    <td class='adminTDPhone' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDLocation' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDNotes' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>�������</span>
                                                    </td>
                                                    <td class='adminTDDate' style='background-color: #dddddd; text-align: center;'>
                                                        <span class='admLabel'>���� �������</span>
                                                    </td>
                                                    <td class='adminTDButtons' style='background-color: #dddddd;'>
                                                        <span class='admLabel'>��������</span>
                                                    </td>
                                                </tr>
                                            ";

                                            $addressResult = $mysqli->query("SELECT * FROM mail WHERE in_send = '0' ORDER BY disactivation_date DESC LIMIT ".$start.", 10");

                                            while($address = $addressResult->fetch_assoc())
                                            {
                                                $count++;
                                                $addressNumber = $_REQUEST['p'] * 10 - 10 + $count;

                                                $locationResult = $mysqli->query("SELECT name FROM locations WHERE id = '".$address['location']."'");
                                                $location = $locationResult->fetch_array(MYSQLI_NUM);

                                                echo "
                                                    <tr>
                                                        <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <span class='admMenuFont'>".$addressNumber."</span>
                                                        </td>
                                                        <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <div id='emailBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' onclick='editEmail(\"".$address['id']."\", \"emailBlock".$address['id']."\")' title='������������� e-mail'>".$address['email']."</span></div>
                                                        </td>
                                                        <td class='adminTDName'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editName(\"".$address['id']."\", \"nameBlock".$address['id']."\")'>
                                                            <div id='nameBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� ��� / �������� �����������'>".$address['name']."</span></div>
                                                        </td>
                                                        <td class='adminTDPhone'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editPhone(\"".$address['id']."\", \"phoneBlock".$address['id']."\")'>
                                                                <div id='phoneBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ����� ��������'>".$address['phone']."</span></div>
                                                        </td>
                                                        <td class='adminTDLocation' "; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <div id='locationBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='�������� ��������������' onclick='editLocation(\"".$address['id']."\", \"".$address['location']."\", \"locationBlock".$address['id']."\")'>".$location[0]."</span></div>
                                                        </td>
                                                        <td class='adminTDNotes'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo " onclick='editNotes(\"".$address['id']."\",\"notesBlock".$address['id']."\")'>
                                                                <div id='notesBlock".$address['id']."'><span class='admULFont' style='cursor: pointer;' title='������������� �������'>".$address['notes']."</span></div>
                                                            </td>
                                                        <td class='adminTDDate'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <span class='admLabel'>".$address['disactivation_date']."</span>
                                                        </td>
                                                        <td class='adminTDButtons'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                            <a href='../scripts/admin/deleteAddress.php?id=".$address['id']."' class='noBorder'><img id='mi".$address['id']."' src='../pictures/system/cross.png' class='noBorder' title='������� ����� �� ���������� ����' style='margin-top: 12px;' onmouseover='mailIcon(\"1\", \"mi".$address['id']."\")' onmouseout='mailIcon(\"0\", \"mi".$address['id']."\")' /></a>
                                                        </td>
                                                    </tr>
                                                ";
                                            }
                                        }
                                        
                                        echo "</table>";

                                        if($numbers > 1)
                                        {
                                            if($numbers <= 7)
                                            {
                                                echo "
                                                    <br /><br />
                                                    <div id='admPageNumbers'>
                                                 ";
                                             
                                                if($_REQUEST['p'] == 1)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                }
                                                    
                                                for($i = 1; $i <= $numbers; $i++)
                                                {
                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".$i."' class='noBorder'>";
                                                    }

                                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "</a>";
                                                    }
                                                }

                                                if($_REQUEST['p'] == $numbers)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                }

                                                echo "</div>";
                                            }
                                            else
                                            {
                                                if($_REQUEST['p'] < 5)
                                                {
                                                    echo "
                                                        <br /><br />
                                                        <div id='admPageNumbers'>
                                                     ";

                                                    if($_REQUEST['p'] == 1)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                    }
                                                    
                                                    for($i = 1; $i <= 5; $i++)
                                                    {
                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".$i."' class='noBorder'>";
                                                        }

                                                        echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "</a>";
                                                        }
                                                    }

                                                    echo "<div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>";
                                                    echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>";

                                                    if($_REQUEST['p'] == $numbers)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                    }

                                                    echo "</div>";
                                                }
                                                else
                                                {
                                                    $check = $numbers - 3;

                                                    if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
                                                                <div class='admPageNumberBlockActive'><span class='admWhiteFont'>".$_REQUEST['p']."</span></div>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>
                                                            </div>
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                        ";

                                                        for($i = ($numbers - 4); $i <= $numbers; $i++)
                                                        {
                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".$i."' class='noBorder'>";
                                                            }

                                                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "</a>";
                                                            }
                                                        }

                                                        if($_REQUEST['p'] == $numbers)
                                                        {
                                                            echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                        }
                                                        else
                                                        {
                                                            echo "<a href='admin.php?section=users&action=maillist&active=".$_REQUEST['active']."&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                        }

                                                        echo "</div>";
                                                    }
                                                }
                                            }
                                        }

                                        echo "</div>";
                                    }
                                    break;
                                case "users":
                                    if(empty($_REQUEST['user']))
                                    {
                                        echo "
                                            <span class='admMenuFont'>������ ������������������ �������������</span>
                                            <br /><br ><br />
                                        ";
                                    }
                                    else
                                    {
                                        $userLoginResult = $mysqli->query("SELECT login FROM users WHERE id = '".$_REQUEST['user']."'");
                                        $userLogin = $userLoginResult->fetch_array(MYSQLI_NUM);

                                        echo "
                                            <span class='admMenuFont'>�������������� ������ ������ ������������ </span><span class='admMenuRedFont'>".$userLogin[0]."</span>
                                            <br /><br ><br />
                                        ";
                                    }

                                    if(!empty($_REQUEST['p']))
                                    {
                                        $count = 0;

                                        echo "<table>";

                                        $usersResult = $mysqli->query("SELECT * FROM users WHERE id <> '1' ORDER BY login LIMIT ".$start.", 10");
                                        while($users = $usersResult->fetch_assoc())
                                        {
                                            $count++;
                                            $usersNumber = $_REQUEST['p'] * 10 - 10 + $count;

                                            echo "
                                                <tr>
                                                    <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                        <span class='admMenuFont'>".$usersNumber."</span>
                                                    </td>
                                                    <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                        <a href='admin.php?section=users&action=users&user=".$users['id']."' class='noBorder'><span class='admULFont' style='cursor: pointer;' title='������������� ������ ������������'>".$users['login']."</span></a>
                                                    </td>
                                                </tr>
                                            ";
                                        }

                                        echo "</table>";

                                        if($numbers > 1)
                                        {
                                            if($numbers <= 7)
                                            {
                                                echo "
                                                    <br /><br />
                                                    <div id='admPageNumbers'>
                                                ";

                                                if($_REQUEST['p'] == 1)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                }

                                                for($i = 1; $i <= $numbers; $i++)
                                                {
                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "<a href='admin.php?section=users&action=users&p=".$i."' class='noBorder'>";
                                                    }

                                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "</a>";
                                                    }
                                                }

                                                if($_REQUEST['p'] == $numbers)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                }

                                                echo "</div>";

                                            }
                                            else
                                            {
                                                if($_REQUEST['p'] < 5)
                                                {
                                                    echo "
                                                        <br /><br />
                                                        <div id='admPageNumbers'>
                                                     ";
                                                 
                                                    if($_REQUEST['p'] == 1)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                    }
                                                    
                                                    for($i = 1; $i <= 5; $i++)
                                                    {
                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "<a href='admin.php?section=users&action=users&p=".$i."' class='noBorder'>";
                                                        }

                                                        echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "</a>";
                                                        }
                                                    }

                                                    echo "<div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>";
                                                    echo "<a href='admin.php?section=users&action=users&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>";

                                                    if($_REQUEST['p'] == $numbers)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                    }

                                                    echo "</div>";
                                                }
                                                else
                                                {
                                                    $check = $numbers - 3;

                                                    if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=users&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
                                                                <div class='admPageNumberBlockActive'><span class='admWhiteFont'>".$_REQUEST['p']."</span></div>
                                                                <a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=users&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>
                                                                <a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>
                                                            </div>
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=users&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                        ";

                                                        for($i = ($numbers - 4); $i <= $numbers; $i++)
                                                        {
                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "<a href='admin.php?section=users&action=users&p=".$i."' class='noBorder'>";
                                                            }

                                                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "</a>";
                                                            }
                                                        }

                                                        if($_REQUEST['p'] == $numbers)
                                                        {
                                                            echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                        }
                                                        else
                                                        {
                                                            echo "<a href='admin.php?section=users&action=users&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                        }

                                                        echo "</div>";
                                                    }
                                                }
                                            }
                                        }

                                        echo "
                                            <div id='admSearchBlock'>
                                                <span class='admMenuFont'>����� ������������</span>
                                                <br /><br />
                                                <form name='admSearchUser' id='admSearchUserForm' method='post'>
                                                    <input type='text' class='admInput' name='userSearch' id='userSearchInput' placeholder='�����...' onfocus='if(this.value==\"�����...\") {this.value = \"\";}' onblur='if(this.value == \"\") {this.value = \"�����...\";}' value='�����...' />
                                                </form>
                                                <div id='admSearchResult' onmouseover='hoverBlock(\"1\")' onmouseout='hoverBlock(\"0\")'></div>
                                            </div>
                                        ";
                                    }

                                    if(!empty($_REQUEST['user']))
                                    {
                                        $userResult = $mysqli->query("SELECT * FROM users WHERE id = '".$_REQUEST['user']."'");
                                        $user = $userResult->fetch_assoc();

                                        echo "
                                            <form name='editUserForm' id='editUserForm' method='post' action='../scripts/admin/editUser.php'>
                                                <div class='colorBlock' style='background-color: #c0ffb8;'>
                                                    <label class='admLabel'>�����:</label>
                                                    <br />
                                                    <input type='text' class='admInput' id='userLoginInput' name='userLogin' value='".$user['login']."' />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ���������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' id='userLoginReasonInput' name='userLoginReason' />
                                                </div>
                                                <br /><br />
                                                <div class='colorBlock' style='background-color: #f9ffb8;'>
                                                    <label class='admLabel'>������:</label>
                                                    <br />
                                                    <span class='admULFont' style='cursor: pointer;' onclick='randomPassword()'>������������� ��������� ������</span>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPassword' id='userPasswordInput' />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ���������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPasswordReason' id='userPasswordReasonInput' />
                                                </div>
                                                <br /><br />
                                                <div class='colorBlock' style='background-color: #ffe2b8;'>
                                                    <label class='admLabel'>E-mail:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userEmail' id='userEmailInput' value='".$user['email']."' />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ���������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userEmailReason' id='userEmailReasonInput' />
                                                </div>
                                                <br /><br />
                                                <div class='colorBlock' style='background-color: #ffb8b8;'>
                                        ";
                                        if(!empty($users['organisation']))
                                        {
                                            echo "
                                                <label class='admLabel'>�����������:</label>
                                                <br />
                                                <input type='text' class='admInput' name='userOrganisation' id='userOrganisationInput' value='".$user['organisation']."' />
                                                <br /><br />
                                                <label class='admLabel'>������� ���������:</label>
                                                <br />
                                                <input type='text' class='admInput' name='userOrganisationReason' id='userOrganisationReasonInput' />
                                                <br /><br />
                                            ";
                                        }
                                        echo "
                                                    <label class='admLabel'>���������� ����:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPerson' id='userPersonInput' value='".$user['person']."' />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ���������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPersonReason' id='userPersonReasonInput' />
                                                </div>
                                                <br /><br />
                                                <div class='colorBlock' style='background-color: #b8d3ff;'>
                                                    <label class='admLabel'>�������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPhone' id='userPhoneInput' value='".$user['phone']."' />
                                                    <br /><br />
                                                    <label class='admLabel'>������� ���������:</label>
                                                    <br />
                                                    <input type='text' class='admInput' name='userPhoneReason' id='userPhoneReasonInput' />
                                                </div>
                                                <br /><br />
                                                <input type='submit' class='admSubmit' value='�������������' />
                                            </form>

                                            <div style='clear: both;'></div>
                                        ";
                                    }
                                    break;
                                case "news":
                                    if(empty($_REQUEST['news']))
                                    {
                                        echo "
                                            <span class='admMenuFont'>������ �������� ��� ���������</span>
                                            <br /><br ><br />
                                        ";
                                    }
                                    else
                                    {
                                        $newsResult = $mysqli->query("SELECT * FROM news WHERE id = '".$_REQUEST['news']."'");
                                        $news = $newsResult->fetch_assoc();

                                        echo "
                                            <span class='admMenuFont'>�������������� ������� \"</span><span class='admMenuRedFont'>".$news['header']."</span><span class='admMenuFont'>\"</span>
                                            <br /><br ><br />
                                        ";
                                    }

                                    if(empty($_REQUEST['news']))
                                    {
                                        $count = 0;

                                        echo "<table>";

                                        $newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news");
                                        $newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);

                                        $newsNum = $newsCount[0] - ($_REQUEST['p'] - 1) * 10 + 1;

                                        $newsResult = $mysqli->query("SELECT * FROM news ORDER BY date_num DESC LIMIT ".$start.", 10");
                                        while($news = $newsResult->fetch_assoc())
                                        {
                                            $count++;
                                            $newsNumber = $newsNum - $count;

                                            $date = substr($news['date'], 0, 10)." � ".substr($news['date'], 11, 5);

                                            echo "
                                                <tr>
                                                    <td class='adminTDNumber'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                        <span class='admMenuFont'>".$newsNumber."</span>
                                                    </td>
                                                    <td class='adminTDMail'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                        <a href='admin.php?section=users&action=news&news=".$news['id']."' class='noBorder'><span class='admULFont'>".$news['header']."</span></a>
                                                    </td>
                                                    <td class='adminTDDate'>
                                                        <span class='admMenuFont' style='font-size: 14px;'>".$date."</span>
                                                    </td>
                                                    <td class='adminTDButtonsNews'"; if($count % 2 == 0) {echo " style='background-color: #dddddd;'";} echo ">
                                                        <a href='admin.php?section=users&action=news&news=".$news['id']."' class='noBorder'><img id='emi".$news['id']."' src='../pictures/system/admEdit.png' class='noBorder' title='������������� �������' style='margin-top: 12px;' onmouseover='editIcon(\"1\", \"emi".$news['id']."\")' onmouseout='editIcon(\"0\", \"emi".$news['id']."\")' /></a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href='../scripts/admin/deleteNews.php?id=".$news['id']."' class='noBorder'><img id='mi".$news['id']."' src='../pictures/system/cross.png' class='noBorder' title='������� �������' style='margin-top: 12px;' onmouseover='mailIcon(\"1\", \"mi".$news['id']."\")' onmouseout='mailIcon(\"0\", \"mi".$news['id']."\")' /></a>
                                                    </td>
                                                </tr>
                                            ";
                                        }

                                        echo "</table>";

                                        echo "
                                            <div id='findNews'>
                                                <span class='admMenuFont'>����� �������</span>
                                                <br /><br />
                                                <form name='admSearchNews' id='admSearchNewsForm' method='post'>
                                                    <input type='text' class='admInput' name='newsSearch' id='newsSearchInput' placeholder='�����...' onfocus='if(this.value==\"�����...\") {this.value = \"\";}' onblur='if(this.value == \"\") {this.value = \"�����...\";}' value='�����...' />
                                                </form>
                                                <div id='admSearchResult' onmouseover='hoverBlock(\"1\")' onmouseout='hoverBlock(\"0\")'></div>

                                                <br /><br />

                                                <a href='admin.php?section=users&action=addNews' class='noBorder'>
                                                    <div id='addNewsButton' onmouseover='buttonColor(\"1\", \"addNewsButton\", \"addNewsText\")' onmouseout='buttonColor(\"0\", \"addNewsButton\", \"addNewsText\")'>
                                                        <span class='admWhiteFont' id='addNewsText' style='font-size: 14px;'>�������� �������</span>
                                                    </div>
                                                </a>

                                            </div>

                                        ";

                                        if($numbers > 1)
                                        {
                                            if($numbers <= 7)
                                            {
                                                echo "
                                                    <br /><br />
                                                    <div id='admPageNumbers'>
                                                ";

                                                if($_REQUEST['p'] == 1)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                }

                                                for($i = 1; $i <= $numbers; $i++)
                                                {
                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "<a href='admin.php?section=users&action=news&p=".$i."' class='noBorder'>";
                                                    }

                                                    echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                    if($_REQUEST['p'] != $i)
                                                    {
                                                        echo "</a>";
                                                    }
                                                }

                                                if($_REQUEST['p'] == $numbers)
                                                {
                                                    echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                }
                                                else
                                                {
                                                    echo "<a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                }

                                                echo "</div>";

                                            }
                                            else
                                            {
                                                if($_REQUEST['p'] < 5)
                                                {
                                                    echo "
                                                        <br /><br />
                                                        <div id='admPageNumbers'>
                                                     ";
                                                 
                                                    if($_REQUEST['p'] == 1)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>����������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>";
                                                    }
                                                    
                                                    for($i = 1; $i <= 5; $i++)
                                                    {
                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "<a href='admin.php?section=users&action=news&p=".$i."' class='noBorder'>";
                                                        }

                                                        echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                        if($_REQUEST['p'] != $i)
                                                        {
                                                            echo "</a>";
                                                        }
                                                    }

                                                    echo "<div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>";
                                                    echo "<a href='admin.php?section=users&action=news&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>";

                                                    if($_REQUEST['p'] == $numbers)
                                                    {
                                                        echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                    }
                                                    else
                                                    {
                                                        echo "<a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                    }

                                                    echo "</div>";
                                                }
                                                else
                                                {
                                                    $check = $numbers - 3;

                                                    if($_REQUEST['p'] >= 5 and $_REQUEST['p'] < $check)
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=news&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] - 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] - 1)."\", \"pbt".($_REQUEST['p'] - 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] - 1)."'>".($_REQUEST['p'] - 1)."</span></div></a>
                                                                <div class='admPageNumberBlockActive'><span class='admWhiteFont'>".$_REQUEST['p']."</span></div>
                                                                <a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div id='pb".($_REQUEST['p'] + 1)."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")' onmouseout='admPageBlock(\"0\", \"pb".($_REQUEST['p'] + 1)."\", \"pbt".($_REQUEST['p'] + 1)."\")'><span class='admMenuRedFont' id='pbt".($_REQUEST['p'] + 1)."'>".($_REQUEST['p'] + 1)."</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                                <a href='admin.php?section=users&action=news&p=".$numbers."' class='noBorder'><div id='pb".$numbers."' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$numbers."\", \"pbt".$numbers."\")' onmouseout='admPageBlock(\"0\", \"pb".$numbers."\", \"pbt".$numbers."\")'><span class='admMenuRedFont' id='pbt".$numbers."'>".$numbers."</span></div></a>
                                                                <a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>
                                                            </div>
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        echo "
                                                            <br /><br />
                                                            <div id='admPageNumbers'>
                                                                <a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] - 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbPrev' onmouseover='admPageBlock(\"1\", \"pbPrev\", \"pbtPrev\")' onmouseout='admPageBlock(\"0\", \"pbPrev\", \"pbtPrev\")'><span class='admMenuRedFont' id='pbtPrev'>����������</span></div></a>
                                                                <a href='admin.php?section=users&action=news&p=1' class='noBorder'><div id='pb1' class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb1\", \"pbt1\")' onmouseout='admPageBlock(\"0\", \"pb1\", \"pbt1\")'><span class='admMenuRedFont' id='pbt1'>1</span></div></a>
                                                                <div class='admPageNumberBlock' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>...</span></div>
                                                        ";

                                                        for($i = ($numbers - 4); $i <= $numbers; $i++)
                                                        {
                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "<a href='admin.php?section=users&action=news&p=".$i."' class='noBorder'>";
                                                            }

                                                            echo "<div id='pb".$i."' "; if($i == $_REQUEST['p']) {echo "class='admPageNumberBlockActive'";} else {echo "class='admPageNumberBlock' onmouseover='admPageBlock(\"1\", \"pb".$i."\", \"pbt".$i."\")' onmouseout='admPageBlock(\"0\", \"pb".$i."\", \"pbt".$i."\")'";} echo "><span "; if($i == $_REQUEST['p']) {echo "class='admWhiteFont'";} else {echo "class='admMenuRedFont' id='pbt".$i."'";} echo ">".$i."</span></div>";

                                                            if($_REQUEST['p'] != $i)
                                                            {
                                                                echo "</a>";
                                                            }
                                                        }

                                                        if($_REQUEST['p'] == $numbers)
                                                        {
                                                            echo "<div class='admPageNumberBlockSide' style='cursor: url(\"../pictures/cursor/no.cur\"), auto;'><span class='admMenuFont'>���������</span></div>";
                                                        }
                                                        else
                                                        {
                                                            echo "<a href='admin.php?section=users&action=news&p=".($_REQUEST['p'] + 1)."' class='noBorder'><div class='admPageNumberBlockSide' id='pbNext' onmouseover='admPageBlock(\"1\", \"pbNext\", \"pbtNext\")' onmouseout='admPageBlock(\"0\", \"pbNext\", \"pbtNext\")'><span class='admMenuRedFont' id='pbtNext'>���������</span></div></a>";
                                                        }

                                                        echo "</div>";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $newsResult = $mysqli->query("SELECT * FROM news WHERE id = '".$_REQUEST['news']."'");
                                        $news = $newsResult->fetch_assoc();

                                        echo "
                                            <form name='editNewsForm' id='editNewsForm' method='post' action='../scripts/admin/editNews.php' enctype='multipart/form-data'>
                                                <label class='admLabel'>���������:</label>
                                                <br />
                                                <input type='text' class='admInput' name='newsHeader' id='newsHeaderInput' value='"; if(isset($_SESSION['newsHeader'])) {echo $_SESSION['newsHeader'];} else {echo $news['header'];} echo "' />
                                                <br /><br />
                                                <label class='admLabel'>������� ��������:</label>
                                                <br />
                                                <input type='text' class='admInput' name='newsShort' id='newsShortInput' value='"; if(isset($_SESSION['newsShort'])) {echo $_SESSION['newsShort'];} else {echo $news['short'];} echo "' style='width: 100%;' />
                                                <br /><br />
                                                <label class='admLabel'>����� �������:</label>
                                                <br />
                                                <textarea name='emailText' id='emailText' style='width: 100%;' rows='20'>"; if(isset($_SESSION['newsText'])) {echo $_SESSION['newsText'];} else {echo $news['text'];} echo "</textarea>
                                                <br /><br />
                                                <input type='submit' value='�������������' class='admSubmit' />
                                            </form>
                                        ";

                                        unset($_SESSION['newsHeader']);
                                        unset($_SESSION['newsShort']);
                                        unset($_SESSION['newsText']);
                                    }
                                    break;
                                case "addNews":
                                    echo "
                                        <span class='admMenuFont'>���������� �������</span>
                                        <br /><br ><br />
                                        <form name='addNewsForm' id='addNewsForm' method='post' action='../scripts/admin/addNews.php' enctype='multipart/form-data'>
                                            <label class='admLabel'>���������:</label>
                                            <br />
                                            <input type='text' class='admInput' name='newsHeader' id='newsHeaderInput' value='"; if(isset($_SESSION['newsHeader'])) {echo $_SESSION['newsHeader'];} echo "' />
                                            <br /><br />
                                            <label class='admLabel'>������� ��������:</label>
                                            <br />
                                            <input type='text' class='admInput' name='newsShort' id='newsShortInput' value='"; if(isset($_SESSION['newsShort'])) {echo $_SESSION['newsShort'];} echo "' style='width: 100%;' />
                                            <br /><br />
                                            <label class='admLabel'>����� �������:</label>
                                            <br />
                                            <textarea name='emailText' id='emailText' style='width: 100%;' rows='20'>"; if(isset($_SESSION['newsText'])) {echo $_SESSION['newsText'];} echo "</textarea>
                                            <br /><br />
                                            <input type='submit' value='��������' class='admSubmit' />
                                        </form>

                                        <div id='newAddress' style='margin-top: -40px;'>
                                            <a href='admin.php?section=users&action=news' class='noBorder'>
                                                <div id='addNewsButton' onmouseover='buttonColor(\"1\", \"addNewsButton\", \"addNewsText\")' onmouseout='buttonColor(\"0\", \"addNewsButton\", \"addNewsText\")'>
                                                    <span class='admWhiteFont' id='addNewsText' style='font-size: 14px;'>����� � ������ ��������</span>
                                                </div>
                                            </a>
                                        </div>

                                        <div style='clear: both;'></div>
                                    ";

                                    unset($_SESSION['newsHeader']);
                                    unset($_SESSION['newsShort']);
                                    unset($_SESSION['newsText']);
                                    break;
                                default:
                                    break;
                            }
                        }
                        else
                        {
                            echo "
                                <span class='admMenuFont'<br />�� ���������� � ������� ������ ��������� ������� �����. ��� ����������� ������ ���������� ������� ���� �� ��������� ��������:</span>
                                <br /><br />
                                <ul class='admUL'>
                                    <a href='admin.php?section=users&action=mail' class='noBorder'><li>����������� �������� e-mail ��������</li></a>
                                    <a href='admin.php?section=users&action=maillist' class='noBorder'><li>������������� �������� ��� ���������� �������������</li></a>
                                    <a href='admin.php?section=users&action=users' class='noBorder'><li>������ ���������� ��������������</li></a>
                                    <a href='admin.php?section=users&action=news' class='noBorder'><li>������ �������� � �������������� ��������</li></a>
                                </ul>
                            ";
                        }
                        break;
                    default:
                        break;
                }
            }
        ?>
    </div>

    <script type='text/javascript'>
        var gbBottom = $('#admGoodBlock').offset().top + $('#admGoodBlock').height();
        var acBottom = $('#admContent').offset().top + $('#admContent').height();

        if(gbBottom >= acBottom)
        {
            $('#admContent').height($('#admContent').offset().top + $('#admGoodBlock').height() + 30);
        }
    </script>

    <?php
        if($_REQUEST['action'] == "maillist")
        {
            if(!empty($_REQUEST['start']))
            {
                echo "
                    <script type='text/javascript'>
                        $('#admContent').height($('#admContent').height() + 80);
                    </script>
                ";
            }
            else
            {
                echo "
                    <script type='text/javascript'>
                        $('#admContent').height($('#admContent').height() + 30);
                    </script>
                ";
            }
        }
    ?>

    <script type='text/javascript'>
        $(window).load(function() {
            if(document.getElementById('admPageNumbers')) {
                var contentBottom = $('#admContent').offset().top + $('#admContent').height();
                var numbersBottom = $('#admPageNumbers').offset().top + $('#admPageNumbers').height();

                if(contentBottom < numbersBottom) {
                    $('#admContent').height(parseInt(numbersBottom - 100))
                }
            }

            if(document.getElementById('newAddress')) {
                var trueHeight = $('#newAddress').offset().top + $('#newAddress').height();

                if($('.container').height() > $('#newAddress').height()) {
                    $('.container').height($('#newAddress').height());

                    if($('#admContent').height() > $('.container').height()) {
                        $('#admContent').height($('.container').height() + 70);
                    }
                }

                if($('.container').height() <= $('#newAddress').height()) {
                    $('.container').height($('#newAddress').height());

                    if($('#admContent').height() <= $('.container').height()) {
                        $('#admContent').height($('.container').height() + 70);
                    }
                }

                if($('#admContent').height() > trueHeight) {
                    $('#admContent').height($('#newAddress').height() + 70);
                }
            }
        });
    </script>

</body>

</html>