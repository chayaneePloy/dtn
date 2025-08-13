<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

$project_id = $_GET['id'] ?? 0;
$project_id = intval($project_id);

// ข้อมูลโครงการ

$project = $pdo->query("SELECT * FROM projects WHERE project_id = $project_id")
               ->fetch(PDO::FETCH_ASSOC);

// งบประมาณ
$budgets = $pdo->query("SELECT * FROM budgets WHERE project_id = $project_id");

// สัญญา + งวดงาน
$contracts = $pdo->query("
    SELECT c.*, p.phase_id, p.phase_number, p.phase_name, p.amount, p.status
    FROM contracts c
    LEFT JOIN phases p ON c.contract_id = p.contract_id
    WHERE c.project_id = $project_id
");

// ปัญหา
$issues = $pdo->query("SELECT * FROM issues WHERE project_id = $project_id");


// ปัญหา
$issues = $pdo->query("SELECT * FROM issues WHERE project_id = $project_id");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายละเอียดโครงการ</title>
<style>
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }
</style>
</head>
<body>

<h1>รายละเอียดโครงการ</h1>

<h2>ข้อมูลหลัก</h2>
<p><strong>ชื่อโครงการ:</strong> <?= htmlspecialchars($project['project_name']) ?></p>
<p><strong>ประเภท:</strong> <?= htmlspecialchars($project['project_type']) ?></p>
<p><strong>ปีงบประมาณ:</strong> <?= htmlspecialchars($project['fiscal_year']) ?></p>
<p><strong>รายละเอียด:</strong> <?= nl2br(htmlspecialchars($project['description'])) ?></p>

<h2>งบประมาณ</h2>
<table>
<tr>
    <th>งบที่ได้รับ</th>
    <th>งบที่ทำสัญญา</th>
    <th>ปีงบ</th>
</tr>
    <?php while($row = $budgets->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
    <td><?= number_format($row['allocated_amount'], 2) ?></td>
    <td><?= number_format($row['contracted_amount'], 2) ?></td>
    <td><?= htmlspecialchars($row['fiscal_year']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<h2>สัญญาและงวดงาน</h2>
<table>
<tr>
    <th>เลขที่สัญญา</th>
    <th>ผู้รับจ้าง</th>
    <th>งวดงาน</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
</tr>
<?php while($row = $contracts->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
    <td><?= htmlspecialchars($row['contract_number']) ?></td>
    <td><?= htmlspecialchars($row['contractor_name']) ?></td>
    <td><?= htmlspecialchars($row['phase_name']) ?></td>
    <td><?= number_format($row['amount'], 2) ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<h2>ปัญหาและอุปสรรค</h2>
<table>
<tr>
    <th>วันที่พบปัญหา</th>
    <th>รายละเอียด</th>
    <th>สถานะ</th>
</tr>
<?php while($row = $issues->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
    <td><?= htmlspecialchars($row['issue_date']) ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<p><a href="index.php">⬅ กลับหน้าหลัก</a></p>

</body>
</html>
