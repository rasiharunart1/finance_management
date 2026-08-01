<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Pipeline Sponsorship</h2>
            <p>Tracking status proposal sponsor & konfirmasi nominal final pencairan ke kas RAB.</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-add-sponsor')">
            <i data-lucide="plus"></i> Prospek Baru
        </button>
    </div>

    @php
        $prospeks = $sponsors->whereIn('status', ['prospek', 'dikirim']);
        $negosiasis = $sponsors->where('status', 'negosiasi');
        $selesais = $sponsors->whereIn('status', ['disetujui', 'lunas']);
    @endphp

    <div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 20px;">
        <!-- Kolom Prospek / Dikirim -->
        <div style="flex: 1; min-width: 310px; background: rgba(255,255,255,0.02); border-radius: var(--radius-md); padding: 16px; display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                <span>Prospek / Dikirim</span>
                <span class="badge" style="background:var(--border-color)">{{ $prospeks->count() }}</span>
            </div>
            @forelse($prospeks as $s)
            <div class="glass-panel" style="padding: 16px; transition: var(--transition);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                    <h4 style="font-size:14px; font-weight: 600;">{{ $s->nama_sponsor }}</h4>
                    <span style="font-size: 10px; color: var(--text-secondary); background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px;">
                        {{ $s->desa->nama_desa ?? 'Umum' }}
                    </span>
                </div>
                <p style="font-size:12px; color:var(--text-secondary); margin-bottom:12px;">
                    Paket {{ $s->paket }} (Rp {{ number_format($s->nominal_proposal, 0, ',', '.') }})
                </p>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 12px;">
                    <span style="font-size:11px; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px;">
                        {{ $s->divisi_pj ?? 'Div. Humas' }}
                    </span>
                    <span style="font-size:11px; color:var(--text-secondary);">
                        {{ $s->tanggal_update ? $s->tanggal_update->diffForHumans() : '-' }}
                    </span>
                </div>
                <div style="display: flex; gap: 6px; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn-primary" style="padding: 4px 10px; font-size: 11px; background: var(--success);" onclick="openModal('modal-confirm-lunas-{{ $s->id }}')">
                        <i data-lucide="check-circle" style="width:12px;"></i> Lunas / Cairkan
                    </button>
                    <button type="button" class="btn-secondary" style="padding: 4px 8px; font-size: 11px;" onclick="openModal('modal-edit-sponsor-{{ $s->id }}')">
                        <i data-lucide="edit" style="width:12px;"></i> Edit
                    </button>
                    <form action="{{ route('sponsor.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sponsor ini?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="padding: 4px 8px; font-size: 11px; color: var(--primary-red);">
                            <i data-lucide="trash-2" style="width:12px;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 13px;">Belum ada prospek</div>
            @endforelse
        </div>

        <!-- Kolom Negosiasi -->
        <div style="flex: 1; min-width: 310px; background: rgba(255,255,255,0.02); border-radius: var(--radius-md); padding: 16px; display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                <span>Dalam Negosiasi</span>
                <span class="badge" style="background:var(--warning-light); color:var(--warning)">{{ $negosiasis->count() }}</span>
            </div>
            @forelse($negosiasis as $s)
            <div class="glass-panel" style="padding: 16px; transition: var(--transition); border-left: 3px solid var(--warning);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                    <h4 style="font-size:14px; font-weight: 600;">{{ $s->nama_sponsor }}</h4>
                    <span style="font-size: 10px; color: var(--text-secondary); background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px;">
                        {{ $s->desa->nama_desa ?? 'Umum' }}
                    </span>
                </div>
                <p style="font-size:12px; color:var(--text-secondary); margin-bottom:12px;">
                    Paket {{ $s->paket }} (Rp {{ number_format($s->nominal_proposal, 0, ',', '.') }})
                </p>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 12px;">
                    <span style="font-size:11px; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px;">
                        {{ $s->divisi_pj ?? 'Div. Humas' }}
                    </span>
                    <span style="font-size:11px; color:var(--text-secondary);">
                        {{ $s->tanggal_update ? $s->tanggal_update->diffForHumans() : '-' }}
                    </span>
                </div>
                <div style="display: flex; gap: 6px; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn-primary" style="padding: 4px 10px; font-size: 11px; background: var(--success);" onclick="openModal('modal-confirm-lunas-{{ $s->id }}')">
                        <i data-lucide="check-circle" style="width:12px;"></i> Lunas / Cairkan
                    </button>
                    <button type="button" class="btn-secondary" style="padding: 4px 8px; font-size: 11px;" onclick="openModal('modal-edit-sponsor-{{ $s->id }}')">
                        <i data-lucide="edit" style="width:12px;"></i> Edit
                    </button>
                    <form action="{{ route('sponsor.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sponsor ini?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="padding: 4px 8px; font-size: 11px; color: var(--primary-red);">
                            <i data-lucide="trash-2" style="width:12px;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 13px;">Belum ada dalam negosiasi</div>
            @endforelse
        </div>

        <!-- Kolom Disetujui / Lunas -->
        <div style="flex: 1; min-width: 310px; background: rgba(255,255,255,0.02); border-radius: var(--radius-md); padding: 16px; display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                <span>Disetujui / Lunas</span>
                <span class="badge" style="background:var(--success-light); color:var(--success)">{{ $selesais->count() }}</span>
            </div>
            @forelse($selesais as $s)
            <div class="glass-panel" style="padding: 16px; transition: var(--transition); border-left: 3px solid var(--success);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                    <h4 style="font-size:14px; font-weight: 600;">{{ $s->nama_sponsor }}</h4>
                    @if($s->status === 'lunas')
                    <span class="badge" style="background: var(--success); color: #fff; font-size: 10px;">LUNAS CAIR</span>
                    @else
                    <span class="badge success" style="font-size: 10px;">{{ strtoupper($s->status) }}</span>
                    @endif
                </div>
                <div style="margin-bottom: 12px;">
                    @if($s->status === 'lunas' && $s->nominal_final)
                    <div style="font-size: 14px; font-weight: 700; color: var(--success);">
                        Cair: Rp {{ number_format($s->nominal_final, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 11px; color: var(--text-secondary);">
                        (Proposal Awal: Rp {{ number_format($s->nominal_proposal, 0, ',', '.') }}) • {{ $s->desa->nama_desa ?? 'Umum' }}
                    </div>
                    @if($s->bukti_struk)
                    <div style="margin-top: 6px;">
                        <a href="{{ asset('storage/' . $s->bukti_struk) }}" target="_blank" class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="image" style="width:12px;"></i> Bukti Transfer
                        </a>
                    </div>
                    @endif
                    @else
                    <p style="font-size:12px; color:var(--text-secondary);">
                        Paket {{ $s->paket }} (Rp {{ number_format($s->nominal_proposal, 0, ',', '.') }}) • {{ $s->desa->nama_desa ?? 'Umum' }}
                    </p>
                    @endif
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 12px;">
                    <span style="font-size:11px; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px;">
                        {{ $s->divisi_pj ?? 'Div. Humas' }}
                    </span>
                    <span style="font-size:11px; color:var(--text-secondary);">
                        {{ $s->tanggal_update ? $s->tanggal_update->diffForHumans() : '-' }}
                    </span>
                </div>
                <div style="display: flex; gap: 6px; justify-content: flex-end; border-top: 1px solid rgba(0,0,0,0.03); padding-top: 8px; flex-wrap: wrap;">
                    @if($s->status !== 'lunas')
                    <button type="button" class="btn-primary" style="padding: 4px 10px; font-size: 11px; background: var(--success);" onclick="openModal('modal-confirm-lunas-{{ $s->id }}')">
                        <i data-lucide="check-circle" style="width:12px;"></i> Lunas / Cairkan
                    </button>
                    @endif
                    <button type="button" class="btn-secondary" style="padding: 4px 8px; font-size: 11px;" onclick="openModal('modal-edit-sponsor-{{ $s->id }}')">
                        <i data-lucide="edit" style="width:12px;"></i> Edit
                    </button>
                    <form action="{{ route('sponsor.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sponsor ini?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="padding: 4px 8px; font-size: 11px; color: var(--primary-red);">
                            <i data-lucide="trash-2" style="width:12px;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 13px;">Belum ada sponsor disetujui</div>
            @endforelse
        </div>
    </div>

    <!-- MODAL KONFIRMASI LUNAS & NOMINAL FINAL -->
    @foreach($sponsors as $s)
    @if($s->status !== 'lunas')
    <div class="modal-overlay" id="modal-confirm-lunas-{{ $s->id }}">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Konfirmasi Pelunasan & Nominal Final</h3>
                <button type="button" onclick="closeModal('modal-confirm-lunas-{{ $s->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('sponsor.confirm-lunas', $s->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); padding: 14px; border-radius: 12px; margin-bottom: 16px;">
                        <div style="font-weight: 700; font-size: 15px; color: var(--text-primary);">{{ $s->nama_sponsor }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                            Paket Proposal: <strong>{{ $s->paket }}</strong> | Nominal Proposal: <strong>Rp {{ number_format($s->nominal_proposal, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nominal Final Disepakati & Cair (Rp)</label>
                        <input type="number" name="nominal_final" class="form-input" required value="{{ (int) ($s->nominal_final ?: $s->nominal_proposal) }}" placeholder="Contoh: 7500000">
                        <small style="font-size: 11px; color: var(--text-secondary);">
                            Nominal final ini yang akan otomatis masuk ke kas bendahara & menambah saldo RAB.
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan / Catatan Bank (Opsional)</label>
                        <input type="text" name="keterangan_cair" class="form-input" placeholder="Contoh: Transfer via BCA / Diskon negosiasi paket">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bukti Transfer / Struk Bank (Foto/Gambar - Opsional)</label>
                        <input type="file" name="bukti_struk" accept="image/*" class="form-input">
                        <small style="font-size: 11px; color: var(--text-secondary);">
                            Unggah foto bukti transfer. Gambar ini akan otomatis terlampir pada catatan Kas Masuk Bendahara.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-confirm-lunas-{{ $s->id }}')">Batal</button>
                    <button type="submit" class="btn-primary" style="background: var(--success);">
                        <i data-lucide="check" style="width: 14px; display: inline;"></i> Konfirmasi Lunas & Cairkan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endforeach

    <!-- MODAL EDIT SPONSOR -->
    @foreach($sponsors as $s)
    <div class="modal-overlay" id="modal-edit-sponsor-{{ $s->id }}">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Status / Data Sponsor</h3>
                <button type="button" onclick="closeModal('modal-edit-sponsor-{{ $s->id }}')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('sponsor.update', $s->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Desa Binaan</label>
                        <select name="desa_id" class="form-select">
                            <option value="">-- Semua / Umum --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}" {{ $s->desa_id == $ds->id ? 'selected' : '' }}>{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Nama Sponsor / Instansi</label>
                        <input type="text" name="nama_sponsor" class="form-input" required value="{{ $s->nama_sponsor }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paket Sponsorship</label>
                        <select name="paket" class="form-select">
                            <option value="Platinum" {{ $s->paket === 'Platinum' ? 'selected' : '' }}>Platinum</option>
                            <option value="Gold" {{ $s->paket === 'Gold' ? 'selected' : '' }}>Gold</option>
                            <option value="Silver" {{ $s->paket === 'Silver' ? 'selected' : '' }}>Silver</option>
                            <option value="Pendukung" {{ $s->paket === 'Pendukung' ? 'selected' : '' }}>Pendukung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal Proposal Awal (Rp)</label>
                        <input type="number" name="nominal_proposal" class="form-input" required value="{{ (int) $s->nominal_proposal }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal Final Disepakati (Rp)</label>
                        <input type="number" name="nominal_final" class="form-input" value="{{ (int) ($s->nominal_final ?: $s->nominal_proposal) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Divisi Penanggung Jawab</label>
                        <input type="text" name="divisi_pj" class="form-input" value="{{ $s->divisi_pj }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Pipeline</label>
                        <select name="status" class="form-select">
                            <option value="prospek" {{ $s->status === 'prospek' ? 'selected' : '' }}>Prospek</option>
                            <option value="dikirim" {{ $s->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="negosiasi" {{ $s->status === 'negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                            <option value="disetujui" {{ $s->status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="lunas" {{ $s->status === 'lunas' ? 'selected' : '' }}>Lunas (Diterima)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-sponsor-{{ $s->id }}')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- MODAL TAMBAH SPONSOR -->
    <div class="modal-overlay" id="modal-add-sponsor">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Prospek Sponsorship</h3>
                <button type="button" onclick="closeModal('modal-add-sponsor')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('sponsor.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if(auth()->user() && auth()->user()->isSuperadmin())
                    <div class="form-group">
                        <label class="form-label">Desa Binaan</label>
                        <select name="desa_id" class="form-select">
                            <option value="">-- Semua / Umum --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Nama Sponsor / Instansi</label>
                        <input type="text" name="nama_sponsor" class="form-input" required placeholder="Contoh: Bank BRI Cabang">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paket Sponsorship</label>
                        <select name="paket" class="form-select">
                            <option value="Platinum">Platinum</option>
                            <option value="Gold">Gold</option>
                            <option value="Silver">Silver</option>
                            <option value="Pendukung">Pendukung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal Proposal (Rp)</label>
                        <input type="number" name="nominal_proposal" class="form-input" required placeholder="Contoh: 5000000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Divisi Penanggung Jawab</label>
                        <input type="text" name="divisi_pj" class="form-input" placeholder="Contoh: Div. Humas / Div. Sponsor">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Awal</label>
                        <select name="status" class="form-select">
                            <option value="prospek">Prospek</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="negosiasi">Negosiasi</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-add-sponsor')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Sponsor</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
