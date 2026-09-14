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
            width: 100%;
            padding: 0;
            page-break-inside: avoid;
        }
        .card img.card-img {
            width: 100%;
            display: block;
        }
        /* Hanya dipakai saat fallback (template tidak ada). */
        .card-fallback {
            border: 1.5px solid #333;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        .card-fallback img {
            width: 150px;
            height: 150px;
        }
        .card-fallback .hw-name {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            word-break: break-word;
            margin-top: 4px;
        }
        .card-fallback .hw-sub {
            font-size: 9px;
            color: #555;
            word-break: break-all;
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
                @if(!empty($hardware->card_composited))
                <div class="card">
                    <img class="card-img" src="{{ $hardware->card_path }}" alt="QR {{ $hardware->hw_serial_number }}">
                </div>
                @else
                <div class="card-fallback">
                    <img src="{{ $hardware->card_path }}" alt="QR">
                    <div class="hw-name">{{ $hardware->card_title ?? ($hardware->hw_name ?: trim($hardware->hw_brand . ' ' . $hardware->hw_model)) }}</div>
                    <div class="hw-sub">{{ $hardware->card_sub ?? ('SN: ' . $hardware->hw_serial_number) }}</div>
                </div>
                @endif
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
