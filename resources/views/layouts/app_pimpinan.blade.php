<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            color: #333;
        }

        .content-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        /* style sidebar */
        .sidebar {
            width: 230px;
            background-color: #2c3e50;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
        }

        .sidebar img {
            width: 50px;
            height: 50px;
            margin-bottom: 30px;
        }

        .sidebar-link{
            padding: 12px 20px;
            width: 100%;
            color: #ecf0f1;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: 0.3s ease;
            box-sizing: border-box;
        }
        
        
        .sidebar-link:hover,
        .sidebar-link.active{
            background-color: #ecf0f1;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .sidebar-link-out {
            padding: 12px 20px;
            width: 100%;
            color: orangered;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: 0.3s ease;
            box-sizing: border-box;
            margin-top: 40px;
        }

        .sidebar-link-out:hover{
            /* width: 100%; */
            background-color: red;
            color: white;
            font-weight: bold;
        }
        
        .sidebar-category {
            font-weight: bold;
            padding: 10px 15px;
            color: #666;
            text-transform: uppercase;
            font-size: 12px;
            text-align: start;
            margin-top: 20px;
        }

        /* style dashboard */
        .dashboard-container {
            background-color: #bdbdf2;
            padding: 20px;
            border-radius: 15px;
        }

        .title-box {
            background-color: #bdbdf2;
            /* border: 2px solid #2f80ed; */
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 0px 50px;
            height: auto;
            font-weight: normal;
            text-align: left; /* ubah dari center jadi kiri */
            font-size: 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-box {
            background-color: #eff0c0;

            font-size: 15px;
            border-radius: 10px;
            padding: 20px;
            height: auto;
            font-weight: bold;
            text-align: center;

            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-wrap: wrap;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-row .info-box {
            flex: 1;
        }

        .filter-info {
            width: 60px;
        }
        
        /* style grafiks */
        .chart-card {
            background-color: #eff0c0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            height: 400px; /* TAMBAHKAN INI */
        }

        .chart-card canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .chart-title {
            font-weight: bold;
            color: #333;
        }

        .chart-c {
            font-size: 15px;
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
        }
        
        .main-content {
            margin-left: 230px;
            padding: 30px;
        }

        .welcome-box {
            background-color: #3498db;
            color: white;
            padding: 20px 25px;
            font-size: 22px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .box h2 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #2c3e50;
        }

        .box p {
            font-size: 14px;
            color: #555;
        }

        .large-box {
            grid-column: span 2;
        }

        @media (max-width: 600px) {
            .sidebar {
                /* position: static; */
                width: 100%;
                height: auto;
                flex-direction: row;
                overflow-x: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .large-box {
                grid-column: span 1;
            }
        }

        /* for children */
        .page-header h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .table-container {
            overflow-x: auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            color: #333;
        }

        thead {
            background-color: #3498db;
            color: #fff;
        }

        thead th {
            padding: 12px 10px;
            text-align: left;
        }

        tbody td {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        table a:hover {
            text-decoration: underline;
        }

        form.flex.items-center.gap-2 select,
        form.flex.items-center.gap-2 input,
        form.flex.items-center.gap-2 button {
            display: inline-block;
        }

    </style>

    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>

<body>
    <div class="sidebar">
        <img src="https://img.icons8.com/ios-filled/50/user.png" alt="user icon">
        {{-- jumlah (rp) pendapatan (barang keluar) dan pengeluaran (barang masuk dan gaji) --}}
        {{-- serta berisikan tabel dengan grafiks garis yang mena garis merah berupa pengeluaran dan garis biru pendapatan --}}
        <a href="/pimpinan/dashboard" class="sidebar-link">Dashboard</a>
        {{-- tabel stok barang --}}
        <a href="/pimpinan/stok-barang" class="sidebar-link">Stok Barang</a> 
        {{-- <a href="#" class="sidebar-link">Supplier</a> --}}
        {{-- <a href="#" class="sidebar-link">Pembelian</a> --}}
        {{-- tabel laporan para pekerja --}}
        <a href="/pimpinan/laporan-karyawan" class="sidebar-link">Laporan Pekerja</a>
        {{-- tabel laporan pembelian --}}
        <a href="/pimpinan/laporan-keuangan" class="sidebar-link">Laporan Keuangan</a>
        
        {{-- <a href="/logout" class="sidebar-link-out">Logout</a> --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a href="#" class="sidebar-link-out"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           Logout
        </a>
        
    </div>
    
    <div class="main-content">
        @yield('content')
    </div>
</body>

<!-- Tambahkan di dalam tag <head> untuk CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tambahkan di bagian bawah sebelum penutup tag </body> untuk JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</html>
