<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Anggaran (RAB) & Modal Awal Kegiatan</h2>
            <p>Kelola Modal Awal kas, buat daftar agenda/kegiatan, anggarkan Rencana Anggaran Biaya (RAB), dan monitoring sisa saldo kas.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @if(!auth()->user()->isSuperadmin())
            <button type="button" class="btn-secondary" onclick="openModal('modal-edit-modal-awal')">
                <i data-lucide="coins"></i> Atur Modal Awal
            </button>
            @endif
            <button type="button" class="btn-primary" onclick="openModal('modal-add-lomba')">
                <i data-lucide="plus"></i> Tambah Agenda & Anggaran
            </button>
        </div>
    </div>

    @if(auth()->user()->isSuperadmin())
    <div style="padding: 14px 18px; margin-bottom: 24px; border-radius: var(--radius-md); background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: center; gap: 12px; color: var(--text-primary);">
        <i data-lucide="shield-alert" style="width: 20px; color: #3b82f6; flex-shrink: 0;"></i>
        <div style="font-size: 13px;">
            <strong>Mode Pemantauan Superadmin:</strong> Anda sedang melihat data dalam mode pantau instansi (read-only). Superadmin tidak berhak mengedit modal awal, pemasukan, atau pengeluaran instansi.
        </div>
    </div>
    @endif

    <!-- Summary Total Cards (Modal Awal, Pemasukan, Pengeluaran, Saldo Kas Aktif) -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card glass" style="position: relative;">
            <span class="stat-label">Modal Awal / Kas Dasar</span>
            <div class="stat-value" style="color: var(--text-primary);">Rp {{ number_format($modalAwal, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Unit/Instansi: <strong>{{ $desa->nama_desa ?? 'Semua Unit / Desa' }}</strong>
            </div>
            @if(!auth()->user()->isSuperadmin())
            <button type="button" onclick="openModal('modal-edit-modal-awal')" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: var(--primary-red); cursor: pointer;" title="Edit Modal Awal">
                <i data-lucide="edit-3" style="width: 16px;"></i>
            </button>
            @endif
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(+) Total Pemasukan Kas</span>
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Sponsor, donasi, iuran & kas masuk
            </div>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(-) Total Pengeluaran Acara</span>
            <div class="stat-value" style="color: var(--primary-red);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Realisasi seluruh agenda & kegiatan
            </div>
        </div>
        <div class="stat-card glass" style="border-left: 4px solid {{ $saldoKasAktif >= 0 ? 'var(--success)' : 'var(--primary-red)' }};">
            <span class="stat-label">Saldo Kas Aktif (Tersisa)</span>
            <div class="stat-value" style="color: {{ $saldoKasAktif >= 0 ? 'var(--success)' : 'var(--primary-red)' }};">
                Rp {{ number_format($saldoKasAktif, 0, ',', '.') }}
            </div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Modal Awal + Pemasukan - Pengeluaran
            </div>
        </div>
    </div>

    <!-- Budgeting vs Realisasi Lomba/Acara -->
    <div class="glass" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700;">Daftar Agenda Kegiatan & Pagu Biaya (RAB)</h3>
                <p style="font-size: 12px; color: var(--text-secondary);">Pantau anggaran rencana tiap kegiatan/agenda dan otomatis potong kas dari realisasi pengeluarannya.</p>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="layout-toggle" style="display: flex; background: rgba(0,0,0,0.1); border-radius: 8px; padding: 4px;">
                    <button type="button" class="btn-layout active" onclick="switchLayout('list')" id="btn-layout-list" style="border: none; background: var(--surface-solid); padding: 4px 8px; border-radius: 6px; color: var(--text-primary); cursor: pointer; box-shadow: var(--shadow-sm);" title="Tampilan List">
                        <i data-lucide="list" style="width: 16px;"></i>
                    </button>
                    <button type="button" class="btn-layout" onclick="switchLayout('grid')" id="btn-layout-grid" style="border: none; background: transparent; padding: 4px 8px; border-radius: 6px; color: var(--text-secondary); cursor: pointer;" title="Tampilan Grid">
                        <i data-lucide="layout-grid" style="width: 16px;"></i>
                    </button>
                </div>
                <div style="font-size: 13px; font-weight: 600;">
                    Total Dianggarkan: <span style="color: var(--primary-red);">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <style>
            .rab-layout-list {
                display: grid;
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .rab-layout-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 20px;
            }
        </style>
        
        <div id="rab-container" class="rab-layout-list">
            @forelse($acaras as $a)
            @php
                $serapan = $a->persentase_anggaran;
                $warna = $serapan > 85 ? 'var(--primary-red)' : ($serapan > 60 ? 'var(--warning)' : 'var(--success)');
                $sisaAnggaran = $a->anggaran_rencana - $a->total_pengeluaran;
            @endphp
            <div class="glass-panel" style="padding: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <h4 style="font-weight: 700; font-size: 15px;">{{ $a->nama_acara }}</h4>
                            <span class="badge" style="background: rgba(0,0,0,0.05); font-size: 11px;">
                                {{ strtoupper($a->status) }}
                            </span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                            <i data-lucide="map-pin" style="width: 12px; display: inline;"></i> {{ $a->lokasi ?? '-' }} • 
                            <i data-lucide="calendar" style="width: 12px; display: inline;"></i> {{ \Carbon\Carbon::parse($a->tanggal_mulai)->translatedFormat('d M Y, H:i') }}
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: var(--text-secondary);">Anggaran vs Realisasi</div>
                        <div style="font-size: 15px; font-weight: 700;">
                            <span style="color: var(--primary-red);">Rp {{ number_format($a->total_pengeluaran, 0, ',', '.') }}</span> / 
                            <span>Rp {{ number_format($a->anggaran_rencana, 0, ',', '.') }}</span>
                        </div>
                        <div style="font-size: 11px; color: {{ $sisaAnggaran >= 0 ? 'var(--success)' : 'var(--primary-red)' }}; font-weight: 600;">
                            Sisa Pagu Anggaran: Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Progress bar anggaran lomba -->
                <div class="progress-bar" style="height: 10px; margin-bottom: 12px;">
                    <div class="progress-fill" style="width: {{ min($serapan, 100) }}%; background: {{ $warna }};"></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 12px;">
                    <div style="font-size: 12px; color: var(--text-secondary);">
                        Serapan Biaya: <strong>{{ round($serapan, 1) }}%</strong> ({{ $a->transaksis_count }} transaksi kas terkait)
                    </div>

                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="openModal('modal-edit-lomba-{{ $a->id }}')">
                            <i data-lucide="edit" style="width: 12px;"></i> Edit Agenda & Anggaran
                        </button>
                        <form action="{{ route('acara.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus agenda kegiatan ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary" style="padding: 4px 10px; font-size: 11px; color: var(--primary-red);">
                                <i data-lucide="trash-2" style="width: 12px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL EDIT LOMBA / ACARA -->
            <div class="modal-overlay" id="modal-edit-lomba-{{ $a->id }}">
                <div class="modal-card">
                    <div class="modal-header">
                        <h3>Edit Agenda Kegiatan & Pagu Anggaran</h3>
                        <button type="button" onclick="closeModal('modal-edit-lomba-{{ $a->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                            <i data-lucide="x"></i>
                        </button>
                    </div>
                    <form action="{{ route('acara.update', $a->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @if(auth()->user() && auth()->user()->isSuperadmin())
                            <div class="form-group">
                                <label class="form-label">Unit / Instansi (Desa/Sekolah/Divisi)</label>
                                <select name="desa_id" class="form-select" required>
                                    @foreach($desas as $ds)
                                        <option value="{{ $ds->id }}" {{ $a->desa_id == $ds->id ? 'selected' : '' }}>{{ $ds->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="desa_id" value="{{ $a->desa_id }}">
                            @endif

                            <div class="form-group">
                                <label class="form-label">Nama Agenda / Kegiatan / Acara</label>
                                <input type="text" name="nama_acara" class="form-input" required value="{{ $a->nama_acara }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Anggaran Biaya Dibutuhkan (Rp)</label>
                                <input type="number" name="anggaran_rencana" class="form-input" required value="{{ (int) $a->anggaran_rencana }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lokasi / Tempat Pelaksanaan</label>
                                <input type="text" name="lokasi" class="form-input" value="{{ $a->lokasi }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-input" value="{{ \Carbon\Carbon::parse($a->tanggal_mulai)->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Kegiatan</label>
                                <select name="status" class="form-select" required>
                                    <option value="planned" {{ $a->status === 'planned' ? 'selected' : '' }}>Planned (Rencana)</option>
                                    <option value="ongoing" {{ $a->status === 'ongoing' ? 'selected' : '' }}>Ongoing (Berlangsung)</option>
                                    <option value="completed" {{ $a->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                    <option value="cancelled" {{ $a->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi / Keterangan</label>
                                <textarea name="deskripsi" class="form-input" rows="2">{{ $a->deskripsi }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-lomba-{{ $a->id }}')">Batal</button>
                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                Belum ada data anggaran atau agenda kegiatan. Klik tombol "+ Tambah Agenda & Anggaran" di atas untuk membuat daftar kegiatan.
            </div>
            @endforelse
        </div>
    </div>

    @if(!auth()->user()->isSuperadmin())
    <!-- MODAL EDIT MODAL AWAL -->
    <div class="modal-overlay" id="modal-edit-modal-awal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Atur Modal Awal / Kas Dasar Kegiatan</h3>
                <button type="button" onclick="closeModal('modal-edit-modal-awal')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('anggaran.update-modal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
                        Modal awal adalah pagu kas awal/modal dasar operasional kegiatan yang menjadi patokan awal sebelum ditambah pemasukan dan dikurangi pengeluaran.
                    </p>
                    <div class="form-group">
                        <label class="form-label">Nominal Modal Awal (Rp)</label>
                        <input type="number" name="modal_awal" class="form-input" required value="{{ (int) $modalAwal }}" placeholder="Contoh: 25000000">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-modal-awal')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Modal Awal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- MODAL TAMBAH LOMBA & ANGGARAN -->
    <div class="modal-overlay" id="modal-add-lomba">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Agenda Kegiatan & Anggaran Biaya</h3>
                <button type="button" onclick="closeModal('modal-add-lomba')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('acara.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Unit / Instansi (Desa/Sekolah/Divisi)</label>
                        <select name="desa_id" class="form-select" required>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="desa_id" value="{{ auth()->user()->desa_id ?? 1 }}">
                    @endif

                    <div class="form-group">
                        <label class="form-label">Nama Agenda / Kegiatan / Acara</label>
                        <input type="text" name="nama_acara" class="form-input" required placeholder="Contoh: Pesta Rakyat / Turnamen Olahraga / Seminar / Dies Natalis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Anggaran Biaya Dibutuhkan (Rp)</label>
                        <input type="number" name="anggaran_rencana" class="form-input" required placeholder="Contoh: 3500000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi / Tempat Pelaksanaan</label>
                        <input type="text" name="lokasi" class="form-input" placeholder="Contoh: Lapangan Utama / Aula Sekolah / Gedung Balai">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-input" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Pelaksanaan</label>
                        <select name="status" class="form-select" required>
                            <option value="planned">Planned (Rencana)</option>
                            <option value="ongoing">Ongoing (Berlangsung)</option>
                            <option value="completed">Completed (Selesai)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi / Keterangan</label>
                        <textarea name="deskripsi" class="form-input" rows="2" placeholder="Detail keperluan operasional, hadiah, peralatan, & konsumsi kegiatan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-lomba')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Agenda & Anggaran</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function switchLayout(layout) {
            const container = document.getElementById('rab-container');
            const btnList = document.getElementById('btn-layout-list');
            const btnGrid = document.getElementById('btn-layout-grid');
            
            if (layout === 'grid') {
                container.classList.remove('rab-layout-list');
                container.classList.add('rab-layout-grid');
                
                btnList.style.background = 'transparent';
                btnList.style.color = 'var(--text-secondary)';
                btnList.style.boxShadow = 'none';
                
                btnGrid.style.background = 'var(--surface-solid)';
                btnGrid.style.color = 'var(--text-primary)';
                btnGrid.style.boxShadow = 'var(--shadow-sm)';
                
                localStorage.setItem('rab_layout_preference', 'grid');
            } else {
                container.classList.remove('rab-layout-grid');
                container.classList.add('rab-layout-list');
                
                btnGrid.style.background = 'transparent';
                btnGrid.style.color = 'var(--text-secondary)';
                btnGrid.style.boxShadow = 'none';
                
                btnList.style.background = 'var(--surface-solid)';
                btnList.style.color = 'var(--text-primary)';
                btnList.style.boxShadow = 'var(--shadow-sm)';
                
                localStorage.setItem('rab_layout_preference', 'list');
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const savedLayout = localStorage.getItem('rab_layout_preference');
            if (savedLayout) {
                switchLayout(savedLayout);
            }
        });
    </script>
</x-app-layout>
