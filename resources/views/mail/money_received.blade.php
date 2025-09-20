<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>অর্থ প্রাপ্তি</title>
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
            border-bottom: 2px solid #4caf50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #4caf50;
            margin: 0;
        }
        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .transaction-info {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #4caf50;
            border-radius: 5px;
            margin: 20px 0;
        }
        .transaction-info strong {
            display: inline-block;
            width: 140px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background: #4caf50;
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
        <h2>✅ অর্থ প্রাপ্তি</h2>
    </div>
    <div class="content">
        <p>প্রিয় <strong>{{ $name }}</strong>,</p>
        <p>অভিনন্দন! আপনার অ্যাকাউন্টে অর্থ জমা হয়েছে। বিস্তারিত নিচে দেওয়া হলো:</p>

        <div class="transaction-info">
            <p><strong>লেনদেন আইডি:</strong> {{ $trxId }}</p>
            <p><strong>তারিখ:</strong> {{ $date }}</p>
            <p><strong>প্রাপ্ত অর্থ:</strong> {{ number_format($amount, 2) }} টাকা</p>
            <p><strong>অ্যাকাউন্ট অবস্থা:</strong> আপডেটেড ✅</p>
        </div>

        <p>আপনি চাইলে এখনই আপনার ওয়ালেট ব্যালেন্স চেক করতে পারেন।</p>

        <p style="text-align: center;">
            <a href="{{ $walletUrl }}" class="btn">ওয়ালেট দেখুন</a>
        </p>
    </div>

    <div class="footer">
        <p><strong>GamezoBD</strong> ব্যবহার করার জন্য ধন্যবাদ।
            কোনো সহায়তার প্রয়োজন হলে আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করুন। 01828861788</p>
    </div>
</div>
</body>
</html>
