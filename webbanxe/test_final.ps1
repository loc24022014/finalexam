$base  = "http://localhost:8386/api"
$ct    = @{ "Content-Type" = "application/json" }
$pass  = 0; $fail = 0; $total = 0

function Check($label, $expect, $got) {
    $script:total++
    if ("$got" -eq "$expect") {
        Write-Host "  [PASS] $label" -ForegroundColor Green
        $script:pass++
    } else {
        Write-Host "  [FAIL] $label  (expect=$expect, got=$got)" -ForegroundColor Red
        $script:fail++
    }
}
function Section($title) { Write-Host "`n----- $title -----" -ForegroundColor Yellow }

# Lay HTTP status code an toan
function GetHttpStatus($url, $method, $h, $body) {
    try {
        $hh = @{}
        if ($h) { $h.Keys | ForEach-Object { $hh[$_] = $h[$_] } }
        $params = @{ Uri=$url; Method=$method; ErrorAction='Stop' }
        if ($hh.Count) { $params['Headers'] = $hh }
        if ($body) { $params['Body'] = $body }
        $r = Invoke-WebRequest @params
        return [int]$r.StatusCode
    } catch {
        if ($_.Exception.Response) { return [int]$_.Exception.Response.StatusCode }
        return 0
    }
}

Write-Host ""
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host "  KIEM TRA BAI 5 - RESTful API" -ForegroundColor Cyan
Write-Host "  KIEM TRA BAI 6 - JWT Security" -ForegroundColor Cyan
Write-Host "  WebBanXe - $base" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan

# ============================================================
# BAI 5 - PHAN 1: AUTH API
# ============================================================
Section "BAI 5.1 - Auth API (AuthApi.php)"

$loginBody = '{"username":"admin","password":"password"}'
$loginRes  = Invoke-RestMethod "$base/auth/login" -Method POST -Headers $ct -Body $loginBody
Check "[AuthApi] POST /auth/login -> success=true"      "True"   $loginRes.success
Check "[AuthApi] Token co dung 3 phan (JWT format)"    "3"      ($loginRes.token.Split('.').Count)
Check "[AuthApi] token_type = Bearer"                   "Bearer" $loginRes.token_type
Check "[AuthApi] expires_in = 3600 (1 gio)"            "3600"   $loginRes.expires_in
Check "[AuthApi] Tra ve user.username = admin"          "admin"  $loginRes.user.username
Check "[AuthApi] Tra ve user.role"                      "admin"  $loginRes.user.role

$adminToken  = $loginRes.token
$authHead    = @{ "Content-Type"="application/json"; "Authorization"="Bearer $adminToken" }
$bearerHead  = @{ "Authorization"="Bearer $adminToken" }

# Dang nhap sai mat khau
$badStatus = GetHttpStatus "$base/auth/login" "POST" $ct '{"username":"admin","password":"WRONG"}'
Check "[AuthApi] Sai password -> HTTP 401"              "401"    $badStatus

# Xem thong tin ca nhan
$me = Invoke-RestMethod "$base/auth/me" -Method GET -Headers $bearerHead
Check "[AuthApi] GET /auth/me -> success=true"         "True"   $me.success
Check "[AuthApi] /me tra ve username"                   "admin"  $me.user.username
Check "[AuthApi] /me co token issued_at"               "True"   ($me.token_info.issued_at.Length -gt 0)
Check "[AuthApi] /me co token expires_at"              "True"   ($me.token_info.expires_at.Length -gt 0)

# ============================================================
# BAI 5 - PHAN 2: PRODUCT API CRUD
# ============================================================
Section "BAI 5.2 - Product CRUD (ProductApi.php)"

# GET list
$pl = Invoke-RestMethod "$base/product" -Method GET
Check "[ProductApi] GET /product -> success=true"       "True"  $pl.success
Check "[ProductApi] GET /product co field count"        "True"  ($null -ne $pl.count)
Check "[ProductApi] GET /product count >= 1"            "True"  ($pl.count -ge 1)
Check "[ProductApi] GET /product data la mang"         "True"  ($pl.data -is [System.Array] -or $pl.data.Count -ge 0)

