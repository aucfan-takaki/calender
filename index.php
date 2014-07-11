<?php

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year  = date(Y);
if (empty($this_month)) $this_month = date(n);


$target_time = mktime(0,0,0,$this_month,1,$this_year);
//$target_time = strtotime(20141201); // 2014/7/01 -> UNIXTIME
$next_time = strtotime('next month', $target_time);
$next_year = date("Y",$next_time);
$next_month = date("n",$next_time);

$last_time = strtotime('last month', $target_time);
$last_year = date("Y",$last_time);
$last_month = date("n",$last_time);

//配列
//$arr = array($last_month, $this_month, $next_month);
//echo "$arr[0]$arr[1]$arr[2]";


//今月から($target_time)ヶ月後の日数
$last_day = date(t, $target_time);
echo "日数：$last_day ";

//今月から($target_time)ヶ月後の1日の曜日を計算(0~6:日~土)
$start_day = date(w,$target_time);
echo "1日の曜日：$start_day";

//カレンダーの1マス目の日数
$count_day = 1 - $start_day;

//カレンダーの週数
$count_week = ceil(($last_day + $start_day)/7);

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
	<?php for ($k = $this_year - 1; $k < $this_year + 2; $k++) : ?>
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


<?php //foreach ($arr as $value) :?>
	<table>
	<tr><?php echo "$this_year" . '年' . "$this_month" . '月' ?></tr>
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
		for($i=0; $i < $count_week; $i++) : ?>
		<tr>
			<?php for($j = 0 ; $j < 7 ; $j++ ) : ?>
				<td <?php
				//本日の日付のセルを黄色に
				if ($this_year == date(Y) && $this_month == date(n) && $count_day == date(j) ) : ?>
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
				if($count_day > 0 && $count_day <= $last_day) {
	 				echo $count_day; 
	 			} ?></td>
				<?php //土曜だったらループを抜ける
				if(($count_day + $start_day) % 7 == 0) {
					$count_day++;
					break;
				} ?>
			<?php $count_day++;
			endfor ?>
		</tr>
		<?php endfor ?>
	</table>
<?php //endforeach ?>
</body>
</html>