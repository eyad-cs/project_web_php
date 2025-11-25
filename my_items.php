<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

$stmt = $pdo->prepare("SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-3 sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="p-4">
            <h3 class="mb-4">عناصري المبلغ عنها</h3>
            
            <?php if ($items): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>العنوان</th>
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد عناصر مبلغ عنها</h4>
                    <a href="report_item.php" class="btn btn-primary mt-3">الإبلاغ عن عنصر</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>