<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Pengeluaran Kas Bendahara</h2>
            <p>Pencatatan realisasi biaya & pengeluaran operasional kegiatan per desa.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('keuangan.export.print', ['tipe' => 'pengeluaran']) }}" target="_blank" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" style="width: 16px;"></i>
                <span>Cetak / PDF</span>
            </a>
            <a href="{{ route('keuangan.export.excel', ['tipe' => 'pengeluaran']) }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.12); color: var(--success); border-color: rgba(16, 185, 129, 0.3);">
                <i data-lucide="file-spreadsheet" style="width: 16px;"></i>
                <span>Export Excel</span>
            </a>
            <button type="button" class="btn-primary" onclick="openModal('modal-add-pengeluaran')">
                <i data-lucide="minus-circle"></i> Catat Pengeluaran
            </button>
        </div>
    </div>

    <!-- TABEL PENGELUARAN -->
    <div class="glass-panel" style="overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan / Penggunaan</th>
                        <th>Kategori</th>
                        <th>Acara Terkait</th>
                        <th>Nominal</th>
                        <th>Bukti Struk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluarans as $trx)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                        <td>
                            <strong>{{ $trx->keterangan }}</strong>
                            <br><span style="font-size:12px;color:var(--text-secondary)">{{ $trx->nomor_transaksi }}</span>
                        </td>
                        <td><span class="badge danger">{{ $trx->kategori }}</span></td>
                        <td>
                            {{ $trx->acara->nama_acara ?? '-' }}
                            <br><span style="font-size:11px;color:var(--text-secondary)">Desa: {{ $trx->acara->desa->nama_desa ?? '-' }}</span>
                        </td>
                        <td style="font-weight:600; color: var(--primary-red);">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($trx->bukti_file)
                            <a href="{{ asset('storage/' . $trx->bukti_file) }}" target="_blank" class="badge" style="background: rgba(220, 38, 38, 0.15); color: var(--primary-red); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="image" style="width:12px;"></i> Lihat Struk
                            </a>
                            @else
                            <span style="font-size: 11px; color: var(--text-secondary);">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="openModal('modal-edit-pengeluaran-{{ $trx->id }}')">
                                    <i data-lucide="edit" style="width:14px;"></i> Edit
                                </button>
                                <form action="{{ route('pengeluaran.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary" style="padding: 4px 10px; font-size: 11px; color: var(--primary-red);">
                                        <i data-lucide="trash-2" style="width:14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL EDIT PENGELUARAN -->
                    <div class="modal-overlay" id="modal-edit-pengeluaran-{{ $trx->id }}">
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Edit Pengeluaran Kas</h3>
                                <button type="button" onclick="closeModal('modal-edit-pengeluaran-{{ $trx->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                                    <i data-lucide="x"></i>
                                </button>
                            </div>
                            <form action="{{ route('pengeluaran.update', $trx->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Kegiatan / Acara Tujuan</label>
                                        <select name="acara_id" class="form-select" required>
                                            @foreach($acaras as $ac)
                                                <option value="{{ $ac->id }}" {{ $trx->acara_id == $ac->id ? 'selected' : '' }}>
                                                    {{ $ac->nama_acara }} ({{ $ac->desa->nama_desa ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nominal Pengeluaran (Rp)</label>
                                        <input type="number" name="jumlah" class="form-input" required value="{{ (int) $trx->jumlah }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Transaksi</label>
                                        <input type="date" name="tanggal_transaksi" class="form-input" value="{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Kategori Pengeluaran</label>
                                        <select name="kategori" class="form-select">
                                            <option value="Operasional / Hadiah" {{ $trx->kategori === 'Operasional / Hadiah' ? 'selected' : '' }}>Operasional / Hadiah</option>
                                            <option value="Sewa Perlengkapan" {{ $trx->kategori === 'Sewa Perlengkapan' ? 'selected' : '' }}>Sewa Perlengkapan</option>
                                            <option value="Konsumsi & Logistik" {{ $trx->kategori === 'Konsumsi & Logistik' ? 'selected' : '' }}>Konsumsi & Logistik</option>
                                            <option value="Lainnya" {{ $trx->kategori === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Keterangan Penggunaan</label>
                                        <input type="text" name="keterangan" class="form-input" required value="{{ $trx->keterangan }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Bukti Struk / Nota (Foto/Gambar)</label>
                                        <input type="file" name="bukti_file" accept="image/*" class="form-input">
                                        @if($trx->bukti_file)
                                            <small style="color:var(--primary-red);display:block;margin-top:4px;">Nota saat ini tersedia: <a href="{{ asset('storage/' . $trx->bukti_file) }}" target="_blank" style="text-decoration:underline;">Lihat Gambar</a></small>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-pengeluaran-{{ $trx->id }}')">Batal</button>
                                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            Belum ada data transaksi pengeluaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengeluarans->hasPages())
        <div style="padding: 16px;">
            {{ $pengeluarans->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL TAMBAH PENGELUARAN -->
    <div class="modal-overlay" id="modal-add-pengeluaran">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Catat Data Pengeluaran Kas</h3>
                <button type="button" onclick="closeModal('modal-add-pengeluaran')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kegiatan / Acara Tujuan</label>
                        <select name="acara_id" class="form-select" required>
                            @foreach($acaras as $ac)
                                <option value="{{ $ac->id }}">{{ $ac->nama_acara }} ({{ $ac->desa->nama_desa ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal Pengeluaran (Rp)</label>
                        <input type="number" name="jumlah" class="form-input" required placeholder="Contoh: 3500000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Pengeluaran</label>
                        <select name="kategori" class="form-select">
                            <option value="Operasional / Hadiah">Operasional / Hadiah</option>
                            <option value="Sewa Perlengkapan">Sewa Perlengkapan</option>
                            <option value="Konsumsi & Logistik">Konsumsi & Logistik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan Penggunaan</label>
                        <input type="text" name="keterangan" class="form-input" required placeholder="Contoh: Pembelian hadiah juara 1, 2, 3">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bukti Struk / Nota (Foto/Gambar)</label>
                        <input type="file" name="bukti_file" accept="image/*" class="form-input">
                        <small style="font-size:11px;color:var(--text-secondary);">Unggah foto nota belanja atau kwitansi pengeluaran.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-pengeluaran')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
