<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

// إحصائيات
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_items,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_items,
        SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified_items
    FROM items 
    WHERE user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();

// العناصر الحديثة
$stmt = $pdo->prepare("SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$recent_items = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="row">
    <!-- الشريط الجانبي -->
    <div class="col-md-3 sidebar">
        <div class="p-3">
            <h5 class="text-center mb-4">القائمة الرئيسية</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="report_item.php">
                        <i class="fas fa-plus me-2"></i>إبلاغ عن عنصر
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_items.php">
                        <i class="fas fa-list me-2"></i>عناصري
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="browse.php">
                        <i class="fas fa-search me-2"></i>تصفح العناصر
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- المحتوى الرئيسي -->
    <div class="col-md-9">
        <div class="p-4">
            <h3 class="mb-4">لوحة التحكم</h3>
            
            <!-- الإحصائيات -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card p-3">
                        <h5>إجمالي العناصر</h5>
                        <h2 class="text-primary"><?php echo $stats['total_items']; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3">
                        <h5>قيد الانتظار</h5>
                        <h2 class="text-warning"><?php echo $stats['pending_items']; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card p-3">
                        <h5>مُتحقق منها</h5>
                        <h2 class="text-success"><?php echo $stats['verified_items']; ?></h2>
                    </div>
                </div>
            </div>
            
            <!-- العناصر الحديثة -->
            <div class="card">
                <div class="card-header">
                    <h5>آخر العناصر المبلغ عنها</h5>
                </div>
                <div class="card-body">
                    <?php if ($recent_items): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>النوع</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $item['item_type'] == 'lost' ? 'danger' : 'success'; ?>">
                                                    <?php echo $item['item_type'] == 'lost' ? 'مفقود' : 'موجود'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $item['status'] == 'pending' ? 'warning' : 
                                                           ($item['status'] == 'verified' ? 'success' : 'info'); 
                                                ?>">
                                                    <?php 
                                                        echo $item['status'] == 'pending' ? 'قيد المراجعة' : 
                                                               ($item['status'] == 'verified' ? 'مُتحقق' : 'مُطالب به'); 
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">لا توجد عناصر مبلغ عنها بعد</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>