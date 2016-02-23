<?php
	session_start();
	if(empty($_SESSION['id']))
	{
		header("Location: ../index.php");
	}
	
	include('../scripts/connect.php');
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php
		$codes = array();
		$free = array();
		$codeResult = mysql_query("SELECT code FROM catalogue_new ORDER BY code");
		while($code = mysql_fetch_array($codeResult, MYSQL_NUM))
		{
			if($code[0] != 0)
			{
				array_push($codes, $code[0]);
			}
		}
		
		for($i = 0001; $i <= 9999; $i++)
		{
			$count = 0;
			
			for($j = 0; $j < count($codes); $j++)
			{
				if($i == $codes[$j])
				{
					$count++;
				}
			}
			
			if($count == 0)
			{
				array_push($free, $i);
			}
		}
		
		for($i = 0; $i < count($free); $i++)
		{
			echo $free[$i]."<br />";
		}
	?>
</body>
</html>