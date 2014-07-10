<?php

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year  = date(Y);
if (empty($this_month)) $this_month = date(n);

$target_month = mktime(0,0,0,$this_month,1,$this_year);

//今月から($target_month)ヶ月後の日数
$last_day = date(t, $target_month);
echo "日数：$last_day ";

//今月から($target_month)ヶ月後の1日の曜日を計算(0~6:日~土)
$start_day = date(w,$target_month);
echo "1日の曜日：$start_day";


/*
今月を中心に3ヶ月表記ver（先月、今月、来月）
nの値を変更することによってカレンダーの月をnヶ月ずらせる

//nヶ月後を表記
$n = 0;
//1ヶ月前、該当月、1か月後のカレンダー3つを作成
for ($target_number = $n -1; $target_number <= $n+1; $target_number++) :
//今月から何ヶ月ずれているか
$target_month = strtotime("+$target_number month");


//カレンダー作成

$this_year  = date(Y,$target_month);
$this_month = date(n,$target_month);

//今月から($target_month)ヶ月後の日数
$last_day = date(t,$target_month);
echo "日数：$last_day ";

//今月から($target_month)ヶ月後の1日の曜日を計算(0~6:日~土)
$start_day = (date(w,$target_month) - date(j,$target_month) +15 ) % 7;
echo "1日の曜日：$start_day";

*/

//カレンダーの1マス目の日数
$count_day = 1 - $start_day;

//カレンダーの週数
$count_week = ceil(($last_day + $start_day)/7);

?>
<!DOCTYPE html>
<html>
<head>
	<?php //tableの設定 ?>
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
		<option value=<?php echo "$k"?>><?php echo "$k"?></option>
	<?php endfor ?>
	</select>
	<input type="submit" name="submit" value="反映する">
</form>

<form method="GET" action="index.php">
	<select name="month">
	<?php for ($k = 1; $k <= 12; $k++) : ?>
		<option value=<?php echo "$k"?>><?php echo "$k"?></option>
	<?php endfor ?>
	</select>
	<input type="submit" name="submit" value="反映する">
</form>


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
	<?php /*endfor (←3ヶ月ループ用)*/ ?>
</body>
</html>