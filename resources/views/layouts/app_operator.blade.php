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

    </style>
</head>

<body>
    <div class="sidebar">
        <img src="https://img.icons8.com/ios-filled/50/user.png" alt="user icon">

        <!-- Dashboard -->
        <a href="/dashboard-operator" class="sidebar-link">Dashboard</a>

        <!-- Kategori: Data -->
        <div class="sidebar-category">Data</div>
        <a href="/karyawan" class="sidebar-link">Karyawan</a>
        <a href="/barang" class="sidebar-link">Data Barang</a>

        <!-- Kategori: Aktivitas -->
        <div class="sidebar-category">Aktivitas</div>
        <a href="/presensi" class="sidebar-link">Presensi</a>
        <a href="/barang-masuk" class="sidebar-link">Barang Masuk</a>
        <a href="/barang-keluar" class="sidebar-link">Barang Keluar</a>

        <!-- Logout -->
        {{-- <a href="/logout" class="sidebar-link-out">Logout</a> --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</body>


</html>
