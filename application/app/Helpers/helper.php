<?php

/**
 * We will use this function for styling validation error message
 * @param string
 * @return string
 */
function validation_error($message, $elementId = '', $optional = false) {
    $myMessage = $message == "" ? "*" : "[$message]";
    if ($message == '' && $optional == true) {
        $myMessage = '';
    }
    
    $elmId = $elementId != '' ? "id=ve-" . trim($elementId) : '';
    return "<small $elmId class='validation-error'>$myMessage</small>";
}

function show_error($message, $show_class = false, $validation_class = 'is-invalid')
{
    if($message) {
        if($show_class) {
            return ' '.$validation_class;
        }

        return "<small class='invalid-feedback'>$message</small>";
    }
}

function validationHints() {
    return "<small class='validation-error-hints pull-left'><i>All fields marked with an asterisk (*) are required.</i></small>";
}

/**
 * Display pagination summary
 *
 * @param int $totalData
 * @param int $dataPerPage
 * @param int $currentPage
 */
function getPaginationSummary($totalData, $dataPerPage, $currentPage) {
    $paginationSummary = "";
    if ($totalData > $dataPerPage) {
        if ($currentPage == 1) {
            $paginationSummary = "Showing 1 to $dataPerPage records of $totalData";
        } else {
            if (($totalData - $currentPage * $dataPerPage) > $dataPerPage) {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = $currentPage * $dataPerPage;
                $paginationSummary = "Showing $from to $to records of $totalData";
            } else {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = ($totalData - ($currentPage - 1) * $dataPerPage) + ($currentPage - 1) * $dataPerPage;
                $paginationSummary = "Showing $from to $to records of $totalData";
            }
        }
    }
    return $paginationSummary;
}


function toastMessage($message, $type = 'success') {
    return ['message' => $message, 'type' => $type];
}

function formatAmount($value) {
    return number_format((float) $value, 2, '.', '');
}

function getMonths($month_no = null, $prepend = true)
{
    $dateObject = Carbon::now()->startOfYear();

    if($month_no) {
        return $dateObject->addMonths($month_no - 1)->format('F');
    }

    $month_arr = [];

    if($prepend)  {
        $month_arr[''] = 'Select a Month';
    }

    for ($i=1; $i <= 12; $i++) { 
        $month_arr[$i] = $dateObject->format('F');
        
        $dateObject = $dateObject->addMonth();
    }

    return $month_arr;
}

function addColon($val) 
{ 
    return str_replace('.', ':', $val);
}

function getTotalTime($timeArr)
{
    $totalTime = '00:00';

    $timeArr = array_map('addColon', $timeArr);

    foreach($timeArr as $timeItem) {
        $totalTime = explode(':', $totalTime);
        $time = explode(':', $timeItem);
        $mins = (int)($totalTime[1]) + (int)($time[1]);
        $minhours = floor($mins / 60);

        $hrs = (int)($totalTime[0]) + (int)($time[0]) + $minhours;
        $mins = $mins%60;

        $totalTime = sprintf("%02d", $hrs) . ':' . sprintf("%02d", $mins);
    }
    
    return $totalTime;
}

function getDiffTime($startDateTime, $stopDateTime)
{
    $startDateTime = Carbon::parse($startDateTime);
    $stopDateTime = Carbon::parse($stopDateTime);

    $diffMins = $startDateTime->diffInMinutes($stopDateTime);

    $hrs = floor($diffMins/60);
    $mins = $diffMins%60;

    return sprintf("%02d", $hrs) . ':' . sprintf("%02d", $mins);
}

function getDiffTimeFromTimeValue($startTime, $endTime, $separator = '.')
{
    $start_time_arr = explode(':', $startTime);
    $start_time_hour = $start_time_arr[0];
    $start_time_minute = $start_time_arr[1];

    $end_time_arr = explode(':', $endTime);
    $end_time_hour = $end_time_arr[0];
    $end_time_minute = $end_time_arr[1];

    $min = $end_time_minute-$start_time_minute;

    $hour_carry = 0;
    if($min < 0){
       $min += 60;
       $hour_carry += 1;
    }
    $hrs = $end_time_hour - $start_time_hour - $hour_carry;

    return sprintf("%02d", $hrs) . $separator . sprintf("%02d", $min);
}

function getReportInfo(array $data)
{
    $report_title_arr = [];
    $report_title = '';
    $query_string = '';

    if($data) {
        $i = 1;
        foreach($data as $key=>$value) { 
           $report_title_arr[] = trim(ucfirst(str_replace('id', '', str_replace('_', ' ', $key))));
            $query_string .=$key .'='. $value .'&';
            $i++;
        }

        $report_items = implode(', ', $report_title_arr);
        
        if(str_contains($report_items, ', ')) {
            $report_title = str_replace_last(', ', ' and ', $report_items) . ' wise Report';
        }else {
            $report_title = $report_items . ' wise Report';
        }
        
        $query_string = rtrim($query_string, '&');
    }

    return [
        'report_title'  => $report_title,
        'query_string'  => $query_string,
    ];
}

function getShortNames($name)
{
    $short_name = '';
    if($name) {
        if($name_arr = explode(' ', $name)) {
            foreach($name_arr as $key=>$value) {
                $short_name.= ucfirst(str_limit($value, 1, ''));
            }
        }
    }

    return $short_name;
}