@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Supplier</h3>

    <!-- Tombol Tambah -->
    <button type="button" class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Supplier</button>

    <!-- Modal Create -->
    @include('operator.supplier.create')


    <!-- Tabel Supplier -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Supplier</th>
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
      timer: 2000,
      showConfirmButton: false
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
