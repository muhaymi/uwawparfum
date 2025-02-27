<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>


<?php

use Picqer\Barcode\BarcodeGeneratorPNG;

function barcode($b, $bcd)
{
    $ui = $bcd;
    // $ui = 'BRG' . strtoupper(substr(uniqid(), -7));

    $generator = new BarcodeGeneratorPNG();
    $barcodeImage = $generator->getBarcode($ui, $generator::TYPE_EAN_13);

    // Simpan gambar barcode ke dalam file sementara
    $tempBarcodeFile = tempnam(sys_get_temp_dir(), 'barcode_');
    file_put_contents($tempBarcodeFile, $barcodeImage);

    if ($b == 1) {
        // Tampilkan gambar barcode di HTML
        return '<img src="data:image/png;base64,' . base64_encode(file_get_contents($tempBarcodeFile)) . '">';
    } else {
        return $ui;
    }
}
?>
<?php if (session()->has('success')) : ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php elseif (session()->has('updates')) : ?>
    <div class="alert alert-primary">
        <?= session('updates') ?>
    </div>
<?php elseif (session()->has('deletes')) : ?>
    <div class="alert alert-danger">
        <?= session('deletes') ?>
    </div>
<?php endif; ?>




<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-address-card"></i>
            Data Produk Grosir
        </h3>
        <div class="card-tools">
            <ul class="nav nav-pills ml-auto">

                <li class="nav-item mx-2">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" title="tambah produk">
                        <i class="fas fa-plus-square"></i>
                    </button>
                </li>
                <li class="nav-item mx-2">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-xl" title="barcode produk">
                        <i class="fas fa-barcode"> </i>
                    </button>
                </li>
                <li class="nav-item mx-2">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stokmin" title="stok hampir habis">
                        <i class="fas fa-search-minus"></i>
                    </button>
                </li>


            </ul>
        </div>
    </div>
    <div class="card-body">

        <ul class="nav nav-pills ">


            <li class="nav-item" style="margin: 3px;">

            </li>
        </ul>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        ID Produk
                        <!-- <input type="checkbox" id="select-all">ID Produk -->
                    </th>
                    <th>Nama Produk</th>
                    <th>kategori Produk</th>
                    <th>Stok Produk</th>
                    <th>stok minimum Produk</th>
                    <th>Harga Beli Produk</th>
                    <th>Harga Jual Produk</th>
                    <th>Opsi</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($produk as $pr) :
                    if ($pr->id_toko == user()->toko) {
                ?>

                        <tr>
                            <td><?= $pr->id_produk; ?></td>
                            <td><?= $pr->nama_produk; ?></td>
                            <td><?= $pr->kategori_produk; ?></td>
                            <td><?= $pr->stok_toko . '   ' . $pr->satuan_produk; ?></td>
                            <td><?= $pr->stok_min . ' ' . $pr->satuan_produk; ?></td>
                            <td><?= $pr->jenis_harga . '. ' . $pr->harga_beli_produk; ?></td>
                            <td><?= $pr->jenis_harga . '. ' . $pr->harga_jual_produk; ?></td>

                            <td>
                                <div class="d-flex">
                                    <button type="button" title="Detail Harga" class="btn btn-warning mx-2 detail-btn" data-toggle="modal" data-target="#detail<?= $pr->id_produk; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button style="margin: 2px;" class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editModal" data-id-produk="<?= $pr->id_produk ?>" data-nama-produk="<?= $pr->nama_produk ?>" data-kategori-produk="<?= $pr->kategori_produk ?>" data-banyak-produk="<?= $pr->stok_toko ?>" data-satuan-produk="<?= $pr->satuan_produk ?>" data-stok-min-produk="<?= $pr->stok_min ?>" data-harga-jual-produk="<?= $pr->harga_jual_produk ?>" data-harga-beli-produk="<?= $pr->harga_beli_produk ?>" data-id_stok_toko="<?= $pr->id_stok_toko; ?>" title="ubah data produk"><i class="	fas fa-pencil-alt"></i></button>

                                    <form action="<?= base_url('hapus_produk_grosir/' . $pr->id_produk); ?>" method="post"><?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger ml-2" type="submit" onclick="return confirm('Yakin ingin menghapus data : <?= $pr->nama_produk ?> ')" title="hapus data produk"><i class="	fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>

                        </tr>


                        <!-- Modal untuk Detail Penjualan -->
                        <div class="modal fade" id="detail<?= $pr->id_produk; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="vvv">Detail Harga Produk</h6><br>

                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="container">
                                            <?php
                                            foreach ($mpg as $m) :
                                                if ($m->produk_id == $pr->id_produk) {
                                            ?>
                                                    <h6><?= $m->nama_paket ?> : <b><?= $m->harga . ' ' . $pr->jenis_harga ?></b></h6>
                                            <?php   };
                                            endforeach; ?>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>



                <?php   };
                endforeach; ?>



            </tbody>
        </table>

        <!-- <button type="button" class="btn btn-primary" onclick="cbx()">Print</button> -->
        <div id="selected-values"></div>

    </div>
