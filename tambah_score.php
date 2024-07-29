<?php
include 'koneksi.php';
$judul = 'Pertandingan';
include('layout/header.php');
// Menyimpan skor satu per satu
if (isset($_POST['single_save'])) {
    $klub1_id = $_POST['klub1'];
    $klub2_id = $_POST['klub2'];
    $skor1 = $_POST['skor1'];
    $skor2 = $_POST['skor2'];

    if ($klub1_id != $klub2_id && is_numeric($skor1) && is_numeric($skor2)) {
        $query = "INSERT INTO pertandingan (klub1_id, klub2_id, skor1, skor2) VALUES ( '$klub1_id', '$klub2_id', '$skor1', '$skor2')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "Skor pertandingan berhasil disimpan!";
            header("location: index.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($coon);
        }
        $result;
    } else {
        echo "Data pertandingan tidak valid!";
    }
}

// Menyimpan skor multiple
if (isset($_POST['multiple_save'])) {
    $klub1_ids = $_POST['klub1'];
    $klub2_ids = $_POST['klub2'];
    $skor1s = $_POST['skor1'];
    $skor2s = $_POST['skor2'];

    $error = false;
    $conn->begin_transaction();

    for ($i = 0; $i < count($klub1_ids); $i++) {
        if ($klub1_ids[$i] != $klub2_ids[$i] && is_numeric($skor1s[$i]) && is_numeric($skor2s[$i])) {
            $query_multi = "INSERT INTO pertandingan (klub1_id, klub2_id, skor1, skor2) VALUES ($klub1_ids[$i], $klub2_ids[$i], $skor1s[$i], $skor2s[$i])";
            $result_multi = mysqli_query($conn, $query_multi);
            if (!$result_multi) {
                $error = true;
                echo "Error: " . mysqli_error($conn);
                break;
            }
        } else {
            $error = true;
            echo "Data pertandingan tidak valid pada baris " . ($i + 1) . "<br>";
            break;
        }
    }

    if ($error) {
        $conn->rollback();
    } else {
        $conn->commit();
        echo "Semua skor pertandingan berhasil disimpan!";
    }
}

$klubs = $conn->query("SELECT id, nama FROM klub");

?>

<div class="page-body">
    <div class="container d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Input Satu per Satu
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for=""> Klub 1</label>
                                <select name="klub1" class="form-control">
                                    <?php $klubs->data_seek(0);
                                    while ($row = $klubs->fetch_assoc()) { ?>
                                        <option class="form-control" value="">Pilih Klub Pertama</option>
                                        <option class="form-control" value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Klub 2</label>
                                <select name="klub2" class="form-control">
                                    <?php $klubs->data_seek(0);
                                    while ($row = $klubs->fetch_assoc()) { ?>
                                        <option class="form-control" value="">Pilih Klub Pertama</option>
                                        <option class="form-control" value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for=""> Skor 1: </label>
                                <input type="text" class="form-control" name="skor1">
                            </div>
                            <div class="mb-3">
                                <label for=""> Skor 2: </label>
                                <input type="text" class="form-control" name="skor2">
                            </div>
                            <button type="submit" name="single_save" class="btn btn-primary"> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Input Multiple</div>
                    <div class="card-body">

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Multiple Match
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div id="multipleMatches">
                        <div class="mb-3">
                            Klub 1: <select name="klub1[]">
                                <?php $klubs->data_seek(0);
                                while ($row = $klubs->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                <?php } ?>
                            </select>
                            Klub 2: <select name="klub2[]">
                                <?php $klubs->data_seek(0);
                                while ($row = $klubs->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                <?php } ?>
                            </select>
                            Skor 1: <input type="text" name="skor1[]">
                            Skor 2: <input type="text" name="skor2[]"><br>
                        </div>
                    </div>
                    <button type="button" onclick="addMatch()" class="btn btn-primary">Add Match</button><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="multiple_save" class="btn btn-primary" data-bs-dismiss="modal">Save </button>
                </div>
            </form>
        </div>
    </div>
</div>







<script>
    function addMatch() {
        var container = document.getElementById("multipleMatches");
        var newMatch = document.createElement("div");
        newMatch.innerHTML = `
            <div class="mb-3">
                Klub 1: <select name="klub1[]">
                    <?php $klubs->data_seek(0);
                    while ($row = $klubs->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                    <?php } ?>
                </select>
                Klub 2: <select name="klub2[]">
                    <?php $klubs->data_seek(0);
                    while ($row = $klubs->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                    <?php } ?>
                </select>
                Skor 1: <input type="text" name="skor1[]">
                Skor 2: <input type="text" name="skor2[]"><br>
            </div>`;
        container.appendChild(newMatch);
    }
</script>
<?php include('layout/footer.php'); ?>