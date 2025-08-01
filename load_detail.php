<?php
$pdo = new PDO("mysql:host=localhost;dbname=budget_dtn;charset=utf8", "root", "");
$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT detail_name, requested_amount, approved_amount, percentage FROM budget_detail WHERE budget_item_id = ?");
$stmt->execute([$id]);
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$details){ echo "<p>ไม่มีรายละเอียด</p>"; exit; }

echo "<table class='table table-bordered'><thead><tr><th>รายละเอียด</th><th>ขอ</th><th>อนุมัติ</th><th>%</th></tr></thead><tbody>";
foreach($details as $d){
    echo "<tr>
        <td>{$d['detail_name']}</td>
        <td>".number_format($d['requested_amount'],2)."</td>
        <td>".number_format($d['approved_amount'],2)."</td>
        <td>{$d['percentage']}%</td>
    </tr>";
}
echo "</tbody></table>";
