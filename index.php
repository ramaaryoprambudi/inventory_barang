<?php
    require 'function.php';
    session_start();

    if(!isset($_SESSION["is_login"])){
        header("Location: login.php");
    }

    if(isset($_POST["logout"])){
        session_unset();
        session_destroy();
        header("Location:login.php");
        exit();
    }

    stockBarang($koneksi);
    
    if(isset($_GET['idbarang'])){
        $sql = "DELETE FROM stock WHERE idbarang=$_GET[idbarang]";
        $result = $koneksi->query($sql);
        if($result){
            echo "Berhasil Delete";
            echo "<meta http-equiv=refresh content=2;URL='index.php'> ";
        }else{
            echo "Gagal Delete";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <form action="" method="post">
                        <li>
                            <button class="dropdown-item" name="logout" type="submit">Logout</button>
                        </li>
                    </form>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Dashboard
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 fw-bold">Stock Barang</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#tambahbarang">
                                Tambah Barang
                            </button>

                            <!-- The Modal -->
                            <div class="modal mt-5 pt-5" id="tambahbarang">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Tambah Stock Barang</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <!-- Modal body -->
                                        <form method="post" class="modal-body">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="namabarang" id="inputstockBarang"
                                                    type="text" placeholder="Masukan Nama Barang" required />
                                                <label for="inputstockBarang">Nama Barang</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="deskripsibarang"
                                                    id="deskripsiStockBarang" type="text" 
                                                    placeholder="Masukan Deskripsi Barang" required />
                                                <label for="deskripsiStockBarang">Deskripsi Barang</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="stockbarang" id="StockBarang"
                                                    type="number" placeholder="Masukan Stock Barang" pattern="[0-9]" required />
                                                <label for="StockBarang">Stock Barang</label>
                                                <i><?= isset($_SESSION['stockBarang_Message']) ? $_SESSION['stockBarang_Message'] : "";?></i>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button name="submit" class="btn btn-primary">Tambah Stock</button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            List Stock Barang
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Deskripsi</th>
                                        <th>Stock</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getdata = mysqli_query($koneksi,"SELECT * FROM stock");
                                    $nomor = 1;
                                    while($data = mysqli_fetch_assoc($getdata)){
                                        $idbarang = $data['idbarang'];
                                        $namaBarang = $data['nama_barang'];
                                        $deskripsiBarang = $data['deskripsi'];
                                        $stockBarang = $data['stock'];
                                        $date = $data['date'];                            
                                    ?>

                                    <tr>

                                        <td><?= $nomor; ?></td>
                                        <td><?= htmlspecialchars($namaBarang);?></td>
                                        <td><?= htmlspecialchars($deskripsiBarang);?></td>
                                        <td><?= htmlspecialchars($stockBarang);?></td>
                                        <td><?= date('D ,F d Y', strtotime($date)); ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="update_barang.php?update_stock=<?=$idbarang; ?>">Update</a>
                                            <a class="btn btn-danger btn-sm" href="index.php?idbarang=<?= $idbarang?>"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                    $nomor++;
                                    };
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>