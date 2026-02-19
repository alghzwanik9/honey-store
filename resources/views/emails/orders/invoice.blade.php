<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; text-align: right;">
    <h2>مرحباً {{ $order->user->name }}،</h2>
    <p>شكراً لتسوقك معنا في مناحل وادي الجرفة.</p>
    <p>مرفق في هذا البريد فاتورة طلبك رقم #{{ $order->id }}.</p>
    <p>نتمنى لك وقتاً ممتعاً!</p>
    <br>
    <p>فريق مناحل وادي الجرفة</p>
</body>
</html>
