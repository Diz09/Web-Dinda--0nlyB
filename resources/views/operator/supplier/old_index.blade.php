<?php
// resources/views/supplier/index.blade.php
?>

@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Data Supplier</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Rute menuju Create --}}
    <a href="{{ route('supplier.create') }}" class="btn btn-primary mb-3">Tambah Supplier</a>
    @include('supplier.modal-create')

    {{-- tabel --}}
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
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        @if($supplier->pemasok)
                            {{ $supplier->pemasok->kode }}
                        @elseif($supplier->konsumen)
                            {{ $supplier->konsumen->kode }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $supplier->nama }}</td>
                    <td>{{ $supplier->alamat }}</td>
                    <td>
                        @if($supplier->no_rekening)
                            {{ $supplier->no_rekening }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $supplier->no_tlp }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $barang->id }}">Edit</button>
                        @include('supplier.modal-edit')
                        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.btn-edit');
    const form = document.getElementById('editForm');

    editButtons.forEach(button => {
      button.addEventListener('click', function () {
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editAlamat').value = this.dataset.alamat;
        document.getElementById('editTelepon').value = this.dataset.telepon;
        form.action = this.dataset.action;
      });
    });
  });
</script>

@endsection
