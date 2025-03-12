<table class="table" id="pic-table">
    <thead>
        <tr>
            <th>No</th> 
            <th>ID Coll</th>
            <th>Nama Collector</th>
            <th>No Whatsapp</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @foreach($pic as $piccol)
        <tr>
            <td>{{ $piccol->nomor ?? 'null' }}</td>
            <td>{{ $piccol->id_coll ?? 'null' }}</td>
            <td>{{ $piccol->nama_collector ?? 'null' }}</td>
            <td>{{ $piccol->no_wa ?? 'null' }}</td>
            <td>{{ $piccol->status ?? 'null' }}</td>
            <td>
                <button class="btn btn-primary btn-sm editPIC-btn" data-bs-toggle="modal" data-bs-target="#editModalPIC"
                    data-nomor="{{$piccol->nomor}}"
                    data-id_coll="{{$piccol->id_coll}}"
                    data-nama_collector="{{$piccol->nama_collector}}"
                    data-no_wa="{{$piccol->no_wa}}"
                    data-status="{{$piccol->status}}"
                >  
                Edit
                </button>
                <form action="{{ route('pic.destroy', $piccol->nomor) }}" method="post" style="display:inline;">
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
                {{ $pic->appends([
                    'sort' => request('sort'),
                    'direction' => request('direction')
                ])->links('pagination::bootstrap-4') }}
            </td>
        </tr>
    </tfoot>
</table>


