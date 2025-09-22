<header class="topbar">
    <div class="with-vertical">
        <nav class="navbar navbar-expand-lg p-0">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                    <!-- ------------------------------- -->
                    <!-- Notifikasi Peminjaman -->
                    <!-- ------------------------------- -->
                    @php
                        use Carbon\Carbon;

                        $notifCount = \App\Models\Peminjaman::whereDoesntHave('pengembalian')->count();
                        $notifs = \App\Models\Peminjaman::with('user','buku')
                                    ->whereDoesntHave('pengembalian')
                                    ->latest()->take(5)->get();
                    @endphp
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="javascript:void(0)" id="notifDropdown" data-bs-toggle="dropdown">
                            <i class="ti ti-bell fs-5"></i>
                            @if($notifCount > 0)
                                <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle p-1">
                                    {{ $notifCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up p-0" style="width: 300px;">
                            <li class="p-3 border-bottom">
                                <strong>Notifikasi Peminjaman</strong>
                            </li>
                            @forelse($notifs as $item)
                                <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item->user->name }}</strong> meminjam <em>{{ $item->buku->judul }}</em>
                                        <br><small>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</small>
                                    </div>
                                </li>
                            @empty
                                <li class="px-3 py-2 text-center text-muted">
                                    Tidak ada peminjaman baru
                                </li>
                            @endforelse
                            <li class="text-center py-2">
                                <a href="{{ route('peminjaman.notifikasi') }}" class="text-primary fw-semibold">Lihat Semua</a>
                            </li>
                        </ul>
                    </li>


                    <!-- ------------------------------- -->
                    <!-- Profile Dropdown -->
                    <!-- ------------------------------- -->
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                     style="width:35px; height:35px; font-weight:bold;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                            <div class="profile-dropdown p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="user-profile-img d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                         style="width:50px; height:50px; font-weight:bold;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                        <p class="mb-0 text-muted">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</header>
