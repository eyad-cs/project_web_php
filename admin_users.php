<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) redirect('login.php');

// جلب جميع المستخدمين
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
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
                    <a class="nav-link" href="admin_items.php">
                        <i class="fas fa-list me-2"></i>إدارة العناصر
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="admin_users.php">
                        <i class="fas fa-users me-2"></i>إدارة المستخدمين
                    </a>
                </li>
              
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <div class="p-4">
            <h3 class="mb-4">إدارة المستخدمين</h3>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>رقم الهاتف</th>
                                    <th>تاريخ التسجيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                                <?php echo $user['role'] == 'admin' ? 'مدير' : 'طالب'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['phone'] ?? 'غير متوفر'); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
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