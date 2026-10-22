# Sube archivos al servidor FTP (respaldo manual del deploy de GitHub Actions).
# Uso:
#   .\scripts\ftp-upload.ps1 -Lista archivos.txt -Remoto /paginas     # rutas relativas, una por linea
#   .\scripts\ftp-upload.ps1 -Archivo build\config-servidor.php -Destino /privado/config.php
# Los binarios (imagenes, PDF, audio, fuentes) se omiten si en el servidor ya existen con el mismo tamano.
param(
    [string]$Lista,
    [string]$Remoto = "/paginas",
    [string]$Archivo,
    [string]$Destino
)

$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $PSScriptRoot
$cfg = @{}
Get-Content (Join-Path $root ".ftp.env") | Where-Object { $_ -match "^\s*([^#=]+)=(.*)$" } | ForEach-Object {
    $cfg[$Matches[1].Trim()] = $Matches[2].Trim()
}
$cred = New-Object System.Net.NetworkCredential($cfg.FTP_USER, $cfg.FTP_PASS)
$base = "ftp://$($cfg.FTP_HOST)"
$binarios = '\.(png|jpe?g|gif|webp|ico|pdf|m4a|mp3|mp4|woff2?|ttf|eot|psd)$'

function New-FtpRequest([string]$path, [string]$method) {
    $uri = $base + (($path -split '/' | ForEach-Object { [Uri]::EscapeDataString($_) }) -join '/')
    $req = [System.Net.FtpWebRequest]::Create($uri)
    $req.Credentials = $cred
    $req.Method = $method
    $req.UsePassive = $true
    $req.UseBinary = $true
    $req.KeepAlive = $false
    $req.Timeout = 60000
    $req
}

$creadas = @{}
function Confirm-FtpDir([string]$dir) {
    if ($dir -eq '' -or $dir -eq '/' -or $creadas.ContainsKey($dir)) { return }
    Confirm-FtpDir (($dir -replace '/[^/]+$', ''))
    try { (New-FtpRequest $dir ([System.Net.WebRequestMethods+Ftp]::MakeDirectory)).GetResponse().Close() } catch { }  # ya existe
    $creadas[$dir] = $true
}

function Get-FtpSize([string]$path) {
    try {
        $res = (New-FtpRequest $path ([System.Net.WebRequestMethods+Ftp]::GetFileSize)).GetResponse()
        $n = $res.ContentLength; $res.Close(); return $n
    } catch { return -1 }
}

function Send-FtpFile([string]$local, [string]$remote) {
    for ($intento = 1; $intento -le 4; $intento++) {
        try {
            $req = New-FtpRequest $remote ([System.Net.WebRequestMethods+Ftp]::UploadFile)
            $bytes = [System.IO.File]::ReadAllBytes($local)
            $req.ContentLength = $bytes.Length
            $s = $req.GetRequestStream(); $s.Write($bytes, 0, $bytes.Length); $s.Close()
            $req.GetResponse().Close()
            return
        } catch {
            if ($intento -eq 4) { throw "Fallo al subir ${remote}: $_" }
            Start-Sleep -Seconds (2 * $intento)
        }
    }
}

if ($Archivo) {
    Confirm-FtpDir ($Destino -replace '/[^/]+$', '')
    Send-FtpFile (Resolve-Path $Archivo).Path $Destino
    "Subido $Archivo -> $Destino"
    exit
}

$archivos = Get-Content -Encoding UTF8 $Lista | Where-Object { $_ }
$subidos = 0; $omitidos = 0
foreach ($rel in $archivos) {
    $local = Join-Path $root $rel
    $remote = "$Remoto/$rel"
    Confirm-FtpDir ($remote -replace '/[^/]+$', '')
    if ($rel -match $binarios -and (Get-FtpSize $remote) -eq (Get-Item -LiteralPath $local).Length) { $omitidos++; continue }
    Send-FtpFile $local $remote
    $subidos++
    if ($subidos % 25 -eq 0) { "  $subidos subidos..." }
}
"Listo: $subidos subidos, $omitidos sin cambios"
