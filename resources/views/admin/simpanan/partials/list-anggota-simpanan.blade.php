@forelse ($semuaAnggota as $anggota)
    <tr>
        <td><strong>{{ $anggota->id_anggota }}</strong></td>
        <td>{{ $anggota->nama }}</td>
        <td class="text-end">Rp{{ number_format($anggota->simpanan_sum_jumlah ?? 0, 0, ',', '.') }}</td>
        <td class="text-center">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-sm btn-info me-1" title="Lihat Rincian"
                    data-bs-toggle="modal"
                    data-bs-target="#detailSimpananModal"
                    data-url="{{ route('admin.simpanan.show', $anggota->id) }}">
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
