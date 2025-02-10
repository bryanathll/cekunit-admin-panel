@extends('components.user.master-layout')

@section('content')
    <div class="content-body">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li>
                    <h5 class="bc-title">Input Data</h5>
                </li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z"
                                stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Home's </a>
                </li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Input Data</a></li>
            </ol>
        </div>
        <div class="container-fluid">
            <div class="row">

            
                <!-- form insert data .csv -->
                <div class="container mt-2">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> Insert Data Dari CSV </h4>
                            
                        </div>
                        <div class="card-body">
                            <form action="{{route('input.data.import')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="csv_file">Pilih File</label>
                                <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Insert Data</button>  
                            </form>
                        </div>
                    </div>
                 </div>    


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

                <h1>
                    Input Data
                </h1>
                
                <!-- pesan error input data -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="crawlForm" action="{{ route('input.data-nasabah') }}" method="post">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label for="no_perjanjian">Nomor Perjanjian</label>
                        <input type="text" class="form-control" name="no_perjanjian" id="no_perjanjian" required    ae>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama_nasabah">Nama Nasabah</label>
                        <input type="text" class="form-control" name="nama_nasabah" id="nama_nasabah">
                    </div>
                    <div class="form-group mb-3">
                        <label for="nopol">Nopol</label>
                        <input type="text" class="form-control" name="nopol" id="nopol">
                    </div>
                    <div class="form-group mb-3">
                        <label for="coll">Coll</label>
                        <input type="text" class="form-control" name="coll" id="coll">
                    </div>
                    <div class="form-group mb-3">
                        <label for="pic">PIC</label>
                        <input type="text" class="form-control" name="pic" id="pic">
                    </div>
                    <div class="form-group mb-3">
                        <label for="kategori">Kategori</label>
                        <input type="text" class="form-control" name="kategori" id="kategori">
                    </div>
                    <div class="form-group mb-3">
                        <label for="jto">JTO</label>
                        <input type="text" class="form-control" name="jto" id="jto">
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_rangka">Nomor Rangka</label>
                        <input type="text" class="form-control" name="no_rangka" id="no_rangka">
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_mesin">Nomor Mesin</label>
                        <input type="text" class="form-control" name="no_mesin" id="no_mesin">
                    </div>
                    <div class="form-group mb-3">
                        <label for="merk">Merk</label>
                        <input type="text" class="form-control" name="merk" id="merk">
                    </div>
                    <div class="form-group mb-3">
                        <label for="type">Type</label>
                        <input type="text" class="form-control" name="type" id="type">
                    </div>
                    <div class="form-group mb-3">
                        <label for="warna">Warna</label>
                        <input type="text" class="form-control" name="warna" id="warna">
                    </div>
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" name="status" id="status">
                    </div>
                

                    <button class="btn btn-primary" type="submit">Input</button>
                    <div id="loadingIndicator" style="display: none;"> <!-- Initially hidden -->
                        <div class="row justify-content-center">
                            <img src="{{ asset('images/loading.gif') }}" alt="Loading" style="width: 100px">
                        </div>
                    </div>
                </form>


@endsection
