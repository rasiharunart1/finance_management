<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Manajemen User & Role (Superadmin)</h2>
            <p>Kelola akses pengguna sistem, penetapan Desa untuk Admin Bendahara (Has One Desa), dan Superadmin (Has Many Desa).</p>
        </div>
        <button type="button" class="btn-primary" onclick="openModal('modal-tambah-user')">
            <i data-lucide="user-plus"></i>
            <span>Tambah User Baru</span>
        </button>
    </div>

    <div class="glass" style="padding: 0; overflow: hidden;">
        <div class="table-container">
            <table>
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>No. Telepon / WA</th>
                        <th>Hak Akses / Peran</th>
                        <th>Desa Penugasan</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-red); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if(auth()->id() === $user->id)
                                    <span style="font-size:11px; color: var(--primary-red); font-weight: 600;"> (Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'superadmin' ? 'danger' : ($user->role === 'anggota_panitia' ? 'warning' : 'success') }}">
                                {{ $user->role === 'superadmin' ? 'Superadmin (All Desa)' : ($user->role === 'anggota_panitia' ? 'Anggota Panitia' : 'Admin Bendahara') }}
                            </span>
                        </td>
                        <td>
                            @if($user->role === 'superadmin')
                                <span class="badge" style="background: rgba(255,255,255,0.05);">Semua Desa (Has Many)</span>
                            @else
                                <span class="badge success" style="font-weight: 600;">
                                    {{ $user->desa->nama_desa ?? 'Belum Ditugaskan' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            @if(auth()->id() !== $user->id)
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" class="btn-secondary" style="padding: 6px 12px; font-size: 11px;"
                                    onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->role }}', '{{ $user->desa_id ?? '' }}', {{ $user->is_active ? 'true' : 'false' }})">
                                    <i data-lucide="edit" style="width:14px;"></i> Edit
                                </button>
                                <form action="{{ route('user.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna {{ $user->name }}?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary" style="padding: 6px 12px; font-size: 11px; color: var(--primary-red);">
                                        <i data-lucide="trash-2" style="width:14px;"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span style="font-size:12px; color: var(--text-secondary);">Self Account</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 40px;">
                            Tidak ada data pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 16px;">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH USER -->
    <div class="modal-overlay" id="modal-tambah-user">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Tambah User Baru</h3>
                <button type="button" onclick="closeModal('modal-tambah-user')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-input" required value="{{ old('email') }}" placeholder="email@nhfinance.id">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon / WA</label>
                        <input type="text" name="phone" class="form-input" placeholder="0812xxxx" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peran Pengguna (Role)</label>
                        <select name="role" class="form-select" id="add_role" required onchange="toggleDesaSelect('add_role', 'add_desa_group')">
                            <option value="admin_bendahara">Admin Bendahara (Has One Desa)</option>
                            <option value="superadmin">Superadmin (Has Many Desa)</option>
                            <option value="anggota_panitia">Anggota Panitia (View RAB Only)</option>
                        </select>
                    </div>
                    <div class="form-group" id="add_desa_group">
                        <label class="form-label">Pilih Desa Penugasan (Has One Desa)</label>
                        <select name="desa_id" class="form-select">
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }} ({{ $ds->kecamatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required minlength="8" placeholder="Minimal 8 karakter">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-tambah-user')">Batal</button>
                    <button type="submit" class="btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div class="modal-overlay" id="modal-edit-user">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Data User & Penetapan Desa</h3>
                <button type="button" onclick="closeModal('modal-edit-user')" style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:18px;">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <form id="form-edit-user" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="edit_email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon / WA</label>
                        <input type="text" name="phone" id="edit_phone" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Peran Pengguna (Role)</label>
                        <select name="role" id="edit_role" class="form-select" required onchange="toggleDesaSelect('edit_role', 'edit_desa_group')">
                            <option value="superadmin">Superadmin (Has Many Desa)</option>
                            <option value="admin_bendahara">Admin Bendahara (Has One Desa)</option>
                            <option value="anggota_panitia">Anggota Panitia (View RAB Only)</option>
                        </select>
                    </div>
                    <div class="form-group" id="edit_desa_group">
                        <label class="form-label">Desa Penugasan (Has One Desa)</label>
                        <select name="desa_id" id="edit_desa_id" class="form-select">
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $ds)
                                <option value="{{ $ds->id }}">{{ $ds->nama_desa }} ({{ $ds->kecamatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Akun</label>
                        <select name="is_active" id="edit_is_active" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-input" placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-edit-user')">Batal</button>
                    <button type="submit" class="btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditUser(id, name, email, phone, role, desaId, isActive) {
            document.getElementById('form-edit-user').action = "/user/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_desa_id').value = desaId || '';
            document.getElementById('edit_is_active').value = isActive ? "1" : "0";
            toggleDesaSelect('edit_role', 'edit_desa_group');
            openModal('modal-edit-user');
        }

        function toggleDesaSelect(roleSelectId, groupDivId) {
            const role = document.getElementById(roleSelectId).value;
            const group = document.getElementById(groupDivId);
            if (role === 'superadmin') {
                group.style.opacity = '0.5';
            } else {
                group.style.opacity = '1';
            }
        }
    </script>
</x-app-layout>
