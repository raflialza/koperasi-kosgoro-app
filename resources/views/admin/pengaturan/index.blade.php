@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h4 class="mb-3">Pengaturan Simpanan Otomatis</h4>

            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Ubah Jumlah Iuran Bulanan</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p class="text-muted">Nilai yang Anda masukkan di sini akan digunakan untuk penambahan simpanan otomatis pada bulan berikutnya.</p>
                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="simpanan_pokok_otomatis" class="form-label fw-bold">Simpanan Pokok Bulanan (Rp)</label>
                            <input type="number" class="form-control" id="simpanan_pokok_otomatis" name="simpanan_pokok_otomatis" 
                                   value="{{ old('simpanan_pokok_otomatis', $simpananPokok->value ?? '100000') }}" required>
                            @error('simpanan_pokok_otomatis') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="simpanan_wajib_otomatis" class="form-label fw-bold">Simpanan Wajib Bulanan (Rp)</label>
                            <input type="number" class="form-control" id="simpanan_wajib_otomatis" name="simpanan_wajib_otomatis" 
                                   value="{{ old('simpanan_wajib_otomatis', $simpananWajib->value ?? '50000') }}" required>
                             @error('simpanan_wajib_otomatis') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
