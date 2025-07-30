Write-Host "Starting Queue Worker for SIPPPHI..." -ForegroundColor Green
Write-Host ""
Write-Host "This will process pending jobs and send notifications" -ForegroundColor Yellow
Write-Host "Press Ctrl+C to stop" -ForegroundColor Yellow
Write-Host ""

try {
    php artisan queue:work --tries=3 --timeout=60
} catch {
    Write-Host "Queue worker stopped or encountered an error" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

Write-Host ""
Write-Host "Press any key to exit..." -ForegroundColor Cyan
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown") 