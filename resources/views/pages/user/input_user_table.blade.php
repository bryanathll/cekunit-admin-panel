@extends('components.user.master-layout')

@section('content')
<div class="content-body">
    <div class="page-titles">
        <ol class="breadcrumb">
            <li>
                <h5 class="bc-title">Data Nasabah</h5>
            </li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Home's </a>
            </li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Data Nasabah</a></li>
        </ol>
    </div>


    <div class="container-fluid">
    <div class="card">


        <h4 class="card-header">Data Table Input User</h4>
          <div class="table-responsive">
            <div class="ps-2 pt-3">
              <!-- dropdown sorting -->
              <select id="sortColumn">
                <option value="id">No</option>
                <option value="created_at">Created at</option>
                <option value="userID">UserID</option>
                <option value="nopol">Nopol</option>
                <option value="lokasi">Lokasi</option>
                <option value="ForN">ForN</option>
                <option value="nama">Nama</option>
              </select>
  
              <select id="sortDirection">
                <option value="asc">Asc</option>
                <option value="desc">Desc</option>
              </select>
  
              <button id='sortButton' class="btn btn-secondary" style="--bs-btn-padding-y: .20rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .55rem; --bs-btn-border-color: var(--bd-violet-bg);">
              Sort
              </button>
              <div class="dropdown mb-3 mt-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                  Pilih Format
              </button>
  
              <ul class="dropdown-menu mt-3">
                <li><a href="#" class="dropdown-item" data-format="csv">Csv(.csv)</a></li>
              </ul>

              <a href="#" id="downloadButton" class="btn btn-success">
                <i class="fas fa-download"></i> 
                Download
              </a>

            </div>


            </div>
                <!-- Data table dimuat di sini melalui AJAX -->
                @include('pages/user/input_user', ['sort' => $sort, 'direction' => $direction])
          </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Sertakan jQuery -->
<!-- script pagination -->
<script>
$(document).ready(function() {
    function loadPage(page) {
        let sortColumn = $('#sortColumn').val();
        let sortDirection = $('#sortDirection').val();

        $.ajax({
            url: "{{ route('input.user') }}?page=" + page + "&sort=" + sortColumn + "&direction=" + sortDirection,
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                console.log('Data received:', data); // Debug: Lihat respons dari server
                $('#input-user-table tbody').html($(data).find('tbody').html());
                $('#input-user-table tfoot').html($(data).find('tfoot').html());

                let newUrl = `?page=${page}&sort=${sortColumn}&direction=${sortDirection}`;
                window.history.pushState({ path: newUrl }, '', newUrl);
            },
            error: function(xhr, status, error) {
                console.error('Error loading pagination data:', error);
            }
        });
    }

    // Menangani klik tautan pagination
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        let url = $(this).attr('href');
        let page = new URL(url).searchParams.get('page'); // Ekstrak page dengan benar
        loadPage(page);
    });
});

</script>

<!-- script sort feature -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#sortButton').click(function () {
            let sortColumn = $('#sortColumn').val();
            let sortDirection = $('#sortDirection').val();

            // send AJAX request to sort endpoint
            $.ajax({
                url: "{{ route('input_user.sort') }}",
                method: 'POST',
                data: {
                    sort: sortColumn,
                    direction: sortDirection,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    // Pastikan response.data ada sebelum menggunakan forEach
                        $('#input-user-table tbody').empty();
                        response.data.forEach(function (input) {
                            let row = `<tr>
                                <td>${input.id}</td>
                                <td>${input.created_at}</td>
                                <td>${input.userID}</td>
                                <td>${input.nopol}</td>
                                <td>${input.lokasi}</td>
                                <td>${input.ForN}</td>
                                <td>${input.nama}</td>
                            </tr>`;
                            $('#input-user-table tbody').append(row);
                        });

                        // Update pagination
                        // if (response.pagination) {
                        //     $('.pagination').html(response.pagination);
                        // }

                    // Perbarui URL dengan parameter sorting
                    let newUrl = `{{ route('input.user') }}?sort=${sortColumn}&direction=${sortDirection}`;
                    window.history.pushState({ path: newUrl }, '', newUrl);
                },
                error: function (xhr) {
                    console.log(xhr.responseText); // Tampilkan pesan error di console
                }
            });
        });
    });
</script>


<!-- script download excel dan csv -->
<script>
    $(document).ready(function() {
        let selectedFormat = 'csv'; // Default format
        
        // Handle klik dropdown item
        $('.dropdown-item').on('click', function() {
            selectedFormat = $(this).data('format');
            $('#dropdownMenuButton').text($(this).text());
        });

        // Handle klik tombol download
        $('#downloadButton').on('click', function() {
            let sortColumn = $('#sortColumn').val();
            let sortDirection = $('#sortDirection').val();
            
            
            let url = "{{ route('input_user.export') }}";
            url += `?format=${selectedFormat}`;
            url += `&sort=${sortColumn}`;
            url += `&direction=${sortDirection}`;

            window.location.href = url;
        });
    });
</script>

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

@endsection