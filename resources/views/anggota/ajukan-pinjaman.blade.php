@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengajuan Pinjaman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('anggota.pinjaman.proses_ajukan') }}" method="POST" id="form-ajukan-pinjaman">
                        @csrf
                        <div class="mb-3">
                            <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman (Rp)</label>
                            <input type="number" class="form-control @error('jumlah_pinjaman') is-invalid @enderror" id="jumlah_pinjaman" name="jumlah_pinjaman" value="{{ old('jumlah_pinjaman') }}" placeholder="Contoh: 500000" required>
                            @error('jumlah_pinjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input untuk Margin (YANG BARU DITAMBAHKAN) --}}
                        <div class="mb-3">
                            <label for="margin" class="form-label">Margin (%)</label>
                            <select class="form-select @error('margin') is-invalid @enderror" id="margin" name="margin" required>
                                <option value="">-- Pilih Margin --</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('margin') == $i ? 'selected' : '' }}>{{ $i }}%</option>
                                @endfor
                            </select>
                            @error('margin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tenor" class="form-label">Tenor (Bulan)</label>
                            <input type="number" class="form-control @error('tenor') is-invalid @enderror" id="tenor" name="tenor" value="{{ old('tenor') }}" placeholder="Contoh: 12" required>
                             @error('tenor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan Pinjaman</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" required>{{ old('keterangan') }}</textarea>
                             @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('anggota.pinjaman.riwayat') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
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
    const form = document.getElementById('form-ajukan-pinjaman');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form dikirim secara otomatis

            Swal.fire({
                title: 'Konfirmasi Pengajuan',
                text: "Apakah Anda yakin data yang dimasukkan sudah benar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim Pengajuan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush

