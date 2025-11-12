<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Dokumen')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* === Global Style === */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff;
            color: #333;
            line-height: 1.6;
        }

        .document-container {
            max-width: 21cm; /* A4 width */
            margin: 0 auto;
            padding: 1.5cm;
            background: white;
            border: 1px solid #ddd;
        }

        h1, h2, h3 {
            color: #1e3a8a;
            margin-bottom: 0.5em;
        }

        /* === Kop Surat === */
        .kop-surat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .kop-left {
            display: flex;
            align-items: center;
        }

        .kop-left img {
            width: 70px;
            height: auto;
            margin-right: 15px;
        }

        .kop-right {
            text-align: right;
        }

        /* === Table Style === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: bold;
        }

        /* === Section Headings === */
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        /* === Ringkasan Keuangan === */
        .finance-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-left: 6px solid #1d4ed8;
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px 15px;
        }

        .card h4 {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .card p {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .card.blue { border-left-color: #2563eb; }
        .card.green { border-left-color: #16a34a; }
        .card.red { border-left-color: #dc2626; }

        /* === Footer === */
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
            width: 45%;
        }

        .signature p {
            margin-top: 60px;
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 5px;
        }

        @page { size: A4; margin: 1cm; }

        @media print {
            body { margin: 0; }
            .document-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint=function(){ window.close(); }">
    @yield('content')
</body>
 <script>
        // === Realtime Clock untuk waktu cetak ===
        function updateClock() {
            const now = new Date();
            const options = { 
                weekday: 'long', year: 'numeric', month: 'long', 
                day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit'
            };
            const formatted = now.toLocaleString('id-ID', options) + ' WIB';
            const el = document.getElementById('print-time');
            if (el) el.textContent = formatted;
        }

        // Update setiap detik
        setInterval(updateClock, 1000);
        updateClock();

        // Tunggu sedikit sebelum print biar waktu tampil
        window.onload = () => {
            setTimeout(() => {
                window.print();
                window.onafterprint = () => window.close();
            }, 800);
        };
    </script>
</html>
