<?php

	session_start();
	include('connect.php');

	if(!empty($_POST['searchQuery']))
	{
		$query = iconv('UTF-8', 'CP1251', $_POST['searchQuery']);

		$searchResult = $mysqli->query("SELECT * FROM catalogue_new WHERE name LIKE '%".$query."%' OR code LIKE '%".$query."%' LIMIT 5");
		
		if(MYSQLI_NUM_rows($searchResult) > 0)
		{
			$count = 0;

			while($search = $searchResult->fetch_assoc())
			{
				$count++;

				$categoryResult = $mysqli->query("SELECT name FROM categories_new WHERE id = '".$search['category']."'");
				$category = $categoryResult->fetch_array(MYSQLI_NUM);

				if(!empty($search['subcategory']) and $search['subcategory'] != 0)
				{
					$subcategoryResult = $mysqli->query("SELECT name FROM subcategories_new WHERE id = '".$search['subcategory']."'");
					$subcategory = $subcategoryResult->fetch_array(MYSQLI_NUM);
				}

				if(!empty($search['subcategory2']) and $search['subcategory2'] != 0)
				{
					$subcategory2Result = $mysqli->query("SELECT name FROM subcategories2 WHERE id = '".$search['subcategory2']."'");
					$subcategory2 = $subcategory2Result->fetch_array(MYSQLI_NUM);
				}

				if($count % 2 == 0)
				{
					echo "<div class='searchGreyBG'>";
				}

				echo "
					<div class='searchLine'"; if($count != 1) {echo " style='margin-top: 10px;'";} echo ">
						<a href='catalogue.php' class='noBorder'><span class='catalogueItemTextItalic'>Каталог</span></a><span class='redItalic'> > </span><a href='catalogue.php?type=".$search['type']."' class='noBorder'><span class='catalogueItemTextItalic'>"; switch($search['type']){case 'fa': echo "Мебельная фурнитура"; break; case 'em': echo "Кромочные материалы"; break; case 'ca': echo "Аксессуары для штор"; break; default: break;} echo "</span></a><span class='redItalic'> > </span><a href='catalogue.php?type=".$search['type']."&category=".$search['category']."' class='noBorder'><span class='catalogueItemTextItalic'>".$category[0]."</span></a>"; if(!empty($search['subcategory']) and $search['subcategory'] != 0) {echo "<span class='redItalic'> > </span><a href='catalogue.php?type=".$search['type']."&category=".$search['category']."&subcategory=".$search['subcategory']."' class='noBorder'><span class='catalogueItemTextItalic'>".$subcategory[0]."</span></a>";} if(!empty($search['subcategory2']) and $search['subcategory2'] != 0) {echo "<span class='redItalic'> > </span><a href='catalogue.php?type=".$search['type']."&category=".$search['category']."&subcategory=".$search['subcategory']."&subcategory2=".$search['subcategory2']."' class='noBorder'><span class='catalogueItemTextItalic'>".$subcategory2[0]."</span></a>";} echo "
						<br /><br />
						<div class='pictureSmall'><a href='pictures/catalogue/big/' class='noBorder' rel='lightbox'><img src='pictures/catalogue/small/".$search['small']."' class='noBorder' style='width: 30px; height: 30px;' /></a></div>
						<div class='goodDescriptionSmall'>
							<span class='goodStyle'>".$search['name']."</span>
							<br /><br />
							<span class='basic'>".str_replace("../", '', $search['description'])."</span>
							<br /><br />
							<span class='basic'><b>Артикул: </b>".$search['code']."</span>
						</div>
					</div>
				";

				if($count % 2 == 0)
				{
					echo "</div>";
				}
			}
		}
		else
		{
			echo "<span class='basic'>К сожалению, поиск не дал результатов.</span>";
		}
	}

?>