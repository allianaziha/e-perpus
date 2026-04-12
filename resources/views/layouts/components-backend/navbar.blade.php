<header class="topbar">
    <div class="with-vertical">
        <nav class="navbar navbar-expand-lg p-0">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                    <!-- ------------------------------- -->
                    <!-- Notifikasi -->
                    <!-- ------------------------------- -->
                    @php
                        use Carbon\Carbon;
                        use App\Models\Peminjaman;
                        use App\Models\PerpanjanganRequest;
                        
                        $isAdminOrPetugas = auth()->user()->role === 'admin' || auth()->user()->role === 'petugas';
                        
                        if ($isAdminOrPetugas) {
                            // Admin/Petugas: Notifikasi Peminjaman
                            $notifCount = Peminjaman::whereDoesntHave('pengembalian')->count();
                            $notifs = Peminjaman::with('user','buku')
                                        ->whereDoesntHave('pengembalian')
                                        ->latest()->take(5)->get();
                        } else {
                            // User: Notifikasi Peminjaman yang sudah di-approve dan Perpanjangan yang disetujui
                            $approvedPeminjamans = Peminjaman::where('user_id', auth()->id())
                                                            ->where('status', 'dipinjam')
                                                            ->with('buku')
                                                            ->latest()->take(5)->get()
                                                            ->map(function($item) {
                                                                $item->type = 'peminjaman_approved';
                                                                $item->created_at_notif = $item->updated_at; // atau tgl_pinjam
                                                                return $item;
                                                            });

                            $approvedPerpanjangans = PerpanjanganRequest::whereHas('peminjaman', function($q) {
                                                                $q->where('user_id', auth()->id());
                                                            })
                                                            ->where('status', 'approved')
                                                            ->with('peminjaman.buku', 'approvedBy')
                                                            ->latest()->take(5)->get()
                                                            ->map(function($item) {
                                                                $item->type = 'perpanjangan_approved';
                                                                $item->created_at_notif = $item->approved_at;
                                                                return $item;
                                                            });

                            // Gabungkan dan urutkan berdasarkan tanggal terbaru
                            $allNotifs = $approvedPeminjamans->concat($approvedPerpanjangans)
                                                            ->sortByDesc('created_at_notif')
                                                            ->take(5);

                            $notifCount = $approvedPeminjamans->count() + $approvedPerpanjangans->count();
                            $notifs = $allNotifs;
                        }
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
                                <h5 class="mb-0 fs-5 fw-semibold">
                                    @if($isAdminOrPetugas)
                                        Notifikasi Peminjaman
                                    @else
                                        Notifikasi Perpanjangan
                                    @endif
                                </h5>
                                @if($notifCount > 0)
                                    <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">{{ $notifCount }} baru</span>
                                @endif
                            </div>

                            <div class="message-body" data-simplebar style="max-height: 300px;">
                                @forelse($notifs as $item)
                                    @if($isAdminOrPetugas)
                                        {{-- Admin/Petugas: Notifikasi Peminjaman (Clickable) --}}
                                        <a href="{{ route('admin.peminjaman.index', $item->id) }}" 
                                           class="py-6 px-7 d-flex align-items-center justify-content-between dropdown-item"
                                           style="cursor: pointer; text-decoration: none; border-bottom: 1px solid #e0e0e0; transition: background-color 0.2s;"
                                           onmouseover="this.style.backgroundColor='#f5f5f5'" 
                                           onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="w-100">
                                                <h6 class="mb-1 fw-semibold lh-base">
                                                    {{ $item->user->name }} meminjam <em>{{ $item->buku->judul }}</em>
                                                </h6>
                                                <span class="fs-2 d-block text-body-secondary">
                                                    {{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}
                                                </span>
                                            </div>
                                            <i class="ti ti-eye text-primary ms-2" style="font-size: 18px; flex-shrink: 0;"></i>
                                        </a>
                                    @else
                                        {{-- User: Notifikasi Peminjaman Approved atau Perpanjangan Approved --}}
                                        @if($item->type === 'peminjaman_approved')
                                            {{-- Notifikasi Peminjaman yang sudah di-approve --}}
                                            <div class="py-6 px-7 d-flex align-items-start gap-3" 
                                                 style="background: linear-gradient(to right, #e3f2fd, transparent); border-bottom: 1px solid #bbdefb;">
                                                <div>
                                                    <i class="ti ti-check-circle text-primary" style="font-size: 24px;"></i>
                                                </div>
                                                <div class="w-100">
                                                    <h6 class="mb-2 fw-semibold lh-base">
                                                        Peminjaman Disetujui
                                                    </h6>
                                                    <p class="mb-2 text-body-secondary">
                                                        <strong>{{ $item->buku->judul }}</strong>
                                                    </p>
                                                    <small class="d-block text-body-secondary mb-2">
                                                        {{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y H:i') }}
                                                    </small>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <span class="badge bg-primary text-white">{{ $item->jumlah_buku }} buku</span>
                                                        <small class="text-body-secondary">Buku sudah bisa diambil</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Notifikasi Perpanjangan Approved --}}
                                            <div class="py-6 px-7 d-flex align-items-start gap-3" 
                                                 style="background: linear-gradient(to right, #e8f5e9, transparent); border-bottom: 1px solid #c8e6c9;">
                                                <div>
                                                    <i class="ti ti-clock-check text-success" style="font-size: 24px;"></i>
                                                </div>
                                                <div class="w-100">
                                                    <h6 class="mb-2 fw-semibold lh-base">
                                                        Perpanjangan Disetujui
                                                    </h6>
                                                    <p class="mb-2 text-body-secondary">
                                                        <strong>{{ $item->peminjaman->buku->judul }}</strong>
                                                    </p>
                                                    <small class="d-block text-body-secondary mb-2">
                                                        {{ \Carbon\Carbon::parse($item->approved_at)->format('d M Y H:i') }}
                                                    </small>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <span class="badge bg-success text-white">+{{ $item->lama_perpanjangan }} hari</span>
                                                        @if($item->catatan_admin)
                                                        <small class="text-body-secondary">{{ $item->catatan_admin }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @empty
                                    <div class="py-6 px-7 text-center text-muted">
                                        @if($isAdminOrPetugas)
                                            <i class="ti ti-inbox" style="font-size: 32px; opacity: 0.5;"></i>
                                            <p class="mt-2 mb-0">Tidak ada peminjaman baru</p>
                                        @else
                                            <i class="ti ti-bell-off" style="font-size: 32px; opacity: 0.5;"></i>
                                            <p class="mt-2 mb-0">Tidak ada notifikasi perpanjangan</p>
                                        @endif
                                    </div>
                                @endforelse
                            </div>

                            <div class="py-6 px-7 mb-1">
                                @if($isAdminOrPetugas)
                                    <a href="{{ route('peminjaman.notifikasi') }}" class="btn btn-outline-primary w-100">
                                        Lihat Semua
                                    </a>
                                @endif
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
