<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Kegiatan & Event - NHMEDIA-FINANCE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.5;
            padding: 40px;
        }
        .container {
            max-width: 1050px;
            margin: 0 auto;
            background: #ffffff;
            padding: 48px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        .no-print-bar {
            max-width: 1050px;
            margin: 0 auto 24px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e293b;
            padding: 16px 24px;
            border-radius: 12px;
            color: #ffffff;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-primary {
            background: #10b981;
            color: #ffffff;
        }
        .btn-primary:hover { background: #059669; }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.25); }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #1e293b;
            padding-bottom: 24px;
            margin-bottom: 28px;
            gap: 24px;
        }
        .logo-box {
            width: 76px;
            height: 76px;
            background: #1e293b;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 32px;
            flex-shrink: 0;
        }
        .kop-text {
            text-align: center;
            flex-grow: 1;
        }
        .kop-text h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .kop-text h2 {
            font-size: 16px;
            font-weight: 700;
            color: #334155;
            margin-top: 4px;
        }
        .kop-text p {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        .summary-card {
            padding: 16px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .summary-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 800;
            margin-top: 4px;
        }

        /* Table */
        .table-wrap {
            overflow-x: auto;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #f1f5f9;
            font-weight: 700;
            color: #334155;
            border-top: 1px solid #e2e8f0;
            border-bottom: 2px solid #cbd5e1;
        }
        tr:hover {
            background: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Signature */
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 48px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
        }
        .signature-box .title {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        .signature-box .space {
            height: 80px;
        }
        .signature-box .name {
            font-size: 15px;
            font-weight: 800;
            text-decoration: underline;
            color: #0f172a;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print-bar {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div>
            <span style="font-weight: 700;"><i class="fa-solid fa-file-invoice-dollar"></i> Preview Cetak Laporan Keuangan</span>
            <div style="font-size: 12px; opacity: 0.8;">Klik tombol Cetak/Simpan PDF untuk mengunduh dokumen resmi dengan kop surat.</div>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="container">
        <!-- KOP SURAT -->
        <div class="kop-surat">
            <div class="logo-box">
                <i class="fa-solid fa-flag"></i>
            </div>
            <div class="kop-text">
                <h1>LAPORAN KEUANGAN & ANGGARAN KEGIATAN</h1>
                <h2>NHMEDIA-FINANCE EVENT MANAGEMENT SYSTEM</h2>
                <p>Unit / Instansi: <strong>{{ $namaDesa }}</strong> | Rekapitulasi Arus Kas & Rencana Anggaran Biaya (RAB)</p>
                <p style="font-size: 12px; color: #94a3b8;">Dicetak secara otomatis oleh sistem pada tanggal: {{ date('d F Y, H:i') }} WIB</p>
            </div>
            <div class="logo-box" style="background: transparent; color: #e2e8f0; font-size: 28px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <!-- EXECUTIVE SUMMARY CARDS -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">MODAL AWAL / KAS DASAR</div>
                <div class="summary-value" style="color: #475569;">
                    Rp {{ number_format($modalAwal, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-label">(+) TOTAL PEMASUKAN</div>
                <div class="summary-value" style="color: #059669;">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-label">(-) TOTAL PENGELUARAN</div>
                <div class="summary-value" style="color: #dc2626;">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-card" style="background: #f1f5f9; border-color: #cbd5e1;">
                <div class="summary-label">SALDO KAS BENDAHARA</div>
                <div class="summary-value" style="color: #0f172a;">
                    Rp {{ number_format($saldoKas, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal</th>
                        <th>Acara / Lingkup</th>
                        <th>Tipe</th>
                        <th>Keterangan / Sumber</th>
                        <th class="text-right">Pemasukan</th>
                        <th class="text-right">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = 1;
                        $sumIn = 0;
                        $sumOut = 0;
                    @endphp
                    @forelse($transaksis as $t)
                    @php
                        $in = $t->tipe === 'pemasukan' ? $t->jumlah : 0;
                        $out = $t->tipe === 'pengeluaran' ? $t->jumlah : 0;
                        $sumIn += $in;
                        $sumOut += $out;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $t->nomor_transaksi }}</strong></td>
                        <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $t->acara->nama_acara ?? '-' }}</strong>
                            <div style="font-size: 11px; color: #64748b;">Desa: {{ $t->acara->desa->nama_desa ?? 'Umum' }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $t->tipe === 'pemasukan' ? 'badge-success' : 'badge-danger' }}">
                                {{ strtoupper($t->tipe) }}
                            </span>
                        </td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td class="text-right" style="font-weight: 600; color: #059669;">
                            {{ $in > 0 ? 'Rp ' . number_format($in, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-right" style="font-weight: 600; color: #dc2626;">
                            {{ $out > 0 ? 'Rp ' . number_format($out, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: #94a3b8;">
                            Belum ada catatan transaksi arus kas pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transaksis->count() > 0)
                <tfoot>
                    <tr style="background: #f8fafc; font-weight: 800;">
                        <td colspan="6" style="text-align: right; padding: 14px;">TOTAL TRANSAKSI PERIODE INI:</td>
                        <td class="text-right" style="color: #059669; padding: 14px;">Rp {{ number_format($sumIn, 0, ',', '.') }}</td>
                        <td class="text-right" style="color: #dc2626; padding: 14px;">Rp {{ number_format($sumOut, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: #f1f5f9; font-weight: 800;">
                        <td colspan="6" style="text-align: right; padding: 14px;">SALDO AKHIR BENDAHARA (TERMASUK MODAL AWAL):</td>
                        <td colspan="2" class="text-right" style="color: #0f172a; padding: 14px; font-size: 15px;">
                            Rp {{ number_format($modalAwal + $sumIn - $sumOut, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- SIGNATURE BLOCK -->
        <div class="signature-grid">
            <div class="signature-box">
                <div class="title">Mengetahui & Menyetujui,<br>Ketua Pelaksana / Pimpinan</div>
                <div class="space"></div>
                <div class="name">( ............................................ )</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Penanggung Jawab Acara & Anggaran</div>
            </div>
            <div class="signature-box">
                <div class="title">Dibuat Oleh,<br>Bendahara / Bagian Keuangan</div>
                <div class="space"></div>
                <div class="name">{{ auth()->user()->name ?? '( ............................................ )' }}</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">{{ $namaDesa }}</div>
            </div>
        </div>
    </div>

</body>
</html>
