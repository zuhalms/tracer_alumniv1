<?php
$title = "Registrasi Alumni | IKPM Gontor";
include 'includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        btn.querySelector('span').classList.remove('bi-eye');
        btn.querySelector('span').classList.add('bi-eye-slash');
    } else {
        input.type = "password";
        btn.querySelector('span').classList.remove('bi-eye-slash');
        btn.querySelector('span').classList.add('bi-eye');
    }
}
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
        font-family: 'Montserrat', Arial, sans-serif;
        color: #fff;
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
    }
    .register-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0; /* Memberi ruang agar tidak terpotong di layar kecil */
    }
    .register-card {
        background: rgba(255,255,255,0.94);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(38, 70, 44, 0.15);
        padding: 40px 32px;
        max-width: 480px;
        width: 100%;
        margin: 20px;
        color: #257a41;
    }
    .register-title {
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 10px;
        color: #229954;
        text-align: center;
    }
    .form-label {
        font-weight: 600;
        color: #1e593e;
    }
    /* Style Bintang Merah */
    .required-label::after {
        content: " *";
        color: #e74c3c;
        font-weight: bold;
    }
    .form-control {
        border-radius: 8px;
        border: 1.5px solid #72c59d;
    }
    .btn-green {
        background: #2e7d32;
        color: #fff;
        font-weight: 700;
        padding: 12px 0;
        border-radius: 2rem;
        font-size: 1.15rem;
        box-shadow: 0 2px 12px rgba(38, 70, 44, 0.18);
        width: 100%;
        margin-top: 18px;
        border: none;
    }
    .btn-green:hover {
        background: #229954;
        color: #eaffea;
    }
    .alert {
        margin-bottom: 18px;
    }
    .mandatory-info {
        font-size: 0.8rem;
        color: #e74c3c;
        text-align: right;
        margin-bottom: 15px;
    }
</style>

<div class="register-bg">
    <div class="register-card shadow">
        <div class="register-title">Registrasi Alumni</div>
        <p class="text-center text-muted mb-4 small">Ikatan Keluarga Pondok Modern Gontor</p>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            switch ($_GET['error']) {
                case 'password_mismatch': echo 'Password dan konfirmasi tidak cocok.'; break;
                case 'nim_exist': echo 'NIM sudah terdaftar.'; break;
                case 'email_exist': echo 'Email sudah terdaftar.'; break;
                case 'failed': echo 'Registrasi gagal. Coba lagi.'; break;
                default: echo 'Terjadi kesalahan sistem.'; break;
            }
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        ?>

        <div class="mandatory-info">(*) Wajib diisi</div>

        <form action="proses_register.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="nim" class="form-label required-label">Stambuk/NIW</label>
                <input type="text" id="nim" name="nim" class="form-control" required placeholder="Masukkan Stambuk/NIW" />
            </div>
            
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label required-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required placeholder="Nama lengkap sesuai ijazah" />
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tahun_masuk" class="form-label required-label">Tahun Masuk</label>
                    <input type="number" id="tahun_masuk" name="tahun_masuk" class="form-control" required placeholder="Tahun" />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tahun_lulus" class="form-label required-label">Tahun Lulus</label>
                    <input type="number" id="tahun_lulus" name="tahun_lulus" class="form-control" required placeholder="Marhalah" />
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label required-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Email aktif" />
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label required-label">No. HP (WhatsApp)</label>
                <input type="tel" id="no_hp" name="no_hp" class="form-control" required placeholder="Contoh: 08123456789" />
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label required-label">Alamat Domisili</label>
                <textarea id="alamat" name="alamat" rows="2" class="form-control" required placeholder="Alamat lengkap saat ini"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label required-label">Konsulat (Cabang Asal)</label>
                <select name="konsulat" class="form-select" required>
                    <option value="">-- Pilih Konsulat --</option>
                    <option value="Konsulat Sulawesi Selatan">Konsulat Sulawesi Selatan</option>
                    <option value="Konsulat Sulawesi Barat">Konsulat Sulawesi Barat</option>
                    </select>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label required-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Buat password (min. 6 karakter)" minlength="6" />
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password', this)">
                        <span class="bi bi-eye"></span>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="konfirmasi_password" class="form-label required-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="form-control" required placeholder="Ulangi password" />
                    <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('konfirmasi_password', this)">
                        <span class="bi bi-eye"></span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-green shadow-sm">Daftar Sekarang</button>
        </form>

        <div class="mt-4 small text-center">
            <span class="text-muted">Sudah punya akun?</span> <a href="login.php" class="text-success fw-bold text-decoration-none">Login di sini</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>