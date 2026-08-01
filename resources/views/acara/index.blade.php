<x-app-layout>
    <div class="page-header">
        <div class="page-title">
            <h1>Data Nama Acara (Event)</h1>
            <p>Kelola seluruh agenda acara & kegiatan, relasi ke unit/instansi (desa/sekolah/organisasi), serta pengawasan anggaran operasional.</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-tambah-acara')">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Acara Baru</span>
        </button>
    </div>

    <!-- FILTER BAR -->
    <div class="card-box" style="padding: 1.25rem; margin-bottom: 1.5rem;">
        <form action="{{ route('acara.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 220px;">
                <select name="desa_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Unit / Instansi --</option>
                    @foreach($desas as $d)
                        <option value="{{ $d->id }}" {{ $desaId == $d->id ? 'selected' : '' }}>{{ $d->nama_desa }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 200px;">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="planned" {{ $status == 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="ongoing" {{ $status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            @if($desaId || $status || $search)
                <a href="{{ route('acara.index') }}" class="btn-outline" style="padding: 0.65rem 1rem; font-size: 0.8rem;">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Reset Filter</span>
                </a>
            @endif
        </form>
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama Acara</th>
                        <th>Unit / Instansi Terkait</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Anggaran Rencana</th>
                        <th>Realisasi Bendahara</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($acaras as $acara)
                    <tr>
                        <td>
                            <strong>{{ $acara->nama_acara }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $acara->lokasi ?? 'Balai Desa' }}</div>
                        </td>
                        <td>
                            <a href="{{ route('acara.index', ['desa_id' => $acara->desa_id]) }}" style="color: var(--accent-blue); text-decoration: none; font-weight: 500;">
                                {{ $acara->desa->nama_desa ?? '-' }}
                            </a>
                        </td>
                        <td>{{ $acara->tanggal_mulai->translatedFormat('d M Y') }}</td>
                        <td>Rp {{ number_format($acara->anggaran_rencana, 0, ',', '.') }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>Rp {{ number_format($acara->total_pengeluaran, 0, ',', '.') }}</span>
                                <div style="width: 70px; height: 6px; background: rgba(255,255,255,0.08); border-radius: 99px; overflow: hidden;">
                                    <div style="height: 100%; width: {{ $acara->persentase_anggaran }}%; background: linear-gradient(90deg, var(--accent-primary), #06b6d4); border-radius: 99px;"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = 'info';
                                if($acara->status === 'ongoing') $badgeClass = 'success';
                                if($acara->status === 'completed') $badgeClass = 'info';
                                if($acara->status === 'cancelled') $badgeClass = 'danger';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($acara->status) }}</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <button type="button" class="btn-outline" style="padding: 0.4rem 0.75rem;"
                                    onclick="openEditAcara({{ $acara->id }}, {{ $acara->desa_id }}, '{{ addslashes($acara->nama_acara) }}', '{{ addslashes($acara->lokasi) }}', '{{ $acara->tanggal_mulai->format('Y-m-d') }}', '{{ $acara->anggaran_rencana }}', '{{ $acara->status }}', '{{ addslashes($acara->deskripsi) }}')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('acara.destroy', $acara) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara {{ $acara->nama_acara }}?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-outline" style="padding: 0.4rem 0.75rem; color: var(--accent-rose);">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                            Tidak ada data acara yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $acaras->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH ACARA -->
    <div class="modal-overlay" id="modal-tambah-acara">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Data Acara (Event)</h3>
                <button type="button" class="btn-close" onclick="closeModal('modal-tambah-acara')">&times;</button>
            </div>
            <form action="{{ route('acara.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Acara</label>
                        <input type="text" name="nama_acara" class="form-input" placeholder="Contoh: Gebyar Seni Budaya" required value="{{ old('nama_acara') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilih Unit / Instansi Penyelenggara</label>
                        <select name="desa_id" class="form-select" required>
                            @foreach($desas as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_mulai" class="form-input" required value="{{ old('tanggal_mulai', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Kegiatan</label>
                        <input type="text" name="lokasi" class="form-input" placeholder="Gedung / Aula / Lapangan" value="{{ old('lokasi') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rencana Anggaran (Rp)</label>
                        <input type="number" name="anggaran_rencana" class="form-input" placeholder="50000000" required value="{{ old('anggaran_rencana') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Acara</label>
                        <select name="status" class="form-select" required>
                            <option value="planned">Planned</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi / Catatan Singkat</label>
                        <textarea name="deskripsi" class="form-textarea" rows="2" placeholder="Keterangan agenda...">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('modal-tambah-acara')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Acara</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT ACARA -->
    <div class="modal-overlay" id="modal-edit-acara">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Data Acara</h3>
                <button type="button" class="btn-close" onclick="closeModal('modal-edit-acara')">&times;</button>
            </div>
            <form id="form-edit-acara" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Acara</label>
                        <input type="text" name="nama_acara" id="edit_nama_acara" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilih Unit / Instansi Penyelenggara</label>
                        <select name="desa_id" id="edit_desa_id" class="form-select" required>
                            @foreach($desas as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Kegiatan</label>
                        <input type="text" name="lokasi" id="edit_lokasi" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rencana Anggaran (Rp)</label>
                        <input type="number" name="anggaran_rencana" id="edit_anggaran_rencana" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Acara</label>
                        <select name="status" id="edit_status_acara" class="form-select" required>
                            <option value="planned">Planned</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi / Catatan Singkat</label>
                        <textarea name="deskripsi" id="edit_deskripsi_acara" class="form-textarea" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('modal-edit-acara')">Batal</button>
                    <button type="submit" class="btn-primary">Update Acara</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditAcara(id, desaId, nama, lokasi, tgl, anggaran, status, desc) {
            document.getElementById('form-edit-acara').action = "/acara/" + id;
            document.getElementById('edit_nama_acara').value = nama;
            document.getElementById('edit_desa_id').value = desaId;
            document.getElementById('edit_tanggal_mulai').value = tgl;
            document.getElementById('edit_lokasi').value = lokasi;
            document.getElementById('edit_anggaran_rencana').value = anggaran;
            document.getElementById('edit_status_acara').value = status;
            document.getElementById('edit_deskripsi_acara').value = desc;
            openModal('modal-edit-acara');
        }
    </script>
</x-app-layout>
