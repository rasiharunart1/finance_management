<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Jadwal Event & Agenda Kegiatan</h2>
            <p>Jadwal lengkap pelaksanaan lomba dan perayaan di tiap desa binaan.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('acara.index') }}" class="btn-primary"><i data-lucide="calendar-plus"></i> Kelola Nama Acara (CRUD)</a>
            <a href="{{ route('desa.index') }}" class="btn-secondary"><i data-lucide="map-pin"></i> Kelola Nama Desa (CRUD)</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        @forelse($acaras as $ev)
        <div class="glass" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <span class="badge {{ $ev->status === 'ongoing' ? 'success' : ($ev->status === 'planned' ? 'warning' : 'danger') }}">
                        <i data-lucide="{{ $ev->status === 'ongoing' ? 'play-circle' : 'calendar' }}" style="width: 12px;"></i>
                        {{ strtoupper($ev->status) }}
                    </span>
                    <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600;">
                        {{ \Carbon\Carbon::parse($ev->tanggal_mulai)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 6px;">{{ $ev->nama_acara }}</h3>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                    {{ $ev->deskripsi }}
                </p>
            </div>

            <div style="border-top: 1px dashed var(--border-color); padding-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase;">Lokasi / Desa</div>
                    <div style="font-weight: 600; font-size: 14px;">{{ $ev->desa->nama_desa ?? '-' }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: var(--text-secondary); text-transform: uppercase;">Pagu RAB</div>
                    <div style="font-weight: 700; font-size: 14px; color: var(--primary-red);">Rp {{ number_format($ev->anggaran_rencana, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @empty
        <div class="glass" style="grid-column: 1 / -1; padding: 40px; text-align: center; color: var(--text-secondary);">
            Belum ada agenda kegiatan.
        </div>
        @endforelse
    </div>
</x-app-layout>
