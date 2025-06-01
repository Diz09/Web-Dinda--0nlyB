@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Mitra Bisnis</h3>

    <div style="display: flex; justify-content:end; align-items: center; space-between: 10px;">
        {{-- form filter --}}
        <form id="filterForm" method="GET" action="{{ route('supplier.index') }}" class="mb-3">
            <div style="display: flex; flex-direction: row; justify-content: center; align-items: center; flex-wrap: nowrap; margin-right: 20px;">
                <input type="hidden" name="kategori" id="kategoriInput" value="{{ request('kategori') }}">
                
                <div style="display: flex; justify-content: space-between; margin-right: 20px;">
                    <button type="button" class="btn btn-outline-primary btn-sm kategoriBtn {{ request('kategori') === 'pemasok' ? 'active disabled' : '' }}" data-value="pemasok">
                        Pemasok
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm kategoriBtn {{ request('kategori') === 'konsumen' ? 'active disabled' : '' }}" data-value="konsumen">
                        Konsumen
                    </button>
                </div>

                <input type="text" name="keyword" id="keywordInput" value="{{ request('keyword') }}" class="form-control" placeholder="Cari nama atau alamat mitra...">
            </div>
        </form>

        <!-- Tombol Tambah -->
        <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Mitra</button>
    </div>

    <!-- Tabel Supplier -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mitra</th>
                    <th>Alamat</th>
                    <th>No Rekening</th>
                    <th>No Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $i => $supplier)
                @php
                    $kode = $supplier->pemasok->kode ?? $supplier->konsumen->kode ?? '-';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $kode }}</td>
                    <td>{{ $supplier->nama }}</td>
                    <td>{{ $supplier->alamat }}</td>
                    <td>{{ $supplier->no_rekening ?? '-' }}</td>
                    <td>{{ $supplier->no_tlp }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-sm btn-info btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal"
                            data-id="{{ $supplier->id }}"
                            data-nama="{{ $supplier->nama }}"
                            data-alamat="{{ $supplier->alamat }}"
                            data-telepon="{{ $supplier->no_tlp }}"
                            data-rekening="{{ $supplier->no_rekening }}"
                            data-kategori="{{ $supplier->pemasok ? 'pemasok' : 'konsumen' }}"
                            data-action="{{ route('supplier.update', $supplier->id) }}">
                            Edit
                        </button>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="formDeleteSupplier d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
@include('operator.supplier.create')
<!-- Modal Edit Supplier -->
@include('operator.supplier.edit')

<!-- SweetAlert & Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // SweetAlert untuk session
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: '{{ session('success') }}',
      showConfirmButton: false,
      timer: 2000
    });
  @endif

  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan',
      html: `{!! implode('<br>', $errors->all()) !!}`,
    });
  @endif
</script>

<script src="{{ asset('js/supplier.js') }}"></script>

@endsection
