<x-app-layout>
    <div class="section-header">
        <div class="section-title">
            <h2>Dashboard Utama</h2>
            <p>Overview keuangan dan progres persiapan event HUT RI ke-79 & Desa Makmur.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('keuangan.index') }}" class="btn-secondary"><i data-lucide="download" style="width:16px;"></i> Semua Kas</a>
            <a href="{{ route('pemasukan.index') }}" class="btn-primary"><i data-lucide="plus" style="width:16px;"></i> Transaksi Baru</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card glass">
            <div class="stat-header">
                <span class="stat-label">Saldo Kas Saat Ini</span>
                <div class="stat-icon green"><i data-lucide="wallet"></i></div>
            </div>
            <div class="stat-value" style="color: {{ $saldoKas >= 0 ? 'var(--success)' : 'var(--primary-red)' }};">
                Rp {{ number_format($saldoKas, 0, ',', '.') }}
            </div>
            <div class="stat-trend trend-up">
                <i data-lucide="info" style="width:14px;"></i> Modal Awal + Pemasukan - Pengeluaran
            </div>
        </div>

        <div class="stat-card glass">
            <div class="stat-header">
                <span class="stat-label">Modal Awal HUT RI</span>
                <div class="stat-icon green"><i data-lucide="coins"></i></div>
            </div>
            <div class="stat-value">Rp {{ number_format($modalAwal, 0, ',', '.') }}</div>
            <div class="stat-trend trend-up">
                Modal dasar perayaan 17 Agustus
            </div>
        </div>

        <div class="stat-card glass">
            <div class="stat-header">
                <span class="stat-label">(+) Total Pemasukan</span>
                <div class="stat-icon green"><i data-lucide="arrow-down-circle"></i></div>
            </div>
            <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-trend trend-up">
                Sponsorship, Donasi & Iuran Warga
            </div>
        </div>

        <div class="stat-card glass">
            <div class="stat-header">
                <span class="stat-label">(-) Total Pengeluaran</span>
                <div class="stat-icon red"><i data-lucide="arrow-up-circle"></i></div>
            </div>
            <div class="stat-value" style="color: var(--primary-red);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-trend trend-up" style="color: var(--text-secondary);">
                Realisasi seluruh lomba / acara
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 32px;">
        <!-- Chart Area (Line Chart Trend Pemasukan vs Pengeluaran) -->
        <div class="glass" style="padding: 24px; display: flex; flex-direction: column;">
            <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <div style="font-size: 16px; font-weight: 700; color: var(--text-primary);">Tren Arus Kas HUT RI ke-79</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">Visualisasi perbandingan tren Pemasukan vs Pengeluaran (Satuan Juta Rp)</div>
                </div>
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); font-size: 11px;">
                    <i data-lucide="trending-up" style="width: 12px;"></i> Realtime Trend
                </span>
            </div>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="cashFlowLineChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="glass" style="padding: 24px; display: flex; flex-direction: column;">
            <div style="margin-bottom: 24px;">
                <div style="font-size: 16px; font-weight: 600;">Aktivitas Terbaru</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 10px;">
                @forelse($recentActivities ?? [] as $log)
                <div style="display: flex; gap: 16px; position: relative;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--surface-solid); box-shadow: var(--shadow-sm); z-index: 1; flex-shrink: 0;">
                        <i data-lucide="check-circle" style="color:var(--success); width:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600;">{{ $log->aksi }}</div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">{{ $log->keterangan }}</div>
                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 32px 16px; color: var(--text-secondary);">
                    <i data-lucide="inbox" style="width: 36px; height: 36px; margin: 0 auto; opacity: 0.4;"></i>
                    <p style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-top: 8px;">Belum Ada Aktivitas</p>
                    <p style="font-size: 11px; opacity: 0.8; margin-top: 2px;">Semua aktivitas pencatatan kas dan progres lomba akan otomatis muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('cashFlowLineChart');
            if (ctx && typeof Chart !== 'undefined') {
                const gradientIn = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
                gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                const gradientOut = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
                gradientOut.addColorStop(0, 'rgba(220, 38, 38, 0.35)');
                gradientOut.addColorStop(1, 'rgba(220, 38, 38, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendLabels ?? ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', '17 Agustus', 'Pasca Event']) !!},
                        datasets: [
                            {
                                label: 'Pemasukan Kas (Juta Rp)',
                                data: {!! json_encode($trendPemasukan ?? [2, 5, 8, 10, 4, 1]) !!},
                                borderColor: '#10b981',
                                backgroundColor: gradientIn,
                                borderWidth: 3,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Realisasi Pengeluaran (Juta Rp)',
                                data: {!! json_encode($trendPengeluaran ?? [1, 3, 5, 8, 3, 0.5]) !!},
                                borderColor: '#DC2626',
                                backgroundColor: gradientOut,
                                borderWidth: 3,
                                pointBackgroundColor: '#DC2626',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        family: 'Inter',
                                        size: 12,
                                        weight: '600'
                                    },
                                    color: '#64748b'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(30, 41, 59, 0.9)',
                                titleFont: { family: 'Inter', size: 13, weight: '600' },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y + ' Juta';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(100, 116, 139, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { family: 'Inter', size: 11 },
                                    color: '#64748b',
                                    callback: function(value) {
                                        return 'Rp ' + value + ' Jt';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
