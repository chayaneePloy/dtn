<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include 'db.php';

$year = $_GET['year'] ?? date('Y');
$type = $_GET['type'] ?? 'items';
$mode = $_GET['mode'] ?? 'preview';

// ✅ ตรวจสอบฟอนต์ THSarabunNew
$fontPath = __DIR__ . './fonts';
$useThaiFont = file_exists($fontPath);

// ✅ CSS พร้อมฟอนต์ (ถ้ามี)
if ($useThaiFont) {
    $style = "
    <style>
    @font-face {
        font-family: 'Sarabun-Thin';
        src: url('file://$fontPath') format('truetype');
    }
    body {
        font-family: 'Sarabun-Thin', sans-serif;
        font-size: 16pt;
    }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 6px; }
    th { background: #eee; }
    h2 { text-align: center; }
    </style>
    ";
} else {
    // ✅ ใช้ DejaVu Sans ถ้าไม่มี THSarabunNew
    $style = "
    <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 16pt; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 6px; }
    th { background: #eee; }
    h2 { text-align: center; }
    </style>
    ";
}

// ✅ สร้าง HTML ของรายงาน
$html = $style;
$html .= "<h2>รายงานงบประมาณ ปี $year</h2>";
$html .= "<table><tr><th>ชื่อโครงการ / รายละเอียด</th><th>งบที่ขอ</th><th>งบที่ได้</th><th>%</th></tr>";

if ($type === 'items') {
    $stmt = $pdo->prepare("SELECT * FROM budget_items WHERE fiscal_year = ?");
    $stmt->execute([$year]);
    foreach ($stmt as $row) {
        $html .= "<tr>
            <td>{$row['item_name']}</td>
            <td>".number_format($row['requested_amount'], 2)."</td>
            <td>".number_format($row['approved_amount'], 2)."</td>
            <td>{$row['percentage']}%</td>
        </tr>";
    }
} elseif ($type === 'detail') {
    $stmt = $pdo->prepare("SELECT * FROM budget_detail WHERE fiscal_year = ?");
    $stmt->execute([$year]);
    foreach ($stmt as $row) {
        $html .= "<tr>
            <td>{$row['detail_name']}</td>
            <td>".number_format($row['requested_amount'], 2)."</td>
            <td>".number_format($row['approved_amount'], 2)."</td>
            <td>{$row['percentage']}%</td>
        </tr>";
    }
} else {
    // ✅ รวม Items + Detail
    $stmtItems = $pdo->prepare("SELECT * FROM budget_items WHERE fiscal_year = ?");
    $stmtItems->execute([$year]);
    foreach ($stmtItems as $item) {
        $html .= "<tr style='background:#ddd;'><td colspan='4'><b>{$item['item_name']}</b></td></tr>";
        $stmtDetail = $pdo->prepare("SELECT * FROM budget_detail WHERE budget_item_id = ?");
        $stmtDetail->execute([$item['id']]);
        foreach ($stmtDetail as $d) {
            $html .= "<tr>
                <td style='padding-left:20px;'>- {$d['detail_name']}</td>
                <td>".number_format($d['requested_amount'], 2)."</td>
                <td>".number_format($d['approved_amount'], 2)."</td>
                <td>{$d['percentage']}%</td>
            </tr>";
        }
    }
}
$html .= "</table>";

// ✅ สร้าง DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ✅ โหลด HTML + UTF-8
$dompdf->loadHtml($html, 'UTF-8');

// ✅ ตั้งกระดาษ
$dompdf->setPaper('A4', 'portrait');

// ✅ Render PDF
$dompdf->render();

// ✅ Stream (แสดงตัวอย่างหรือดาวน์โหลด)
$fileName = "budget_report_{$year}_{$type}.pdf";
if ($mode === 'download') {
    $dompdf->stream($fileName, ["Attachment" => true]);
} else {
    $dompdf->stream($fileName, ["Attachment" => false]);
}
