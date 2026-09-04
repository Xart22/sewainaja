<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table.grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            table-layout: fixed;
        }
        table.grid tr {
            page-break-inside: avoid;
        }
        table.grid td {
            width: 50%;
            vertical-align: top;
        }
        .card {
            border: 1.5px solid #333;
            border-radius: 6px;
            padding: 8px;
            height: 250px;
            page-break-inside: avoid;
            overflow: hidden;
        }
        .card-header {
            border-bottom: 1px dashed #999;
            padding-bottom: 5px;
            margin-bottom: 5px;
            text-align: center;
        }
        .card-header h3 {
            font-size: 13px;
            color: #1e3a8a;
            word-break: break-word;
        }
        .card-header .sn {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
            word-break: break-all;
        }
        .qr {
            text-align: center;
            margin: 4px 0;
        }
        .qr img {
            width: 130px;
            height: 130px;
        }
        .customer {
            font-size: 10px;
            color: #333;
            text-align: center;
            word-break: break-word;
        }
    </style>
</head>

<body>
    @php($pairs = $hardwares->chunk(2))
    <table class="grid">
        @foreach ($pairs as $pair)
        <tr>
            @foreach ($pair as $hardware)
            <td>
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $hardware->hw_name ?: $hardware->hw_brand . ' ' . $hardware->hw_model }}</h3>
                        <div class="sn">{{ $hardware->hw_brand }} {{ $hardware->hw_model }} | SN: {{ $hardware->hw_serial_number }}</div>
                    </div>
                    <div class="qr"><img src="{{ $hardware->qr_path }}" alt="QR"></div>
                    <div class="customer">
                       {{ optional($hardware->customer)->name }}<br>
                        Scan untuk lapor kendala
                    </div>
                </div>
            </td>
            @endforeach
            @if ($pair->count() === 1)
            <td></td>
            @endif
        </tr>
        @endforeach
    </table>
</body>

</html>
