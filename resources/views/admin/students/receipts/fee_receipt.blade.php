<!DOCTYPE html>
<html>
<head>
    <title>Fee Receipt</title>
    <style>
        body { font-family: DejaVu Sans; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; }
        .title { font-size: 20px; font-weight: bold; }
        .box { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 10px; text-align: left; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="title">School Fee Receipt</div>
        <div>Date: {{ $data->paid_date }}</div>
    </div>

    <div class="box">
        <p><strong>Student Name:</strong> {{ $data->student_name ?? '' }}</p>
        <p><strong>Fee Type:</strong> {{ $data->fee_type_name ?? '' }}</p>
        <p><strong>Month:</strong> {{ $data->month_name }}</p>
    </div>

    <table>
        <tr>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Paid Date</th>
        </tr>
        <tr>
            <td>₹ {{ $data->amount }}</td>
            <td>{{ ucfirst($data->payment_mode) }}</td>
            <td>{{ $data->paid_date }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Authorized Signature</p>
    </div>
</div>

</body>
</html>