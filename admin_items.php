<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) redirect('login.php');

// معالجة حذف العناصر
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $_SESSION['success'] = "تم حذف العنصر بنجاح";
}

// جلب جميع العناصر
$stmt = $pdo->query("SELECT i.*, u.name as user_name FROM items i JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC");
$items = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-3 sidebar">
        <div class="p-3">
            <h5 class="text-center mb-4">لوحة المدير</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i>الإحصائيات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="admin_items.php">
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
            <h3 class="mb-4">إدارة العناصر</h3>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>المستخدم</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['user_name']); ?></td>
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
                                        <td>
                                            <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">عرض</a>
                                            <?php if ($item['status'] == 'pending'): ?>
                                                <a href="verify_item.php?id=<?php echo $item['id']; ?>&action=verify" class="btn btn-sm btn-success" onclick="return confirm('هل تريد التحقق من هذا العنصر؟')">تحقق</a>
                                            <?php endif; ?>
                                            <a href="admin_items.php?delete_id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>