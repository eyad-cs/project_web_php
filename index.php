<?php
require_once 'config.php';

// إحصائيات للصفحة الرئيسية
$total_items = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'verified'")->fetchColumn();
$recent_items = $pdo->query("SELECT i.*, u.name as user_name FROM items i JOIN users u ON i.user_id = u.id WHERE i.status = 'verified' ORDER BY i.created_at DESC LIMIT 6")->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container-fluid p-0">
    <!-- الهيرو -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">مفقوداتي</h1>
                    <p class="lead">خدمة مقدمة من جامعة تبوك لمساعدة الطلاب في العثور على متعلقاتهم المفقودة</p>
                    <div class="mt-4">
                        <?php if (!isLoggedIn()): ?>
                            <a href="register.php" class="btn btn-light btn-lg me-3">إنشاء حساب</a>
                            <a href="login.php" class="btn btn-outline-light btn-lg">تسجيل الدخول</a>
                        <?php else: ?>
                            <a href="<?php echo isAdmin() ? 'admin_dashboard.php' : 'dashboard.php'; ?>" class="btn btn-light btn-lg me-3">
                                لوحة التحكم
                            </a>
                            <a href="browse.php" class="btn btn-outline-light btn-lg">تصفح العناصر</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <i class="fas fa-search fa-10x text-white-50"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- الإحصائيات -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="text-primary"><?php echo $total_items; ?></h3>
                        <p class="text-muted">عنصر مُتحقق</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="text-success"><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'returned'")->fetchColumn(); ?></h3>
                        <p class="text-muted">عنصر تم إرجاعه</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="text-warning"><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE item_type = 'lost' AND status = 'verified'")->fetchColumn(); ?></h3>
                        <p class="text-muted">عنصر مفقود</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="text-info"><?php echo $pdo->query("SELECT COUNT(*) FROM items WHERE item_type = 'found' AND status = 'verified'")->fetchColumn(); ?></h3>
                        <p class="text-muted">عنصر موجود</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- أحدث العناصر -->
    <section class="recent-items py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">أحدث العناصر المُتحقق منها</h2>
            <div class="row">
                <?php if ($recent_items): ?>
                    <?php foreach ($recent_items as $item): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if ($item['image']): ?>
                                    <img src="<?php echo $item['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                <?php else: ?>
                                    <div class="card-img-top bg-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="card-text text-muted"><?php echo substr($item['description'], 0, 100); ?>...</p>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-<?php echo $item['item_type'] == 'lost' ? 'danger' : 'success'; ?>">
                                            <?php echo $item['item_type'] == 'lost' ? 'مفقود' : 'موجود'; ?>
                                        </span>
                                        <span class="badge bg-success">مُتحقق</span>
                                    </div>
                                    
                                    <p class="card-text"><small class="text-muted">المكان: <?php echo htmlspecialchars($item['location']); ?></small></p>
                                    <p class="card-text"><small class="text-muted">الفئة: <?php echo htmlspecialchars($item['category']); ?></small></p>
                                </div>
                                
                                <div class="card-footer">
                                    <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm">عرض التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">لا توجد عناصر متاحة حالياً</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="browse.php" class="btn btn-primary">عرض جميع العناصر</a>
            </div>
        </div>
    </section>

    <!-- كيفية الاستخدام -->
    <section class="how-to-use py-5">
        <div class="container">
            <h2 class="text-center mb-5">كيفية استخدام النظام</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h4>إنشاء حساب</h4>
                    <p class="text-muted">قم بإنشاء حساب جديد في النظام باستخدام بريدك الجامعي</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="step-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-flag fa-2x"></i>
                    </div>
                    <h4>الإبلاغ عن عنصر</h4>
                    <p class="text-muted">أبلغ عن عنصر مفقود أو وجدت عنصراً مع تفاصيل دقيقة</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="step-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h4>المطالبة أو الإرجاع</h4>
                    <p class="text-muted">اطلب عنصرك المفقود أو سلم العنصر الذي وجدته</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
