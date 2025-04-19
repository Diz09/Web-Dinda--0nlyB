<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #c6d6f4;
        }
        .sidebar {
            width: 250px;
            background-color: #4a4a4a;
            height: 100vh;
            position: fixed;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }
        .sidebar img {
            width: 40px;
            height: 40px;
            margin-bottom: 30px;
        }
        .sidebar a {
            padding: 15px 20px;
            width: 100%;
            color: white;
            text-decoration: none;
            display: block;
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: #ffd6a1;
            color: #4a4a4a;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .welcome-box {
            background-color: #95c5cc;
            padding: 15px;
            margin-bottom: 20px;
            width: fit-content;
            color: white;
            font-size: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .box {
            background-color: #8e9eb5;
            padding: 20px;
            color: white;
            border-radius: 8px;
        }
        .large-box {
            grid-column: span 2;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="https://img.icons8.com/ios-filled/50/user.png" alt="user icon">
        {{-- jumlah (rp) pendapatan (barang keluar) dan pengeluaran (barang masuk dan gaji) --}}
        {{-- serta berisikan tabel dengan grafiks garis yang mena garis merah berupa pengeluaran dan garis biru pendapatan --}}
        {{--  --}}
        <a href="/pimpinan/dashboard" class="active">Dashboard</a>
        {{--  --}}
        <a href="#">Laporan Pekerja</a>
        {{--  --}}
        <a href="#">Stok Barang</a> 
        <a href="#">Supplier</a>
        <a href="#">Pembelian</a>
        <a href="#">Laporan Keuangan</a>
        <a href="#">Logout</a>
    </div>
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
