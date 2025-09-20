<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>রিফান্ড সম্পন্ন হয়েছে</title>
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
            border-bottom: 2px solid #2196f3;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #2196f3;
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
            border-left: 4px solid #2196f3;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-info strong {
            display: inline-block;
            width: 130px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background: #2196f3;
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
        <h2>💰 রিফান্ড সম্পন্ন হয়েছে</h2>
    </div>
    <div class="content">
        <p>প্রিয় <strong>{{ $name }}</strong>,</p>
        <p>দুঃখিত, আপনার দেওয়া UID সঠিক না থাকায় আপনার অর্ডারটি প্রক্রিয়া করা সম্ভব হয়নি।</p>
        <p>আপনার প্রদত্ত অর্থ স্বয়ংক্রিয়ভাবে আপনার ওয়ালেটে ফেরত দেওয়া হয়েছে।</p>

        <div class="order-info">
            <p><strong>অর্ডার আইডি:</strong> #{{ $orderId }}</p>
            <p><strong>তারিখ:</strong> {{ $date }}</p>
            <p><strong>রিফান্ডকৃত অর্থ:</strong> {{ number_format($amount, 2) }} টাকা</p>
            <p><strong>অবস্থা:</strong> রিফান্ড সম্পন্ন ✅</p>
        </div>

        <p>আপনি চাইলে সঠিক UID দিয়ে আবার অর্ডার করতে পারেন।</p>

        <p style="text-align: center;">
            <a href="{{ $orderUrl }}" class="btn">আবার অর্ডার করুন</a>
        </p>
    </div>

    <div class="footer">
        <p><strong>Gaming Shop</strong> ব্যবহার করার জন্য ধন্যবাদ।
            কোনো সমস্যায় আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করুন।</p>
    </div>
</div>
</body>
</html>
