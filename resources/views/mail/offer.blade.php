<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>বিশেষ অফার</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 30px;
            color: #333;
        }
        .mail-container {
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(90deg, #ff5722, #ff9800);
            color: #fff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 25px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.7;
            margin: 15px 0;
        }
        .offer-box {
            background: #fff3e0;
            border: 2px dashed #ff9800;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            font-size: 18px;
            font-weight: bold;
            color: #e65100;
        }
        .btn {
            display: inline-block;
            background: #ff5722;
            color: #fff !important;
            text-decoration: none;
            padding: 14px 25px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            margin-top: 15px;
        }
        .footer {
            background: #fafafa;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="mail-container">
    <div class="header">
        <h2>🎉 বিশেষ অফার চলছে!</h2>
        <p>সীমিত সময়ের জন্য</p>
    </div>
    <div class="content">
        <p>প্রিয় <strong>{{ $name }}</strong>,</p>
        <p>আমাদের সর্বশেষ অফারটি মিস করবেন না। এখনই <strong>{{ $discount }}%</strong> ছাড় পাচ্ছেন নির্বাচিত পণ্যে!</p>

        <div class="offer-box">
            অর্ডার করলেই পাবেন ক্যাশব্যাক
        </div>

        <p>তাড়াতাড়ি করুন! অফারটি শেষ হবে <strong>{{ $expiryDate }}</strong> তারিখে।</p>

        <p>
            <a href="{{ $offerUrl }}" class="btn">এখনই কিনুন</a>
        </p>
    </div>

    <div class="footer">
        <p><strong>GamezoBD</strong> ব্যবহার করার জন্য ধন্যবাদ।
            সর্বশেষ আপডেট ও অফার পেতে আমাদের সাথে থাকুন।</p>
    </div>
</div>
</body>
</html>