</div>
</div>

<!-- Modal add -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="<?= base_url('/simpan_produk_grosir') ?>" method="post" enctype="multipart/form-data"><?= csrf_field() ?>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="id_produk" class="form-label">ID Produk</label>
                                <a href="#" style='font-size:20px' class='far' onclick="ubahNilai(); return false;" title="Generate ID">&#xf359;</a>
                                <input type="text" list="namprod" class="form-control" id="id_produk" oninput="cekProduk()" name="id_produk" required autocomplete="off">
                                <datalist id="namprod">
                                    <?php foreach ($produkAll as $pr) : ?>
                                        <option value="<?= $pr['nama_produk'] ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="kategori_produk" class="form-label">Kategori Produk</label>
                                <input type="text" list="kaprod" class="form-control" id="kategori_produk" name="kategori_produk" required autocomplete="off">
                                <datalist id="kaprod">
                                    <?php foreach ($produkAll as $pr) : ?>
                                        <option value="<?= $pr['kategori_produk'] ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-md-6">
                                <label for="banyak_produk" class="form-label">Banyak Produk</label>
                                <input type="number" class="form-control" id="banyak_produk" name="banyak_produk" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="satuan_produk" class="form-label">Satuan Produk</label>
                                <input type="text" list="satprod" class="form-control" id="satuan_produk" name="satuan_produk" required autocomplete="off">
                                <datalist id="satprod">
                                    <?php foreach ($produkAll as $pr) : ?>
                                        <option value="<?= $pr['satuan_produk'] ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>


                            <div class="col-md-6">
                                <label for="stok_min_produk" class="form-label">Stok Minimum Produk</label>
                                <input type="number" class="form-control" id="stok_min_produk" name="stok_min_produk" required>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="MataUang" class="form-label small">Mata Uang</label>
                                <select class="form-control" name="mataUang" required>
                                    <option value="" disabled selected>Pilih Mata Uang</option>
                                    <option value="IDR">Rupiah (IDR)</option>
                                    <option value="USD">Dollar (USD)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="harga_beli_produk" class="form-label small">Harga Beli Produk</label>
                                <input type="text" class="form-control" id="harga_beli_produk" name="harga_beli_produk" required>
                            </div>
                            <div class="col-md-4">
                                <label for="harga_jual_produk" class="form-label small">Harga Jual Produk</label>
                                <input type="text" class="form-control" id="harga_jual_produk" name="harga_jual_produk" required>
                            </div>
                        </div>


                        <hr>
                        <div class="row mb-2">
                            <?php foreach ($paket as $p) { ?>
                                <div class="col-md-3">
                                    <label for="harga_jual_produk" class="form-label">
                                        <p class="small">Harga <?= $p['nama_paket'] ?></p>
                                    </label>
                                    <input type="text" class="form-control" name="<?= $p['id_paket'] ?>" required>
                                </div>
                            <?php } ?>
                        </div>


                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- barcode -->
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Barcode Produk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
                <div class="modal-body" id="modal-body-content" style="max-height: 400px; overflow-y: auto;">
                    <div class="container mt-5">
                        <div class="row justify-content">

                            <?php foreach ($produk as $pr) : ?>
                                <div class="col-sm-4 mb-4 ">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-header"><?= $pr->nama_produk ?></h5>
                                            <h5 class="card-footer"><?= barcode(1, $pr->id_produk); ?></h5>
                                            <p class="card-footer"><?= $pr->id_produk ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printModal()">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal untuk StokMin produk -->
