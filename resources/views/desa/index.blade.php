<x-app-layout>
    <div class="page-header">
        <div class="page-title">
            <h1>Data Nama Desa</h1>
            <p>Kelola daftar desa binaan dan kecamatan yang terintegrasi di sistem NH-Finance.</p>
        </div>
        @if(auth()->user()->isSuperadmin())
        <button type="button" class="btn-primary" onclick="openModal('modal-tambah-desa')">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Desa Baru</span>
        </button>
        @else
        <span class="badge info" style="padding: 0.5rem 1rem;">View Only Mode (Bendahara)</span>
        @endif
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kode Desa</th>
                        <th>Nama Desa</th>
                        <th>Kecamatan</th>
                        <th>Kepala Desa / P.J.</th>
                        <th>Kontak</th>
                        <th>Jumlah Acara</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($desas as $desa)
                    <tr>
                        <td><strong>{{ $desa->kode_desa }}</strong></td>
                        <td>{{ $desa->nama_desa }}</td>
                        <td>{{ $desa->kecamatan }}</td>
                        <td>{{ $desa->kepala_desa ?? '-' }}</td>
                        <td>{{ $desa->kontak ?? '-' }}</td>
                        <td>
                            <span class="badge info">{{ $desa->acaras_count }} Acara</span>
                        </td>
                        <td>
                            <span class="badge {{ $desa->status === 'aktif' ? 'success' : 'danger' }}">
                                {{ ucfirst($desa->status) }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            @if(auth()->user()->isSuperadmin())
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <button type="button" class="btn-outline" style="padding: 0.4rem 0.75rem;" 
                                    onclick="openEditDesa({{ $desa->id }}, '{{ $desa->kode_desa }}', '{{ addslashes($desa->nama_desa) }}', '{{ addslashes($desa->kecamatan) }}', '{{ addslashes($desa->kepala_desa) }}', '{{ $desa->kontak }}', '{{ $desa->status }}')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('desa.destroy', $desa) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus desa {{ $desa->nama_desa }}?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-outline" style="padding: 0.4rem 0.75rem; color: var(--accent-rose);">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span style="font-size:0.75rem; color: var(--text-muted);">Read Only</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                            Tidak ada data desa yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $desas->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH DESA -->
    @if(auth()->user()->isSuperadmin())
    <div class="modal-overlay" id="modal-tambah-desa">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah Data Desa</h3>
                <button type="button" class="btn-close" onclick="closeModal('modal-tambah-desa')">&times;</button>
            </div>
            <form action="{{ route('desa.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Desa</label>
                        <input type="text" name="kode_desa" class="form-input" placeholder="Contoh: DS-011" required value="{{ old('kode_desa') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Desa</label>
                        <input type="text" name="nama_desa" class="form-input" placeholder="Contoh: Desa Sukamulya" required value="{{ old('nama_desa') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-input" placeholder="Contoh: Kecamatan Horizon" required value="{{ old('kecamatan', 'Kecamatan Horizon') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kepala Desa / Penanggung Jawab</label>
                        <input type="text" name="kepala_desa" class="form-input" placeholder="Nama lengkap Kades" value="{{ old('kepala_desa') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Kontak / WhatsApp</label>
                        <input type="text" name="kontak" class="form-input" placeholder="0812xxxx" value="{{ old('kontak') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Desa</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('modal-tambah-desa')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Desa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT DESA -->
    <div class="modal-overlay" id="modal-edit-desa">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Data Desa</h3>
                <button type="button" class="btn-close" onclick="closeModal('modal-edit-desa')">&times;</button>
            </div>
            <form id="form-edit-desa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Desa</label>
                        <input type="text" name="kode_desa" id="edit_kode_desa" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Desa</label>
                        <input type="text" name="nama_desa" id="edit_nama_desa" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" id="edit_kecamatan" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kepala Desa / Penanggung Jawab</label>
                        <input type="text" name="kepala_desa" id="edit_kepala_desa" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Kontak / WhatsApp</label>
                        <input type="text" name="kontak" id="edit_kontak" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Desa</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal('modal-edit-desa')">Batal</button>
                    <button type="submit" class="btn-primary">Update Desa</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditDesa(id, kode, nama, camat, kades, kontak, status) {
            document.getElementById('form-edit-desa').action = "/desa/" + id;
            document.getElementById('edit_kode_desa').value = kode;
            document.getElementById('edit_nama_desa').value = nama;
            document.getElementById('edit_kecamatan').value = camat;
            document.getElementById('edit_kepala_desa').value = kades;
            document.getElementById('edit_kontak').value = kontak;
            document.getElementById('edit_status').value = status;
            openModal('modal-edit-desa');
        }
    </script>
    @endif
</x-app-layout>
