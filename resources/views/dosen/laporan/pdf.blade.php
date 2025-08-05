{{-- Isi dengan HTML sederhana untuk PDF, sesuai wireframe yang kita diskusikan --}}
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bimbingan</title>
    <style> /* ... CSS sederhana untuk tabel ... */ </style>
</head>
<body>
    <h1>Rekap Aktivitas Bimbingan</h1>
    <p><strong>Dosen:</strong> {{ $dosenNama }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Mahasiswa</th>
                <th>Topik</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bimbingans as $bimbingan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bimbingan->tanggal_bimbingan_format}}</td>
                <td>{{ $bimbingan->mahasiswa->user->name }}</td>
                <td>{{ $bimbingan->topik }}</td>
                <td>{{ $bimbingan->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>