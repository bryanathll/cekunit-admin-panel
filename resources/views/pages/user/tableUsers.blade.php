<table class="table" id="users-table">
    <thead>
        <tr>
            <th>No</th> 
            <th>Tanggal</th>
            <th>Nama</th>
            <th>No Whatsapp</th>
            <th>Email</th>
        </tr>
    </thead>

    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->nomor ?? 'null' }}</td>
            <td>{{ $user->created_at ?? 'null' }}</td>
            <td>{{ $user->nama ?? 'null' }}</td>
            <td>{{ $user->no_wa ?? 'null' }}</td>
            <td>{{ $user->email ?? 'null' }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="13" class="text-center">
                {{ $users->appends([
                    'sort' => request('sort'),
                    'direction' => request('direction')
                ])->links('pagination::bootstrap-4') }}
            </td>
        </tr>
    </tfoot>
</table>


