<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT i.*, u.name as user_name, u.phone as user_phone FROM items i JOIN users u ON i.user_id = u.id WHERE i.id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    die("العنصر غير موجود");
}

// معالجة المطالبة

?>

<?php include 'header.php'; ?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="browse.php">تصفح العناصر</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($item['title']); ?></li>
        </ol>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                </div>
                <div class="card-body">
                    <?php if ($item['image']): ?>
                        <img src="<?php echo $item['image']; ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <?php endif; ?>
                    
                    <p><strong>الوصف:</strong></p>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>الفئة:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                            <p><strong>المكان:</strong> <?php echo htmlspecialchars($item['location']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>النوع:</strong> 
                                <span class="badge bg-<?php echo $item['item_type'] == 'lost' ? 'danger' : 'success'; ?>">
                                    <?php echo $item['item_type'] == 'lost' ? 'مفقود' : 'موجود'; ?>
                                </span>
                            </p>
                            <p><strong>الحالة:</strong> 
                                <span class="badge bg-<?php echo $item['status'] == 'verified' ? 'success' : 'warning'; ?>">
                                    <?php echo $item['status'] == 'verified' ? 'مُتحقق' : 'قيد المراجعة'; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <p><strong>التاريخ:</strong> <?php echo date('Y-m-d', strtotime($item['date_lost_found'])); ?></p>
                    <p><strong>أضيف بواسطة:</strong> <?php echo htmlspecialchars($item['user_name']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if (isLoggedIn() && $item['status'] == 'verified' && $item['item_type'] == 'found'): ?>
                <div class="card">
                    <div class="card-header">
                        <h5>المطالبة بالعنصر</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="claim_details" class="form-label">تفاصيل المطالبة</label>
                                <textarea class="form-control" id="claim_details" name="claim_details" rows="4" required placeholder="صف العنصر وكيفية التعرف عليه..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">تقديم المطالبة</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>معلومات الاتصال</h5>
                </div>
                <div class="card-body">
                    <p><strong>اسم المبلغ:</strong> <?php echo htmlspecialchars($item['user_name']); ?></p>
                    <?php if (isAdmin() || $item['user_id'] == $_SESSION['user_id']): ?>
                        <p><strong>الهاتف:</strong> <?php echo htmlspecialchars($item['user_phone']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>