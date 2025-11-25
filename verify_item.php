<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) redirect('login.php');

$id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? '';

if ($id && $action) {
    if ($action == 'verify') {
        $stmt = $pdo->prepare("UPDATE items SET status = 'verified' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "تم التحقق من العنصر بنجاح";
    } elseif ($action == 'reject') {
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "تم رفض العنصر وحذفه";
    }
    
    redirect('admin_dashboard.php');
} else {
    redirect('admin_dashboard.php');
}
?>