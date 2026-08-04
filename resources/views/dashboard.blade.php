<x-app-layout>
    <style>
        /* Mode Switcher & Controls */
        .mode-switcher {
            display: inline-flex;
            background: var(--surface-solid, #1e293b);
            padding: 4px;
            border-radius: 14px;
            border: 1px solid var(--border-color, rgba(255, 255, 255, 0.08));
            box-shadow: var(--shadow-sm);
            gap: 4px;
        }
        .btn-mode {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-mode:hover {
            color: var(--text-primary);
        }
        .btn-mode.active {
            background: linear-gradient(135deg, var(--accent-primary, #10b981), #059669);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Flow Schematic Toolbar */
        .schematic-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 14px 20px;
            background: var(--surface-solid, #1e293b);
            border-radius: 16px;
            border: 1px dashed rgba(16, 185, 129, 0.45);
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        /* Per-Widget Color Themes */
        .widget-theme-emerald {
            --widget-accent: #10b981;
            --widget-bg-tint: rgba(16, 185, 129, 0.08);
            --widget-glow: rgba(16, 185, 129, 0.25);
            border-left: 4px solid #10b981 !important;
        }
        .widget-theme-blue {
            --widget-accent: #3b82f6;
            --widget-bg-tint: rgba(59, 130, 246, 0.08);
            --widget-glow: rgba(59, 130, 246, 0.25);
            border-left: 4px solid #3b82f6 !important;
        }
        .widget-theme-amber {
            --widget-accent: #f59e0b;
            --widget-bg-tint: rgba(245, 158, 11, 0.08);
            --widget-glow: rgba(245, 158, 11, 0.25);
            border-left: 4px solid #f59e0b !important;
        }
        .widget-theme-rose {
            --widget-accent: #f43f5e;
            --widget-bg-tint: rgba(244, 63, 94, 0.08);
            --widget-glow: rgba(244, 63, 94, 0.25);
            border-left: 4px solid #f43f5e !important;
        }
        .widget-theme-purple {
            --widget-accent: #8b5cf6;
            --widget-bg-tint: rgba(139, 92, 246, 0.08);
            --widget-glow: rgba(139, 92, 246, 0.25);
            border-left: 4px solid #8b5cf6 !important;
        }
        .widget-theme-cyan {
            --widget-accent: #06b6d4;
            --widget-bg-tint: rgba(6, 182, 212, 0.08);
            --widget-glow: rgba(6, 182, 212, 0.25);
            border-left: 4px solid #06b6d4 !important;
        }
        .widget-theme-indigo {
            --widget-accent: #6366f1;
            --widget-bg-tint: rgba(99, 102, 241, 0.08);
            --widget-glow: rgba(99, 102, 241, 0.25);
            border-left: 4px solid #6366f1 !important;
        }
        .widget-theme-slate {
            --widget-accent: #64748b;
            --widget-bg-tint: rgba(100, 116, 139, 0.08);
            --widget-glow: rgba(100, 116, 139, 0.25);
            border-left: 4px solid #64748b !important;
        }

        .widget-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: linear-gradient(145deg, var(--surface-color, #1e293b), var(--widget-bg-tint, transparent));
            overflow: visible !important;
        }
        .widget-card:hover {
            box-shadow: 0 10px 28px var(--widget-glow, rgba(0, 0, 0, 0.15));
            transform: translateY(-2px);
        }
        .widget-card.edit-mode-active {
            border: 2px dashed var(--widget-accent, #10b981) !important;
            animation: editPulse 2s infinite;
        }

        @keyframes editPulse {
            0% { box-shadow: 0 0 0 0 var(--widget-glow, rgba(16, 185, 129, 0.3)); }
            70% { box-shadow: 0 0 0 10px rgba(0, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        /* Color Picker & Layout Tools */
        .widget-color-picker {
            display: none;
            position: absolute;
            top: -18px;
            right: 12px;
            z-index: 30;
            background: rgba(15, 23, 42, 0.96);
            backdrop-filter: blur(12px);
            padding: 6px 12px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            gap: 6px;
            align-items: center;
        }
        .widget-card.edit-mode-active .widget-color-picker {
            display: flex;
        }
        .color-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .color-dot:hover {
            transform: scale(1.25);
            border-color: #ffffff;
        }
        .color-dot.active {
            transform: scale(1.15);
            border-color: #ffffff;
            box-shadow: 0 0 10px currentColor;
        }

        .btn-reorder {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: rgba(255,255,255,0.1);
            border: none;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-reorder:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Flow Schematic Presentation Mode */
        .schematic-badge {
            display: none;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: var(--widget-bg-tint, rgba(16, 185, 129, 0.15));
            color: var(--widget-accent, #10b981);
            margin-bottom: 12px;
            width: fit-content;
        }
        .schematic-arrow-connector {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--widget-accent, #10b981);
            padding: 6px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px dashed rgba(255, 255, 255, 0.12);
            margin: 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .view-mode-schematic .schematic-badge {
            display: inline-flex;
        }
        .view-mode-schematic .schematic-arrow-connector {
            display: flex;
        }
        .view-mode-schematic .stat-card {
            border-top: 2px solid var(--widget-accent, #10b981);
            position: relative;
        }
        .view-mode-schematic #stats-container {
            position: relative;
        }
        .view-mode-schematic .stat-label {
            color: var(--widget-accent, #10b981);
            font-weight: 700;
        }

        /* Responsive Grid */
        .dashboard-bottom-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        @media (max-width: 768px) {
            .dashboard-bottom-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="section-header">
        <div class="section-title">
            <h2>Dashboard Utama</h2>
            <p>Overview keuangan, progres anggaran, dan persentase kepanitiaan seluruh kegiatan & event.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <!-- Mode Switcher (Standard vs Flow Schematic) -->
            <div class="mode-switcher">
                <button type="button" id="btn-mode-standard" class="btn-mode active" onclick="switchDashboardMode('standard')" title="Tampilan Standar Dashboard">
                    <i data-lucide="layout-grid" style="width: 16px;"></i> Standar
                </button>
                <button type="button" id="btn-mode-schematic" class="btn-mode" onclick="switchDashboardMode('schematic')" title="Tampilan Flow Schematic Arus Keuangan">
                    <i data-lucide="git-merge" style="width: 16px;"></i> Flow Schematic
                </button>
            </div>

            <a href="{{ route('keuangan.index') }}" class="btn-secondary"><i data-lucide="download" style="width:16px;"></i> Semua Kas</a>
            <a href="{{ route('pemasukan.index') }}" class="btn-primary"><i data-lucide="plus" style="width:16px;"></i> Transaksi Baru</a>
        </div>
    </div>

    <!-- Flow Schematic Action Bar & Edit Layout Toolbar -->
    <div id="schematic-edit-toolbar" class="schematic-toolbar" style="display: none;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 38px; height: 38px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; color: #10b981;">
                <i data-lucide="sliders" style="width: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">Mode Flow Schematic & Kustomisasi Widget</div>
                <div style="font-size: 12px; color: var(--text-secondary);">Ubah warna per-widget dan sesuaikan urutan layout skema arus keuangan.</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="btn-toggle-edit-mode" onclick="toggleEditLayoutMode()" class="btn-secondary" style="border: 1px solid rgba(255,255,255,0.2); display: inline-flex; align-items: center; gap: 6px;">
                <i data-lucide="edit-3" style="width: 16px;"></i> <span id="text-edit-mode">Mode Edit: OFF</span>
            </button>
            <!-- Tombol Save untuk edit layout saat mode tampilan flow schematic -->
            <button type="button" id="btn-save-layout" onclick="saveSchematicLayout()" class="btn-primary" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); display: none; align-items: center; gap: 6px;">
                <i data-lucide="save" style="width: 16px;"></i> <span id="text-save-layout">Simpan Layout</span>
            </button>
            <button type="button" id="btn-reset-layout" onclick="resetSchematicLayout()" class="btn-secondary" style="display: none; align-items: center; gap: 6px;" title="Kembalikan ke warna dan urutan default">
                <i data-lucide="rotate-ccw" style="width: 16px;"></i> <span>Reset</span>
            </button>
        </div>
    </div>

    <!-- Main Unified Dashboard Container (Standard & Flow Schematic) -->
    <div id="dashboard-widgets-container" class="view-mode-standard">
        <!-- Stats Grid / Flow Nodes Top Row -->
        <div id="stats-container" class="stats-grid">
            <!-- WIDGET 1: Modal Awal / Sumber Dana -->
            <div id="widget-modal-awal" data-widget-id="widget-modal-awal" class="stat-card glass widget-card widget-theme-amber">
                <!-- Color Picker Toolbar -->
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-modal-awal', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-modal-awal', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-modal-awal', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-modal-awal', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-modal-awal', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-modal-awal', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-modal-awal', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-modal-awal', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-modal-awal', -1)" title="Geser ke Kiri"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-modal-awal', 1)" title="Geser ke Kanan"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="git-commit" style="width:12px;"></i> NODE 01 • SUMBER DANA</div>
                <div class="stat-header">
                    <span class="stat-label">Modal Awal / Kas Dasar</span>
                    <div class="stat-icon green"><i data-lucide="coins"></i></div>
                </div>
                <div class="stat-value">Rp {{ number_format($modalAwal, 0, ',', '.') }}</div>
                <div class="stat-trend trend-up">
                    Modal dasar operasional kegiatan & event
                </div>
                <div class="schematic-arrow-connector">
                    <span>Arus Modal & Sumber Dana</span>
                    <i data-lucide="arrow-right" style="width:14px;"></i>
                </div>
            </div>

            <!-- WIDGET 2: Total Pemasukan -->
            <div id="widget-total-pemasukan" data-widget-id="widget-total-pemasukan" class="stat-card glass widget-card widget-theme-blue">
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-total-pemasukan', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-total-pemasukan', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-total-pemasukan', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-total-pemasukan', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-total-pemasukan', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-total-pemasukan', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-total-pemasukan', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-total-pemasukan', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-total-pemasukan', -1)" title="Geser ke Kiri"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-total-pemasukan', 1)" title="Geser ke Kanan"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="arrow-down-circle" style="width:12px;"></i> NODE 02 • INFLOW KAS</div>
                <div class="stat-header">
                    <span class="stat-label">(+) Total Pemasukan</span>
                    <div class="stat-icon green"><i data-lucide="arrow-down-circle"></i></div>
                </div>
                <div class="stat-value" style="color: var(--success);">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                <div class="stat-trend trend-up">
                    Sponsorship, Donasi, Iuran & Pemasukan Lainnya
                </div>
                <div class="schematic-arrow-connector">
                    <span>Akumulasi Penerimaan Kas</span>
                    <i data-lucide="arrow-right" style="width:14px;"></i>
                </div>
            </div>

            <!-- WIDGET 3: Saldo Kas Saat Ini (Treasury Hub) -->
            <div id="widget-saldo-kas" data-widget-id="widget-saldo-kas" class="stat-card glass widget-card widget-theme-emerald">
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-saldo-kas', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-saldo-kas', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-saldo-kas', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-saldo-kas', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-saldo-kas', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-saldo-kas', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-saldo-kas', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-saldo-kas', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-saldo-kas', -1)" title="Geser ke Kiri"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-saldo-kas', 1)" title="Geser ke Kanan"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="layers" style="width:12px;"></i> NODE 03 • TREASURY HUB</div>
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
                <div class="schematic-arrow-connector">
                    <span>Distribusi Anggaran Acara</span>
                    <i data-lucide="arrow-right" style="width:14px;"></i>
                </div>
            </div>

            <!-- WIDGET 4: Total Pengeluaran -->
            <div id="widget-total-pengeluaran" data-widget-id="widget-total-pengeluaran" class="stat-card glass widget-card widget-theme-rose">
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-total-pengeluaran', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-total-pengeluaran', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-total-pengeluaran', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-total-pengeluaran', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-total-pengeluaran', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-total-pengeluaran', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-total-pengeluaran', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-total-pengeluaran', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-total-pengeluaran', -1)" title="Geser ke Kiri"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-total-pengeluaran', 1)" title="Geser ke Kanan"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="arrow-up-circle" style="width:12px;"></i> NODE 04 • OUTFLOW KAS</div>
                <div class="stat-header">
                    <span class="stat-label">(-) Total Pengeluaran</span>
                    <div class="stat-icon red"><i data-lucide="arrow-up-circle"></i></div>
                </div>
                <div class="stat-value" style="color: var(--primary-red);">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                <div class="stat-trend trend-up" style="color: var(--text-secondary);">
                    Realisasi seluruh agenda & acara
                </div>
                <div class="schematic-arrow-connector">
                    <span>Realisasi Selesai</span>
                    <i data-lucide="check-circle" style="width:14px;"></i>
                </div>
            </div>
        </div>

        <!-- Bottom Grid (Chart Area & Recent Activities) -->
        <div id="bottom-grid-container" class="dashboard-bottom-grid">
            <!-- WIDGET 5: Chart Area (Line Chart Trend Pemasukan vs Pengeluaran) -->
            <div id="widget-chart-trend" data-widget-id="widget-chart-trend" class="glass widget-card widget-theme-cyan" style="padding: 24px; display: flex; flex-direction: column;">
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-chart-trend', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-chart-trend', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-chart-trend', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-chart-trend', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-chart-trend', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-chart-trend', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-chart-trend', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-chart-trend', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-chart-trend', -1)" title="Geser Kiri/Atas"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-chart-trend', 1)" title="Geser Kanan/Bawah"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="activity" style="width:12px;"></i> NODE 05 • ANALITIK ARUS KAS</div>
                <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: var(--text-primary);">Tren Arus Kas Keseluruhan Event</div>
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

            <!-- WIDGET 6: Recent Activities -->
            <div id="widget-recent-activities" data-widget-id="widget-recent-activities" class="glass widget-card widget-theme-indigo" style="padding: 24px; display: flex; flex-direction: column;">
                <div class="widget-color-picker" onclick="event.stopPropagation()">
                    <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Warna:</span>
                    <div class="color-dot" style="background:#10b981;" title="Emerald" onclick="changeWidgetColor('widget-recent-activities', 'emerald')"></div>
                    <div class="color-dot" style="background:#3b82f6;" title="Blue" onclick="changeWidgetColor('widget-recent-activities', 'blue')"></div>
                    <div class="color-dot" style="background:#f59e0b;" title="Amber" onclick="changeWidgetColor('widget-recent-activities', 'amber')"></div>
                    <div class="color-dot" style="background:#f43f5e;" title="Rose" onclick="changeWidgetColor('widget-recent-activities', 'rose')"></div>
                    <div class="color-dot" style="background:#8b5cf6;" title="Purple" onclick="changeWidgetColor('widget-recent-activities', 'purple')"></div>
                    <div class="color-dot" style="background:#06b6d4;" title="Cyan" onclick="changeWidgetColor('widget-recent-activities', 'cyan')"></div>
                    <div class="color-dot" style="background:#6366f1;" title="Indigo" onclick="changeWidgetColor('widget-recent-activities', 'indigo')"></div>
                    <div class="color-dot" style="background:#64748b;" title="Slate" onclick="changeWidgetColor('widget-recent-activities', 'slate')"></div>
                    <div style="width: 1px; height: 16px; background: rgba(255,255,255,0.2); margin: 0 4px;"></div>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-recent-activities', -1)" title="Geser Kiri/Atas"><i data-lucide="arrow-left" style="width:14px;"></i></button>
                    <button type="button" class="btn-reorder" onclick="moveWidgetOrder('widget-recent-activities', 1)" title="Geser Kanan/Bawah"><i data-lucide="arrow-right" style="width:14px;"></i></button>
                </div>

                <div class="schematic-badge"><i data-lucide="list-checks" style="width:12px;"></i> NODE 06 • AUDIT TRAIL</div>
                <div style="margin-bottom: 24px;">
                    <div style="font-size: 16px; font-weight: 600;">Aktivitas Terbaru</div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 10px;">
                    @forelse(($latestActivities ?? $recentActivities ?? []) as $log)
                    <div style="display: flex; gap: 16px; position: relative;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--surface-solid); box-shadow: var(--shadow-sm); z-index: 1; flex-shrink: 0;">
                            <i data-lucide="check-circle" style="color:var(--success); width:20px;"></i>
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 600;">{{ $log->aksi ?? $log->action ?? 'Aktivitas Log' }}</div>
                            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">{{ $log->keterangan ?? $log->description ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">{{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}</div>
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
    </div>

    <!-- Script Flow Schematic, Warna Per Widget, & Chart -->
    <script>
        // State Global Layout & Warna Widget
        let currentDashboardMode = localStorage.getItem('nhfinance_dashboard_mode_v1') || 'standard';
        let isEditLayoutMode = false;
        const DEFAULT_WIDGET_THEMES = {
            'widget-modal-awal': 'amber',
            'widget-total-pemasukan': 'blue',
            'widget-saldo-kas': 'emerald',
            'widget-total-pengeluaran': 'rose',
            'widget-chart-trend': 'cyan',
            'widget-recent-activities': 'indigo'
        };

        // Ganti Mode Tampilan (Standard vs Flow Schematic)
        function switchDashboardMode(mode) {
            currentDashboardMode = mode;
            localStorage.setItem('nhfinance_dashboard_mode_v1', mode);

            const btnStd = document.getElementById('btn-mode-standard');
            const btnSch = document.getElementById('btn-mode-schematic');
            const container = document.getElementById('dashboard-widgets-container');
            const toolbar = document.getElementById('schematic-edit-toolbar');

            if (mode === 'schematic') {
                btnStd.classList.remove('active');
                btnSch.classList.add('active');
                container.classList.remove('view-mode-standard');
                container.classList.add('view-mode-schematic');
                toolbar.style.display = 'flex';
            } else {
                btnSch.classList.remove('active');
                btnStd.classList.add('active');
                container.classList.remove('view-mode-schematic');
                container.classList.add('view-mode-standard');
                toolbar.style.display = 'none';

                // Matikan mode edit layout bila kembali ke standar
                if (isEditLayoutMode) {
                    toggleEditLayoutMode(false);
                }
            }

            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        }

        // Toggle Mode Edit Layout & Warna
        function toggleEditLayoutMode(forceState = null) {
            isEditLayoutMode = forceState !== null ? forceState : !isEditLayoutMode;
            const textMode = document.getElementById('text-edit-mode');
            const saveBtn = document.getElementById('btn-save-layout');
            const resetBtn = document.getElementById('btn-reset-layout');
            const widgets = document.querySelectorAll('.widget-card');

            if (isEditLayoutMode) {
                textMode.textContent = "Mode Edit: ON";
                saveBtn.style.display = "inline-flex";
                resetBtn.style.display = "inline-flex";
                widgets.forEach(w => w.classList.add('edit-mode-active'));
            } else {
                textMode.textContent = "Mode Edit: OFF";
                saveBtn.style.display = "none";
                resetBtn.style.display = "none";
                widgets.forEach(w => w.classList.remove('edit-mode-active'));
            }

            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        }

        // Ubah Warna Tema Per Widget
        function changeWidgetColor(widgetId, themeName) {
            const widget = document.getElementById(widgetId);
            if (!widget) return;

            // Hapus class warna tema sebelumnya
            const themes = ['emerald', 'blue', 'amber', 'rose', 'purple', 'cyan', 'indigo', 'slate'];
            themes.forEach(t => widget.classList.remove('widget-theme-' + t));

            // Tambahkan class warna tema yang baru
            widget.classList.add('widget-theme-' + themeName);
            widget.dataset.currentTheme = themeName;

            // Efek interaktif sederhana
            widget.style.transform = 'scale(1.02)';
            setTimeout(() => {
                widget.style.transform = '';
            }, 200);
        }

        // Geser Urutan Widget di dalam Kontainernya
        function moveWidgetOrder(widgetId, direction) {
            const widget = document.getElementById(widgetId);
            if (!widget) return;

            const parent = widget.parentElement;
            const siblings = Array.from(parent.children);
            const idx = siblings.indexOf(widget);
            const targetIdx = idx + direction;

            if (targetIdx >= 0 && targetIdx < siblings.length) {
                if (direction === -1) {
                    parent.insertBefore(widget, siblings[targetIdx]);
                } else {
                    parent.insertBefore(siblings[targetIdx], widget);
                }

                // Animasi swap
                widget.style.transform = 'scale(1.03)';
                setTimeout(() => {
                    widget.style.transform = '';
                }, 200);
            }
        }

        // Simpan Konfigurasi Layout & Warna Ke localStorage (Tombol Save Flow Schematic)
        function saveSchematicLayout() {
            const widgets = document.querySelectorAll('.widget-card');
            const colors = {};
            widgets.forEach(w => {
                const id = w.getAttribute('data-widget-id');
                const theme = w.dataset.currentTheme || DEFAULT_WIDGET_THEMES[id] || 'emerald';
                colors[id] = theme;
            });

            const topOrder = Array.from(document.getElementById('stats-container').children).map(c => c.getAttribute('data-widget-id'));
            const bottomOrder = Array.from(document.getElementById('bottom-grid-container').children).map(c => c.getAttribute('data-widget-id'));

            const layoutData = {
                colors: colors,
                topOrder: topOrder,
                bottomOrder: bottomOrder,
                savedAt: new Date().toISOString()
            };

            localStorage.setItem('nhfinance_schematic_layout_v1', JSON.stringify(layoutData));

            // Feedback Visual pada Tombol
            const saveBtn = document.getElementById('btn-save-layout');
            const saveText = document.getElementById('text-save-layout');
            saveBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';
            saveText.innerHTML = 'Tersimpan ✓';

            // Toast feedback
            if (typeof showToast === 'function') {
                showToast(
                    "Layout & Warna Tersimpan!",
                    "Konfigurasi tampilan flow schematic dan warna per-widget telah berhasil disimpan.",
                    "success"
                );
            }

            setTimeout(() => {
                saveBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                saveText.innerHTML = 'Simpan Layout';
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }
            }, 2000);
        }

        // Reset Konfigurasi ke Default
        function resetSchematicLayout() {
            localStorage.removeItem('nhfinance_schematic_layout_v1');

            // Set ulang ke warna default
            Object.keys(DEFAULT_WIDGET_THEMES).forEach(widgetId => {
                changeWidgetColor(widgetId, DEFAULT_WIDGET_THEMES[widgetId]);
            });

            if (typeof showToast === 'function') {
                showToast("Layout Direset", "Warna dan konfigurasi widget telah dikembalikan ke default.", "success");
            }
        }

        // Muat Pengaturan dari localStorage
        function loadSchematicLayout() {
            const savedDataStr = localStorage.getItem('nhfinance_schematic_layout_v1');
            if (savedDataStr) {
                try {
                    const savedData = JSON.parse(savedDataStr);
                    if (savedData.colors) {
                        Object.keys(savedData.colors).forEach(widgetId => {
                            changeWidgetColor(widgetId, savedData.colors[widgetId]);
                        });
                    }
                    if (savedData.topOrder && Array.isArray(savedData.topOrder)) {
                        const topContainer = document.getElementById('stats-container');
                        savedData.topOrder.forEach(id => {
                            const elem = document.getElementById(id);
                            if (elem && topContainer) topContainer.appendChild(elem);
                        });
                    }
                    if (savedData.bottomOrder && Array.isArray(savedData.bottomOrder)) {
                        const botContainer = document.getElementById('bottom-grid-container');
                        savedData.bottomOrder.forEach(id => {
                            const elem = document.getElementById(id);
                            if (elem && botContainer) botContainer.appendChild(elem);
                        });
                    }
                } catch (e) {
                    console.error('Error loading schematic layout:', e);
                }
            } else {
                // Terapkan default themes ke dataset
                Object.keys(DEFAULT_WIDGET_THEMES).forEach(widgetId => {
                    const widget = document.getElementById(widgetId);
                    if (widget) {
                        widget.dataset.currentTheme = DEFAULT_WIDGET_THEMES[widgetId];
                    }
                });
            }

            // Aktifkan mode terakhir yang dipilih
            switchDashboardMode(currentDashboardMode);
        }

        // Initialize ketika DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            loadSchematicLayout();

            // Inisialisasi Chart.js Line Chart
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
                        labels: {!! json_encode($trendLabels ?? ['Tahap 1', 'Tahap 2', 'Tahap 3', 'Tahap 4', 'Hari-H Event', 'Pasca Event']) !!},
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