<div class="modal fade" id="stokmin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Stok < Stok Minimum</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
            </div>
            <div class="modal-body" id="ctksm">
                <div class="table-responsive-sm" style="font-size: small;">
                    <table class="table table-striped" style="max-height: 400px; overflow-y: auto;">
                        <thead>
                            <tr>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>kategori Produk</th>
                                <th>Stok Produk</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($produk as $pr) :
                                if ($pr->stok_toko <= $pr->stok_min) { ?>
                                    <tr>
                                        <td><?= $pr->id_produk; ?></td>
                                        <td><?= $pr->nama_produk; ?></td>
                                        <td><?= $pr->kategori_produk; ?></td>
                                        <td><?= $pr->stok_toko . '   ' . $pr->satuan_produk; ?></td>
                                    </tr>
                            <?php  }
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printsm()">Print</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="<?= base_url('/ubah_produk_grosir') ?>" method="post"><?= csrf_field() ?>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="id_produk" class="form-label">ID Produk</label>
                                <input type="text" name="id_produk" class="form-control" id="ids" required readonly>
                                <input type="text" name="id_stok_toko" class="form-control" id="ist" required hidden>

                            </div>
                            <div class="col-md-6">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="np" name="nama_produk" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="kategori_produk" class="form-label">Kategori Produk</label>
                                <input type="text" list="kaprod" class="form-control" id="kp" name="kategori_produk" required autocomplete="off">
                                <datalist id="kaprod">
                                    <?php foreach ($produkAll as $pr) : ?>
                                        <option value="<?= $pr['kategori_produk'] ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="col-md-6">
                                <label for="banyak_produk" class="form-label">Banyak Produk</label>
                                <input type="number" class="form-control" id="bp" name="banyak_produk" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="satuan_produk" class="form-label">Satuan Produk</label>
                                <input type="text" list="satprod" class="form-control" id="sp" name="satuan_produk" required autocomplete="off">
                                <datalist id="satprod">
                                    <?php foreach ($produkAll as $pr) : ?>
                                        <option value="<?= $pr['satuan_produk'] ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>


                            <div class="col-md-6">
                                <label for="stok_min_produk" class="form-label">Stok Minimum Produk</label>
                                <input type="number" class="form-control" id="smp" name="stok_min_produk" required>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="MataUang" class="form-label small">Mata Uang</label>
                                <select class="form-control" name="mataUang" required>
                                    <option value="" disabled selected>Pilih Mata Uang</option>
                                    <option value="IDR">Rupiah (IDR)</option>
                                    <option value="USD">Dollar (USD)</option>
                                </select>


                            </div>
                            <div class="col-md-4">
                                <label for="harga_beli_produk" class="form-label small">Harga Beli Produk</label>
                                <input type="text" class="form-control" id="hbp" name="harga_beli_produk" required>
                            </div>
                            <div class="col-md-4">
                                <label for="harga_jual_produk" class="form-label small">Harga Jual Produk</label>
                                <input type="text" class="form-control" id="hjp" name="harga_jual_produk" required>
                            </div>
                        </div>


                        <hr>
                        <div class="row mb-2">
                            <?php foreach ($paket as $p) { ?>
                                <div class="col-md-3">
                                    <label for="harga_jual_produk" class="form-label">
                                        <p class="small">Harga <?= $p['nama_paket'] ?></p>
                                    </label>
                                    <input type="text" class="form-control" name="<?= $p['id_paket'] ?>" required>
                                </div>
                            <?php } ?>
                        </div>


                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    var dataAllProduk = <?php echo json_encode($produkAll); ?>;
    var dataProduk = <?php echo json_encode($produk); ?>;

    function cekProduk() {
        // Ambil nilai ID atau nama produk dari input
        var id_or_nama_produk = document.getElementById("id_produk").value;


        var Allproduk = dataAllProduk.find(function(item) {
            return item.id_produk === id_or_nama_produk || item.nama_produk === id_or_nama_produk;
        });

        // Mencari produk berdasarkan ID atau nama yang dimasukkan
        var produk = dataProduk.find(function(item) {
            return item.id_produk === id_or_nama_produk || item.nama_produk === id_or_nama_produk;
        });



        if (Allproduk) {
            document.getElementById("id_produk").value = Allproduk.id_produk;
            document.getElementById("nama_produk").value = Allproduk.nama_produk;
            document.getElementById("kategori_produk").value = Allproduk.kategori_produk;
            document.getElementById("satuan_produk").value = Allproduk.satuan_produk;
            if (produk) {
                document.getElementById("stok_min_produk").value = produk.stok_min;
                document.getElementById("harga_beli_produk").value = produk.harga_beli_produk;
                document.getElementById("harga_jual_produk").value = produk.harga_jual_produk;
            } else {}

        } else {
            document.getElementById("nama_produk").value = "";
        }
    }
