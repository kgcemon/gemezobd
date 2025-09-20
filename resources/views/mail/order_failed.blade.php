<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>অর্ডার ব্যর্থ হয়েছে</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 30px;
            color: #333;
        }
        .mail-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f44336;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #f44336;
            margin: 0;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #f44336;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-info strong {
            display: inline-block;
            width: 120px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background: #f44336;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="mail-container">
    <div class="header">
        <h2>⚠️ অর্ডার ব্যর্থ হয়েছে</h2>
    </div>
    <div class="content">
        <p>প্রিয় <strong>{{ $name }}</strong>,</p>
        <p>দুঃখিত, আপনার অর্ডারটি সফল হয়নি। পেমেন্ট সম্পন্ন না করায় আপনার অর্ডারটি বাতিল করা হয়েছে।</p>

        <div class="order-info">
            <p><strong>অর্ডার আইডি:</strong> #{{ $orderId }}</p>
            <p><strong>তারিখ:</strong> {{ $date }}</p>
            <p><strong>পরিমাণ:</strong> {{ number_format($amount, 2) }} টাকা</p>
            <p><strong>অবস্থা:</strong> বাতিল ❌</p>
        </div>

        <p>আপনি চাইলে এখনই পুনরায় পেমেন্ট করে আবার অর্ডার করতে পারেন।</p>

        <p style="text-align: center;">
            <a href="{{ $retryUrl }}" class="btn">আবার অর্ডার করুন</a>
        </p>
    </div>

    <div class="footer">
        <p><strong>Gaming Shop</strong> ব্যবহার করার জন্য ধন্যবাদ।
            কোনো সহায়তার প্রয়োজন হলে আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করুন।</p>
    </div>
</div>
</body>
</html>
