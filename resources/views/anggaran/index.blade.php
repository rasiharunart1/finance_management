<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Anggaran & Modal Awal HUT RI ke-79</h2>
            <p>Kelola Modal Awal desa, buat daftar lomba/kegiatan, anggarkan biaya per lomba, dan monitoring sisa saldo kas.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="btn-secondary" onclick="openModal('modal-edit-modal-awal')">
                <i data-lucide="coins"></i> Atur Modal Awal
            </button>
            <button type="button" class="btn-primary" onclick="openModal('modal-add-lomba')">
                <i data-lucide="plus"></i> Tambah Lomba & Anggaran
            </button>
        </div>
    </div>

    <!-- Summary Total Cards (Modal Awal, Pemasukan, Pengeluaran, Saldo Kas Aktif) -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card glass" style="position: relative;">
            <span class="stat-label">Modal Awal HUT RI ke-79</span>
            <div class="stat-value" style="color: var(--text-primary);">Rp {{ number_format($modalAwal, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Desa: <strong>{{ $desa->nama_desa ?? 'Semua Desa' }}</strong>
            </div>
            <button type="button" onclick="openModal('modal-edit-modal-awal')" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: var(--primary-red); cursor: pointer;" title="Edit Modal Awal">
                <i data-lucide="edit-3" style="width: 16px;"></i>
            </button>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(+) Total Pemasukan Kas</span>
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Sponsor, donasi & iuran warga
            </div>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(-) Total Pengeluaran Acara</span>
            <div class="stat-value" style="color: var(--primary-red);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                Realisasi seluruh lomba & kegiatan
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
                <h3 style="font-size: 16px; font-weight: 700;">Daftar Acara / Perlombaan & Pagu Biaya (RAB)</h3>
                <p style="font-size: 12px; color: var(--text-secondary);">Pantau anggaran rencana tiap perlombaan dan otomatis potong kas dari realisasi pengeluarannya.</p>
            </div>
            <div style="font-size: 13px; font-weight: 600;">
                Total Dianggarkan: <span style="color: var(--primary-red);">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 20px;">
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
                            Sisa Pagu Lomba: Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
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
                            <i data-lucide="edit" style="width: 12px;"></i> Edit Anggaran / Lomba
                        </button>
                        <form action="{{ route('acara.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lomba/kegiatan ini?');" style="margin: 0;">
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
                        <h3>Edit Perlombaan & Pagu Anggaran</h3>
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
                                <label class="form-label">Desa</label>
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
                                <label class="form-label">Nama Lomba / Acara</label>
                                <input type="text" name="nama_acara" class="form-input" required value="{{ $a->nama_acara }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Anggaran Biaya Dibutuhkan (Rp)</label>
                                <input type="number" name="anggaran_rencana" class="form-input" required value="{{ (int) $a->anggaran_rencana }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lokasi Perlombaan</label>
                                <input type="text" name="lokasi" class="form-input" value="{{ $a->lokasi }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="datetime-local" name="tanggal_mulai" class="form-input" value="{{ \Carbon\Carbon::parse($a->tanggal_mulai)->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Perlombaan</label>
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
                Belum ada data anggaran atau perlomban. Klik tombol "+ Tambah Lomba & Anggaran" di atas untuk membuat daftar lomba 17 Agustus.
            </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL EDIT MODAL AWAL -->
    <div class="modal-overlay" id="modal-edit-modal-awal">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Atur Modal Awal HUT RI ke-79</h3>
                <button type="button" onclick="closeModal('modal-edit-modal-awal')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('anggaran.update-modal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
                        Modal awal adalah pagu kas awal/modal dasar perayaan 17 Agustus yang akan menjadi patokan awal sebelum ditambah pemasukan dan dikurangi pengeluaran tiap lomba.
                    </p>
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Pilih Desa</label>
                        <select name="desa_id" class="form-select" required>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}" {{ ($desa && $desa->id == $ds->id) ? 'selected' : '' }}>{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
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

    <!-- MODAL TAMBAH LOMBA & ANGGARAN -->
    <div class="modal-overlay" id="modal-add-lomba">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Perlombaan & Anggaran Biaya</h3>
                <button type="button" onclick="closeModal('modal-add-lomba')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('acara.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Desa</label>
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
                        <label class="form-label">Nama Lomba / Acara 17 Agustus</label>
                        <input type="text" name="nama_acara" class="form-input" required placeholder="Contoh: Lomba Panjat Pinang & Balap Karung">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Anggaran Biaya Dibutuhkan (Rp)</label>
                        <input type="number" name="anggaran_rencana" class="form-input" required placeholder="Contoh: 3500000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Perlombaan</label>
                        <input type="text" name="lokasi" class="form-input" placeholder="Contoh: Lapangan Utama Desa">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="datetime-local" name="tanggal_mulai" class="form-input" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Perlombaan</label>
                        <select name="status" class="form-select" required>
                            <option value="planned">Planned (Rencana)</option>
                            <option value="ongoing">Ongoing (Berlangsung)</option>
                            <option value="completed">Completed (Selesai)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi / Keterangan</label>
                        <textarea name="deskripsi" class="form-input" rows="2" placeholder="Detail keperluan hadiah & peralatan lomba"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-lomba')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Lomba & Anggaran</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
