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
	
$show_month = 4;
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

//カレンダーの1マス目の日数
//$count_day = 1 - $start_day;
function countday($target) {
	return 1 - startday($target);
}

//カレンダーの週数
//$count_week = ceil(($last_day + $start_day)/7);
function countweek($target) {
	return ceil((lastday($target) + startday($target)) / 7);
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
		} ?>><?php echo $k ?></option>
	<?php endfor ?>
	</select>
		<select name="month">
	<?php for ($k = 1; $k <= 12; $k++) : ?>
		<option value=<?php echo $k;
		if ($k == $this_month) {
			echo " selected";
		} ?>><?php echo $k ?>
		</option>
	<?php endfor ?>
	</select>
	<input type="submit" value="反映する">
</form>


<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo jumpyear(-1) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(-1) ?> name="month">
	<input type="submit" value="＜＜">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo date("Y") ?> name="year">
	<input type="hidden" value=<?php echo date("n") ?> name="month">
	<input type="submit" value="今月">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo jumpyear(1) ?> name="year">
	<input type="hidden" value=<?php echo jumpmonth(1) ?> name="month">
	<input type="submit" value="＞＞">
</form>


<?php foreach ($year_months as $key => $value) :?>
	<table>
	<tr><?php echo $value['year'] . '年' . $value['month'] . '月' ?></tr>
		<tr>
			<td style="background:pink" >日</td>
			<td>月</td>
			<td>火</td>
			<td>水</td>
			<td>木</td>
			<td>金</td>
			<td style="background:lightblue" >土</td>
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