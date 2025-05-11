@extends('layouts.app_operator')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Supplier</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Supplier</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $supplier->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" required>{{ old('alamat', $supplier->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="no_tlp" class="form-label">No Telepon</label>
            <input type="text" class="form-control" id="no_tlp" name="no_tlp" value="{{ old('no_tlp', $supplier->no_tlp) }}" required>
        </div>

        <div class="mb-3">
            <label for="no_rekening" class="form-label">No Rekening</label>
            <input type="text" class="form-control" id="no_rekening" name="no_rekening" value="{{ old('no_rekening', $supplier->no_rekening) }}">
        </div>

       <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                <option value="pemasok" {{ $supplier->pemasok ? 'selected' : '' }}>Pemasok</option>
                <option value="konsumen" {{ $supplier->konsumen ? 'selected' : '' }}>Konsumen</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
