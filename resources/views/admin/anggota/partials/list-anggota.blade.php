@forelse ($anggota as $user)
    <tr>
        <td>{{ $user->id_anggota }}</td>
        <td>{{ $user->nama }}</td>
        <td>{{ $user->email }}</td>
        <td>
            <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');">
                <a href="{{ route('admin.anggota.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">Tidak ada anggota yang cocok dengan pencarian Anda.</td>
    </tr>
@endforelse