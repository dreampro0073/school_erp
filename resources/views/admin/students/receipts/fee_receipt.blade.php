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
        @page {
            margin: 23px 80px 20px 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <img src="{{$data->logo}}" style="height: 80px;width: 80px;">
        </div>
        <div class="title" style="margin-top:-20px;">{{$data->school_name}} <p style="font-size: 16px;font-weight: normal;">{{$data->school_address}}</p></div>
        <div style="margin-top:-15px;">Date: {{ $data->paid_date }}</div>
    </div>

    <div class="box">
        <p style="margin-top:4px;margin-bottom: 0;"><strong>Student Name:</strong> {{ $data->student_name ?? '' }}</p>
        <p style="margin-top:4px;margin-bottom: 0;"><strong>Fee Type:</strong> {{ $data->fee_type_name ?? '' }}</p>
        <p style="margin-top:4px;margin-bottom: 0;"><strong>Month:</strong> {{ $data->month_name }}</p>
        <p style="margin-top:4px;margin-bottom: 0;"><strong>Financial Year:</strong> {{ $data->period }}</p>
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