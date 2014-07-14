<?php

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year  = date(Y);
if (empty($this_month)) $this_month = date(n);

$target_time = mktime(0,0,0,$this_month,1,$this_year);
//$target_time = strtotime(20141201); // 2014/7/01 -> UNIXTIME


//来月へ遷移ボタン用
$next_time = strtotime('next month', $target_time);
$next_year = date("Y",$next_time);
$next_month = date("n",$next_time);
//先月へ遷移ボタン用
$last_time = strtotime('last month', $target_time);
$last_year = date("Y",$last_time);
$last_month = date("n",$last_time);

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

$test = date('Y', jumptime(18));
echo $test;

//前後nヶ月表示		
$show_month = 1;
$year_months = array();
for ($i = -$show_month; $i <= $show_month; $i++) { 
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
	<input type="hidden" value=<?php echo $last_year ?> name="year">
	<input type="hidden" value=<?php echo $last_month ?> name="month">
	<input type="submit" value="＜＜">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo date("Y") ?> name="year">
	<input type="hidden" value=<?php echo date("n") ?> name="month">
	<input type="submit" value="今月">
</form>
<form method="GET" action="index.php">
	<input type="hidden" value=<?php echo $next_year ?> name="year">
	<input type="hidden" value=<?php echo $next_month ?> name="month">
	<input type="submit" value="＞＞">
</form>


<?php foreach ($year_months as $key => $value) :?>
	<table>
	<tr><?php echo $value['year'] . '年' . $value['month'] . '月' ?></tr>
		<tr>
			<td style="background:#ffcccc" >日</td>
			<td>月</td>
			<td>火</td>
			<td>水</td>
			<td>木</td>
			<td>金</td>
			<td style="background:#aaccff" >土</td>
		</tr>
		<?php
		// 週の数だけ繰り返す
		for($i = 0, $c = countday($value['time']); $i < countweek($value['time']); $i++) : ?>
		<tr>
			<?php for($j = 0 ; $j < 7 ; $j++ ) : ?>
				<td <?php
				//本日の日付のセルを黄色に
				if ($value['year'] == date(Y) && $value['month'] == date(n) && $c == date(j) ) : ?>
					style="background:#ffffaa"
				<?php endif ?>
				<?php
				//日曜日を赤に
				if ($j == 0) : ?>
					style="background:#ffcccc"
				<?php endif ?>
				<?php
				//土曜を青に
				if ($j == 6) : ?>
					style="background:#aaccff"
				<?php endif ?>
				> <?php //1〜月の日数を表示
				if($c > 0 && $c <= lastday($value['time'])) {
	 				echo $c; 
	 			} ?></td>
				<?php //土曜だったらループを抜ける
				if(($c + startday($value['time'])) % 7 == 0) {
					$c++;
					break;
				} ?>
			<?php $c++;
			endfor ?>
		</tr>
		<?php endfor ?>
	</table>
<?php endforeach ?>
</body>
</html>