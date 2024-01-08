<?php

session_start();

//Koneksi
$connect = mysqli_connect('localhost', 'root', '', 'kasir');


if (isset($_POST['tambahbarang'])) {
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    $insert = mysqli_query($connect, "insert into produk (namaproduk,deskripsi,harga,stock) values ('$namaproduk','$deskripsi','$harga','$stock')");

    if ($insert) {
        header('location:stock.php');
    } else {
        echo '
        <script>alert("Gagal menambahkan harga baru");
        window.location.href="stock.php"
        </script>
        ';
    }
};

//function hapus barang 
if (isset($_POST['hapusbarang'])) {
    $idp = $_POST['idp'];

    $query = mysqli_query($connect,"delete from produk where idproduk='$idp'");//query untuk menghapus barang
    if ($query) {
        header('location:stock.php');

    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="stock.php"
        </script>
        ';
    }

}

//function untuk edit barang 
if (isset($_POST['editbarang'])) {
    $np = $_POST['namaproduk'];
    $desc = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp'];// idproduk

    $query = mysqli_query($connect,"update produk set namaproduk='$np', deskripsi='$desc', stock='$stock', harga='$harga' where idproduk='$idp' ");

    if ($query) {
        header('location:stock.php');
    } else {
        echo '
            <script>alert("Gagal");
            window.location.href="stock.php"
            </script>
            ';
    }
}

//function tambah pelanggan
if (isset($_POST['tambahpelanggan'])) {
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    $insert = mysqli_query($connect, "insert into pelanggan (namapelanggan,notelp,alamat) values ('$namapelanggan','$notelp','$alamat')");

    if ($insert) {
        header('location:pelanggan.php');
    } else {
        echo '
        <script>alert("Gagal menambahkan pelanggan baru");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

//function edit pelanggan
if (isset($_POST['editpelanggan'])) {
    $np = $_POST['namapelanggan'];
    $nt = $_POST['notelp'];
    $a = $_POST['alamat'];
    $id = $_POST['idpl'];

    $query = mysqli_query($connect,"update pelanggan set namapelanggan='$np', notelp='$nt', alamat='$a' where idpelanggan='$id' ");

    if ($query) {
        header('location:pelanggan.php');
    } else {
        echo '
            <script>alert("Gagal");
            window.location.href="pelanggan.php"
            </script>
            ';
    }

}

//function untuk hapus pelanggan
if (isset($_POST['hapuspelanggan'])) {
    $idpl = $_POST['idpl'];

    $query = mysqli_query($connect,"delete from pelanggan where idpelanggan='$idpl'");
    if ($query) {
        header('location:pelanggan.php');

    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }

}

if (isset($_POST['tambahpesanan'])) {
    $idpelanggan = $_POST['idpelanggan'];

    $insert = mysqli_query($connect, "insert into pesanan (idpelanggan) values ('$idpelanggan')");

    if ($insert) {
        header('location:index.php');
    } else {
        echo '
        <script>alert("Gagal menambahkan pesanan baru");
        window.location.href="index.php"
        </script>
        ';
    }
}

//hapus pesanan
if (isset($_POST['hapuspesanan'])) {
    $idp = $_POST['idp'];

    $query = mysqli_query($connect,"delete from pesanan where idpesanan='$idp'");
    if ($query) {
        header('location:index.php');

    }else {
        echo '
        <script>alert("Gagal");
        window.location.href="index.php"
        </script>
        ';
    }

}


//produk di pilih di pesanan
if (isset($_POST['tambahbelanjaan'])) {
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty']; // Jumlah Produk

    //hitung stock yang tersedia
    $hitung1 = mysqli_query($connect, "select * from produk where idproduk='$idproduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocktersedia = $hitung2['stock']; //stock barang saat ini

    if ($stocktersedia>=$qty) {

        //mengurangi stock tersedia dengan jumlah yang akan di keluarkan
        $selisih = $stocktersedia - $qty;

        //query untuk menambahkan produk dari stock produk ke dalam  detailpesanan
        $insert = mysqli_query($connect, "insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
        //untuk update stock yang telah di ambil dan di inputkan ke detailpesanan 
        $update = mysqli_query($connect, "update produk set stock='$selisih' where idproduk='$idproduk'");

        if ($insert&&$update) {
            header('location:view.php?idp=' . $idp);
        } else {
            echo '
            <script>alert("Gagal menambahkan pesanan baru");
            window.location.href="view.php?idp=' . $idp . '"
            </script>
            ';
        }
    } else {
        //stock tidak cukup
        echo '
            <script>alert("Stock barang tidak cukup");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
    }
}

//function modal hapus
if (isset($_POST['hapusprodukpesanan'])) {
    $idp = $_POST['idp']; // iddetailpesanan
    $idpr = $_POST['idpr'];
    $idpesanan = $_POST['idpesanan'];

    //cek qty yang tersedia
    $cek1 = mysqli_query($connect,"select * from detailpesanan where iddetailpesanan='idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtys = $cek2['qty']; //Jumlah 

    //cek stock stock tersedia
    $cek3 = mysqli_query($connect,"select * from produk where idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stocktersedia = $cek4['stock'];

    $hitung = $stocktersedia+$qtys;

    $update = mysqli_query($connect,"update produk set stock='$hitung' where idproduk='$idpr'"); //update stock barang
    $hapus = mysqli_query($connect,"delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'");

    //function untuk mengeksekusi perintah update dan delete
    if ($update&&$hapus) {
        header('location:view.php?idp='.$idpesanan);// jika berhasil akan kembali ke halaman view
    } else {
        //jika gagal akan muncul peringatan "Gagal menghapus barang" pada halaman view
        echo '
        <script>alert("Gagal menghapus barang");
        window.location.href="view.php?idp='.$idpesanan.'"
        </script>
        ';
    }
}


?>