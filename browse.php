<?php
require_once 'config.php';

// معالجة البحث والتصفية
$category = $_GET['category'] ?? '';
$item_type = $_GET['item_type'] ?? '';
$status = $_GET['status'] ?? 'verified';
$search = $_GET['search'] ?? '';

$query = "SELECT i.*, u.name as user_name FROM items i JOIN users u ON i.user_id = u.id WHERE 1=1";
$params = [];

if ($category) {
    $query .= " AND i.category = ?";
    $params[] = $category;
}

if ($item_type) {
    $query .= " AND i.item_type = ?";
    $params[] = $item_type;
}

if ($status) {
    $query .= " AND i.status = ?";
    $params[] = $status;
}

if ($search) {
    $query .= " AND (i.title LIKE ? OR i.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY i.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container py-4">
    <h3 class="mb-4">تصفح العناصر</h3>
    
    <!-- عوامل التصفية -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="بحث..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">جميع الفئات</option>
                        <option value="هواتف وأجهزة" <?php echo $category == 'هواتف وأجهزة' ? 'selected' : ''; ?>>هواتف وأجهزة</option>
                        <option value="محافظ ونقود" <?php echo $category == 'محافظ ونقود' ? 'selected' : ''; ?>>محافظ ونقود</option>
                        <option value="مفاتيح" <?php echo $category == 'مفاتيح' ? 'selected' : ''; ?>>مفاتيح</option>
                        <option value="كتب ودراسة" <?php echo $category == 'كتب ودراسة' ? 'selected' : ''; ?>>كتب ودراسة</option>
                        <option value="ملابس" <?php echo $category == 'ملابس' ? 'selected' : ''; ?>>ملابس</option>
                        <option value="مستندات" <?php echo $category == 'مستندات' ? 'selected' : ''; ?>>مستندات</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="item_type" class="form-select">
                        <option value="">النوعين</option>
                        <option value="lost" <?php echo $item_type == 'lost' ? 'selected' : ''; ?>>مفقود</option>
                        <option value="found" <?php echo $item_type == 'found' ? 'selected' : ''; ?>>موجود</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="verified" <?php echo $status == 'verified' ? 'selected' : ''; ?>>مُتحقق</option>
                        <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>قيد المراجعة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                    <a href="browse.php" class="btn btn-secondary">إعادة تعيين</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- قائمة العناصر -->
    <div class="row">
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($item['image']): ?>
                            <img src="<?php echo $item['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
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
                                <span class="badge bg-<?php echo $item['status'] == 'verified' ? 'success' : 'warning'; ?>">
                                    <?php echo $item['status'] == 'verified' ? 'مُتحقق' : 'قيد المراجعة'; ?>
                                </span>
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
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد عناصر مطابقة للبحث</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>