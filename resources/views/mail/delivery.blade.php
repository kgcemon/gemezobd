<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Delivered</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: #28a745;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .content {
            padding: 25px;
            color: #333;
        }
        .content h2 {
            color: #28a745;
            margin-top: 0;
        }
        .order-info {
            margin: 20px 0;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
        }
        .order-info p {
            margin: 6px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Your Order Has Been Delivered!</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $customerName }},</h2>
        <p>We’re excited to let you know that your order has been successfully delivered. 🎁</p>

        <div class="order-info">
            <p><strong>Order ID:</strong> #{{ $orderId }}</p>
            <p><strong>Order Date:</strong> {{ $orderDate }}</p>
            <p><strong>Item:</strong> {{ $orderItem }}</p>
            <p><strong>Your Address:</strong> {{ $orderAddress }}</p>
            <p><strong>Total Amount:</strong> {{ $orderAmount }}৳</p>
            <p><strong>Status:</strong> Delivered ✅</p>
        </div>

        <p>We hope you enjoy your purchase! If you have any questions or concerns, feel free to reach out to our support team anytime.</p>

        <a href="{{ $orderLink }}" class="btn">View My Order</a>
    </div>
    <div class="footer">
        <p>Thank you for shopping with <strong>{{ config('app.name') }}</strong> 💚</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
