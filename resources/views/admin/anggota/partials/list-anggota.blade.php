@forelse ($anggota as $a)
<tr>
    <td>{{ $a->id_anggota }}</td>
    <td>{{ $a->nama }}</td>
    <td>{{ $a->email }}</td>
    <td>{{ $a->instansi }}</td>
    <td>
        <div class="d-flex">
            <!-- Tombol Detail -->
            <button type="button" class="btn btn-info btn-sm me-1" 
                    data-bs-toggle="modal" 
                    data-bs-target="#detailAnggotaModal"
                    data-id-anggota="{{ $a->id_anggota }}"
                    data-nama="{{ $a->nama }}"
                    data-email="{{ $a->email }}"
                    data-no-telp="{{ $a->no_telp }}"
                    data-alamat="{{ $a->alamat }}"
                    data-instansi="{{ $a->instansi }}"
                    data-tahun-gabung="{{ $a->tahun_gabung }}">
                <i class="bi bi-eye"></i>
            </button>

            <!-- Tautan Edit -->
            <a href="{{ route('admin.anggota.edit', ['anggota' => $a->id_anggota]) }}" class="btn btn-warning btn-sm me-1">
                <i class="bi bi-pencil-square"></i>
            </a>

            @if (Auth::user()->role == 'super_admin')
            <!-- Form Hapus -->
            <form action="{{ route('admin.anggota.destroy', ['anggota' => $a->id_anggota]) }}" method="POST" class="form-delete d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">Tidak ada data anggota ditemukan.</td>
</tr>
@endforelse

