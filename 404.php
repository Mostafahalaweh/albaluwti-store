<?php
// صفحة الخطأ 404
http_response_code(404);
?>
<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>الصفحة غير موجودة - متجر البلوطي</title>
    <link href='https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap' rel='stylesheet'>
    <style>
        body { 
            font-family: 'Cairo', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            margin: 0; 
            padding: 20px; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container { 
            max-width: 600px; 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
        }
        .error-code {
            font-size: 8em;
            color: #e74c3c;
            margin: 0;
            font-weight: bold;
        }
        .error-title {
            font-size: 2em;
            color: #2c3e50;
            margin: 20px 0;
        }
        .error-message {
            color: #7f8c8d;
            font-size: 1.1em;
            margin-bottom: 30px;
        }
        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class='error-container'>
        <h1 class='error-code'>404</h1>
        <h2 class='error-title'>الصفحة غير موجودة</h2>
        <p class='error-message'>عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.</p>
        <div>
            <a href='../index.php' class='btn'>🏠 العودة للرئيسية</a>
            <a href='products.php' class='btn'>🛍️ تصفح المنتجات</a>
        </div>
    </div>
</body>
</html>