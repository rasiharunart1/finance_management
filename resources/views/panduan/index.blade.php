<x-app-layout>
    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-title">
            <h1>Panduan & Manual Pengguna Pemula</h1>
            <p>Petunjuk interaktif langkah demi langkah mengelola kas, anggaran, sponsor, dan laporan berbagai jenis kegiatan (Desa, Sekolah, Kampus, Organisasi & Event).</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" onclick="window.print()" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <i data-lucide="printer" style="width: 18px;"></i>
                <span>Cetak / Simpan PDF Panduan</span>
            </button>
        </div>
    </div>

    <!-- QUICK NAV ANCHOR PILLS -->
    <div class="glass" style="padding: 16px 20px; margin-bottom: 28px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; border-radius: var(--radius-lg);">
        <span style="font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-right: 6px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="compass" style="width: 16px; color: var(--primary-red);"></i> Daftar Isi:
        </span>
        <a href="#sec-role" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">1. Konsep & Peran</a>
        <a href="#sec-modal-awal" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">2. Modal Awal</a>
        <a href="#sec-rab" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">3. RAB & Agenda</a>
        <a href="#sec-kas-struk" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">4. Kas & Bukti Struk</a>
        <a href="#sec-sponsor" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">5. Sponsor & Cairkan</a>
        <a href="#sec-export" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">6. Export PDF & Excel</a>
        <a href="#sec-faq" class="btn-secondary" style="font-size: 12px; padding: 6px 12px; text-decoration: none;">7. Tanya Jawab (FAQ)</a>
    </div>

    <!-- SECTION 1: ROLE & KONCEP -->
    <div id="sec-role" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.12); color: var(--primary-red); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="users" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">1. Konsep Sistem & Hak Akses Peran (Role)</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Sistem ini memisahkan kendali agar data keuangan setiap unit/instansi aman dan terfokus.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <div style="padding: 20px; border-radius: var(--radius-md); background: var(--surface-color); border: 1px solid rgba(255,255,255,0.06); box-shadow: var(--shadow-inset);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); font-size: 12px;">Bendahara Unit / Instansi (hasOne)</span>
                </div>
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">Pengelola Kas Unit / Desa / Sekolah</h3>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Sebagai Bendahara, akun Anda <strong>terhubung dengan 1 unit/instansi penugasan</strong>. Anda bertugas mengatur <strong>Modal Awal Kegiatan</strong>, menyusun anggaran agenda/acara, mencatat pemasukan & pengeluaran kas, serta mengunggah foto bukti struk belanja.
                </p>
            </div>

            <div style="padding: 20px; border-radius: var(--radius-md); background: var(--surface-color); border: 1px solid rgba(255,255,255,0.06); box-shadow: var(--shadow-inset);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; font-size: 12px;">Superadmin (hasMany)</span>
                </div>
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">Pengawas Seluruh Unit & User</h3>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Superadmin adalah koordinator yang dapat memantau data kas dari <strong>banyak unit/instansi sekaligus</strong>. Superadmin memiliki menu eksklusif <strong>Unit / Instansi (Desa/Sekolah)</strong> dan <strong>Manajemen User</strong> untuk menetapkan penugasan akun bendahara.
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION 2: MODAL AWAL -->
    <div id="sec-modal-awal" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: var(--success); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="wallet" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">2. Langkah Pertama: Mengatur Modal Awal Kegiatan</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Modal awal adalah dasar saldo kas sebelum dikurangi atau ditambah transaksi lainnya.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 12px; font-weight: 700; color: var(--primary-red); margin-bottom: 6px;">LANGKAH 1</div>
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px;">Buka Menu Anggaran (RAB)</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Klik menu <strong>Anggaran (RAB)</strong> di sidebar kiri untuk masuk ke halaman perencanaan biaya.</p>
            </div>
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 12px; font-weight: 700; color: var(--primary-red); margin-bottom: 6px;">LANGKAH 2</div>
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px;">Tekan Tombol Modal Awal</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Di bagian atas halaman, klik tombol <strong>"Atur Modal Awal"</strong> untuk memunculkan form popup.</p>
            </div>
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 12px; font-weight: 700; color: var(--primary-red); margin-bottom: 6px;">LANGKAH 3</div>
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px;">Simpan Nominal Modal</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Masukkan angka nominal (tanpa titik atau koma, mis: 15000000) dan klik <strong>Simpan</strong>.</p>
            </div>
        </div>
    </div>

    <!-- SECTION 3: RAB & AGENDA -->
    <div id="sec-rab" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); color: var(--warning); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="pie-chart" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">3. Membuat Daftar Agenda & Anggaran (RAB)</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Pantau batas biaya maksimal per agenda kegiatan agar pengeluaran terkendali.</p>
            </div>
        </div>

        <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
            Di menu <strong>Anggaran (RAB)</strong>, Anda dapat menekan tombol <strong>"Tambah Agenda & Anggaran"</strong>. Masukkan nama kegiatan (mis. <em>Seminar / Turnamen / Pentas Seni</em>), tanggal, dan Rencana Anggaran Biaya (RAB).
        </p>
        <div style="padding: 16px; border-left: 4px solid var(--warning); background: rgba(245, 158, 11, 0.08); border-radius: 0 var(--radius-md) var(--radius-md) 0;">
            <strong style="font-size: 13px; color: var(--text-primary);">💡 Info Progress Bar Otomatis:</strong>
            <p style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                Setiap kali Anda mencatat pengeluaran kas pada suatu agenda, sistem secara otomatis menghitung persentase penyerapan dana dan mengubah warna <em>progress bar</em> (Hijau -> Kuning -> Merah) jika mendekati batas anggaran.
            </p>
        </div>
    </div>

    <!-- SECTION 4: KAS & BUKTI STRUK -->
    <div id="sec-kas-struk" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.12); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="camera" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">4. Mencatat Pemasukan & Pengeluaran + Foto Bukti Struk</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Semua transaksi kas kini dilengkapi arsip digital foto kwitansi dan nota belanja.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <div style="padding: 20px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 14px; font-weight: 700; color: var(--success); margin-bottom: 8px;">
                    <i data-lucide="arrow-down-circle" style="width: 16px; display: inline;"></i> Pemasukan Kas (`/pemasukan`)
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Gunakan menu ini untuk mencatat uang masuk seperti Iuran Warga, Donasi, atau Bantuan Desa. Pada form input, pilih file foto <strong>Bukti Struk/Kwitansi</strong> (format `.jpg/.png`).
                </p>
            </div>

            <div style="padding: 20px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 14px; font-weight: 700; color: var(--primary-red); margin-bottom: 8px;">
                    <i data-lucide="arrow-up-circle" style="width: 16px; display: inline;"></i> Pengeluaran Kas (`/pengeluaran`)
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Gunakan untuk mencatat biaya operasional dan hadiah lomba. Pilih acara terkait agar RAB terpotong. Unggah foto <strong>Nota/Bon Belanja</strong> untuk bukti transparansi.
                </p>
            </div>
        </div>

        <div style="margin-top: 16px; padding: 14px 18px; border-radius: var(--radius-md); background: rgba(59, 130, 246, 0.08); display: flex; align-items: center; gap: 12px;">
            <i data-lucide="eye" style="width: 20px; color: #3b82f6; flex-shrink: 0;"></i>
            <span style="font-size: 13px; color: var(--text-primary);">
                <strong>Fitur Preview Struk Cepat:</strong> Pada tabel transaksi, klik tombol <strong>"Lihat Struk"</strong> untuk mempratinjau gambar bukti bayar dalam ukuran besar (Modal Popup) tanpa harus pindah halaman!
            </span>
        </div>
    </div>

    <!-- SECTION 5: SPONSOR & CAIRKAN -->
    <div id="sec-sponsor" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(168, 85, 247, 0.12); color: #a855f7; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="handshake" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">5. Alur Sponsor & Otomasi Tombol "Lunas / Cairkan"</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Kelola prospek kerja sama sponsor dengan satu langkah konfirmasi otomatis ke kas.</p>
            </div>
        </div>

        <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
            Saat perusahaan atau donatur berjanji memberikan sponsorship, catat di menu <strong>Sponsor (`/sponsor`)</strong> dengan status awal <em>Prospek</em> atau <em>Negosiasi</em>.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 13px; font-weight: 700; margin-bottom: 6px; color: #a855f7;">1. Tekan Tombol "Lunas / Cairkan"</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Saat dana sponsor cair, klik tombol hijau <strong>"Lunas / Cairkan"</strong> pada kartu atau tabel sponsor tersebut.</p>
            </div>
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 13px; font-weight: 700; margin-bottom: 6px; color: #a855f7;">2. Isi Nominal Final & Bukti Transfer</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Akan muncul form popup! Masukkan <strong>Nominal Final Disepakati</strong> (karena angka realisasi bisa berbeda dengan rencana awal) dan upload foto <strong>Bukti Transfer Bank</strong>.</p>
            </div>
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 13px; font-weight: 700; margin-bottom: 6px; color: #a855f7;">3. Otomatis Tercatat di Kas Bendahara</div>
                <p style="font-size: 12px; color: var(--text-secondary);">Tekan Konfirmasi Lunas! Sistem langsung membuatkan entri <strong>Pemasukan Kas</strong> baru dan memperbarui RAB secara otomatis.</p>
            </div>
        </div>
    </div>

    <!-- SECTION 6: EXPORT PDF & EXCEL -->
    <div id="sec-export" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: var(--success); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="file-text" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">6. Cetak Laporan Formal (PDF Kop Surat) & Export Excel</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Buat laporan pertanggungjawaban resmi yang rapi hanya dengan satu klik.</p>
            </div>
        </div>

        <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
            Di menu <strong>Buku Kas Semua (`/keuangan`)</strong>, <strong>Pemasukan</strong>, dan <strong>Pengeluaran</strong>, Anda akan menemukan dua tombol eksekutif di pojok kanan atas:
        </p>

        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 260px; padding: 20px; border-radius: var(--radius-md); background: var(--surface-color); border-left: 4px solid var(--text-primary);">
                <div style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">
                    <i data-lucide="printer" style="width: 18px; display: inline; margin-right: 4px;"></i> Tombol [🖨️ Cetak / PDF]
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Membuka dokumen formal yang dilengkapi <strong>Kop Surat Resmi Panitia & NHMEDIA-FINANCE EVENT MANAGEMENT</strong>, 4 kartu ringkasan keuangan, tabel rincian transaksi, dan <strong>Kolom Tanda Tangan Resmi (Ketua Panitia & Bendahara Kegiatan)</strong>. Siap dicetak atau disimpan langsung sebagai PDF!
                </p>
            </div>

            <div style="flex: 1; min-width: 260px; padding: 20px; border-radius: var(--radius-md); background: var(--surface-color); border-left: 4px solid var(--success);">
                <div style="font-size: 15px; font-weight: 700; margin-bottom: 8px; color: var(--success);">
                    <i data-lucide="file-spreadsheet" style="width: 18px; display: inline; margin-right: 4px;"></i> Tombol [📊 Export Excel]
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Mengunduh seluruh data transaksi dalam format file <code>.csv</code> (kompatibel penuh dengan Microsoft Excel & Google Sheets) yang dilengkapi nomor urut, tanggal, nama unit/instansi, kategori, serta nominal pemasukan & pengeluaran.
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION 7: FAQ & DEVELOPER -->
    <div id="sec-faq" class="glass" style="padding: 28px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.12); color: var(--primary-red); display: flex; align-items: center; justify-content: center;">
                <i data-lucide="help-circle" style="width: 24px;"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">7. Tanya Jawab (FAQ Pemula) & Dukungan</h2>
                <p style="font-size: 13px; color: var(--text-secondary);">Pertanyaan umum yang sering diajukan saat pertama kali menggunakan sistem.</p>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px; color: var(--text-primary);">
                    ❓ Apa bedanya "Modal Awal" dengan "Anggaran (RAB) Kegiatan"?
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    <strong>Modal Awal</strong> adalah uang kas nyata yang sudah dipegang bendahara sebelum kegiatan dimulai. Sedangkan <strong>Anggaran (RAB) Kegiatan</strong> adalah batas target rencana pengeluaran yang dialokasikan untuk kegiatan tersebut.
                </p>
            </div>

            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px; color: var(--text-primary);">
                    ❓ Mengapa saya tidak melihat menu "Unit / Instansi" dan "Manajemen User"?
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                    Menu tersebut khusus untuk akun dengan peran <strong>Superadmin</strong>. Akun <strong>Admin Bendahara</strong> sengaja disederhanakan agar fokus pada pengelolaan kas unit/instansi penugasannya.
                </p>
            </div>

            <div style="padding: 16px; border-radius: var(--radius-md); background: var(--surface-color);">
                <div style="font-size: 14px; font-weight: 700; margin-bottom: 6px; color: var(--text-primary);">
                    ❓ Siapa pengembang aplikasi ini dan bagaimana cara mendapatkan bantuan teknis?
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span>Aplikasi ini dikembangkan oleh</span>
                    <a href="https://harunarrasyid.vercel.app" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 14px; background: rgba(220, 38, 38, 0.12); color: var(--primary-red); font-weight: 700; text-decoration: none;">
                        <i data-lucide="code-2" style="width: 14px;"></i>
                        <span>nhmedia technology (Harun Ar Rasyid)</span>
                        <i data-lucide="external-link" style="width: 12px;"></i>
                    </a>
                    <span>— Anda dapat mengklik tombol/badge developer di sidebar untuk mengunjungi website resmi.</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
