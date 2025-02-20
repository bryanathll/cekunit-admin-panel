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

        <div class="dropdown mb-3 mt-5">
            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
              Pilih Format
            </button>
  
            <ul class="dropdown-menu mt-3">
                <li><a href="#" class="dropdown-item" data-format="csv">Csv(.csv)</a></li>
            </ul>

            <a href="#" id="downloadButton" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> 
                Download
            </a>
        </div>

    <div class="card">


        <h4 class="card-header">Data Table Input Pengecekan Unit</h4>
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
  
              <button id='sortButton' class="btn btn-primary">
                Sort
              </button>

              <div id="dateRangeFilter" style="display:none;" class="mt-2">
                <input type="text" id="startDate" placeholder="Tanggal Mulai" class="datepicker">
                <input type="text" id="endDate" placeholder="Tanggal Akhir" class="datepicker">
              </div>

            <div class="mt-5">
                <!-- Data table dimuat di sini melalui AJAX -->
                @include('pages.user.input_user', ['input_user' => $input_user, 'sort' => $sort, 'direction' => $direction])
            </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Sertakan jQuery -->
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

<!-- script pagination and sort -->
<script>
$(document).ready(function() {
    // Fungsi untuk memuat data

    $('#sortColumn').on('change', function(){
        if($(this).val()==='created_at'){
            $('#dateRangeFilter').show();
        }else{
            $('#dateRangeFilter').hide();
        }
    });

    // fungsi untuk memuat data
    function fetchData(page = 1) {
        const sort = $('#sortColumn').val();
        const direction = $('#sortDirection').val();
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

    // hanya kirim tanggal jika sorting by created_at
    const params = {
        page:page,
        sort:sort,
        direction:direction
    };

    if(sort==='created_at'){
        params.start_date = startDate;
        params.end_date = endDate;
    }

        $.ajax({
            url: "{{ route('input.user') }}",
            method: 'GET',
            data: params,
            success: function(response) {
                $('#input-user-table').replaceWith(response);
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    }

    // Fungsi update URL browser
    function updateBrowserURL(page, sort, direction) {
        const params = new URLSearchParams({
            page: page,
            sort: sort,
            direction: direction
        });
        const newUrl = `{{ route('input.user') }}?${params.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);
    }

    // Event handler untuk tombol sort
    $('#sortButton').on('click', function() {
        fetchData(1);
    });

    // Event handler untuk pagination
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        fetchData(page);
    });
});
</script>


<!-- script date picker -->
 <script>
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
 </script>



<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

@endsection