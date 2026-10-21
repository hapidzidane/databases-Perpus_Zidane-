<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perpustakaan Zidane</title>
    <link rel="stylesheet" href="src/style.css?v=2">
</head>

<body>

    <header class="navbar">

        <div class="logo">
            <span>📚</span>
            <h2>Perpustakaan Zidane</h2>
        </div>

        <nav>
            <a href="buku.php">Buku</a>
            <a href="index.php">Beranda</a>
            <a href="#">Tentang Sistem</a>
            <a href="src/login.php">Login</a>
        </nav>

    </header>

    <main>

        <section class="hero">

            <div class="hero-content">

                <h1 class="subtitle">SISTEM INFORMASI PERPUSTAKAAN</h1>

                <p>
                    Kelola Perpustakaan
                    <br>
                    dengan Lebih Mudah
                </p>

            </div>

        </section>

        <section class="features">

            <div class="section-title">
                <h2></h2>

                <p>
                    Pilih data yang ingin kamu kelola.
                </p>
            </div>


            <div class="feature-container">

                <!-- BUKU -->
                <a href="buku.php" class="feature-card">

                    <div class="icon">
                        📚
                    </div>

                    <div>
                        <h3>Buku</h3>

                        <p>
                            Kelola data buku yang tersedia
                            di perpustakaan.
                        </p>
                    </div>

                    <span class="arrow">→</span>

                </a>

                <a href="anggota.php" class="feature-card">

                    <div class="icon">
                        👥
                    </div>

                    <div>
                        <h3>Anggota</h3>

                        <p>
                            Kelola data anggota
                            perpustakaan.
                        </p>
                    </div>

                    <span class="arrow">→</span>

                </a>

                <a href="peminjaman.php" class="feature-card">

                    <div class="icon">
                        📋
                    </div>

                    <div>
                        <h3>Peminjaman</h3>

                        <p>
                            Kelola transaksi peminjaman
                            dan pengembalian buku.
                        </p>
                    </div>

                    <span class="arrow">→</span>

                </a>

            </div>

        </section>

    </main>

    <footer>

        <p>
            © 2026 Perpustakaan Zidane
        </p>

    </footer>

</body>

</html>