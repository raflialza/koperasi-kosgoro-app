@forelse ($semuaAnggota as $anggota)
    @php
        // Hitung rincian simpanan di sini agar bisa disematkan ke tombol
        $rincian = $anggota->simpanan->groupBy('jenis_simpanan');
        $totalPokok = $rincian->get('Pokok', collect())->sum('jumlah');
        $totalWajib = $rincian->get('Wajib', collect())->sum('jumlah');
        $totalSukarela = $rincian->get('Sukarela', collect())->sum('jumlah');
    @endphp
    <tr>
        <td><strong>{{ $anggota->id_anggota }}</strong></td>
        <td>{{ $anggota->nama }}</td>
        <td class="text-end">Rp{{ number_format($anggota->simpanan_sum_jumlah ?? 0, 0, ',', '.') }}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center">
                {{-- PERUBAHAN: Data disematkan langsung di tombol --}}
                <button type="button" class="btn btn-sm btn-info me-1" title="Lihat Rincian"
                    data-bs-toggle="modal"
                    data-bs-target="#detailSimpananModal"
                    data-nama="{{ $anggota->nama }}"
                    data-id-anggota="{{ $anggota->id_anggota }}"
                    data-total-pokok="Rp{{ number_format($totalPokok, 0, ',', '.') }}"
                    data-total-wajib="Rp{{ number_format($totalWajib, 0, ',', '.') }}"
                    data-total-sukarela="Rp{{ number_format($totalSukarela, 0, ',', '.') }}"
                    data-total-semua="Rp{{ number_format($anggota->simpanan_sum_jumlah ?? 0, 0, ',', '.') }}">
                    <i class="bi bi-eye-fill"></i>
                </button>
                
                <a href="{{ route('admin.simpanan.create', ['anggota_id' => $anggota->id]) }}" class="btn btn-sm btn-success" title="Tambah Simpanan">
                    <i class="bi bi-plus-circle"></i>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Tidak ada data anggota.</td>
    </tr>
@endforelse
