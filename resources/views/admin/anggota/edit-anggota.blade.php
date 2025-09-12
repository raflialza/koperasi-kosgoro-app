@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Edit Data Anggota: {{ $anggota->nama }}</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- PERBAIKAN FINAL: Menggunakan id_anggota di dalam action form -->
                    <form action="{{ route('admin.anggota.update', ['anggota' => $anggota->id_anggota]) }}" method="POST" id="edit-anggota-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $anggota->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp', $anggota->no_telp) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                        </div>
                        
                        <hr>
                        <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('edit-anggota-form');

    if (editForm) {
        editForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form dikirim langsung

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data yang Anda masukkan sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirim form
                    this.submit();
                }
            });
        });
    }
});
</script>
@endpush

