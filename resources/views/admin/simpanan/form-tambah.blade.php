@extends('layouts.app')

{{-- Menambahkan style kustom untuk Select2 --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px; /* Menyesuaikan tinggi dengan input Bootstrap */
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Formulir Tambah Simpanan</h5>
                </div>
                <div class="card-body">

                    <!-- Menampilkan Error Validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Terdapat kesalahan:</h6>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.simpanan.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Anggota</label>
                            {{-- DIUBAH: ID diubah untuk target JavaScript --}}
                            <select class="form-select" id="select-anggota" name="user_id" required>
                                <option></option> {{-- Option kosong untuk placeholder Select2 --}}
                                @foreach ($anggota as $item)
                                    <option value="{{ $item->id }}" {{ old('user_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->id_anggota }} - {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_simpanan" class="form-label">Jenis Simpanan</label>
                            <select class="form-select" id="jenis_simpanan" name="jenis_simpanan" required>
                                <option value="Pokok" {{ old('jenis_simpanan') == 'Pokok' ? 'selected' : '' }}>Pokok</option>
                                <option value="Wajib" {{ old('jenis_simpanan') == 'Wajib' ? 'selected' : '' }}>Wajib</option>
                                <option value="Sukarela" {{ old('jenis_simpanan') == 'Sukarela' ? 'selected' : '' }}>Sukarela</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" placeholder="Contoh: 50000" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.simpanan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Menambahkan script untuk Select2 --}}
@push('scripts')
    {{-- jQuery diperlukan oleh Select2 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Inisialisasi Select2 pada dropdown anggota
        $(document).ready(function() {
            $('#select-anggota').select2({
                placeholder: "-- Pilih atau cari nama anggota --",
                allowClear: true
            });
        });
    </script>
@endpush
