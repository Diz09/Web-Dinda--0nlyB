<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Tambahkan di dalam tag <head> untuk CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            color: #333;
        }

        .i-g{
            width: 50%;
        }

        .sidebar {
            width: 230px;
            background-color: #2c3e50;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
            overflow: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar img {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
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
            margin-top: 20px;
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
            margin-top: 10px;
        }

        .sidebar-submenu {
            margin-left: 10px;
            font-size: 14px;
        }

        .sidebar-submenu .sidebar-link {
            display: block;
            padding: 4px 0 4px 20px;
            color: #ccc;
        }

        .sidebar-submenu .sidebar-link:hover {
            color: white;
        }

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

        .subtitle-box {
            background-color: #eff0c0;

            font-size: 15px;
            border-radius: 10px;
            padding: 20px;
            height: auto;
        }

        .info-box {
            background-color: #eff0c0;

            font-size: 15px;
            border-radius: 10px;
            padding: 20px;
            height: auto;
            font-weight: bold;
            text-align: center; /* ubah dari center jadi kiri */

            display: flex;
            justify-content: space-evenly;
            align-items: center;
            flex-wrap: wrap;

            margin-bottom: 20px
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-all {
            display: flex;
            float: right;
        }

        .info-row .info-box {
            flex: 1;
        }

        .filter-info {
            width: 60px;
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
            text-align: center;
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

        .row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .box-custom {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);

            text-align: center;
        }

        .b-pri {
            text-align: right;
            /* padding-right: 25px; */
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="https://img.icons8.com/ios-filled/50/user.png" alt="user icon">

        <!-- Dashboard -->
        <a href="/operator/dashboard" class="sidebar-link">Dashboard</a>

        <!-- Kategori: Data -->
        <div class="sidebar-category">Data</div>
        <a href="/operator/barang" class="sidebar-link">Barang</a>
            <div class="sidebar-submenu ms-3">
                <a href="{{ route('barang.index', ['filter' => 'produk']) }}" class="sidebar-link">Produk</a>
                <a href="{{ route('barang.index', ['filter' => 'pendukung']) }}" class="sidebar-link">Pendukung</a>
                {{-- <a href="{{ route('barang.index', ['filter' => 'dasar']) }}" class="sidebar-link">Barang Dasar</a> --}}
            </div>
        <a href="/operator/suplier" class="sidebar-link">Suplier</a>
        <a href="/operator/karyawan" class="sidebar-link">Karyawan</a>
        <a href="/operator/gaji" class="sidebar-link">Gaji</a>
        
        <!-- Kategori: Aktivitas -->
        <div class="sidebar-category">Aktivitas</div>
        {{-- <a href="/operator/presensi" class="sidebar-link">Presensi</a> --}}
        
        @php
            $latestKuartalId = \App\Models\Kuartal::max('id');
        @endphp
        <a href="{{ route('presensi.index', ['kuartal_id' => $latestKuartalId]) }}" class="sidebar-link">Presensi</a>

        <a href="/operator/transaksi" class="sidebar-link">Transaksi</a>
        {{-- <a href="/operator/barang-masuk" class="sidebar-link">Barang Masuk</a>
        <a href="/operator/barang-keluar" class="sidebar-link">Barang Keluar</a> --}}

        <!-- Logout -->
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

    <!-- Tambahkan di bagian bawah sebelum penutup tag </body> untuk JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
