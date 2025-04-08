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
            <th>Kategori</th>
            <th>Nama Nasabah</th>
            <th>No Perjanjain</th>
        </tr>
    </thead>

    <tbody>
    @foreach($input_user as $input)
        <tr>
            <td>{{ $input->id ?? 'null' }}</td>
            <td>{{ $input->created_at ?? 'null' }}</td>
            <td>{{ $input->userID ?? 'null' }}</td>
            <td>{{ $input->nopol ?? 'null' }}</td>
            <td>
                @php
                    $location = $input->lokasi ?? null;
                    if($location){
                        $location = str_replace(['LatLng(lat: ', ')'], '', $location);
                        list($lat, $lng) = explode(', lng: ', $location);
                    }

                @endphp

                @if($location)
                    <a href="https://www.google.com/maps?q={{ $lat }}, {{$lng}}" target="_blank" class="text-info">
                        {{ $lat }}, {{ $lng }}
                    </a>
                @else
                    null
                @endif
            </td>
            <td>{{ $input->ForN ?? 'null' }}</td>
            <td>{{ $input->nama ?? 'null' }}</td>
            <td>{{ $input->kategori ?? 'null' }}</td>
            <td>{{ $input->nama_nasabah ?? 'null' }}</td>
            <td>{{ $input->no_perjanjian ?? 'null' }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="13" class="text-center">
                {{ $input_user->appends([
                    'sort' => request('sort'),
                    'direction' => request('direction'),
                    'search' => request('search')
                ])->links('pagination::bootstrap-4') }}
            </td>
        </tr>
    </tfoot>
</table>


