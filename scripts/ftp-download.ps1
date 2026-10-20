# Descarga recursiva del sitio desde el servidor FTP.
# Uso:
#   .\scripts\ftp-download.ps1 -List                 # solo lista la raiz del FTP
#   .\scripts\ftp-download.ps1 -Remote /public_html -Local .\site
param(
    [string]$Remote = "/",
    [string]$Local = ".\site",
    [switch]$List
)

$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $PSScriptRoot
$envFile = Join-Path $root ".ftp.env"
$cfg = @{}
Get-Content $envFile | Where-Object { $_ -match "^\s*([^#=]+)=(.*)$" } | ForEach-Object {
    $cfg[$Matches[1].Trim()] = $Matches[2].Trim()
}
$cred = New-Object System.Net.NetworkCredential($cfg.FTP_USER, $cfg.FTP_PASS)
$base = "ftp://$($cfg.FTP_HOST)"

function New-FtpRequest([string]$path, [string]$method) {
    $uri = $base + ($path -replace " ", "%20")
    $req = [System.Net.FtpWebRequest]::Create($uri)
    $req.Credentials = $cred
    $req.Method = $method
    $req.UsePassive = $true
    $req.UseBinary = $true
    $req.KeepAlive = $false
    $req
}

function Get-FtpEntries([string]$path) {
    $res = (New-FtpRequest $path ([System.Net.WebRequestMethods+Ftp]::ListDirectoryDetails)).GetResponse()
    $reader = New-Object System.IO.StreamReader($res.GetResponseStream())
    $lines = $reader.ReadToEnd() -split "`r?`n" | Where-Object { $_ }
    $reader.Close(); $res.Close()
    foreach ($line in $lines) {
        # Formato Unix: drwxr-xr-x 2 user group 4096 Jan 01 12:00 nombre
        if ($line -match "^([\-dl])\S+\s+\d+\s+\S+\s+\S+\s+(\d+)\s+\w+\s+\d+\s+[\d:]+\s+(.+)$") {
            $name = $Matches[3]
            if ($name -in ".", "..") { continue }
            [pscustomobject]@{ Name = $name; IsDir = ($Matches[1] -eq "d"); Size = [long]$Matches[2] }
        }
    }
}

function Get-FtpTree([string]$remotePath, [string]$localPath) {
    New-Item -ItemType Directory -Force $localPath | Out-Null
    foreach ($e in Get-FtpEntries $remotePath) {
        $r = ($remotePath.TrimEnd("/") + "/" + $e.Name)
        $l = Join-Path $localPath $e.Name
        if ($e.IsDir) {
            Get-FtpTree $r $l
        } elseif ((Test-Path -LiteralPath $l) -and (Get-Item -LiteralPath $l).Length -eq $e.Size) {
            continue  # ya descargado
        } else {
            Write-Host "  $r"
            for ($try = 1; ; $try++) {
                try {
                    $res = (New-FtpRequest $r ([System.Net.WebRequestMethods+Ftp]::DownloadFile)).GetResponse()
                    $fs = [System.IO.File]::Create($l)
                    try { $res.GetResponseStream().CopyTo($fs) } finally { $fs.Close(); $res.Close() }
                    break
                } catch {
                    if ($try -ge 5) { throw }
                    Write-Host "    reintento $try..."
                    Start-Sleep -Seconds (3 * $try)
                }
            }
        }
    }
}

if ($List) {
    Get-FtpEntries $Remote | ForEach-Object { "{0}  {1}" -f ($(if ($_.IsDir) { "[DIR]" } else { "     " })), $_.Name }
} else {
    Get-FtpTree $Remote (New-Item -ItemType Directory -Force $Local).FullName
    Write-Host "Descarga completa en $Local"
}
