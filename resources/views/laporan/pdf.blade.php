<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Bimbingan - {{ $user->mahasiswa->nim }}</title>
    <style>
        /* CSS untuk keseluruhan dokumen */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        
        /* CSS untuk Kop Surat */
        .header-table {
            width: 100%;
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }
        .logo {
            width: 75px;
            height: auto;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2, .header-text h3, .header-text p {
            margin: 0;
            padding: 0;
        }
        .header-text h2 { font-size: 16px; }
        .header-text h3 { font-size: 14px; }
        .header-text p { font-size: 10px; }

        /* CSS untuk Judul Bagian */
        .section-title {
            text-align: center;
            text-decoration: underline;
            font-size: 14px;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        /* CSS untuk Tabel Informasi Mahasiswa */
        .info-table {
            margin: 0 auto; /* Trik untuk membuat tabel ke tengah */
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .info-table td {
            padding: 3px 5px;
        }
        .info-label { width: 30%; }
        .info-separator { width: 5%; text-align: center; }
        .info-data { width: 65%; font-weight: bold; }

        /* CSS untuk Tabel Riwayat Bimbingan */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .main-table th, .main-table td {
            border: 1px solid #777;
            padding: 8px;
            text-align: left;
        }
        .main-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <table class="header-table">
        <tr>
            <td style="width: 15%;">
                <img src="{{ public_path('img/logo.png') }}" alt="Logo" class="logo">
            </td>
            <td class="header-text" style="width: 85%;">
                <h3>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h3>
                <h2>UNIVERSITAS NEGERI MANADO</h2>
                <h2>FAKULTAS TEKNIK</h2>
                <h2 style="font-weight: 800;">PROGRAM STUDI S1 TEKNIK INFORMATIKA</h2>
                 <p>
                    Alamat : Kampus UNIMA Tondano 95618<br />
                    website :
                    <a
                        class="website-link"
                        href="https://ti.unima.ac.id"
                        target="_blank"
                        >https://ti.unima.ac.id</a
                    >, email: teknikinformatika@unima.ac.id
                </p>
            </td>
        </tr>
    </table>

    {{-- DETAIL MAHASISWA --}}  
    <table class="info-table">
        <tr>
            <td class="info-label">NAMA LENGKAP</td>
            <td class="info-separator">:</td>
            <td class="info-data">{{ $user->name}}</td>
        </tr>
        <tr>
            <td class="info-label">NIM</td>
            <td class="info-separator">:</td>
            <td class="info-data">{{ $user->mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td class="info-label">EMAIL</td>
            <td class="info-separator">:</td>
            <td class="info-data">{{ $user->email }}</td>
        </tr>
        <tr>
            <td class="info-label">PROGRAM STUDI</td>
            <td class="info-separator">:</td>
            <td class="info-data">S1 TEKNIK INFORMATIKA</td>
        </tr>
    </table>

    {{-- RIWAYAT BIMBINGAN --}}
    <h3 class="section-title">REKAPITULASI BIMBINGAN</h3>
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Topik</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bimbingans as $bimbingan)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $bimbingan->tanggal_bimbingan_format }}</td>
                <td>{{ ucfirst($bimbingan->jenis_bimbingan) }}</td>
                <td>{{ $bimbingan->topik }}</td>
                <td>{{ ucfirst($bimbingan->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada riwayat bimbingan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>