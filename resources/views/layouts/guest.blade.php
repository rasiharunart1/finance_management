<!DOCTYPE html>
<html lang="id" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NH-Finance') }} | Autentikasi Sistem</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg-primary: #090d16;
                --bg-secondary: #111827;
                --bg-glass: rgba(17, 24, 39, 0.85);
                --border-color: rgba(255, 255, 255, 0.08);
                --text-primary: #f8fafc;
                --text-secondary: #94a3b8;
                --accent-primary: #10b981;
                --accent-primary-glow: rgba(16, 185, 129, 0.25);
                --font-main: 'Plus Jakarta Sans', sans-serif;
                --font-heading: 'Outfit', sans-serif;
            }

            body {
                background-color: var(--bg-primary);
                color: var(--text-primary);
                font-family: var(--font-main);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background-image: 
                    radial-gradient(at 15% 25%, rgba(16, 185, 129, 0.12) 0px, transparent 55%),
                    radial-gradient(at 85% 75%, rgba(59, 130, 246, 0.12) 0px, transparent 55%);
            }

            .guest-card {
                background: var(--bg-glass);
                backdrop-filter: blur(20px);
                border: 1px solid var(--border-color);
                border-radius: 28px;
                padding: 2.5rem;
                width: 100%;
                max-width: 440px;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            }

            .brand-box {
                text-align: center;
                margin-bottom: 2rem;
            }

            .brand-icon {
                width: 52px;
                height: 52px;
                border-radius: 16px;
                background: linear-gradient(135deg, var(--accent-primary), #06b6d4);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                font-size: 1.5rem;
                font-weight: 700;
                box-shadow: 0 10px 25px var(--accent-primary-glow);
                margin-bottom: 0.75rem;
            }

            .brand-text {
                font-family: var(--font-heading);
                font-weight: 700;
                font-size: 1.5rem;
                color: var(--text-primary);
            }
            .brand-text span {
                color: var(--accent-primary);
            }

            .demo-credentials {
                margin-top: 1.75rem;
                padding: 1rem;
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid var(--border-color);
                font-size: 0.775rem;
                color: var(--text-secondary);
            }

            .demo-credentials strong {
                color: var(--text-primary);
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.8rem;
            }
        </style>
    </head>
    <body>
        <div class="guest-card">
            <div class="brand-box">
                <div class="brand-icon">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div class="brand-text">NH-<span>Finance</span></div>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">Sistem Manajemen Acara, Desa & Keuangan</p>
            </div>

            {{ $slot }}

            <div class="demo-credentials">
                <strong><i class="fa-solid fa-circle-info"></i> Demo Akun Tersedia:</strong>
                <div>Superadmin: <span style="color:#fff;">superadmin@nhfinance.id</span></div>
                <div>Bendahara: <span style="color:#fff;">bendahara@nhfinance.id</span></div>
                <div style="margin-top:0.25rem;">Password: <span style="color:#10b981; font-weight:600;">password</span></div>
            </div>

            <div style="text-align: center; margin-top: 1.25rem;">
                <a href="https://harunarrasyid.vercel.app" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.75rem; color: var(--text-secondary); text-decoration: none; transition: 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    <span>Developed by <strong style="color: #fff;">nhmedia<span style="color: #94a3b8; font-weight: 400;">technology</span></strong></span>
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i>
                </a>
            </div>
        </div>
    </body>
</html>
