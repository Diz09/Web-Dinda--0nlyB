<div class="grid grid-cols-2 gap-4">
    <!-- Tabel Barang Masuk -->
    <div class="bg-blue-200 p-4 rounded">
        <h3 class="font-semibold mb-2">Barang Masuk</h3>
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangMasukTerbaru as $masuk)
                    <tr>
                        <td>{{ $masuk->tanggal->format('d M Y') }}</td>
                        <td>{{ $masuk->barang->nama }}</td>
                        <td>{{ $masuk->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tabel Barang Keluar -->
    <div class="bg-blue-200 p-4 rounded">
        <h3 class="font-semibold mb-2">Barang Keluar</h3>
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluarTerbaru as $keluar)
                    <tr>
                        <td>{{ $keluar->tanggal->format('d M Y') }}</td>
                        <td>{{ $keluar->barang->nama }}</td>
                        <td>{{ $keluar->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
