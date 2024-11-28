<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $reportData = new Report();
        $reportData->soLuongTruyCap = 10012;
        $reportData->soNguoiBan = 20;
        $reportData->soNguoiBanMoi = 5;
        $reportData->tongSoSanPham = 20000;
        $reportData->soSanPhamMoi = 1200;
        $reportData->soLuongGiaoDich = 200;
        $reportData->tongSoDonHangThanhCong = 150;
        $reportData->tongSoDonHangKhongThanhCong = 50;
        $reportData->tongGiaTriGiaoDich = 1500000000;
        return response()->json($reportData, 200);

    }
}
