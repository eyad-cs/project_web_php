<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) redirect('login.php');

// إحصائيات محدثة
$total_items = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();
$pending_items = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'pending'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
// العناصر قيد الانتظار
$stmt = $pdo->query("SELECT i.*, u.name as user_name FROM items i JOIN users u ON i.user_id = u.id WHERE i.status = 'pending' ORDER BY i.created_at DESC LIMIT 10");
$pending_reports = $stmt->fetchAll();

// المطالبات قيد الانتظار

?>
<?php 
include 'header.php'; 
?>

<div class="row">
    <div class="col-md-3 sidebar">
        <div class="p-3">
            <h5 class="text-center mb-4">لوحة المدير</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>الإحصائيات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_items.php">
                        <i class="fas fa-list me-2"></i>إدارة العناصر
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_users.php">
                        <i class="fas fa-users me-2"></i>إدارة المستخدمين
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <div class="p-4">
            <h3 class="mb-4">لوحة تحكم المدير</h3>
            
            <!-- الإحصائيات -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card p-3 text-center">
                        <div class="bg-primary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <h3 class="text-primary"><?php echo $total_items; ?></h3>
                        <p class="text-muted mb-0">إجمالي العناصر</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card p-3 text-center">
                        <div class="bg-warning text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h3 class="text-warning"><?php echo $pending_items; ?></h3>
                        <p class="text-muted mb-0">قيد المراجعة</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card p-3 text-center">
                        <div class="bg-success text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h3 class="text-success"><?php echo $total_users; ?></h3>
                        <p class="text-muted mb-0">إجمالي المستخدمين</p>
                    </div>
                </div>
                
            </div>

            <div class="row">
                <!-- التقارير قيد الانتظار -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>التقارير قيد المراجعة</h5>
                            <a href="admin_items.php" class="btn btn-primary btn-sm">عرض الكل</a>
                        </div>
                        <div class="card-body">
                            <?php if ($pending_reports): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th>المستخدم</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pending_reports as $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['user_name']); ?></td>
                                                    <td>
                                                        <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="verify_item.php?id=<?php echo $item['id']; ?>&action=verify" class="btn btn-sm btn-success" title="تحقق" onclick="return confirm('هل تريد التحقق من هذا العنصر؟')">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="verify_item.php?id=<?php echo $item['id']; ?>&action=reject" class="btn btn-sm btn-danger" title="رفض" onclick="return confirm('هل تريد رفض وحذف هذا العنصر؟')">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-center text-muted mb-0">لا توجد تقارير قيد المراجعة</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <!-- إحصائيات إضافية -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>الإحصائيات التفصيلية</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-2">
                                    <h6 class="text-primary">عناصر مفقودة</h6>
                                    <h4><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE item_type = 'lost'")->fetchColumn(); ?></h4>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="text-success">عناصر موجودة</h6>
                                    <h4><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE item_type = 'found'")->fetchColumn(); ?></h4>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="text-info">عناصر مُتحقق</h6>
                                    <h4><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'verified'")->fetchColumn(); ?></h4>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="text-warning">عناصر مُرجعة</h6>
                                    <h4><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'returned'")->fetchColumn(); ?></h4>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>