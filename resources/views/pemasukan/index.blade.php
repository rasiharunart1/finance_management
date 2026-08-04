<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Pemasukan Kas Bendahara</h2>
            <p>Pencatatan dana masuk dari sponsor, iuran warga, dan donatur event per desa.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('keuangan.export.print', ['tipe' => 'pemasukan']) }}" target="_blank" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" style="width: 16px;"></i>
                <span>Cetak / PDF</span>
            </a>
            <a href="{{ route('keuangan.export.excel', ['tipe' => 'pemasukan']) }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.12); color: var(--success); border-color: rgba(16, 185, 129, 0.3);">
                <i data-lucide="file-spreadsheet" style="width: 16px;"></i>
                <span>Export Excel</span>
            </a>
            @if(!auth()->user()->isSuperadmin())
            <button type="button" class="btn-primary" onclick="openModal('modal-add-pemasukan')">
                <i data-lucide="plus-circle"></i> Tambah Pemasukan
            </button>
            @endif
        </div>
    </div>

    @if(auth()->user()->isSuperadmin())
    <div style="padding: 14px 18px; margin-bottom: 24px; border-radius: var(--radius-md); background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: center; gap: 12px; color: var(--text-primary);">
        <i data-lucide="shield-alert" style="width: 20px; color: #3b82f6; flex-shrink: 0;"></i>
        <div style="font-size: 13px;">
            <strong>Mode Pemantauan Superadmin:</strong> Anda sedang melihat data dalam mode pantau instansi (read-only). Superadmin tidak berhak mengedit atau mencatat transaksi pemasukan.
        </div>
    </div>
    @endif

    <!-- TABEL PEMASUKAN -->
    <div class="glass-panel" style="overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan / Sumber</th>
                        <th>Kategori</th>
                        <th>Acara Terkait</th>
                        <th>Nominal</th>
                        <th>Bukti Struk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasukans as $trx)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->translatedFormat('d M Y') }}</td>
                        <td>
                            <strong>{{ $trx->keterangan }}</strong>
                            <br><span style="font-size:12px;color:var(--text-secondary)">{{ $trx->nomor_transaksi }}</span>
                        </td>
                        <td><span class="badge success">{{ $trx->kategori }}</span></td>
                        <td>
                            {{ $trx->acara->nama_acara ?? '-' }}
                            <br><span style="font-size:11px;color:var(--text-secondary)">Desa: {{ $trx->acara->desa->nama_desa ?? '-' }}</span>
                        </td>
                        <td style="font-weight:600; color: var(--success);">Rp {{ number_format($trx->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($trx->bukti_file)
                            <a href="{{ asset('storage/' . $trx->bukti_file) }}" target="_blank" class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="image" style="width:12px;"></i> Lihat Struk
                            </a>
                            @else
                            <span style="font-size: 11px; color: var(--text-secondary);">-</span>
                            @endif
                        </td>
                        <td>
                            @if(!auth()->user()->isSuperadmin())
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="openModal('modal-edit-pemasukan-{{ $trx->id }}')">
                                    <i data-lucide="edit" style="width:14px;"></i> Edit
                                </button>
                                <form action="{{ route('pemasukan.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pemasukan ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary" style="padding: 4px 10px; font-size: 11px; color: var(--primary-red);">
                                        <i data-lucide="trash-2" style="width:14px;"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span style="font-size: 11px; color: var(--text-secondary);">-</span>
                            @endif
                        </td>
                    </tr>

                    @if(!auth()->user()->isSuperadmin())
                    <!-- MODAL EDIT PEMASUKAN -->
                    <div class="modal-overlay" id="modal-edit-pemasukan-{{ $trx->id }}">
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Edit Pemasukan Kas</h3>
                                <button type="button" onclick="closeModal('modal-edit-pemasukan-{{ $trx->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                                    <i data-lucide="x"></i>
                                </button>
                            </div>
                            <form action="{{ route('pemasukan.update', $trx->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="form-label">Kegiatan / Acara Tujuan</label>
                                        <select name="acara_id" class="form-select" required>
                                            @foreach($acaras as $a)
                                            <option value="{{ $a->id }}" {{ $trx->acara_id == $a->id ? 'selected' : '' }}>{{ $a->nama_acara }} ({{ $a->desa->nama_desa ?? '-' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" name="tanggal_transaksi" class="form-input" required value="{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Kategori Pemasukan</label>
                                        <select name="kategori" class="form-select" required>
                                            <option value="Sponsor / Donatur" {{ $trx->kategori == 'Sponsor / Donatur' ? 'selected' : '' }}>Sponsor / Donatur</option>
                                            <option value="Iuran Warga" {{ $trx->kategori == 'Iuran Warga' ? 'selected' : '' }}>Iuran Warga</option>
                                            <option value="Dana Kas Unit / Desa" {{ $trx->kategori == 'Dana Kas Unit / Desa' ? 'selected' : '' }}>Dana Kas Unit / Desa</option>
                                            <option value="Lainnya" {{ $trx->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Keterangan / Sumber Dana</label>
                                        <input type="text" name="keterangan" class="form-input" required value="{{ $trx->keterangan }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nominal (Rp)</label>
                                        <input type="number" name="jumlah" class="form-input" required value="{{ (int)$trx->jumlah }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Bukti Struk / Kwitansi (Foto/Gambar)</label>
                                        <input type="file" name="bukti_file" accept="image/*" class="form-input">
                                        @if($trx->bukti_file)
                                            <small style="color:var(--success);display:block;margin-top:4px;">Struk saat ini tersedia: <a href="{{ asset('storage/' . $trx->bukti_file) }}" target="_blank" style="text-decoration:underline;">Lihat Gambar</a></small>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-pemasukan-{{ $trx->id }}')">Batal</button>
                                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            Belum ada data transaksi pemasukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pemasukans->hasPages())
        <div style="padding: 16px; border-top: 1px solid var(--glass-border);">
            {{ $pemasukans->links() }}
        </div>
        @endif
    </div>

    @if(!auth()->user()->isSuperadmin())
    <!-- MODAL TAMBAH PEMASUKAN -->
    <div class="modal-overlay" id="modal-add-pemasukan">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Data Pemasukan Kas</h3>
                <button type="button" onclick="closeModal('modal-add-pemasukan')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('pemasukan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kegiatan / Acara Tujuan</label>
                        <select name="acara_id" class="form-select" required>
                            @foreach($acaras as $a)
                            <option value="{{ $a->id }}">{{ $a->nama_acara }} ({{ $a->desa->nama_desa ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_transaksi" class="form-input" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Pemasukan</label>
                        <select name="kategori" class="form-select">
                            <option value="Sponsor / Iuran">Sponsor / Iuran</option>
                            <option value="Donasi Warga">Donasi Warga</option>
                            <option value="Subsidi Pemerintah">Subsidi Pemerintah</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan / Sumber</label>
                        <input type="text" name="keterangan" class="form-input" required placeholder="Contoh: PT. Indofood (Sponsorship Utama)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="jumlah" class="form-input" required placeholder="Contoh: 1500000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bukti Struk / Kwitansi (Foto/Gambar)</label>
                        <input type="file" name="bukti_file" accept="image/*" class="form-input">
                        <small style="font-size:11px;color:var(--text-secondary);">Unggah foto bukti transfer atau kwitansi format JPG/PNG.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-pemasukan')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Pemasukan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-app-layout>
