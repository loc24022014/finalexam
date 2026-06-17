$base = "http://127.0.0.1:8000/api"
$json = @{ "Content-Type" = "application/json" }
$pass = 0; $fail = 0

function Check($label, $expect, $got) {
    if ($got -eq $expect) {
        Write-Host "  OK  $label" -ForegroundColor Green
        $script:pass++
    } else {
        Write-Host "  FAIL $label (expect=$expect, got=$got)" -ForegroundColor Red
        $script:fail++
    }
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host " WebBanXe Full API Test" -ForegroundColor Cyan
Write-Host "=====================================`n" -ForegroundColor Cyan

# --- Auth ---
Write-Host "[Auth] AuthApi.php" -ForegroundColor Yellow
$r = Invoke-RestMethod "$base/auth/login" -Method POST -Headers $json -Body '{"username":"admin","password":"password"}'
Check "Login success"       $true   $r.success
Check "Has JWT token"       $true   ($r.token.Split('.').Count -eq 3)
$token = $r.token

$r2 = Invoke-RestMethod "$base/auth/me" -Method GET -Headers @{ Authorization = "Bearer $token" }
Check "/me returns user"    $true   $r2.success
Check "/me role=admin"      "admin" $r2.user.role

# --- Product ---
Write-Host "`n[Product] ProductApi.php" -ForegroundColor Yellow
$r3 = Invoke-RestMethod "$base/product" -Method GET
Check "GET list OK"         $true   $r3.success
Check "Has count field"     $true   ($null -ne $r3.count)

$r4 = Invoke-RestMethod "$base/product/1" -Method GET
Check "GET detail id=1"     $true   $r4.success

try {
    Invoke-RestMethod "$base/product" -Method POST -Headers $json -Body '{"name":"x","description":"y","price":1}' | Out-Null
    Check "No-token POST=401" 401 200
} catch {
    Check "No-token POST=401" 401 $_.Exception.Response.StatusCode.value__
}

$nb = '{"name":"Test Car","description":"Test desc","price":9999000,"brand":"Test","category_id":1}'
$r5 = Invoke-RestMethod "$base/product" -Method POST -Headers @{"Content-Type"="application/json"; Authorization="Bearer $token"} -Body $nb
Check "Authed POST=201"     $true   $r5.success

# --- Category ---
Write-Host "`n[Category] CategoryApi.php" -ForegroundColor Yellow
$r6 = Invoke-RestMethod "$base/category" -Method GET
Check "GET list OK"         $true   $r6.success
Check "Has count field"     $true   ($null -ne $r6.count)

$nb2 = '{"name":"Test Category","description":"Test","slug":"test-cat-999"}'
$r7 = Invoke-RestMethod "$base/category" -Method POST -Headers @{"Content-Type"="application/json"; Authorization="Bearer $token"} -Body $nb2
Check "Authed POST=201"     $true   $r7.success

# --- Summary ---
Write-Host "`n=====================================" -ForegroundColor Cyan
$color = if ($fail -eq 0) { "Green" } else { "Red" }
Write-Host " Result: $pass/$($pass+$fail) tests PASSED" -ForegroundColor $color
Write-Host "=====================================`n" -ForegroundColor Cyan
