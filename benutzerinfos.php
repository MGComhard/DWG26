
<?php
include 'header.php';

try {
    $datumZeit = date('d.m.Y H:i:s');
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $ipAdresse = $_SERVER['REMOTE_ADDR'];
    $browserInfo = get_browser(null, true);
    $browserName = $browserInfo['browser'] ?? 'Unbekannt';
    $osName = $browserInfo['platform'] ?? 'Unbekannt';
    $osVersion = $browserInfo['platform_version'] ?? 'Unbekannt';
    $bitVersion = $browserInfo['bit'] ?? 'Unbekannt';

    echo "<div class='info'><b>Datum und Uhrzeit:</b> <span id='liveClock'></span></div>";
    echo "<div class='info'><b>Vollständige URL:</b> $url</div>";
    echo "<div class='info'><b>IP-Adresse:</b> $ipAdresse</div>";
    echo "<div class='info'><b>Browser:</b> <span id='browserInfo'></span></div>";
    echo "<div class='info'><b>Betriebssystem:</b> <span id='platformInfo'></span></div>";
    echo "<div class='info'><b>Bit-Version:</b> <span id='bitInfo'></span></div>";

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

    $osKey = '';
    if (stripos($osName, 'win') !== false) {
        $osKey = 'Windows';
    } elseif (stripos($osName, 'linux') !== false) {
        $osKey = 'Linux';
    } elseif (stripos($osName, 'mac') !== false || stripos($osName, 'os x') !== false) {
        $osKey = 'MacOS';
    }

    $bitKey = ($bitVersion == '64') ? '64' : '32';

    if ($osKey && isset($downloadLinks[$osKey][$bitKey])) {
        $downloadUrl = $downloadLinks[$osKey][$bitKey];
        echo "<a href='$downloadUrl'><button class='button'>Download für $osKey $bitKey-bit</button></a>";
    } else {
        echo "<button class='button' disabled>Kein passender Download verfügbar</button>";
    }

} catch (Exception $e) {
    echo "<p>
    Fehler beim Auslesen der Benutzerinformationen: " . htmlspecialchars($e->getMessage()) . "
    </p>";
}
?>

<script>
    document.getElementById('browserInfo').innerText = navigator.userAgent;
    document.getElementById('platformInfo').innerText = navigator.platform;
    document.getElementById('bitInfo').innerText = navigator.userAgent.includes("x64") ? "64-bit" : "32-bit";
</script>

<?php
include 'footer.php';
?>
