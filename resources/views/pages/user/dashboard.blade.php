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


        <div class="row dropdown ">
            <!-- dropdown download -->
            <div class="col-3">
                <div class="dropdown mb-2 mt-3">
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
            </div>

            <!-- dropdown hapus by kategori -->
            <div class="col-9 mt-3 text-end">
                <button class="btn btn-outline-danger dropdown-toggle btn-sm" type="button" id="combinedDropdown" data-bs-toggle="dropdown" > 
                    Hapus Data By Kategori
                </button>

                <ul class="dropdown-menu p-3" style="max-width: 400px;" >
                    <div class="row g-2">
                        <div class="col-12">
                            <select id="columnSelect" class="form-select">
                                <!-- Dropdown untuk memilih kolom -->
                                <option value="">Pilih Kolom</option>
                                <option value="kategori">Kategori</option>
                                <option value="status">Status</option>
                                <option value="actual_penyelesaian">Actual Penyelesaian</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <select id="valueSelect" class="form-select" disabled>
                                <!-- Dropdown untuk memilih nilai -->
                                <option value="">Pilih Nilai</option>
                                <option value="null">null</option>
                            </select>
                        </div>

                        <div class="col-12 mt-2">
                            <!-- Tombol Hapus -->
                            <button class="btn btn-danger w-100 btn-sm" id="deleteButton" disabled>
                                <i class="fas fa-trash me-2"></i>
                                Hapus Data
                            </button>
                        </div>

                        <div class="col-12 mt-3">
                            <button class="btn btn-outline-danger w-100 btn-sm" id="deleteAllButton">
                                <form id="deleteAllForm" action="{{ route('cekunit.deleteAll') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    Hapus Semua Data
                                </form>
                            </button>
                        </div>
                    </div>
                </ul>
            </div>

            
        </div>
        

        <!-- Pesan Sukses/Gagal -->
        <div id="message"></div>
        
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
                        <input class="form-control me-2  rounded-pill search-border " type="text" id="search-input" placeholder="Cari..." value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            
            <div class="table-responsive ">
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
                        <option value="actual_penyelesaian">Actual Penyelesaian</option>
                    </select>
  
                    <select id="sortDirection">
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
  
                    <button id='sortButton' class="btn btn-primary    ">
                        Sort
                    </button>                     


                        

                    <div id="search-results" class="pt-5">
                        @include('pages.user.pagination_table', [
                            'cekunit' => $cekunit,
                            'sort' => $sort,
                            'direction' => $direction,
                            'search' => $search,
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

                                <div class="form-group pb-3">
                                    <label for="actual_penyelesaian">Actual Penyelesaian</label>
                                    <input type="text" name="actual_penyelesaian" id="actual_penyelesaian" class="form-control">
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

<script>
    $(document).ready(function() {
        // Ketika tombol edit diklik
        $(document).on('click', '.edit-btn', function() {
            // Ambil data dari atribut data-*
            var no = $(this).data('no');
            var no_perjanjian = $(this).data('no_perjanjian');
            var nama_nasabah = $(this).data('nama_nasabah');
            var nopol = $(this).data('nopol');
            var coll = $(this).data('coll');
            var pic = $(this).data('pic');
            var kategori = $(this).data('kategori');
            var jto = $(this).data('jto');
            var no_rangka = $(this).data('no_rangka');
            var no_mesin = $(this).data('no_mesin');
            var merk = $(this).data('merk');
            var type = $(this).data('type');
            var warna = $(this).data('warna');
            var status = $(this).data('status');
            var actual_penyelesaian = $(this).data('actual_penyelesaian');

            // Isi form di modal dengan data yang sesuai
            $('#editForm').attr('action', '/cekunit/' + no); // Set action form
            $('#no_perjanjian').val(no_perjanjian);
            $('#nama_nasabah').val(nama_nasabah);
            $('#nopol').val(nopol);
            $('#coll').val(coll);
            $('#pic').val(pic);
            $('#kategori').val(kategori);
            $('#jto').val(jto);
            $('#no_rangka').val(no_rangka);
            $('#no_mesin').val(no_mesin);
            $('#merk').val(merk);
            $('#type').val(type);
            $('#warna').val(warna);
            $('#status').val(status);
            $('#actual_penyelesaian').val(actual_penyelesaian);
            // Jika ada field lain, isi di sini
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
            
            
            let url = "{{ route('cekunit.export') }}";
            url += `?format=${selectedFormat}`;
            url += `&sort=${sortColumn}`;
            url += `&direction=${sortDirection}`;

            window.location.href = url;
        });
    });
</script>

<!-- script deleteAll -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteAllButton = document.getElementById('deleteAllButton');

        if (deleteAllButton) {
            deleteAllButton.addEventListener('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Yakin Untuk DELETE SEMUA DATA?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteAllForm').submit();
                    }
                });
            });
        }
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

