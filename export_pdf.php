<?php
require __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

include 'db.php';

$year = $_GET['year'] ?? date('Y');
$type = $_GET['type'] ?? 'items';
$mode = $_GET['mode'] ?? 'preview';

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'default_font_size' => 16,
    'default_font' => 'thsarabun',
    'fontDir' => [__DIR__ . '/fonts'],
    'fontdata' => [
        'thsarabun' => [
            'R' => 'THSarabun.ttf',
            'B' => 'THSarabun Bold.ttf',
            'I' => 'THSarabun Italic.ttf',
            'BI' => 'THSarabun BoldItalic.ttf',
        ]
    ]
]);

$style = "
<style>
body { font-family: 'thsarabun'; font-size: 16pt; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #000; padding: 6px; }
th { background: #f2f2f2; }
h2 { text-align: center; }
</style>
";

$html = $style;

if (isset($_GET['budget_item_id'])) {
    $budget_item_id = intval($_GET['budget_item_id']);
    // ดึงชื่อโครงการ
    $stmtProject = $pdo->prepare("SELECT item_name FROM budget_items WHERE id = ?");
    $stmtProject->execute([$budget_item_id]);
    $project = $stmtProject->fetch(PDO::FETCH_ASSOC);
    $projectName = $project ? $project['item_name'] : '-';

    // ดึงรายละเอียดทั้งหมดของโครงการ
    $stmt = $pdo->prepare("SELECT detail_name, requested_amount, approved_amount, percentage FROM budget_detail WHERE budget_item_id = ?");
    $stmt->execute([$budget_item_id]);
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html .= "<h2>รายละเอียดโครงการ: {$projectName}</h2>";
    $html .= "<table>
        <tr>
            <th>ลำดับ</th>
            <th>รายละเอียด</th>
            <th>งบที่ขอ</th>
            <th>งบที่ได้</th>
            <th>%</th>
        </tr>";
    $no = 1;
    foreach ($details as $row) {
        $html .= "<tr>
            <td style='text-align:center;'>{$no}</td>
            <td>{$row['detail_name']}</td>
            <td>".number_format($row['requested_amount'], 2)."</td>
            <td>".number_format($row['approved_amount'], 2)."</td>
            <td>{$row['percentage']}%</td>
        </tr>";
        $no++;
    }
    $html .= "</table>";
}

$mpdf->WriteHTML($html);

$fileName = "budget_report_{$year}.pdf";
if ($mode === 'download') {
    $mpdf->Output($fileName, "D");
} else {
    $mpdf->Output($fileName, "I");
}