<?php

//新規：値をGET（押した日付）
//DB接続
//日付をデフォルト値に
//入力
//送信&元の画面に戻る

require_once dirname(__FILE__). '/calendar.php';

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year   = date(Y);
if (empty($this_month)) $this_month = date(m);

$target_time = mktime(0,0,0,$this_month,1,$this_year);

$cal = new Calendar($target_time);



$this_day= $_GET["day"];
if (empty($this_day)) $this_day  = date(d);

$id= $_GET["id"];

$schedule = $cal->getdb($target_time, $pmonth, $nmonth+1);

if (isset($id)) {
$title = $cal->select_sche($id, 'title');
$s_time = $cal->select_sche($id, 'start_at');
$f_time = $cal->select_sche($id, 'finish_at');
$place = $cal->select_sche($id, 'place');
$remark = $cal->select_sche($id, 'remark');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php if (!isset($id)) : ?>
		<form method="post" action="result.php">
			タイトル：
			<input type="text" name="title" maxlength="64"/>
			予定開始時間：
			<input type="text" name="s_year"  size="3" maxlength="4" value="<?php echo ($this_year) ?>"/>年
			<input type="text" name="s_month" size="1" maxlength="2" value="<?php echo ($this_month) ?>"/>月
			<input type="text" name="s_day"   size="1" maxlength="2" value="<?php echo ($this_day) ?>"/>日
			<input type="time" name="s_sche_time" step="1" value="00:00:00"/>
			予定終了時間：
			<input type="text" name="f_year"  size="3" maxlength="4" value="<?php echo ($this_year) ?>"/>年
			<input type="text" name="f_month" size="1" maxlength="2" value="<?php echo ($this_month) ?>"/>月
			<input type="text" name="f_day"   size="1" maxlength="2" value="<?php echo ($this_day) ?>"/>日
			<input type="time" name="f_sche_time" step="1" value="00:00:00"/>
			場所：
			<input type="text" name="place" maxlength="64"/>
			備考：
			<input type="text" name="remark" size="100" maxlength="128"/>

			<input type="hidden" name="result" value="0"/>

			<input type="submit" value="作成する"/>

		</form>
	<?php else :?>
		<form method="post" action="result.php">
			タイトル：
			<input type="text" name="title" maxlength="64" value="<?php echo ($title['title']) ?>" />
			予定開始時間：
			<input type="text" name="s_year"  size="3" maxlength="4" value="<?php echo (date(Y,strtotime($s_time['start_at']))) ?>"/>年
			<input type="text" name="s_month" size="1" maxlength="2" value="<?php echo (date(n,strtotime($s_time['start_at']))) ?>"/>月
			<input type="text" name="s_day"   size="1" maxlength="2" value="<?php echo (date(j,strtotime($s_time['start_at']))) ?>"/>日
			<input type="time" name="s_sche_time" step="1" value="00:00:00"/>
			予定終了時間：
			<input type="text" name="f_year"  size="3" maxlength="4" value="<?php echo (date(Y,strtotime($f_time['finish_at']))) ?>"/>年
			<input type="text" name="f_month" size="1" maxlength="2" value="<?php echo (date(n,strtotime($f_time['finish_at']))) ?>"/>月
			<input type="text" name="f_day"   size="1" maxlength="2" value="<?php echo (date(j,strtotime($f_time['finish_at']))) ?>"/>日
			<input type="time" name="f_sche_time" step="1" value="00:00:00"/>
			場所：
			<input type="text" name="place" maxlength="64" value="<?php echo ($place['place']) ?>"/>
			備考：
			<input type="text" name="remark" size="100" maxlength="128" value="<?php echo ($remark['remark']) ?>"/>

			<input type="hidden" name="id" value="<?php echo ($id) ?>"/>

			<input type="hidden" name="result" value="1"/>

			<input type="submit" value="編集する"/>

		</form>
		<form method="post" action="result.php">
			<input type="hidden" name="id" value="<?php echo ($id) ?>"/>
			<input type="hidden" name="result" value="2"/>
			<input type="submit" value="削除する"/>
		</form>

	<?php endif ?>
</body>
</html>




<?php

//idを持っているか判定

//持っていたら「予定削除」ボタン表示

//MySQLのdeleted_atに現在の時刻を入れる

?>