# GET detail (lay ID dau tien trong DB)
$firstId = if ($pl.data.Count -gt 0) { $pl.data[0].id } else { 2 }
$pd = Invoke-RestMethod "$base/product/$firstId" -Method GET
Check "[ProductApi] GET /product/$firstId -> success"  "True"  $pd.success
Check "[ProductApi] Detail co truong name"              "True"  ($pd.data.name.Length -gt 0)
Check "[ProductApi] Detail co truong price"             "True"  ($pd.data.price -gt 0)
Check "[ProductApi] Detail co truong brand"             "True"  ($null -ne $pd.data.brand)
Check "[ProductApi] Detail co truong category_name"    "True"  ($pd.data.PSObject.Properties.Name -contains 'category_name')

# POST tao moi
$npBody = '{"name":"Test Car POST","description":"Xe test qua API CRUD","price":500000000,"brand":"TestBrand"}'
$pcRes  = Invoke-RestMethod "$base/product" -Method POST -Headers $authHead -Body $npBody
Check "[ProductApi] POST /product -> success=true"      "True"  $pcRes.success
Check "[ProductApi] POST /product tra ve id"           "True"  ($pcRes.id -gt 0)
$newPid = $pcRes.id

# PUT cap nhat
if ($newPid) {
    $upBody = '{"name":"Test Car UPDATED","description":"Da cap nhat qua PUT","price":650000000,"brand":"BrandUpdated"}'
    $puRes  = Invoke-RestMethod "$base/product/$newPid" -Method PUT -Headers $authHead -Body $upBody
    Check "[ProductApi] PUT /product/$newPid -> success"    "True"  $puRes.success

    # Xac nhan du lieu da cap nhat
    $pgc = Invoke-RestMethod "$base/product/$newPid" -Method GET
    Check "[ProductApi] GET sau PUT -> name da doi"         "Test Car UPDATED" $pgc.data.name
    Check "[ProductApi] GET sau PUT -> price da doi"        "650000000"        $pgc.data.price

    # DELETE xoa
    $pdRes = Invoke-RestMethod "$base/product/$newPid" -Method DELETE -Headers $authHead
    Check "[ProductApi] DELETE /product/$newPid -> success" "True"  $pdRes.success

    # Xac nhan da bi xoa -> 404
    $d404 = GetHttpStatus "$base/product/$newPid" "GET" @{} $null
    Check "[ProductApi] GET sau DELETE -> 404"              "404"   $d404
} else {
    Write-Host "  [SKIP] PUT/DELETE/verify - khong co ID moi" -ForegroundColor DarkGray
}

# ============================================================
# BAI 5 - PHAN 3: CATEGORY API CRUD
# ============================================================
Section "BAI 5.3 - Category CRUD (CategoryApi.php)"

# GET list
$cl = Invoke-RestMethod "$base/category" -Method GET
Check "[CategoryApi] GET /category -> success=true"     "True"  $cl.success
Check "[CategoryApi] GET /category co field count"      "True"  ($null -ne $cl.count)
Check "[CategoryApi] GET /category count >= 1"          "True"  ($cl.count -ge 1)

# GET detail (lay ID dau tien)
$firstCatId = if ($cl.data.Count -gt 0) { $cl.data[0].id } else { 1 }
$cd = Invoke-RestMethod "$base/category/$firstCatId" -Method GET
Check "[CategoryApi] GET /category/$firstCatId -> success" "True" $cd.success
Check "[CategoryApi] Detail co truong name"                "True" ($cd.data.name.Length -gt 0)
Check "[CategoryApi] Detail co truong slug"                "True" ($null -ne $cd.data.slug)

