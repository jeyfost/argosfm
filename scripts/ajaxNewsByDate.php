<?php

	include('connect.php');
	include('calendar.php');

	for($i = 1; $i < $_POST['start']; $i++)
	{
		echo "<div class='cDay'></div>";
	}

	for($i = 0; $i < $_POST['days']; $i++)
	{
		$date = ($i + 1)."-".$_POST['month']."-".$_POST['year'];

		$newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news WHERE date_dmy = '".$date."'");
		$newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);

		if($_POST['year'] != getCurrentYear() or ($_POST['year'] == getCurrentYear() and $_POST['month'] != getCurrentMonth()))
		{
			if($newsCount[0] == 0)
			{
				echo "<div class='cDay'><span class='goodStyle'>".($i + 1)."</span></div>";
			}
			else
			{
				if(strlen($i) == 1)
				{
					$d = "0".($i + 1);
				}
				else
				{
					$d = $i + 1;
				}

				if($_POST['selectedDate'] != "" and substr($_POST['selectedDate'], 0, 2) == $d and $_POST['month'] == substr($_POST['selectedDate'], 3, 2) and $_POST['year'] == substr($_POST['selectedDate'], 6, 4))
				{
					echo "<div class='cDay' style='background-color: #df4e47;'><span class='goodStyle' style='color: #ffffff'>".($i+1)."</span></div>";
				}
				else
				{
					echo "
						<a href='news.php?date=".$date."&p=1' class='noBorder'>
							<div class='cDay' id='cDay".($i+1)."' style='background-color: #dddddd;' onmouseover='buttonColor(\"1\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")' onmouseout='buttonColor(\"0\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")'><span class='goodStyle' style='color: #df4e47' id='cDayText".($i+1)."'>".($i+1)."</span></div>
						</a>
					";
				}
			}
		}

		if($_POST['year'] == getCurrentYear() and $_POST['month'] == getCurrentMonth())
		{
			if($i >= getCurrentDay())
			{
				echo "
					<div class='cDay'><span class='goodStyle' style='color: #aeaeae;'>".($i + 1)."</span></div>
				";
			}
			else
			{
				if($newsCount[0] == 0)
				{
					echo "<div class='cDay'><span class='goodStyle'>".($i + 1)."</span></div>";
				}
				else
				{

					if(strlen($i) == 1)
					{
						$d = "0".($i + 1);
					}
					else
					{
						$d = $i + 1;
					}

					if($_POST['selectedDate'] != "" and substr($_POST['selectedDate'], 0, 2) == $d and $_POST['month'] == substr($_POST['selectedDate'], 3, 2) and $_POST['year'] == substr($_POST['selectedDate'], 6, 4))
					{
						echo "<div class='cDay' style='background-color: #df4e47;'><span class='goodStyle' style='color: #ffffff'>".($i+1)."</span></div>";
					}
					else
					{
						echo "
							<a href='news.php?date=".$date."&p=1' class='noBorder'>
								<div class='cDay' id='cDay".($i+1)."' style='background-color: #dddddd;' onmouseover='buttonColor(\"1\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")' onmouseout='buttonColor(\"0\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")'><span class='goodStyle' style='color: #df4e47' id='cDayText".($i+1)."'>".($i+1)."</span></div>
							</a>
						";
					}
				}
			}
		}
	}

?>