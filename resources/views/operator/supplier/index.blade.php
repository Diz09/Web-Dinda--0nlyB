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
                        <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
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
@endsection
