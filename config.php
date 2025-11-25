<?php
session_start();

// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'campus_lost_found');

// ألوان جامعة تبوك
define('UT_PRIMARY', '#1E3A8A');
define('UT_SECONDARY', '#DC2626');
define('UT_ACCENT', '#F59E0B');
define('UT_NEUTRAL', '#6B7280');
define('UT_BACKGROUND', '#F9FAFB');

// الاتصال بقاعدة البيانات
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// دالة التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة التحقق من صلاحية المدير
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// دالة إعادة التوجيه
function redirect($url) {
    header("Location: $url");
    exit();
}
?>