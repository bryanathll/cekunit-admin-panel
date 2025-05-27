<table class="table" id="cekunit-table">
    <thead>
        <tr>
            <th>No</th>
            <th>No Perjanjian</th>
            <th>Nama Nasabah</th>
            <th>Nopol</th>
            <th>Coll</th>
            <th>PIC</th>
            <th>Kategori</th>
            <th>JTO</th>
            <th>No Rangka</th>
            <th>No Mesin</th>
            <th>Merk</th>
            <th>Type</th>
            <th>Warna</th>
            <th>Status</th>
            <th>Actual Penyelesaian</th>
            <th>Angsuran Ke</th>
            <th>Tenor</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cekunit as $unit)
            <tr>
                <td>{{ $unit->no ?? 'null' }}</td>
                <td>{{ $unit->no_perjanjian ?: 'null' }}</td>
                <td>{{ $unit->nama_nasabah ?: 'null' }}</td>
                <td>{{ $unit->nopol ?: 'null' }}</td>
                <td>{{ $unit->coll ?: 'null' }}</td>
                <td>{{ $unit->pic ?: 'null' }}</td>
                <td>{{ $unit->kategori ?: 'null' }}</td>
                <td>{{ $unit->jto ?: 'null' }}</td>
                <td>{{ $unit->no_rangka ?: 'null' }}</td>
                <td>{{ $unit->no_mesin ?: 'null' }}</td>
                <td>{{ $unit->merk ?: 'null' }}</td>
                <td>{{ $unit->type ?: 'null' }}</td>
                <td>{{ $unit->warna ?: 'null' }}</td>
                <td>{{ $unit->status ?: 'null' }}</td>
                <td>{{ $unit->actual_penyelesaian ?: 'null' }}</td>
                <td>{{ $unit->angsuran_ke ?: 'null' }}</td>
                <td>{{ $unit->tenor ?: 'null' }}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" 
                        data-no="{{ $unit->no }}" 
                        data-no_perjanjian="{{ $unit->no_perjanjian }}" 
                        data-nama_nasabah="{{ $unit->nama_nasabah }}"
                        data-nopol="{{ $unit->nopol }}"
                        data-coll="{{ $unit->coll }}"
                        data-pic="{{ $unit->pic }}"
                        data-kategori="{{ $unit->kategori }}"
                        data-jto="{{ $unit->jto }}"
                        data-no_rangka="{{ $unit->no_rangka }}"
                        data-no_mesin="{{ $unit->no_mesin }}"
                        data-merk="{{ $unit->merk }}"
                        data-type="{{ $unit->type }}"
                        data-warna="{{ $unit->warna }}"
                        data-status="{{ $unit->status }}"
                        data-actual_penyelesaian="{{ $unit->actual_penyelesaian }}"
                        data-angsuran_ke="{{ $unit->angsuran_ke }}"
                        data-tenor="{{ $unit->tenor }}"
                        >
                        Edit
                    </button>
                    <form action="{{ route('cekunit.destroy', $unit->no) }}" method="post" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"> Hapus </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="13" class="text-center">
                {{ $cekunit->appends([
                    'search' => request('search'),
                    'sort' => request('sort'),
                    'direction' => request('direction')
                ])->links('pagination::bootstrap-4') }}
            </td>
        </tr>
    </tfoot>
</table>