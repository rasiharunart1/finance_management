<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Struktur Panitia Inti</h2>
            <p>Daftar panitia pelaksana kegiatan & manajemen struktur kepanitiaan/organisasi.</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-add-panitia')">
            <i data-lucide="plus"></i> Tambah Panitia
        </button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
        @forelse($panitias as $p)
        <div class="glass" style="padding: 20px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px; position: relative;">
            <div style="position: absolute; top: 12px; left: 12px; font-size: 11px; background: rgba(0,0,0,0.05); padding: 4px 8px; border-radius: 6px; color: var(--text-secondary);">
                {{ $p->desa->nama_desa ?? 'Umum / Semua' }}
            </div>
            
            <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-red); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; box-shadow: var(--shadow-sm); margin-top: 12px;">
                {{ $p->avatar ?? strtoupper(substr($p->nama, 0, 2)) }}
            </div>
            <div style="width: 100%;">
                <h4 style="font-size: 16px; font-weight: 600;">{{ $p->nama }}</h4>
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 8px;">{{ $p->jabatan }}</p>
                <div style="font-size: 12px; color: var(--primary-red); font-weight: 500; margin-bottom: 8px;">
                    {{ $p->divisi ?? '-' }}
                </div>
                @if(strtolower($p->status) === 'aktif')
                    <span class="badge success"><i data-lucide="check-circle" style="width:12px;"></i> Aktif</span>
                @else
                    <span class="badge warning"><i data-lucide="clock" style="width:12px;"></i> {{ $p->status }}</span>
                @endif
            </div>
            @if($p->keterangan)
            <div style="font-size: 11px; color: var(--text-secondary); border-top: 1px dashed var(--border-color); padding-top: 8px; width: 100%;">
                {{ $p->keterangan }}
            </div>
            @endif

            <!-- CRUD Action Buttons -->
            <div style="display: flex; gap: 8px; width: 100%; justify-content: center; margin-top: 8px; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 10px;">
                <button type="button" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="openModal('modal-edit-panitia-{{ $p->id }}')">
                    <i data-lucide="edit" style="width:14px;"></i> Edit
                </button>
                <form action="{{ route('struktur.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus panitia ini?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 12px; color: var(--primary-red);">
                        <i data-lucide="trash-2" style="width:14px;"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT PANITIA -->
        <div class="modal-overlay" id="modal-edit-panitia-{{ $p->id }}">
            <div class="modal-card">
                <div class="modal-header">
                    <h3>Edit Anggota Panitia</h3>
                    <button type="button" onclick="closeModal('modal-edit-panitia-{{ $p->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('struktur.update', $p->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        @if(auth()->user() && auth()->user()->isSuperadmin())
                        <div class="form-group">
                            <label class="form-label">Desa Penugasan</label>
                            <select name="desa_id" class="form-select">
                                <option value="">-- Semua / Umum --</option>
                                @foreach($desas as $ds)
                                    <option value="{{ $ds->id }}" {{ $p->desa_id == $ds->id ? 'selected' : '' }}>{{ $ds->nama_desa }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap / Tim</label>
                            <input type="text" name="nama" class="form-input" required value="{{ $p->nama }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-input" required value="{{ $p->jabatan }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Divisi</label>
                            <input type="text" name="divisi" class="form-input" value="{{ $p->divisi }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Aktif" {{ $p->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Sibuk" {{ $p->status === 'Sibuk' ? 'selected' : '' }}>Sibuk</option>
                                <option value="Siaga" {{ $p->status === 'Siaga' ? 'selected' : '' }}>Siaga</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan Tambahan</label>
                            <input type="text" name="keterangan" class="form-input" value="{{ $p->keterangan }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-panitia-{{ $p->id }}')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="glass" style="grid-column: 1 / -1; padding: 40px; text-align: center; color: var(--text-secondary);">
            Belum ada data struktur panitia.
        </div>
        @endforelse
    </div>

    <!-- MODAL TAMBAH PANITIA -->
    <div class="modal-overlay" id="modal-add-panitia">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Anggota Panitia</h3>
                <button type="button" onclick="closeModal('modal-add-panitia')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('struktur.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Desa Penugasan</label>
                        <select name="desa_id" class="form-select">
                            <option value="">-- Semua / Umum --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap / Tim</label>
                        <input type="text" name="nama" class="form-input" required placeholder="Contoh: Andi Nugroho">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input" required placeholder="Contoh: Ketua Panitia / Divisi Acara">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Divisi</label>
                        <input type="text" name="divisi" class="form-input" placeholder="Contoh: Pimpinan Inti">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Sibuk">Sibuk</option>
                            <option value="Siaga">Siaga</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" class="form-input" placeholder="Contoh: Koordinator Umum / Ketua Pelaksana / Penanggung Jawab">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-panitia')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Panitia</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
