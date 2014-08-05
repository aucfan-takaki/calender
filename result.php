<?php

require_once dirname(__FILE__). '/calendar.php';

//年月を取得、nullの場合は今月の値
$this_year = $_GET["year"];
$this_month= $_GET["month"];
if (empty($this_year)) $this_year   = date(Y);
if (empty($this_month)) $this_month = date(m);

$target_time = mktime(0,0,0,$this_month,1,$this_year);

$cal = new Calendar($target_time);

if ($_POST['result'] !== 2) {

$s_time = str_replace(":", ",", strtotime($_POST['s_sche_time']));

var_dump($s_time);

$f_time = str_replace(":", ",", strtotime($_POST['f_sche_time']));

$start_time  = date("Y-m-d H:i:s", mktime(date(H,$s_time), date(i,$s_time), date(s,$s_time), $_POST['s_month'], $_POST['s_day'], $_POST['s_year']));

var_dump($start_time);

$finish_time = date("Y-m-d H:i:s", mktime(date(H,$f_time), date(i,$f_time), date(s,$f_time), $_POST['f_month'], $_POST['f_day'], $_POST['f_year']));

}

//idがnullだったら
if ($_POST['result'] == 0) 
{
	$cal->insert_sche($_POST['title'], $start_time, $finish_time, $_POST['place'], $_POST['remark']);
}
elseif ($_POST['result'] == 1) 
{
	$cal->update_sche($_POST['title'], $start_time, $finish_time, $_POST['place'], $_POST['remark'], $_POST['id']);
}
elseif ($_POST['result'] == 2) 
{
	$cal->delete_sche($_POST['id']);
}
else
{
	echo "error";
}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<button type="button">
		<a href="http://kensyu.aucfan.com/index.php">
			カレンダーへ戻る	
		</a>
	</button>
</body>
</html>

