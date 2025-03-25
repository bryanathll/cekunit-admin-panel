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


        <h4 class="card-header">Data User</h4>
        <div class="table-responsive">
            <div class="ps-2 pt-3">
              <!-- dropdown sorting -->
                <select id="sortColumn">
                    <option value="created_at">No</option>
                </select>
 
                <select id="sortDirection">
                    <option value="asc">Asc</option>
                    <option value="desc">Desc</option>
                </select>
  
                <button id='sortButton' class="btn btn-primary">
                    Sort
                </button>

                <div class="mt-5">
                    <!-- Data table dimuat di sini melalui AJAX -->
                    @include('pages.user.tableUsers', ['sort' => $sort, 'direction' => $direction])
                </div>

                <div class="modal fade" id="editModalUsers" tabindex="-1" aria-labelledby="editModalLabel" aria-hidded="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Data User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="editFormUsers" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group pb-3">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control">
                                    </div>

                                    <div class="form-group pb-3">
                                        <label for="no_wa">No Whatsapp</label>
                                        <input type="text" name="no_wa" id="no_wa" class="form-control">
                                    </div>

                                    <div class="form-group pb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>

                                    <div class="form-group pb-3">
                                        <label for="status">Status</label>
                                        <input type="text" name="status" id="status" class="form-control">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div> 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- script pagination and sort -->
<script>
$(document).ready(function() {
    // Fungsi utama untuk mengambil data
    function fetchData(page = 1) {
        const sort = $('#sortColumn').val();
        const direction = $('#sortDirection').val();

        $.ajax({
            url: "{{ route('users') }}",
            method: 'GET',
            data: {
                page: page,
                sort: sort,
                direction: direction
            },
                
            success: function(response) {
                $('#users-table').replaceWith(response);
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
        const newUrl = `{{ route('users') }}?${params.toString()}`;
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


<!-- script modal edit -->
<script>
    $(document).ready(function(){
        $(document).on('click','.editUsers-btn', function(){
            var nomor = $(this).data('nomor')
            var nama = $(this).data('nama');
            var no_wa = $(this).data('no_wa');
            var email = $(this).data('email');
            var status = $(this).data('status');

            $('#editFormUsers').attr('action', '/users/' + nomor);
            $('#nama').val(nama);
            $('#no_wa').val(no_wa);
            $('#email').val(email);
            $('#status').val(status);
        })
    })
</script>

<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

@endsection