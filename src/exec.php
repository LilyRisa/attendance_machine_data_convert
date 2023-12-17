<?php

include "../vendor/autoload.php";


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'function.php';

header('Content-Type: application/json');


function getdata($file, $sheet_name){
    $Spreadsheet = IOFactory::load($file);
    $sheet = $Spreadsheet->getSheetByName($sheet_name);
    if($sheet == null) {
        return false;
    }
    return $sheet->toArray(null, true, true, true);
}

if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
    $tempFilePath = $_FILES['uploadedFile']['tmp_name'];

    // Đọc dữ liệu từ tệp tin tạm thời
    $spreadsheet = IOFactory::load($tempFilePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    
    unset($data[0]);
    unset($data[1]);
    $data = _data_process($data, false);

    $data = _data_process_table($data);
    // In ra mảng dữ liệu
    echo json_encode($data);
} else {
    // Xử lý lỗi tải lên hoặc tệp tin không tồn tại
    echo 'Có lỗi xảy ra trong quá trình tải lên tệp tin.';
}