<script>
    $(document).ready(function() {

        // Saat kolom dipilih





        
    });
    // fungsi untuk reload dropdown data
    function reloadDropdown() {
        const column = $('#columnSelect').val(); // Ambil nilai dropdown pertama

            if (column) {
                $.ajax({
                    url: "{{ route('cekunit.getUniqueValues') }}",
                    method: 'GET',
                    data: { column: column },
                    success: function(response) {
                        $('#valueSelect').empty().append('<option value="">Pilih Nilai</option>');

                        if (Array.isArray(response)) {
                            response.forEach(function(value) {
                                $('#valueSelect').append(`<option value="${value}">${value}</option>`);
                            });
                         }
                        $('#valueSelect').prop('disabled', response.length === 0);
                    },
                    error: function(xhr) {
                        $('#message').html('<div class="alert alert-danger">Gagal mengambil data.</div>');
                    }
                });
            }
        }

</script>

<script>
    $(document).ready(function(){
        // saat dropdown dibuka
        $('#combinedDropdown').on('show.bs.dropdown', function(){
            // reset state
            $('columnSelect').val('');
            $('valueSelect').val('').prop('disabled', true);
            $('deleteButton').val('').prop('disabled', true);
        });

        // saat kolom dipilih
        $('#columnSelect').on('change', function() {
            const column = $(this).val();

            if (column) {
                // Ambil data unik dari server
                $.ajax({
                    url: "{{ route('cekunit.getUniqueValues') }}",
                    method: 'GET',
                    data: { column: column },
                    success: function(response) {
                        $('#valueSelect').empty().append('<option value="">Pilih Nilai</option>');

                        $('#valueSelect').append('<option value="null"> null </option>')

                        response.forEach(function(value) {
                            $('#valueSelect').append(`<option value="${value}">${value}</option>`);
                        });

                        $('#valueSelect').prop('disabled', false);
                    },
                    error: function(xhr) {
                        $('#message').html('<div class="alert alert-danger">Gagal mengambil data.</div>');
                    }
                });

            } else {
                $('#valueSelect').empty().append('<option value="">Pilih Nilai</option>').prop('disabled', true);
                $('#deleteButton').prop('disabled', true);
            }
        });

                // Saat nilai dipilih
                $('#valueSelect').on('change', function() {
            $('#deleteButton').prop('disabled', !$(this).val());
        });

        // Saat tombol hapus diklik
        $('#deleteButton').on('click', function() {
            const column = $('#columnSelect').val();
            const value = $('#valueSelect').val();

            if (column && value) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: "{{ route('cekunit.deleteByCategory') }}",
                        method: 'POST',
                        data: {
                            column: column,
                            value: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#message').html('<div class="alert alert-success">Data berhasil dihapus!</div>');
                            $('#valueSelect').empty().append('<option value="">Pilih Nilai</option>').prop('disabled', true);
                            $('#deleteButton').prop('disabled', true);
                            
                            // muat ulang data table
                            loadTableData();

                            // muat ulang dropdown data
                            reloadDropdown();
                        },
                        error: function(xhr) {
                            $('#message').html('<div class="alert alert-danger">Gagal menghapus data.</div>');
                        }
                    });
                }
            }
        });

        // fungsi untuk reload data di dalam table setelah button hapus di klik
        function loadTableData(){
            $.ajax({
                url: "{{ route('dashboard') }}",
                method: 'GET',
                success: function(response){
                    $('#cekunit-table').html(response);
                },
                error: function(xhr){
                    $('#message').html('<div class="alert alert-danger"> Gagal Memuat Data Table</div>')
                }
            })
        }

    })
</script>

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

@endsection