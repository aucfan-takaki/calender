<?php

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year  = date(Y);
if (empty($this_month)) $this_month = date(n);

$target_time = mktime(0,0,0,$this_month,1,$this_year);

function jumptime($count) {
	global $target_time;
	return strtotime($count . ' month', $target_time);
}
function jumpyear($count) {
	return date("Y", jumptime($count));
}
function jumpmonth($count) {
	return date("n", jumptime($count));
}

$show_month= $_GET["show"];
if (empty($show_month)) $show_month  = 3;
$pmonth = -floor($show_month/2);
if ($show_month % 2 == 1) {
	$nmonth = 	floor($show_month/2);
} else{
	$nmonth = floor($show_month/2) -1;
}

$year_months = array();
for ($i = $pmonth; $i <= $nmonth; $i++) { 
	$year_months[$i] = array(
	'time' => jumptime($i),
	'year' => jumpyear($i),
	'month' => jumpmonth($i),
	);
}



//今月から($target_time)ヶ月後の日数
//$last_day = date(t, $target_time);
function lastday($target) {
	return date(t, $target);
}

//今月から($target_time)ヶ月後の1日の曜日を計算(0~6:日~土)
//$start_day = date(w,$target_time);
function startday($target) {
	return date(w,$target);
}

$start_days_of_the_week= $_GET["startweek"];
if (empty($start_days_of_the_week)) $start_days_of_the_week  = 0;

//カレンダーの1マス目の日数
//$count_day = 1 - $start_day;
function countday($target) {
	global $start_days_of_the_week;
	return 1 - (startday($target) - $start_days_of_the_week + 7) % 7;
}

//カレンダーの週数
//$count_week = ceil(($last_day + $start_day)/7);
function countweek($target) {
	return ceil((lastday($target) - countday($target) + 1 ) / 7);
}

function addcolor($time, $year, $month, $day){
	$this_day = strtotime($day-1 . ' day', $time);
	if (date("Y/m/d",$this_day) == date("Y/m/d")) {
		return "today ";
	}
	if (date(w,$this_day) == 0) {
		return "sunday ";
	}
	if (date(w,$this_day) == 6) {
		return "saturday ";
	}
}

$daysname = array(
	(7 - $start_days_of_the_week) % 7 => '日', 
	(8 - $start_days_of_the_week) % 7 => '月',
	(9 - $start_days_of_the_week) % 7 => '火',
	(10 - $start_days_of_the_week) % 7 => '水',
	(11 - $start_days_of_the_week) % 7 => '木',
	(12 - $start_days_of_the_week) % 7 => '金',
	(13 - $start_days_of_the_week) % 7 => '土',
	);

echo $daysname[0];

function startcolor($day){
	global $start_days_of_the_week;
	$judge = ($day + $start_days_of_the_week) % 7;
	if ($judge == 0) {
		return "sunday ";
	}
	if ($judge == 6) {
		return "saturday ";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<style>
	table {
		border-collapse: collapse;
	}
	td {
		border: solid 1px;
		padding: 0.5em;
	}

	.today{background-color: yellow;}
	.sunday{background-color: pink;}
	.saturday{background-color: lightblue;}

	</style>
	<title></title>
</head>
<body>

<form method="GET" action="index.php">
	<select name="year">
	<?php for ($k = $this_year - 5; $k < $this_year + 6; $k++) : ?>
		<option value=<?php echo $k;
		if ($k == $this_year) {
			echo " selected";
		} ?>><?php echo $k.'年' ?></option>
	<?php endfor ?>
	</select>
	<select name="month">
	<?php for ($k = 1; $k <= 12; $k++) : ?>
		<option value=<?php echo $k;
		if ($k == $this_month) {
			echo " selected";
		} ?>><?php echo $k.'月' ?>
		</option>
	<?php endfor ?>
	</select>
	<select name="show">
	<?php for ($k = 1; $k <= 12; $k++) : ?>
		<option value=<?php echo $k;
		if ($k == $show_month) {
			echo " selected";
		} ?>><?php echo $k.'月分表示' ?>
		</option>
	<?php endfor ?>
	</select>
	<select name="startweek">
	<?php for ($k = 0; $k < 7; $k++) : ?>
		<option value=<?php echo $k;
		if ($k == $start_days_of_the_week) {
			echo " selected";
		} ?>>
		<?php echo $daysname[($k - $start_days_of_the_week +7) %7].'曜日から表示' ?>
		</option>
	<?php endfor ?>
	</select>
	<input type="submit" value="反映する">
</form>


<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo jumpyear(-1) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(-1) ?> name="month">
	<input type="submit" value="＜＜" style="margin:3px; float:left;">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo date("Y") ?> name="year">
	<input type="hidden" value=<?php echo date("n") ?> name="month">
	<input type="submit" value="今月" style="margin:3px; float:left;">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo jumpyear(1) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(1) ?> name="month">
	<input type="submit" value="＞＞" style="margin:3px">
</form>


<?php foreach ($year_months as $key => $value) :?>
	<table>
	<tr><?php echo $value['year'] . '年' . $value['month'] . '月' ?></tr>
		<tr>
			<?php for ($i=0; $i <7 ; $i++) : ?>
						<td class="<?php
			echo startcolor($i);
			?>" ><?php echo $daysname["$i"] ?></td>
			<?php endfor ?>
		</tr>
		<?php
		// 週の数だけ繰り返す
		for($i = 0, $c = countday($value['time']); $i < countweek($value['time']); $i++) : ?>
		<tr>
			<?php for($j = 0 ; $j < 7 ; $j++ ) : ?>
				<td class="<?php
				echo addcolor($value['time'], $value['year'], $value['month'], $c);
				?>">
				<?php if($c > 0 && $c <= lastday($value['time'])) {
	 				echo $c;
	 			}
	 			$c++; ?>
				</td>
			<?php endfor ?>
		</tr>
		<?php endfor ?>
	</table>
<?php endforeach ?>
</body>
</html>