<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Penggunaan Laboratorium</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.subtitle { color: #6b7280; margin-top: 0; margin-bottom: 16px; }
        table.info { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        table.info td { padding: 4px 8px; border: 1px solid #e5e7eb; }
        table.info td.label { font-weight: bold; width: 160px; background: #f9fafb; }
        table.metrik { width: 100%; border-collapse: collapse; }
        table.metrik th, table.metrik td { padding: 8px 10px; border: 1px solid #e5e7eb; text-align: left; }
        table.metrik th { background: #f3f4f6; }
        table.metrik td.angka { text-align: right; font-weight: bold; width: 120px; }
        .footer-note { margin-top: 20px; color: #6b7280; font-size: 10px; }
    </style>
</head>
<body>
    <h1>Rekap Penggunaan Laboratorium</h1>
    <p class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}</p>

    <table class="info">
        <tr>
            <td class="label">Tahun Ajaran</td>
            <td>{{ $filter['tahun_ajaran'] }}</td>
        </tr>
        <tr>
            <td class="label">Laboratorium</td>
            <td>{{ $filter['laboratorium'] }}</td>
        </tr>
        <tr>
            <td class="label">Mata Kuliah</td>
            <td>{{ $filter['mata_kuliah'] }}</td>
        </tr>
    </table>

    <table class="metrik">
        <thead>
            <tr>
                <th>Metrik</th>
                <th class="angka">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jumlah Praktikum</td>
                <td class="angka">{{ $data['jumlah_praktikum'] }}</td>
            </tr>
            <tr>
                <td>Jumlah Penggunaan Laboratorium</td>
                <td class="angka">{{ $data['jumlah_penggunaan_lab'] }}</td>
            </tr>
            <tr>
                <td>Jumlah Reservasi</td>
                <td class="angka">{{ $data['jumlah_reservasi'] }}</td>
            </tr>
            <tr>
                <td>Jumlah Pembatalan</td>
                <td class="angka">{{ $data['jumlah_pembatalan'] }}</td>
            </tr>
            <tr>
                <td>Jam Penggunaan Laboratorium</td>
                <td class="angka">{{ $data['jam_penggunaan_lab'] }} jam</td>
            </tr>
        </tbody>
    </table>

    <p class="footer-note">
        "Jumlah Praktikum" diambil dari data Jadwal Praktikum (jadwal tetap mingguan), tidak dipengaruhi filter Tahun Ajaran/Semester.
        @if($data['ada_filter_lab_atau_matkul'])
            Reservasi yang dibatalkan sendiri oleh peminjam tidak ikut dihitung pada "Jumlah Pembatalan" saat filter Lab/Mata Kuliah aktif.
        @endif
    </p>
</body>
</html>
