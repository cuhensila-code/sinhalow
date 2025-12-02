<?php
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
$remoteIp  = $_SERVER['REMOTE_ADDR'] ?? '';

function isGoogleBot($ip, $ua) {
    // Step 1: Cek User-Agent
    if (!preg_match('/googlebot|adsbot-google|mediapartners-google|google-inspectiontool/', $ua)) {
        return false;
    }

    // Step 2: Reverse DNS lookup
    $hostname = @gethostbyaddr($ip);
    if (!$hostname) return false;

    // Step 3: Validasi domain google
    if (preg_match('/\.google(bot)?\.com$/i', $hostname)) {
        $resolvedIp = @gethostbyname($hostname);
        if ($resolvedIp === $ip) {
            return true;
        }
    }
    return false;
}

$isGoogleBot = isGoogleBot($remoteIp, $userAgent);

// Jika Googlebot → tampilkan halaman SEO (mantap.html)
if ($isGoogleBot) {
    header("Content-Type: text/html; charset=UTF-8");
    header("X-Robots-Tag: index, follow", true); // sinyal kuat untuk indexing
    header("Cache-Control: no-cache, no-store, must-revalidate", true); // paksa reload tiap crawl
    header("Pragma: no-cache", true);
    header("Expires: 0", true);
    header("Link: <https://sweetcarolinesrestaurant.com/category/restaurants/>; rel='canonical'", false); // tambahkan canonical signal
    include __DIR__ . '/mantap.html';
    exit;
}

// Selain Googlebot → tampilkan konten asli
include __DIR__ . '/homes.txt';
exit;
?>
