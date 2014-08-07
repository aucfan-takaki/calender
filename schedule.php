<?php

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
			<textarea name="remark" size="100" maxlength="128"></textarea>

			<input type="hidden" name="result" value="0"/>

			<input type="submit" value="作成する"/>

		</form>
	<?php else :?>
		<form method="post" action="result.php">
			タイトル：
			<input type="text" name="title" maxlength="64" value="<?php echo htmlspecialchars($title['title']) ?>" />
			予定開始時間：
			<input type="text" name="s_year"  size="3" maxlength="4" value="<?php echo htmlspecialchars(date(Y,strtotime($s_time['start_at']))) ?>"/>年
			<input type="text" name="s_month" size="1" maxlength="2" value="<?php echo htmlspecialchars(date(n,strtotime($s_time['start_at']))) ?>"/>月
			<input type="text" name="s_day"   size="1" maxlength="2" value="<?php echo htmlspecialchars(date(j,strtotime($s_time['start_at']))) ?>"/>日
			<input type="time" name="s_sche_time" step="1" value="<?php echo htmlspecialchars(date("H:i:s",strtotime($s_time['start_at']))) ?>"/>
			予定終了時間：
			<input type="text" name="f_year"  size="3" maxlength="4" value="<?php echo htmlspecialchars(date(Y,strtotime($f_time['finish_at']))) ?>"/>年
			<input type="text" name="f_month" size="1" maxlength="2" value="<?php echo htmlspecialchars(date(n,strtotime($f_time['finish_at']))) ?>"/>月
			<input type="text" name="f_day"   size="1" maxlength="2" value="<?php echo htmlspecialchars(date(j,strtotime($f_time['finish_at']))) ?>"/>日
			<input type="time" name="f_sche_time" step="1" value="<?php echo htmlspecialchars(date("H:i:s",strtotime($f_time['finish_at']))) ?>"/>
			場所：
			<input type="text" name="place" maxlength="64" value="<?php echo htmlspecialchars($place['place']) ?>"/>
			備考：
			<input type="text" name="remark" size="100" maxlength="128" value="<?php echo htmlspecialchars($remark['remark']) ?>"/>

			<input type="hidden" name="id" value="<?php echo htmlspecialchars($id) ?>"/>

			<input type="hidden" name="result" value="1"/>

			<input type="submit" value="編集する"/>

		</form>
		<form method="post" action="result.php">
			<input type="hidden" name="id" value="<?php echo htmlspecialchars($id) ?>"/>
			<input type="hidden" name="result" value="2"/>
			<input type="submit" value="削除する"/>
		</form>

	<?php endif ?>
</body>
</html>