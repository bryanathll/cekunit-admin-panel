<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                </div>
                <ul class="navbar-nav header-right">
                    <li class="nav-item ps-3">
                        <div class="dropdown header-profile2">
                            <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <div class="header-info2 d-flex align-items-center">
                                    <div class="header-media">
                                        {{-- <img src="images/tab/1.jpg" alt=""> --}}
                                    </div>
                                    <div class="header-info">
                                        <h6>{{ Auth::user()->name }}</h6>
                                        <p>{{ Auth::user()->email }}</p>
                                    </div>

                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <div class="card border-0 mb-0">
                                    <div class="card-header py-2">
                                        <div class="products">
                                            {{-- <img src="images/tab/1.jpg" class="avatar avatar-md" alt=""> --}}
                                            <div>
                                                <h6>{{ Auth::user()->name }}</h6>
                                                <span>{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="card-footer px-0 py-2">

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger mx-3">Logout</button>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
