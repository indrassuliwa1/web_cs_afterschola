<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Kontrak</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 30px;
            color: #000;
        }

        .document-container {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }

        .kop-surat h2 {
            text-align: center;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .kop-surat p {
            text-align: center;
            margin-top: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .info-table th {
            width: 35%;
            text-align: left;
            padding: 8px;
            background: #f4f4f4;
        }

        .info-table td {
            padding: 8px;
        }

        .ttd-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .ttd-section .left,
        .ttd-section .right {
            width: 45%;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    @yield('content')
</body>
</html>
