<!DOCTYPE html>
<html>
<head>
    <title>Orders Report</title>
    <style>
        /* Add any custom styling for your PDF here */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px; /* Adjust the width as needed */
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h1>FoodHat Orders Report</h1>
    </div>
    <table>
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($memberGrid as $member)
                <tr>
                    @foreach($member as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