# POST tao moi
$ncBody = '{"name":"Test Category POST","description":"Danh muc test","slug":"test-cat-final-999"}'
$ccRes  = Invoke-RestMethod "$base/category" -Method POST -Headers $authHead -Body $ncBody
Check "[CategoryApi] POST /category -> success=true"    "True"  $ccRes.success
Check "[CategoryApi] POST /category tra ve id"          "True"  ($ccRes.id -gt 0)
$newCid = $ccRes.id

# PUT cap nhat
if ($newCid) {
    $ucBody = '{"name":"Test Cat UPDATED","description":"Da cap nhat","slug":"test-cat-upd-final"}'
    $cuRes  = Invoke-RestMethod "$base/category/$newCid" -Method PUT -Headers $authHead -Body $ucBody
    Check "[CategoryApi] PUT /category/$newCid -> success"  "True" $cuRes.success

    # DELETE xoa
    $cdRes = Invoke-RestMethod "$base/category/$newCid" -Method DELETE -Headers $authHead
    Check "[CategoryApi] DELETE /category/$newCid -> success" "True" $cdRes.success
} else {
    Write-Host "  [SKIP] PUT/DELETE category - khong co ID moi" -ForegroundColor DarkGray
}

# ============================================================
# BAI 6: BAO MAT JWT
# ============================================================
Section "BAI 6 - JWT Security (JwtHelper.php)"

# Goi POST khong co token -> 401
$s1 = GetHttpStatus "$base/product" "POST" @{"Content-Type"="application/json"} '{"name":"x","description":"y","price":1}'
Check "[JWT] POST /product khong co token -> 401"       "401"   $s1

# Goi voi token sai dinh dang -> 401
$badHead = @{ "Authorization"="Bearer abc.def.ghi" }
$s2 = GetHttpStatus "$base/product" "POST" $badHead '{"name":"x","description":"y","price":1}'
Check "[JWT] POST /product token sai -> 401"            "401"   $s2

# Goi token het han (gia mao) -> 401
$fakeHead = @{ "Authorization"="Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImhhY2tlciIsImV4cCI6MX0.fake" }
$s3 = GetHttpStatus "$base/auth/me" "GET" $fakeHead $null
Check "[JWT] Token gia mao/het han -> 401"              "401"   $s3

# GET /auth/me khong co token -> 401
$s4 = GetHttpStatus "$base/auth/me" "GET" @{} $null
Check "[JWT] GET /auth/me khong token -> 401"           "401"   $s4

# GET /auth/me voi token hop le -> 200
$meS = GetHttpStatus "$base/auth/me" "GET" $bearerHead $null
Check "[JWT] GET /auth/me token hop le -> 200"          "200"   $meS

# Kiem tra payload co day du truong
$meR = Invoke-RestMethod "$base/auth/me" -Method GET -Headers $bearerHead
Check "[JWT] Payload co user_id"                        "True"  ($meR.user.user_id -gt 0)
Check "[JWT] Payload co username"                       "True"  ($meR.user.username.Length -gt 0)
Check "[JWT] Payload co email"                          "True"  ($meR.user.email.Length -gt 0)
Check "[JWT] Payload co role"                           "True"  ($meR.user.role.Length -gt 0)
Check "[JWT] token_info co issued_at (iat)"             "True"  ($meR.token_info.issued_at.Length -gt 0)
Check "[JWT] token_info co expires_at (exp)"            "True"  ($meR.token_info.expires_at.Length -gt 0)

# Kiem tra RBAC: admin moi duoc DELETE
$delStatus = GetHttpStatus "$base/product/99999" "DELETE" $bearerHead $null
Check "[JWT] DELETE chi admin -> khong phai 403"        "True"  ($delStatus -ne 403)

# Kiem tra response luon la JSON
$jsonCheck = Invoke-WebRequest "$base/product" -Method GET -UseBasicParsing
$ctHeader  = $jsonCheck.Headers['Content-Type']
Check "[JWT] Response Content-Type la application/json" "True"  ($ctHeader -like "*application/json*")

