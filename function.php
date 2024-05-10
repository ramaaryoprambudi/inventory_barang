<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory_barang";

     $koneksi = mysqli_connect($server, $username, $password, $database);

    if($koneksi->connect_error){
        echo"Database Error Connecting to server";
        die();
    }
    
    // Logic for login page
    function login($koneksi){
        session_start();
        
        if(isset($_SESSION["is_login"])){
            header("Location: index.php");
            exit();
        }
        
        $login_message = ""; 
        
        if(isset($_POST["submit"])){
            $email = $_POST["email"];
            $hash_password = hash('md5',$_POST['password']);
        
            $sql = "SELECT * FROM users WHERE email=? AND password =? ";
            $stmt = $koneksi->prepare($sql);
            $stmt ->bind_param('ss',$email,$hash_password);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if(empty($_POST["email"]) || empty($_POST["password"])){
                $login_message = "Invalid username and password";
            }else{
                if($result -> num_rows > 0){
                    $data_session = $result->fetch_assoc();
                    $_SESSION = $data_session;
                    $_SESSION["is_login"] = true;
                    header("Location: index.php");
                    exit();
                }else{
                    $login_message = "Login Gagal";
                }
            }
            $stmt->close();
        }
        $_SESSION["login_message"] = $login_message; // Simpan pesan dalam variabel sesi
    }
    
    // Logic for register
   function register($koneksi){
    session_start();
    if(isset($_SESSION['is_login'])){
        header("Location: index.php");
    }
    $register_message = "";
    if(isset($_POST['submit'])){
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $email = $_POST['email'];
        $password = $_POST['password']; // Tidak perlu di-hash di sini
        
        // Cek apakah email sudah terdaftar
        $check_email_sql = "SELECT * FROM users WHERE email = ?";
        $stmt_check_email = $koneksi->prepare($check_email_sql);
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();
        
        if($result_check_email->num_rows > 0) {
            $register_message = 'Email sudah terdaftar, silahkan gunakan email lain';
        } else {
            // Siapkan prepared statement untuk insert data
            $sql = "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            // Bind parameter ke statement
            global $hash_password;
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $hash_password);
            
            $hash_password = hash('md5', $password); // Hash password
            if(empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $register_message = 'Data belum diisi';
            } else {
                if($stmt->execute()){
                    $register_message = 'Register Berhasil, Silahkan Login';
                } else {
                    $register_message = 'Register Gagal, Silahkan Coba Lagi';
                }
            }
        }
    }
    $_SESSION["register_message"] = $register_message;
}

    function stockBarang($koneksi){
        $stockBarang_Message = "";

        // Jika User Mengclick tombol Tambah Stock Maka lakukan sesuatu di dalam kode nya
        if(isset($_POST['submit'])){
            $namaBarang = $_POST['namabarang'];
            $deskripsiBarang = $_POST['deskripsibarang'];
            $stockBarang = $_POST['stockbarang'];
            
            // Validasi Jika user tidak mengisi form tapi melakukan submit
            if(empty($namaBarang) || empty($deskripsiBarang) ||empty($stockBarang)){
                $stockBarang_Message = "Anda Belum Mengisi form";
            }else{
                $sql_input_barang = "INSERT INTO stock (nama_barang, deskripsi,stock)
                        VALUES ('$namaBarang', '$deskripsiBarang', '$stockBarang')";
                if($koneksi->query($sql_input_barang)){
                    $stockBarang_Message = "Data Stock Barang Telah Ditambahkan";
                }else{
                    $stockBarang_Message = "Data Stock Barang Gagal Ditambahkan";
                }
            }
        }
        $_SESSION["register_message"] = $stockBarang_Message;
    }

   
?>