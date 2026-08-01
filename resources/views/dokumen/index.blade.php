<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Dokumen & Laporan</h2>
            <p>Arsip proposal resmi, surat izin keramaian, LPJ, serta rekapitulasi laporan keuangan.</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-add-dokumen')">
            <i data-lucide="upload"></i> Upload Dokumen
        </button>
    </div>

    <div class="glass" style="padding: 0; overflow:hidden;">
        <div class="table-container">
            <table>
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th>Nama Dokumen</th>
                        <th>Kategori</th>
                        <th>Desa</th>
                        <th>Tipe / Ukuran</th>
                        <th>Tanggal Arsip</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $doc)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--primary-light); color: var(--primary-red); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px;">
                                    {{ strtoupper($doc->tipe_file) }}
                                </div>
                                <span style="font-weight: 600;">{{ $doc->nama_dokumen }}</span>
                            </div>
                        </td>
                        <td><span class="badge" style="background: rgba(255,255,255,0.05);">{{ $doc->kategori }}</span></td>
                        <td>
                            <span style="font-size: 12px; color: var(--text-secondary);">{{ $doc->desa->nama_desa ?? 'Umum' }}</span>
                        </td>
                        <td>{{ $doc->tipe_file }} • {{ $doc->ukuran_file }}</td>
                        <td>{{ $doc->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            @if(strtolower($doc->status) === 'approved')
                                <span class="badge success">Approved</span>
                            @elseif(strtolower($doc->status) === 'pending')
                                <span class="badge warning">Pending</span>
                            @else
                                <span class="badge danger">{{ $doc->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="openModal('modal-edit-dokumen-{{ $doc->id }}')">
                                    <i data-lucide="edit" style="width:14px;"></i> Edit
                                </button>
                                <form action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip dokumen ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary" style="padding: 4px 10px; font-size: 11px; color: var(--primary-red);">
                                        <i data-lucide="trash-2" style="width:14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DOKUMEN -->
                    <div class="modal-overlay" id="modal-edit-dokumen-{{ $doc->id }}">
                        <div class="modal-card">
                            <div class="modal-header">
                                <h3>Edit Arsip Dokumen</h3>
                                <button type="button" onclick="closeModal('modal-edit-dokumen-{{ $doc->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                                    <i data-lucide="x"></i>
                                </button>
                            </div>
                            <form action="{{ route('dokumen.update', $doc->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    @if(auth()->user() && auth()->user()->isSuperadmin())
                                    <div class="form-group">
                                        <label class="form-label">Desa</label>
                                        <select name="desa_id" class="form-select">
                                            <option value="">-- Semua / Umum --</option>
                                            @foreach($desas as $ds)
                                                <option value="{{ $ds->id }}" {{ $doc->desa_id == $ds->id ? 'selected' : '' }}>{{ $ds->nama_desa }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label class="form-label">Nama Dokumen</label>
                                        <input type="text" name="nama_dokumen" class="form-input" required value="{{ $doc->nama_dokumen }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori" class="form-select">
                                            <option value="Proposal" {{ $doc->kategori === 'Proposal' ? 'selected' : '' }}>Proposal</option>
                                            <option value="Laporan Keuangan" {{ $doc->kategori === 'Laporan Keuangan' ? 'selected' : '' }}>Laporan Keuangan</option>
                                            <option value="Surat Izin" {{ $doc->kategori === 'Surat Izin' ? 'selected' : '' }}>Surat Izin</option>
                                            <option value="LPJ" {{ $doc->kategori === 'LPJ' ? 'selected' : '' }}>LPJ (Laporan Pertanggungjawaban)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tipe Ekstensi File</label>
                                        <select name="tipe_file" class="form-select">
                                            <option value="PDF" {{ $doc->tipe_file === 'PDF' ? 'selected' : '' }}>PDF Document</option>
                                            <option value="XLSX" {{ $doc->tipe_file === 'XLSX' ? 'selected' : '' }}>Excel Spreadsheet</option>
                                            <option value="DOCX" {{ $doc->tipe_file === 'DOCX' ? 'selected' : '' }}>Word Document</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Status Arsip</label>
                                        <select name="status" class="form-select">
                                            <option value="Approved" {{ $doc->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="Pending" {{ $doc->status === 'Pending' ? 'selected' : '' }}>Pending Approval</option>
                                            <option value="Revision" {{ $doc->status === 'Revision' ? 'selected' : '' }}>Butuh Revisi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-dokumen-{{ $doc->id }}')">Batal</button>
                                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            Belum ada dokumen atau laporan tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL UPLOAD DOKUMEN -->
    <div class="modal-overlay" id="modal-add-dokumen">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Arsipkan Dokumen / Laporan</h3>
                <button type="button" onclick="closeModal('modal-add-dokumen')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('dokumen.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Desa</label>
                        <select name="desa_id" class="form-select">
                            <option value="">-- Semua / Umum --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" name="nama_dokumen" class="form-input" required placeholder="Contoh: Proposal Resmi HUT RI ke-79.pdf">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="Proposal">Proposal</option>
                            <option value="Laporan Keuangan">Laporan Keuangan</option>
                            <option value="Surat Izin">Surat Izin</option>
                            <option value="LPJ">LPJ (Laporan Pertanggungjawaban)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Ekstensi File</label>
                        <select name="tipe_file" class="form-select">
                            <option value="PDF">PDF Document</option>
                            <option value="XLSX">Excel Spreadsheet</option>
                            <option value="DOCX">Word Document</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Arsip</label>
                        <select name="status" class="form-select">
                            <option value="Approved">Approved</option>
                            <option value="Pending">Pending Approval</option>
                            <option value="Revision">Butuh Revisi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-dokumen')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Arsip</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