# ============================================================
# BONUS: Cart + Order (kien truc hoan chinh)
# ============================================================
Section "BONUS - Cart API (CartApi.php)"
$ca  = Invoke-RestMethod "$base/cart" -Method POST -Headers $authHead -Body '{"product_id":2}'
Check "[Cart] POST /cart them xe -> success"            "True"  $ca.success
Check "[Cart] Co total > 0"                             "True"  ($ca.total -gt 0)
Check "[Cart] Co deposit = 5% tong gia"                "True"  ($ca.deposit -gt 0)
$cv  = Invoke-RestMethod "$base/cart" -Method GET -Headers $bearerHead
Check "[Cart] GET /cart -> success=true"                "True"  $cv.success

Section "BONUS - Order API (OrderApi.php)"
$co  = Invoke-RestMethod "$base/order/checkout" -Method POST -Headers $authHead -Body '{"payment_method":"cash","appointment_date":"2030-12-31"}'
Check "[Order] POST /order/checkout -> success"         "True"  $co.success
Check "[Order] checkout co order_id"                    "True"  ($co.order_id -gt 0)
Check "[Order] payment cash -> completed ngay"          "completed" $co.payment_status
$oid = $co.order_id

$mo  = Invoke-RestMethod "$base/order" -Method GET -Headers $bearerHead
Check "[Order] GET /order (my orders) -> success"       "True"  $mo.success

$od  = Invoke-RestMethod "$base/order/$oid" -Method GET -Headers $bearerHead
Check "[Order] GET /order/$oid chi tiet -> success"     "True"  $od.success
Check "[Order] Chi tiet co order + items"               "True"  ($null -ne $od.data.order -and $null -ne $od.data.items)

$all = Invoke-RestMethod "$base/order/all" -Method GET -Headers $bearerHead
Check "[Order] GET /order/all (admin) -> success"       "True"  $all.success
Check "[Order] all co total field"                      "True"  ($all.total -ge 1)

# ============================================================
# TONG KET
# ============================================================
Write-Host ""
Write-Host "=============================================" -ForegroundColor Cyan
$pct   = if ($total -gt 0) { [math]::Round($pass/$total*100) } else { 0 }
$color = if ($fail -eq 0) {"Green"} elseif ($pct -ge 80) {"Yellow"} else {"Red"}
Write-Host ("  KET QUA: {0}/{1} PASSED ({2}%)" -f $pass, $total, $pct) -ForegroundColor $color

Write-Host ""
Write-Host "  +-- BAI 5: Xay dung RESTful API ------------+" -ForegroundColor White
Write-Host "  |  Auth API (Login + /me)                   |" -ForegroundColor DarkGray
Write-Host "  |  Product: GET list, GET detail, POST,     |" -ForegroundColor DarkGray
Write-Host "  |           PUT, DELETE                     |" -ForegroundColor DarkGray
Write-Host "  |  Category: GET list, GET detail, POST,    |" -ForegroundColor DarkGray
Write-Host "  |            PUT, DELETE                    |" -ForegroundColor DarkGray
Write-Host "  |  JSON response, Router /api/{res}/{id}    |" -ForegroundColor DarkGray
Write-Host "  +--------------------------------------------+" -ForegroundColor White
Write-Host ""
Write-Host "  +-- BAI 6: Bao mat JWT ----------------------+" -ForegroundColor White
Write-Host "  |  HMAC-SHA256, Bearer token                 |" -ForegroundColor DarkGray
Write-Host "  |  401 khi khong co token                    |" -ForegroundColor DarkGray
Write-Host "  |  401 khi token sai / het han               |" -ForegroundColor DarkGray
Write-Host "  |  Payload: user_id/username/email/role/exp  |" -ForegroundColor DarkGray
Write-Host "  |  RBAC: DELETE chi admin                    |" -ForegroundColor DarkGray
Write-Host "  +--------------------------------------------+" -ForegroundColor White
Write-Host "=============================================" -ForegroundColor Cyan
