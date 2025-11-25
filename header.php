<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مفقوداتي - جامعة تبوك</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --ut-primary: <?php echo UT_PRIMARY; ?>;
            --ut-secondary: <?php echo UT_SECONDARY; ?>;
            --ut-accent: <?php echo UT_ACCENT; ?>;
            --ut-neutral: <?php echo UT_NEUTRAL; ?>;
            --ut-background: <?php echo UT_BACKGROUND; ?>;
        }
       
        body {
            background-color: var(--ut-background);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--ut-primary), #2D4BA8);
        }
        
        .btn-primary {
            background-color: var(--ut-primary);
            border-color: var(--ut-primary);
        }
        
        .btn-primary:hover {
            background-color: #2D4BA8;
            border-color: #2D4BA8;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        .sidebar {
            background-color: white;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: var(--ut-neutral);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--ut-primary);
            color: white;
        }
        
        .stat-card {
            background: white;
            border-left: 4px solid var(--ut-primary);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-search"></i>
               مفقوداتي
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <span class="navbar-text me-3">
                        مرحباً، <?php echo $_SESSION['user_name']; ?>
                    </span>
                    <a class="nav-link" href="logout.php">تسجيل الخروج</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">تسجيل الدخول</a>
                    <a class="nav-link" href="register.php">إنشاء حساب</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">