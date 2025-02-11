<table class="table" id="input-user-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Created at</th>
            <th>User ID</th>
            <th>Nopol</th>
            <th>Lokasi</th>
            <th>ForN</th>
            <th>Nama</th>
        </tr>
    </thead>

    <tbody>
    @foreach($input_user as $input)
        <tr>
            <td>{{ $input->id ?? 'null' }}</td>
            <td>{{ $input->created_at ?? 'null' }}</td>
            <td>{{ $input->userID ?? 'null' }}</td>
            <td>{{ $input->nopol ?? 'null' }}</td>
            <td>{{ $input->lokasi ?? 'null' }}</td>
            <td>{{ $input->ForN ?? 'null' }}</td>
            <td>{{ $input->nama ?? 'null' }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="13" class="text-center">
                {{ $input_user->appends([
                    'search' => request('search'),
                    'sort' => request('sort'),
                    'direction' => request('direction')
                ])->links('pagination::bootstrap-4') }}
            </td>
        </tr>
    </tfoot>
</table>


