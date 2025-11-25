<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $item_type = $_POST['item_type'];
    $date_lost_found = $_POST['date_lost_found'];
    
    // معالجة رفع الصورة
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = $upload_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = $image_path;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO items (user_id, title, description, category, location, item_type, date_lost_found, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $category, $location, $item_type, $date_lost_found, $image]);
    
    $_SESSION['success'] = "تم الإبلاغ عن العنصر بنجاح وسيتم مراجعته من قبل الإدارة";
    redirect('my_items.php');
}
?>

<?php include 'header.php'; ?>

<div class="row">
    <div class="col-md-3 sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="p-4">
            <h3 class="mb-4">الإبلاغ عن عنصر</h3>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_type" class="form-label">نوع التقرير *</label>
                                    <select class="form-select" id="item_type" name="item_type" required>
                                        <option value="lost">فقدان عنصر</option>
                                        <option value="found">العثور على عنصر</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">الفئة *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">اختر الفئة</option>
                                        <option value="هواتف وأجهزة">هواتف وأجهزة</option>
                                        <option value="محافظ ونقود">محافظ ونقود</option>
                                        <option value="مفاتيح">مفاتيح</option>
                                        <option value="كتب ودراسة">كتب ودراسة</option>
                                        <option value="ملابس">ملابس</option>
                                        <option value="مستندات">مستندات</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان التقرير *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف المفصل *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">المكان *</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_lost_found" class="form-label">تاريخ الفقدان/العثور *</label>
                                    <input type="date" class="form-control" id="date_lost_found" name="date_lost_found" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">صورة العنصر (اختياري)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">تقديم التقرير</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>