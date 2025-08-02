# Start Queue Worker untuk SIPPPHI
Write-Host "🚀 Memulai Queue Worker untuk SIPPPHI..." -ForegroundColor Green

# Set working directory
Set-Location "D:\Herd\sippphi_ta"

# Clear any existing queue worker processes
Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object {$_.ProcessName -eq "php"} | Stop-Process -Force -ErrorAction SilentlyContinue

# Start queue worker in background
Start-Process -FilePath "php" -ArgumentList "artisan", "queue:work", "--timeout=60", "--sleep=3", "--tries=3" -WindowStyle Hidden

Write-Host "✅ Queue Worker berhasil dimulai di background" -ForegroundColor Green
Write-Host "📧 Email notifications akan diproses secara otomatis" -ForegroundColor Yellow
Write-Host "🔄 Untuk menghentikan, jalankan: Get-Process -Name 'php' | Stop-Process" -ForegroundColor Cyan

# Wait a moment and check if it's running
Start-Sleep -Seconds 2
$phpProcesses = Get-Process -Name "php" -ErrorAction SilentlyContinue
if ($phpProcesses) {
    Write-Host "✅ Queue Worker berjalan dengan PID: $($phpProcesses.Id)" -ForegroundColor Green
} else {
    Write-Host "❌ Queue Worker tidak berjalan" -ForegroundColor Red
} 