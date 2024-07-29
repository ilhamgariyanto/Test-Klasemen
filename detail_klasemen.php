<?php
include 'koneksi.php';

$result = $conn->query("SELECT klub.id, klub.nama,
    COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub1_id THEN 1 ELSE 0 END), 0) + COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub2_id THEN 1 ELSE 0 END), 0) AS main,
    COALESCE(SUM(CASE WHEN (klub.id = pertandingan.klub1_id AND pertandingan.skor1 > pertandingan.skor2) OR (klub.id = pertandingan.klub2_id AND pertandingan.skor2 > pertandingan.skor1) THEN 1 ELSE 0 END), 0) AS menang,
    COALESCE(SUM(CASE WHEN pertandingan.skor1 = pertandingan.skor2 AND (klub.id = pertandingan.klub1_id OR klub.id = pertandingan.klub2_id) THEN 1 ELSE 0 END), 0) AS seri,
    COALESCE(SUM(CASE WHEN (klub.id = pertandingan.klub1_id AND pertandingan.skor1 < pertandingan.skor2) OR (klub.id = pertandingan.klub2_id AND pertandingan.skor2 < pertandingan.skor1) THEN 1 ELSE 0 END), 0) AS kalah,
    COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub1_id THEN pertandingan.skor1 ELSE 0 END), 0) + COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub2_id THEN pertandingan.skor2 ELSE 0 END), 0) AS gm,
    COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub1_id THEN pertandingan.skor2 ELSE 0 END), 0) + COALESCE(SUM(CASE WHEN klub.id = pertandingan.klub2_id THEN pertandingan.skor1 ELSE 0 END), 0) AS gk,
    COALESCE(SUM(CASE WHEN (klub.id = pertandingan.klub1_id AND pertandingan.skor1 > pertandingan.skor2) OR (klub.id = pertandingan.klub2_id AND pertandingan.skor2 > pertandingan.skor1) THEN 3 WHEN pertandingan.skor1 = pertandingan.skor2 AND (klub.id = pertandingan.klub1_id OR klub.id = pertandingan.klub2_id) THEN 1 ELSE 0 END), 0) AS point
    FROM klub
    LEFT JOIN pertandingan ON klub.id = pertandingan.klub1_id OR klub.id = pertandingan.klub2_id
    GROUP BY klub.id, klub.nama
    ORDER BY point DESC, gm DESC, gk ASC");

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>Klub</th>
            <th>Ma</th>
            <th>Me</th>
            <th>S</th>
            <th>K</th>
            <th>GM</th>
            <th>GK</th>
            <th>Point</th>
        </tr>";

    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$no}</td>
            <td>{$row['nama']}</td>
            <td>{$row['main']}</td>
            <td>{$row['menang']}</td>
            <td>{$row['seri']}</td>
            <td>{$row['kalah']}</td>
            <td>{$row['gm']}</td>
            <td>{$row['gk']}</td>
            <td>{$row['point']}</td>
        </tr>";
        $no++;
    }

    echo "</table>";
} else {
    echo "Tidak ada data klasemen.";
}

$conn->close();
