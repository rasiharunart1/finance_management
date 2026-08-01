<x-app-layout>
    <div class="page-header">
        <div class="page-title">
            <h1>Kas & Anggaran Acara (Bendahara)</h1>
            <p>Pencatatan realisasi Pemasukan & Pengeluaran kas acara oleh Admin Bendahara.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('keuangan.export.print', request()->query()) }}" target="_blank" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" style="width: 16px;"></i>
                <span>Cetak / PDF</span>
            </a>
            <a href="{{ route('keuangan.export.excel', request()->query()) }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.12); color: var(--success); border-color: rgba(16, 185, 129, 0.3);">
                <i data-lucide="file-spreadsheet" style="width: 16px;"></i>
                <span>Export Excel</span>
            </a>
            <button type="button" class="btn-primary" onclick="openModal('modal-tambah-transaksi')">
                <i class="fa-solid fa-plus"></i>
                <span>Catat Transaksi Kas</span>
            </button>
        </div>
    </div>

    <!-- FINANCIAL SUMMARY CARDS -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card glass">
            <span class="stat-label">Modal Awal HUT RI ke-79</span>
            <div class="stat-value" style="color: var(--text-primary);">Rp {{ number_format($modalAwal, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">Modal dasar 17 Agustus</div>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(+) Pemasukan Tambahan</span>
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">Sponsorship, Donasi, dll</div>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">(-) Pengeluaran Realisasi</span>
            <div class="stat-value" style="color: var(--primary-red);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">Total biaya kegiatan/lomba</div>
        </div>
        <div class="stat-card glass">
            <span class="stat-label">Saldo Kas Akhir Bendahara</span>
            <div class="stat-value" style="color: {{ $saldoKas >= 0 ? 'var(--success)' : 'var(--primary-red)' }};">
                Rp {{ number_format($saldoKas, 0, ',', '.') }}
            </div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">Modal Awal + Pemasukan - Pengeluaran</div>
        </div>
    </div>

    <!-- Alert Info -->
    <div style="padding: 1.25rem; border-radius: 16px; background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <i class="fa-solid fa-shield-check" style="font-size: 1.5rem; color: var(--accent-primary);"></i>
        <div>
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Status Keuangan Sehat & Terkendali</h4>
            <p style="font-size: 0.825rem; color: var(--text-secondary);">Seluruh realisasi pengeluaran acara masih berada di bawah batas pagu anggaran yang disetujui Superadmin.</p>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card-box" style="padding: 1.25rem; margin-bottom: 1.5rem;">
        <form action="{{ route('keuangan.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 220px;">
                <select name="acara_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter Acara Terkait --</option>
                    @foreach($acaras as $a)
                        <option value="{{ $a->id }}" {{ $acaraId == $a->id ? 'selected' : '' }}>{{ $a->nama_acara }} ({{ $a->desa->nama_desa ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 200px;">
                <select name="tipe" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Tipe --</option>
                    <option value="pemasukan" {{ $tipe == 'pemasukan' ? 'selected' : '' }}>Pemasukan Kas</option>
                    <option value="pengeluaran" {{ $tipe == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran Acara</option>
                </select>
            </div>
            @if($acaraId || $tipe)
                <a href="{{ route('keuangan.index') }}" class="btn-outline" style="padding: 0.65rem 1rem; font-size: 0.8rem;">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Reset Filter</span>
                </a>
            @endif
        </form>
    </div>

    <div class="card-box">
        <div class="card-header">
            <h3>Buku Kas Rekapitulasi Transaksi Bendahara</h3>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Acara & Desa</th>
                        <th>Tipe Transaksi</th>
                        <th>Nominal (Rp)</th>
                        <th>Tanggal</th>
                        <th>Keterangan / Bukti</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                    <tr>
                        <td><strong>{{ $t->nomor_transaksi }}</strong></td>
                        <td>
                            <strong>{{ $t->acara->nama_acara ?? '-' }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $t->acara->desa->nama_desa ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $t->tipe === 'pemasukan' ? 'success' : 'danger' }}">
                                {{ ucfirst($t->tipe) }}
                            </span>
                        </td>
                        <td><strong>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</strong></td>
                        <td>{{ $t->tanggal_transaksi->translatedFormat('d M Y') }}</td>
                        <td>
                            <strong>{{ $t->keterangan ?? '-' }}</strong>
                            @if($t->bukti_file)
                            <br>
                            <a href="{{ asset('storage/' . $t->bukti_file) }}" target="_blank" class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;">
                                <i data-lucide="image" style="width:12px;"></i> Lihat Bukti Struk
                            </a>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('keuangan.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus transaksi kas {{ $t->nomor_transaksi }}?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-outline" style="padding: 0.35rem 0.65rem; color: var(--accent-rose);">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                            Belum ada transaksi kas yang dicatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $transaksis->links() }}
        </div>
    </div>

    <!-- MODAL CATAT TRANSAKSI KAS -->
    <div class="modal-overlay" id="modal-tambah-transaksi">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Catat Arus Kas Acara (Bendahara)</h3>
                <button type="button" class="btn-close" onclick="closeModal('modal-tambah-transaksi')">&times;</button>
            </div>
            <form action="{{ route('keuangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Pilih Acara Terkait</label>
                        <select name="acara_id" class="form-select" required>
                            @foreach($acaras as $a)
                                <option value="{{ $a->id }}">{{ $a->nama_acara }} ({{ $a->desa->nama_desa ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Transaksi</label>
                        <select name="tipe" class="form-select" required>
                            <option value="pengeluaran">Pengeluaran Kas Acara</option>
                            <option value="pemasukan">Pemasukan / Dana Tambahan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal Transaksi (Rp)</label>
                        <input type="number" name="jumlah" class="form-input" placeholder="15000000" required value="{{ old('jumlah') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-input" required value="{{ old('tanggal_transaksi', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan / Bukti Nota</label>
                        <input type="text" name="keterangan" class="form-input" placeholder="Pembayaran sewa panggung / cair dana" required value="{{ old('keterangan') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bukti Struk / Nota (Foto/Gambar)</label>
                        <input type="file" name="bukti_file" accept="image/*" class="form-input">
                        <small style="font-size:11px;color:var(--text-secondary);">Unggah foto bukti transfer/struk pembayaran format JPG/PNG.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('modal-tambah-transaksi')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
