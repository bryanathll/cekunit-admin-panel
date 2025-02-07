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


        <h4 class="card-header">Data Table Cek Unit</h4>
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


            </div>
                <!-- Data table dimuat di sini melalui AJAX -->
                @include('pages/user/pagination_table', ['sort' => $sort, 'direction' => $direction])
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
                    <!-- Tambahkan field lainnya sesuai kebutuhan -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
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

            // Isi form di modal dengan data yang sesuai
            $('#editForm').attr('action', '/cekunit/' + no_perjanjian); // Set action form
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

            // Jika ada field lain, isi di sini
        });
    });
</script>

<!-- Sertakan jQuery -->
<!-- script pagination -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fungsi untuk memuat data pagination
        function loadPage(page) {
          let sortColumn = $('#sortColumn').val();
          let sortDirection = $('#sortDirection').val();

            $.ajax({
                url: '/dashboard?page=' + page + '&sort=' + sortColumn + '&direction=' + sortDirection,// Gunakan URL yang sama
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    console.log('Data received:', data); // Debug: Lihat respons dari server
                    $('#cekunit-table tbody').html($(data).find('tbody').html());
                    $('#cekunit-table tfoot').html($(data).find('tfoot').html());

                  let newUrl = `{{ route('dashboard') }}?page=${page}`;
                        window.history.pushState({ path: newUrl }, '', newUrl);
                        
                },
                error: function(xhr, status, error) {
                    console.error('Error loading pagination data:', error);
                }
            });
        }

        // Menangani klik tautan pagination
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault(); // Mencegah perilaku default
            var page = $(this).attr('href').split('page=')[1];
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
                url: "{{ route('cekunit.sort') }}",
                method: 'POST',
                data: {
                    sort: sortColumn,
                    direction: sortDirection,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    // Pastikan response.data ada sebelum menggunakan forEach
                        $('#cekunit-table tbody').empty();
                        response.data.forEach(function (unit) {
                            let row = `<tr>
                                <td>${unit.no}</td>
                                <td>${unit.no_perjanjian}</td>
                                <td>${unit.nama_nasabah}</td>
                                <td>${unit.nopol}</td>
                                <td>${unit.coll}</td>
                                <td>${unit.pic}</td>
                                <td>${unit.kategori}</td>
                                <td>${unit.jto}</td>
                                <td>${unit.no_rangka}</td>
                                <td>${unit.no_mesin}</td>
                                <td>${unit.merk}</td>
                                <td>${unit.type}</td>
                                <td>${unit.warna}</td>
                                <td>${unit.status}</td>
                                <td>
                                
                                 <button class="btn btn-secondary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
                                      data-no_perjanjian="${unit.no_perjanjian}"
                                      data-nama_nasabah="${unit.nama_nasabah}"
                                      data-nopol="${unit.nopol}"
                                      data-coll="${unit.coll}"
                                      data-pic="${unit.pic}"
                                      data-kategori="${unit.kategori}"
                                      data-jto="${unit.jto}"
                                      data-no_rangka="${unit.no_rangka}"
                                      data-no_mesin="${unit.no_mesin}"
                                      data-merk="${unit.merk}"
                                      data-type="${unit.type}"
                                      data-warna="${unit.warna}"
                                      data-status="${unit.status}">
                                      Edit
                                  </button>

                                <form action="/cekunit/${unit.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                                
                                </td>
                            </tr>`;
                            $('#cekunit-table tbody').append(row);
                        });

                    // Update pagination
                    // if (response.pagination) {
                    //     $('.pagination').html(response.pagination);
                    // }

                    // Perbarui URL dengan parameter sorting
                    let newUrl = `{{ route('dashboard') }}?sort=${sortColumn}&direction=${sortDirection}`;
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
            
            
            let url = "{{ route('cekunit.export') }}";
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