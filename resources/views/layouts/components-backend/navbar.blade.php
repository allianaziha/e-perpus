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
                        use App\Models\Peminjaman;

                        $notifCount = Peminjaman::whereDoesntHave('pengembalian')->count();
                        $notifs = Peminjaman::with('user','buku')
                                    ->whereDoesntHave('pengembalian')
                                    ->latest()->take(5)->get();
                    @endphp
                    <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
                        <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-bell-ringing"></i>
                            @if($notifCount > 0)
                                <div class="notification bg-primary rounded-circle"></div>
                            @endif
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2" style="width: 350px;">
                            <div class="d-flex align-items-center justify-content-between py-3 px-7 border-bottom">
                                <h5 class="mb-0 fs-5 fw-semibold">Notifikasi Peminjaman</h5>
                                @if($notifCount > 0)
                                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">{{ $notifCount }} baru</span>
                                @endif
                            </div>

                            <div class="message-body" data-simplebar style="max-height: 300px;">
                                @forelse($notifs as $item)
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <div class="w-100">
                                            <h6 class="mb-1 fw-semibold lh-base">
                                                {{ $item->user->name }} meminjam <em>{{ $item->buku->judul }}</em>
                                            </h6>
                                            <span class="fs-2 d-block text-body-secondary">
                                                {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-6 px-7 text-center text-muted">
                                        Tidak ada peminjaman baru
                                    </div>
                                @endforelse
                            </div>

                            <div class="py-6 px-7 mb-1">
                                <a href="{{ route('peminjaman.notifikasi') }}" class="btn btn-outline-primary w-100">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- ------------------------------- -->
                    <!-- Profile Dropdown -->
                    <!-- ------------------------------- -->
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                {{-- Avatar navbar kecil 35px --}}
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset(auth()->user()->avatar) }}"
                                         class="rounded-circle"
                                         style="width:35px; height:35px; object-fit:cover;">
                                @else
                                    <div class="user-profile-img d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                         style="width:35px; height:35px; font-weight:bold;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                            <div class="profile-dropdown p-3">
                                <!-- User Info -->
                                <div class="d-flex align-items-center mb-3">
                                    {{-- Avatar dropdown 50px --}}
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset(auth()->user()->avatar) }}"
                                             class="rounded-circle"
                                             style="width:50px; height:50px; object-fit:cover;">
                                    @else
                                        <div class="user-profile-img d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                             style="width:50px; height:50px; font-weight:bold;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ms-3">
                                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                        <p class="mb-0 text-muted">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>

                                <!-- Button ke Profile -->
                                <a href="{{ route('profile.show') }}" class="btn btn-primary w-100 mb-2">
                                    <i class="ti ti-user me-2"></i>View Profile
                                </a>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="ti ti-power me-2"></i>Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</header>
