<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'db.php';

$year = $_GET['year'] ?? date('Y');
$type = $_GET['type'] ?? 'items';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Budget Report');

$row = 1;
$sheet->setCellValue("A$row", 'ชื่อ');
$sheet->setCellValue("B$row", 'งบที่ขอ');
$sheet->setCellValue("C$row", 'งบที่ได้');
$sheet->setCellValue("D$row", '%');
$row++;

if ($type === 'items') {
    $stmt = $pdo->prepare("SELECT * FROM budget_items WHERE fiscal_year=?");
    $stmt->execute([$year]);
    foreach ($stmt as $r) {
        $sheet->setCellValue("A$row", $r['item_name']);
        $sheet->setCellValue("B$row", $r['requested_amount']);
        $sheet->setCellValue("C$row", $r['approved_amount']);
        $sheet->setCellValue("D$row", $r['percentage']);
        $row++;
    }
} elseif ($type === 'detail') {
    $stmt = $pdo->prepare("SELECT * FROM budget_detail WHERE fiscal_year=?");
    $stmt->execute([$year]);
    foreach ($stmt as $r) {
        $sheet->setCellValue("A$row", $r['detail_name']);
        $sheet->setCellValue("B$row", $r['requested_amount']);
        $sheet->setCellValue("C$row", $r['approved_amount']);
        $sheet->setCellValue("D$row", $r['percentage']);
        $row++;
    }
} else {
    $stmtItems = $pdo->prepare("SELECT * FROM budget_items WHERE fiscal_year=?");
    $stmtItems->execute([$year]);
    foreach ($stmtItems as $item) {
        $sheet->setCellValue("A$row", "[Item] ".$item['item_name']);
        $sheet->setCellValue("B$row", $item['requested_amount']);
        $sheet->setCellValue("C$row", $item['approved_amount']);
        $sheet->setCellValue("D$row", $item['percentage']);
        $row++;
        $stmtDetail = $pdo->prepare("SELECT * FROM budget_detail WHERE budget_item_id=?");
        $stmtDetail->execute([$item['id']]);
        foreach ($stmtDetail as $d) {
            $sheet->setCellValue("A$row", "   - ".$d['detail_name']);
            $sheet->setCellValue("B$row", $d['requested_amount']);
            $sheet->setCellValue("C$row", $d['approved_amount']);
            $sheet->setCellValue("D$row", $d['percentage']);
            $row++;
        }
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=budget_report_{$year}_{$type}.xlsx");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
