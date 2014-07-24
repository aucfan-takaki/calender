<?php

class Calendar
{
     private $target_time;


     public function __construct($target_time)
     {
          $this->target_time = $target_time;
     }

     /**
      * 「$countヶ月後の1日のタイムスタンプ」を出力する
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function jumptime($count, $target_time = null)
     {
          if (is_null($target_time)) 
          {
               $target_time = $this->target_time;
          }
          return strtotime($count . ' month', $target_time);
     }

     /**
      * 「$countヶ月後の年の値」を出力する
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function jumpyear($count, $target_time)
     {
          return date("Y", $this->jumptime($count, $target_time));
     }

     /**
      * 「$countヶ月後の月の値」を出力する
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function jumpmonth($count, $target_time)
     {
          return date("n", $this->jumptime($count, $target_time));
     }

     /**
      * 「参照月から$targetヶ月後の日数」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function lastday($target)
     {
          return date(t, $target);
     }

     /**
      * 「参照月から$targetヶ月後の1日の曜日の値(0~6:日~土)」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function startday($target)
     {
          return date(w,$target);
     }

     /**
      * 「カレンダーの1マス目の日数」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function countday($target, $start_days_of_the_week)
     {
          return 1 - ($this->startday($target) - $start_days_of_the_week + 7) % 7;
     }

     /**
      * 「カレンダーの週数」を出力する
      *
      * @access public
      * @param integer $target 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function countweek($target, $start_days_of_the_week)
     {
          return ceil(($this->lastday($target) - $this->countday($target, $start_days_of_the_week) + 1 ) / 7);
     }

     /**
      * 「カレンダーの色付け（今日、土、日、祝日）のためのclassの文字列」を出力する
      *
      * @access public
      * @param integer $time 第一引数
      * @param integer $year 第二引数
      * @param integer $month 第三引数
      * @param integer $day 第四引数
      * @return string
      * @author Shota Takaki
      */
     public function addcolor($time, $year, $month, $day, $holiday)
     {
          $this_day = strtotime($day-1 . ' day', $time);
          if ($day > 0 && $day <= $this->lastday($time))
          {
               if (date("Y/m/d",$this_day) == date("Y/m/d")) {
                    return "today ";
               }
               if (date(w,$this_day) == 0) {
                    return "sunday ";
               }
               if (date(w,$this_day) == 6) {
                    return "saturday ";
               }
               if ($holiday[date("Ymd",$this_day)] !== null){
                    return "holiday";
               }
          }else
          {
               if (date("Y/m/d",$this_day) == date("Y/m/d")) 
               {
                    return "gtoday ";
               }elseif (date(w,$this_day) == 0) 
               {
                    return "gsunday ";
               }elseif (date(w,$this_day) == 6) 
               {
                    return "gsaturday ";
               }elseif ($holiday[date("Ymd",$this_day)] !== null)
               {
                    return "gholiday";
               }else{
                    return "glay";
               }
          }
     }


     /**
      * 「曜日の色付け（日本語用）のためのclassの文字列」を出力する
      *
      * @access public
      * @param integer $day 第一引数
      * @return string
      * @author Shota Takaki
      */
     public function startcolor($day, $start_days_of_the_week)
     {
          $judge = ($day + $start_days_of_the_week) % 7;
          if ($judge == 0) {
               return "sunday ";
          }
          if ($judge == 6) {
               return "saturday ";
          }
     }

     /**
      * 「祝日」を取得する（ネット参照）
      *
      * @access public
      * @param integer $count 第一引数
      * @return integer
      * @author Shota Takaki
      */
     public function getHolidays($start, $finish) 
     {
          $holidays = array();
      
          //Googleカレンダーから、指定年の祝日情報をJSON形式で取得するためのURL
          $url = sprintf(
               'http://www.google.com/calendar/feeds/%s/public/full?alt=json&%s&%s',
               'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com',
               'start-min=' . $start,
               'start-max=' . $finish
          );

          //JSON形式で取得した情報を配列に変換
          $results = json_decode(file_get_contents($url), true);

          //年月日（例：20120512）をキーに、祝日名を配列に格納
          foreach ($results['feed']['entry'] as $value) {
               $date = str_replace('-', '', $value['gd$when'][0]['startTime']);
               $title = $value['title']['$t'];
               $word = explode( ' / ', $title);
               $holidays[$date] = $word[0];
          }
      
          //祝日の配列を早い順に並び替え
          ksort($holidays);
      
          //配列として祝日を返す
          return $holidays;
     }

     /**
      *祝日の場合、その日の値を「date("Ymd"）」で出力する
      *
      * @access public
      * @param integer $time 第一引数
      * @param integer $year 第二引数
      * @param integer $month 第三引数
      * @param integer $day 第四引数
      * @return integer
      * @author Shota Takaki
      */
     public function addholiday($time, $year, $month, $day, $holiday)
     {
          $this_day = strtotime($day-1 . ' day', $time);
          if ($holiday[date("Ymd",$this_day)] !== null){
               return date("Ymd",$this_day);
          }
     }
}

?>