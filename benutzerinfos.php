<?php
date_default_timezone_set('Europe/Berlin');
include 'header.php';

try {
    $datumZeit = date('d.m.Y H:i:s');
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $ipAdresse = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $browserName = 'Unbekannt';
    $osName = 'Unbekannt';
    $bitVersion = 'Unbekannt';

    if (function_exists('get_browser')) {
        $browserInfo = @get_browser(null, true);
        if ($browserInfo && is_array($browserInfo)) {
            $browserName = $browserInfo['browser'] ?? $browserName;
            $osName = $browserInfo['platform'] ?? $osName;
            $bitVersion = $browserInfo['bit'] ?? $bitVersion;
        } else {
            $browserName = 'get_browser nicht verfügbar oder fehlerhaft';
        }
    } else {
        $browserName = 'get_browser() nicht aktiviert';
    }

    if ($osName === 'Unbekannt') {
        if (stripos($userAgent, 'Windows') !== false) {
            $osName = 'Windows';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $osName = 'Linux';
        } elseif (stripos($userAgent, 'Mac') !== false || stripos($userAgent, 'OS X') !== false) {
            $osName = 'MacOS';
        }
    }

    if ($bitVersion === 'Unbekannt') {
        if (stripos($userAgent, 'x64') !== false || stripos($userAgent, 'WOW64') !== false) {
            $bitVersion = '64';
        } else {
            $bitVersion = '32';
        }
    }

    if ($browserName === 'Unbekannt' || stripos($browserName, 'get_browser') !== false) {
        if (stripos($userAgent, 'Edg') !== false) {
            $browserName = 'Microsoft Edge';
        } elseif (stripos($userAgent, 'OPR') !== false || stripos($userAgent, 'Opera') !== false) {
            $browserName = 'Opera';
        } elseif (stripos($userAgent, 'Chrome') !== false) {
            $browserName = 'Google Chrome';
        } elseif (stripos($userAgent, 'Firefox') !== false) {
            $browserName = 'Mozilla Firefox';
        } elseif (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) {
            $browserName = 'Safari';
        }
    }

    echo "<div class='info'><b>Datum und Uhrzeit:</b> $datumZeit</div>";
    echo "<div class='info'><b>Vollständige URL:</b> $url</div>";
    echo "<div class='info'><b>IP-Adresse:</b> $ipAdresse</div>";
    echo "<div class='info'><b>Browser:</b> $browserName</div>";
    echo "<div class='info'><b>Betriebssystem:</b> $osName</div>";
    echo "<div class='info'><b>Bit-Version:</b> $bitVersion</div>";

    $downloadLinks = [
        'Windows' => [
            '32' => 'downloads/software_windows_32bit.exe',
            '64' => 'downloads/software_windows_64bit.exe'
        ],
        'Linux' => [
            '32' => 'downloads/software_linux_32bit.deb',
            '64' => 'downloads/software_linux_64bit.deb'
        ],
        'MacOS' => [
            '64' => 'downloads/software_macos_64bit.dmg'
        ]
    ];

    $osKey = in_array($osName, ['Windows', 'Linux', 'MacOS']) ? $osName : '';
    $bitKey = ($bitVersion == '64') ? '64' : '32';
    if ($osKey && isset($downloadLinks[$osKey][$bitKey])) {
        $downloadUrl = $downloadLinks[$osKey][$bitKey];
        echo "<a href='$downloadUrl'><button class='button'>Download für $osKey $bitKey-bit</button></a>";
    } else {
        echo "<button class='button' disabled>Kein passender Download verfügbar</button>";
    }

} catch (Exception $e) {
    echo "<p>
    Fehler beim Auslesen der Benutzerinformationen: " . htmlspecialchars($e->getMessage()) . 
    "</p>";
}

include 'footer.php';
?>