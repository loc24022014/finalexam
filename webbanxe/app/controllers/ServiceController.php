<?php
require_once 'app/helpers/SessionHelper.php';
require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';

class ServiceController {
    public function __construct() {
        SessionHelper::start();
    }

    public function index() {
        $pageTitle = 'Dịch Vụ & Hỗ Trợ - Loc_Car_1273';
        include 'app/views/service/index.php';
    }
}
