$base  = "http://localhost:8386/api"
$ct    = @{ "Content-Type" = "application/json" }
$pass  = 0; $fail = 0

function Check($label, $expect, $got) {
    if ($got -eq $expect) { Write-Host "  OK  $label" -ForegroundColor Green; $script:pass++ }
    else { Write-Host "  FAIL $label (expect=$expect got=$got)" -ForegroundColor Red; $script:fail++ }
}

Write-Host ""
Write-Host "===== WebBanXe API - Cart and Order Test =====" -ForegroundColor Cyan

# 1. Login
$loginBody = '{"username":"admin","password":"password"}'
$token = (Invoke-RestMethod "$base/auth/login" -Method POST -Headers $ct -Body $loginBody).token
Check "Login OK" $true (!!$token)
$auth = @{ "Content-Type"="application/json"; "Authorization"="Bearer $token" }

# 2. Cart - xem gio hang
$r = Invoke-RestMethod "$base/cart" -Method GET -Headers @{ "Authorization"="Bearer $token" }
Check "GET /cart OK" $true $r.success

# 3. Cart - them san pham 2 (ID dau tien trong DB)
$addBody = '{"product_id":2}'
$r2 = Invoke-RestMethod "$base/cart" -Method POST -Headers $auth -Body $addBody
Check "POST /cart (them p1) OK" $true $r2.success

# 4. Cart - xem lai
$r3 = Invoke-RestMethod "$base/cart" -Method GET -Headers @{ "Authorization"="Bearer $token" }
Check "Cart count >= 1" $true ($r3.count -ge 1)
Check "Co deposit (coc 5%)" $true ($r3.deposit -gt 0)

# 5. Order - checkout cash
$checkoutBody = '{"payment_method":"cash","appointment_date":"2030-12-31"}'
$r4 = Invoke-RestMethod "$base/order/checkout" -Method POST -Headers $auth -Body $checkoutBody
Check "POST /order/checkout OK" $true $r4.success
Check "payment_status=completed (cash)" "completed" $r4.payment_status
$oid = $r4.order_id
Write-Host "    order_id = $oid" -ForegroundColor DarkGray

# 6. Order - xem don cua toi
$r5 = Invoke-RestMethod "$base/order" -Method GET -Headers @{ "Authorization"="Bearer $token" }
Check "GET /order (my orders) OK" $true $r5.success
Check "count >= 1" $true ($r5.count -ge 1)

# 7. Order - chi tiet
$r6 = Invoke-RestMethod "$base/order/$oid" -Method GET -Headers @{ "Authorization"="Bearer $token" }
Check "GET /order/$oid detail OK" $true $r6.success
Check "Chi tiet co items" $true ($null -ne $r6.data.items)

# 8. Admin - xem tat ca don hang
$r7 = Invoke-RestMethod "$base/order/all" -Method GET -Headers @{ "Authorization"="Bearer $token" }
Check "GET /order/all (admin) OK" $true $r7.success
Check "Total >= 1" $true ($r7.total -ge 1)

# 9. Admin - cap nhat trang thai
$statusBody = '{"status":"confirmed"}'
$r8 = Invoke-RestMethod "$base/order/$oid/status" -Method PUT -Headers $auth -Body $statusBody
Check "PUT /order/$oid/status OK" $true $r8.success

Write-Host ""
$color = if($fail -eq 0){"Green"}else{"Red"}
Write-Host "===== Ket qua: $pass/$($pass+$fail) PASSED =====" -ForegroundColor $color
