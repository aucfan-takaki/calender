<?php


var_dump($_POST);


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

$start_time  = date("Y-m-d H:i:s", mktime(0,0,0, $_POST['s_month'], $_POST['s_day'], $_POST['s_year']));

$finish_time = date("Y-m-d H:i:s", mktime(0,0,0, $_POST['f_month'], $_POST['f_day'], $_POST['f_year']));

$cal->insert_sche($_POST['title'], $start_time, $finish_time, $_POST['place'], $_POST['remark']);


//編集：値をGET（押した日付、id、