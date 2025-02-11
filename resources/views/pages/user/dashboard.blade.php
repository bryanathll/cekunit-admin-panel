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
        <div class="row">
            <h1>
                <div>Hello, {{ Auth::user()->name }}</div>
            </h1>
        </div>
    </div>


    <div class="container-fluid">

        <div class="card">
            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif

            <div class="card-header">
                <div class="col-9 ">
                    <h4 class="">Data Table Cek Unit</h4>
                </div>

                <div class="col">
                    <form class="d-flex search" role="search">
                        <input class="form-control me-2  rounded-pill search-border " type="text" id="search-input" placeholder="Search..." value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            
            <div class="table-responsive">
                <div class="ps-2 pt-3">
                <!-- dropdown sorting -->
                    <select id="sortColumn">
                        <option value="no">No</option>
                        <option value="nama_nasabah">Nama Nasabah</option>
                        <option value="no_perjanjian">No Perjanjain</option>
                        <option value="nopol">Nopol</option>
                        <option value="coll">Coll</option>
                        <option value="pic">PIC</option>
                        <option value="kategori">Kategori</option>
                        <option value="jto">JTO</option>
                        <option value="no_rangka">No Rangka</option>
                        <option value="no_mesin">No Mesin</option>
                        <option value="merk">Merk</option>
                        <option value="type">Type</option>
                        <option value="warna">Warna</option>
                        <option value="status">Status</option>
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


                    <div id="search-results">
                        @include('pages.user.pagination_table', [
                            'cekunit' => $cekunit,
                            'sort' => $sort,
                            'direction' => $direction,
                            'search' => $search
                        ])
                    </div>
                </div>    
            </div>

            <!-- modal untuk edit data -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Form Edit akan dimuat di sini -->
                            <form id="editForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group pb-3">
                                    <label for="no_perjanjian">No Perjanjian</label>
                                    <input type="text" name="no_perjanjian" id="no_perjanjian" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="nama_nasabah">Nama Nasabah</label>
                                    <input type="text" name="nama_nasabah" id="nama_nasabah" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="nopol">Nopol</label>
                                    <input type="text" name="nopol" id="nopol" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="coll">Coll</label>
                                    <input type="text" name="coll" id="coll" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="pic">PIC</label>
                                    <input type="text" name="pic" id="pic" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" name="kategori" id="kategori" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="jto">JTO</label>
                                    <input type="text" name="jto" id="jto" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="no_rangka">Nomor Rangka</label>
                                    <input type="text" name="no_rangka" id="no_rangka" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="no_mesin">Nomor Mesin</label>
                                    <input type="text" name="no_mesin" id="no_mesin" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="merk">Merk</label>
                                    <input type="text" name="merk" id="merk" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="type">Type</label>
                                    <input type="text" name="type" id="type" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="warna">Warna</label>
                                    <input type="text" name="warna" id="warna" class="form-control">
                                </div>

                                <div class="form-group pb-3">
                                    <label for="status">Status</label>
                                    <input type="text" name="status" id="status" class="form-control">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            
            
            let url = "{{ route('cekunit.export') }}";
            url += `?format=${selectedFormat}`;
            url += `&sort=${sortColumn}`;
            url += `&direction=${sortDirection}`;

            window.location.href = url;
        });
    });
</script>

<!-- script -->
<script>
$(document).ready(function() {
    // Fungsi utama untuk mengambil data
    function fetchData(page = 1) {
        const search = $('#search-input').val();
        const sort = $('#sortColumn').val();
        const direction = $('#sortDirection').val();

        $.ajax({
            url: "{{ route('dashboard') }}", // Menggunakan route dashboard
            method: 'GET',
            data: {
                page: page,
                search: search,
                sort: sort,
                direction: direction
            },
            success: function(response) {
                $('#search-results').html(response);
                updateBrowserURL(page, search, sort, direction);
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    }

    // Fungsi update URL browser
    function updateBrowserURL(page, search, sort, direction) {
        const params = new URLSearchParams({
            page: page,
            search: search,
            sort: sort,
            direction: direction
        });
        const newUrl = `{{ route('dashboard') }}?${params.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);
    }

    // Event handler untuk search input
    $('#search-input').on('input', function() {
        fetchData(1);
    });

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


<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

@endsection