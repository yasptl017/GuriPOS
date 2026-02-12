<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; margin: 20px; }
    h1   { font-size: 16px; text-align: center; margin-bottom: 4px; }
    .sub { text-align: center; color: #666; font-size: 11px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #2c3e50; color: #fff;
        padding: 7px 8px; text-align: left; font-size: 10px;
    }
    tbody td { padding: 6px 8px; border-bottom: 1px solid #eee; }
    tbody tr:nth-child(even) td { background: #f9f9f9; }
    tfoot td { font-weight: bold; padding: 7px 8px; border-top: 2px solid #2c3e50; background: #ecf0f1; }
    .text-right { text-align: right; }
    .generated { text-align: right; font-size: 10px; color: #999; margin-top: 14px; }
</style>
</head>
<body>
    <h1>{{ $setting->app_name ?? 'Punjabi Paradise' }}</h1>
    <div class="sub">{{ $title }}</div>

    <table>
        <thead>
            <tr>
                @foreach($headings as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="generated">Generated: {{ now()->format('d M Y H:i') }}</div>
</body>
</html>
