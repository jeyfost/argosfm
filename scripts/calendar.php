<?php

	include('config.php');

	$link = mysql_connect($host, $user, $password);
	mysql_select_db($db, $link);

	function initCalendar($selectedDate)
	{
		if($selectedDate == "" or (substr($selectedDate, 3, 2) == getCurrentMonth() and substr($selectedDate, 6, 4) == getCurrentYear()))
		{
			if($selectedDate != "")
			{
				$selectedDay = substr($selectedDate, 0, 2);
			}

			echo "
				<div id='calendar' style='z-index: 18;'>
					<div id='cLeftArrow' class='cArrow'><img src='pictures/system/cArrowLeft.png' class='noBorder' id='cLeftArrowIMG' onclick='prevMonth(\"".getCurrentMonth()."\", \"".getCurrentYear()."\", \"".getCurrentMonth()."\", \"".getCurrentYear()."\""; if($selectedDate != "") {echo ", \"".$selectedDate."\"";} echo ")' /></div>
					<div id='cMonth'><span class='goodStyle' id='cMonthText'>".getMonthName(getCurrentMonth())."</span><span class='goodStyle'>, </span><span class='goodStyle' id='cYearText'>".getCurrentYear()."</span></div>
					<div id='cRightArrow' class='cArrow'></div>
					<div class='cArrow'></div><div class='cBorder'></div><div class='cArrow'></div>
					<div class='cArrow'></div>
					<div id='cDays'>
						<div id='cDaysNames'>
			";

			for($i = 0; $i < 7; $i++)
			{
				if($i < 5)
				{
					echo "
						<div class='cDay'><span class='goodStyle'>";
						switch($i)
						{
							case 0:
								echo "��";
								break;
							case 1:
								echo "��";
								break;
							case 2:
								echo "��";
								break;
							case 3:
								echo "��";
								break;
							case 4:
								echo "��";
								break;
							default:
								break;
						}
						echo "</span></div>
					";
				}
				else
				{
					echo "
						<div class='cDay'><span class='goodStyleRed'>";
						switch($i)
						{
							case 5:
								echo "��";
								break;
							case 6:
								echo "��";
								break;
							default:
								break;
						}
						echo "</span></div>
					";
				}
			}

			echo "
						</div>
			";

			echo "
						<div class='cBorder'></div>
						<div id='cDaysNums'>";

			for($i = 1; $i < getDay('1', getCurrentMonth(), getCurrentYear()); $i++)
			{
				echo "<div class='cDay'></div>";
			}

			for($i = 0; $i < daysQuantity(getCurrentMonth(), getCurrentYear()); $i++)
			{
				$date = ($i + 1)."-".getCurrentMonth()."-".getCurrentYear();

				$newsCountResult = mysql_query("SELECT COUNT(id) FROM news WHERE date_dmy = '".$date."'");
				$newsCount = mysql_fetch_array($newsCountResult, MYSQL_NUM);

				if($i >= getCurrentDay())
				{
					echo "
						<div class='cDay'><span class='goodStyle' style='color: #aeaeae;'>".($i + 1)."</span></div>
					";
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

					if($newsCount[0] == 0)
					{
						echo "<div class='cDay'><span class='goodStyle'>".($i + 1)."</span></div>";
					}
					else
					{
						if($selectedDate != "")
						{
							if($selectedDay != $d)
							{
								echo "
									<a href='news.php?date=".$date."&p=1' class='noBorder'>
										<div class='cDay' id='cDay".($i+1)."' style='background-color: #dddddd;' onmouseover='buttonColor(\"1\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")' onmouseout='buttonColor(\"0\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")'><span class='goodStyle' style='color: #df4e47' id='cDayText".($i+1)."'>".($i+1)."</span></div>
									</a>
								";
							}
							else
							{
								echo "<div class='cDay' style='background-color: #df4e47;'><span class='goodStyle' style='color: #ffffff'>".($i+1)."</span></div>";
							}
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

			echo "
						</div>
						<div class='cBorder'></div>
					</div>
					<div class='cArrow'></div>
				</div>
			";
		}
		else
		{
			$selectedMonth = substr($selectedDate, 3, 2);
			$selectedYear = substr($selectedDate, 6, 4);

			echo "
				<div id='calendar' style='z-index: 18;'>
					<div id='cLeftArrow' class='cArrow'><img src='pictures/system/cArrowLeft.png' class='noBorder' id='cLeftArrowIMG' onclick='prevMonth(\"".$selectedMonth."\", \"".$selectedYear."\", \"".getCurrentMonth()."\", \"".getCurrentYear()."\""; if($selectedDate != "") {echo ", \"".$selectedDate."\"";} echo ")' /></div>
					<div id='cMonth'><span class='goodStyle' id='cMonthText'>".getMonthName($selectedMonth)."</span><span class='goodStyle'>, </span><span class='goodStyle' id='cYearText'>".$selectedYear."</span></div>
					<div id='cRightArrow' class='cArrow'><img src='pictures/system/cArrowRight.png' class='noBorder' id='cRightArrowIMG' onclick='nextMonth(\"".$selectedMonth."\", \"".$selectedYear."\", \"".getCurrentMonth()."\", \"".getCurrentYear()."\""; if($selectedDate != "") {echo ", \"".$selectedDate."\"";} echo ")' /></div>
					<div class='cArrow'></div><div class='cBorder'></div><div class='cArrow'></div>
					<div class='cArrow'></div>
					<div id='cDays'>
						<div id='cDaysNames'>
			";

			for($i = 0; $i < 7; $i++)
			{
				if($i < 5)
				{
					echo "
						<div class='cDay'><span class='goodStyle'>";
						switch($i)
						{
							case 0:
								echo "��";
								break;
							case 1:
								echo "��";
								break;
							case 2:
								echo "��";
								break;
							case 3:
								echo "��";
								break;
							case 4:
								echo "��";
								break;
							default:
								break;
						}
						echo "</span></div>
					";
				}
				else
				{
					echo "
						<div class='cDay'><span class='goodStyleRed'>";
						switch($i)
						{
							case 5:
								echo "��";
								break;
							case 6:
								echo "��";
								break;
							default:
								break;
						}
						echo "</span></div>
					";
				}
			}

			echo "
						</div>
			";

			echo "
						<div class='cBorder'></div>
						<div id='cDaysNums'>";

			for($i = 1; $i < getDay('1', $selectedMonth, $selectedYear); $i++)
			{
				echo "<div class='cDay'></div>";
			}

			for($i = 0; $i < daysQuantity($selectedMonth, $selectedYear); $i++)
			{
				$date = ($i + 1)."-".$selectedMonth."-".$selectedYear;

				$newsCountResult = $mysqli->query("SELECT COUNT(id) FROM news WHERE date_dmy = '".$date."'");
				$newsCount = $newsCountResult->fetch_array(MYSQLI_NUM);

				$selectedDay = substr($selectedDate, 0, 2);

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

					if($selectedDay != $d)
					{
						echo "
							<a href='news.php?date=".$date."&p=1' class='noBorder'>
								<div class='cDay' id='cDay".($i+1)."' style='background-color: #dddddd;' onmouseover='buttonColor(\"1\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")' onmouseout='buttonColor(\"0\", \"cDay".($i+1)."\", \"cDayText".($i+1)."\")'><span class='goodStyle' style='color: #df4e47' id='cDayText".($i+1)."'>".($i+1)."</span></div>
							</a>
						";
					}
					else
					{
						echo "<div class='cDay' style='background-color: #df4e47;'><span class='goodStyle' style='color: #ffffff'>".($i+1)."</span></div>";
					}
				}
			}

			echo "
						</div>
						<div class='cBorder'></div>
					</div>
					<div class='cArrow'></div>
				</div>
			";
		}
	}

	function getDay($day, $month, $year)
	{
		$days = array(7, 1, 2, 3, 4, 5, 6);
		$day = (int)$day; 
		$month = (int)$month;
		$a = (int)((14 - $month) / 12);
		$y = $year - $a;
		$m = $month + 12 * $a - 2;
		$d = (7000 + (int)($day + $y + (int)($y / 4) - (int)($y / 100) + (int)($y / 400) + (31 * $m) / 12)) % 7;
		return $days[$d];
	}

	function daysQuantity($month, $year)
	{
		return cal_days_in_month(CAL_GREGORIAN, $month, $year);
	}

	function getCurrentYear()
	{
		return date('Y');
	}

	function getCurrentMonth()
	{
		return date('m');
	}

	function getCurrentDay()
	{
		return date('d');
	}

	function getMonthName($month)
	{
		$monthNames = array(
			'������' => '01',
			'������' => '1',
			'�������' => '02',
			'�������' => '2',
			'����' => '03',
			'����' => '3',
			'������' => '04',
			'������' => '4',
			'���' => '05',
			'���' => '5', 
			'����' => '06',
			'����' => '6',
			'����' => '07',
			'����' => '7',
			'������' => '08',
			'������' => '8',
			'��������' => '09',
			'��������' => '9',
			'�������' => '10',
			'������' => '11',
			'�������' => '12'
			);

		return array_search((string)$month, $monthNames);
	}

?>