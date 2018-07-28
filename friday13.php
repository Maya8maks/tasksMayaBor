<form action="" method="GET">
    <p>Формат даты  день.месяц.год (Например:01.01.2000)</p>
	<input type="text" name="date1" placeholder="введите начало периода">
    <input type="text" name="date2" placeholder="введите конец периода">
	<input type="submit">
</form>

<?php
	if (isset($_GET['date1'])&&!empty($_GET['date1'])&&(isset($_GET['date2'])&&!empty($_GET['date2']))) {
        $n = 0;
        $date1 = $_GET['date1'];
        $date2 = $_GET['date2'];
        if($date1==date('d.m.Y',strtotime($date1))&&$date2==date('d.m.Y',strtotime($date2))) {
            $day = date('d', strtotime($date1));
            $month = date('n', strtotime($date1));
            $year = date('Y', strtotime($date1));
            $last_date = date('d-m-Y', strtotime($date2));
            function is_leap_year($year)
            {
                if (date('L', mktime(0, 0, 0, 1, 1, $year)) == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            function is_friday($month, $year)
            {
                if (date('w', mktime(0, 0, 0, $month, 13, $year)) == 5) {
                    return true;
                } else {
                    return false;
                }
            }
            function get_start_period($day, $month, $year)
            {
                $list = [];
                $month_for_start = $month;
                if ($day > 13) {
                    $month_for_start = $month + 1;
                }
                $year_for_start = $year;
                for ($i = 0; $i <= 3; $i++) {
                    if (!is_leap_year($year_for_start)) {
                        $year_for_start += 1;
                        $month_for_start = 1;
                    } else {
                        break;
                    }
                }
                $list['day'] = 13;
                $list['month'] = $month_for_start;
                $list['year'] = $year_for_start;
                return $list;
            }
            $period = get_start_period($day, $month, $year);
            $start_date_str = implode('.', $period);
            $start_date = (date('d-m-Y', strtotime($start_date_str)));
            $arr = [];

            while (strtotime($start_date) <= strtotime($last_date)) {
                $current_month = date('n', strtotime($start_date));
                $current_year = date('Y', strtotime($start_date));
                if (!is_leap_year($current_year)) {
                    $current_year += 1;
                    $start_date = date('d-m-Y', mktime(0, 0, 0, $current_month, 13, $current_year));
                } else {
                    while ($current_month <= 12) {
                        if (is_friday($current_month, $current_year)) {
                            $n += 1;
                            $arr[] = date('d-m-Y', mktime(0, 0, 0, $current_month, 13, $current_year));
                        }
                        $current_month+=1;
                        $start_date = date('d-m-Y', mktime(0, 0, 0, $current_month, 13, $current_year));
                        if(strtotime($start_date) > strtotime($last_date)){

                            break;
                        }
                    }
                    $current_month = 1;
                    $current_year += 1;
                    $start_date = date('d-m-Y', mktime(0, 0, 0, $current_month, 13, $current_year));
                }
            }
            echo "В выбраном периоде ". $n ." пятниц 13";

        }
        else{
            echo 'Введите даты в правильном формате';
        }
    }
    else{
	    echo'Введите даты';
    }