</script>


<script>
    function ubahNilai() {

        function generateRandomNumber(length) {
            return Math.floor(Math.random() * Math.pow(10, length));
        }

        function calculateCheckDigit(angka12Digit) {
            var total = 0;
            for (var i = 0; i < angka12Digit.length; i++) {
                var digit = parseInt(angka12Digit[i]);
                total += (i % 2 === 0) ? digit : digit * 3;
            }
            var checkDigit = (10 - (total % 10)) % 10;
            return checkDigit.toString();
        }

        function generateEAN13ID() {
            var angka12Digit = generateRandomNumber(12).toString();
            var digitKontrol = calculateCheckDigit(angka12Digit);
            return angka12Digit + digitKontrol;
        }

        var nilai = generateEAN13ID();

        document.getElementById("id_produk").value = nilai;
    }
</script>

<!--print barcode  -->
<script>
    function printModal() {
        var originalContents = document.body.innerHTML;
        var printContents = document.getElementById('modal-body-content').innerHTML;
        document.body.innerHTML = printContents;
        window.onafterprint = function() {
            window.location.reload();
        };
        window.print();
    }
</script>


<!--print stok min  -->
<script>
    function printsm() {
        var originalContents = document.body.innerHTML;
        var printContents = document.getElementById('ctksm').innerHTML;
        document.body.innerHTML = printContents;
        window.onafterprint = function() {
            window.location.reload();
        };
        window.print();
    }
</script>


<script src="<?= base_url('') ?>jq.js"></script>

<script>
    $(document).ready(function() {
        $(document).on('touchstart click', '.edit-btn', function() {
            var idProduk = $(this).data('id-produk');
            var namaProduk = $(this).data('nama-produk');
            var kategoriProduk = $(this).data('kategori-produk');
            var banyakProduk = $(this).data('banyak-produk');
            var satuanProduk = $(this).data('satuan-produk');
            var stokMinProduk = $(this).data('stok-min-produk');
            var hjp = $(this).data('harga-jual-produk');
            var hbp = $(this).data('harga-beli-produk');
            var ist = $(this).data('id_stok_toko');

            // Mengisi nilai ke dalam form
            $('#editId').val(idProduk);
            $('#ids').val(idProduk);
            $('#np').val(namaProduk);
            $('#kp').val(kategoriProduk);
            $('#bp').val(banyakProduk);
            $('#sp').val(satuanProduk);
            $('#smp').val(stokMinProduk);
            $('#hbp').val(hbp);
            $('#hjp').val(hjp);
            $('#ist').val(ist);

        });

    });
</script>



<?= $this->endSection(); ?>