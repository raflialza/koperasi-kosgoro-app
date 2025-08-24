{{-- File ini HANYA berisi baris-baris tabel (tr) --}}
@forelse ($anggota as $item)
    <tr>
        <td><strong>{{ $item->id_anggota }}</strong></td>
        <td>{{ $item->nama }}</td>
        <td>{{ $item->email }}</td>
        <td>
            @php
                $badgeColor = 'bg-secondary';
                if ($item->instansi == 'SMP') $badgeColor = 'bg-primary';
                if ($item->instansi == 'SMA') $badgeColor = 'bg-success';
                if ($item->instansi == 'SMK') $badgeColor = 'bg-warning text-dark';
            @endphp
            <span class="badge {{ $badgeColor }}">{{ $item->instansi }}</span>
        </td>
        <td>
            <div class="d-flex">
                <!-- Tombol Detail -->
                <button type="button" class="btn btn-sm btn-info me-1" title="Detail"
                    data-bs-toggle="modal"
                    data-bs-target="#detailAnggotaModal"
                    data-nama="{{ $item->nama }}"
                    data-id-anggota="{{ $item->id_anggota }}"
                    data-email="{{ $item->email }}"
                    data-no-telp="{{ $item->no_telp }}"
                    data-alamat="{{ $item->alamat }}"
                    data-instansi="{{ $item->instansi }}"
                    data-tahun-gabung="{{ $item->tahun_gabung }}">
                    <i class="bi bi-eye-fill"></i>
                </button>
                {{-- Perbaikan ada di baris ini. Kita secara eksplisit memberi tahu rute bahwa kita mengirimkan parameter 'anggota' dengan nilai $item->id --}}
                <a href="{{ route('admin.anggota.edit', ['anggota' => $item->id]) }}" class="btn btn-sm btn-warning me-1 action-btn-edit" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Data anggota tidak ditemukan.</td>
    </tr>
@endforelse
