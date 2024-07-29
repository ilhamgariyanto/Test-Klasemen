<?php
include 'koneksi.php';
$judul = 'Tambah Club';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kota = $_POST['kota'];

    if (!empty($nama) && !empty($kota)) {
        $check_query = "SELECT * FROM klub WHERE nama = '$nama'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "Error: Nama klub sudah ada!";
        } else {
            $query = "INSERT INTO klub (nama, kota) VALUES ('$nama', '$kota')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo "Data klub berhasil disimpan!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Nama dan kota klub harus diisi!";
    }
}

$result = mysqli_query($conn, "SELECT * FROM klub ORDER BY id DESC");
include('layout/header.php');
?>

<div class="page-body">
    <div class="container-xl d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Input Klub Sepak Bola
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for=""> Nama Klub :</label>
                                <input type="text" name="nama" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for=""> Kota Klub :</label>
                                <input type="text" name="kota" class="form-control">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Daftar Club
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mt-3">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kota</th>
                            </tr>
                            <?php if (mysqli_num_rows($result) === 0) : ?>
                                <tr>
                                    <td colspan="7">Data Kosong, Silahkan Tambah Data Baru</td>
                                </tr>
                            <?php else : ?>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_array($result)) :
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['nama'] ?></td>
                                        <td><?= $row['kota'] ?></td>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php include('layout/footer.php'); ?>