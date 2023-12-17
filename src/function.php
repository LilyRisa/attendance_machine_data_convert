<?php

function _data_process($data, $is_date = true){
    $newData = [];
    foreach ($data as $item) {
        $date = $item[3]; // Ngày tháng năm là phần tử thứ 3 trong mỗi phần tử mảng con
        $tmp_time = get_start_end($item);
        $startTime = $tmp_time['start']; // Giờ điểm danh đầu
        $endTime = $tmp_time['end']; // Giờ điểm danh cuối
    
        if($is_date){
            if (!empty($date) && !empty($startTime)) {
                if (!isset($newData[$date])) {
                    $newData[$date] = [];
                }
        
                $newData[$date][] = [
                    'employee' => $item[1],
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ];
            }
        }else{
            $employeeName = $item[1];
            if (!empty($employeeName) && !empty($date)) {
                if (!isset($newData[$employeeName])) {
                    $newData[$employeeName] = [];
                }
        
                if (!isset($newData[$employeeName][$date])) {
                    $newData[$employeeName][$date] = [];
                }
        
                $newData[$employeeName][$date] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ];
            }
        }
        
    }
    return $newData;
}

function _data_process_table($data){ //is_date = false
    $dataForDataTables = [];

    $dates = [];
    foreach ($data as $key => $date) {
        foreach ($date as $key => $empl) {
            if (!in_array($key, $dates)) {
                $dates[] = $key;
            }
        }
    }

    foreach($data as $key_emp => $employee){
        $data_data = [];

        foreach($employee as $k => $date_emp){
            $date_key = date('d-m', strtotime($k));
            if(laChuNhat($k)) $date_key .= '(CN)';
            $data_data[$date_key] = $date_emp['start_time'].(!empty($date_emp['end_time'])? "<br/>".$date_emp['end_time'] : '');
        }
        $data_data['employee'] = $key_emp;
       
        $dataForDataTables[] = $data_data;
    }

    return $dataForDataTables;
}

function laChuNhat($date) {
    // Chuyển đổi ngày thành dạng số từ 0 (Chủ Nhật) đến 6 (Thứ Bảy)
    $dayOfWeek = date('w', strtotime($date));
    
    // Kiểm tra nếu ngày là Chủ Nhật (0 là Chủ Nhật trong định dạng w)
    if ($dayOfWeek == 0) {
        return true;
    } else {
        return false;
    }
}

function get_start_end($item){
    $data = [];
    $data['start'] = null;
    $data['end'] = null;
    foreach($item as $key => $it){
        if($key <= 3) continue;
        if(empty($data['start']) && !empty($it)){
            $data['start'] = $it;
            continue;
        }
        if(!empty($it)){
            $data['end'] = $it;
            continue;
        }

    }
    return $data;
}