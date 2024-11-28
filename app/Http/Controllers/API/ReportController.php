<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\GoogleAnalyticsService;

class ReportController extends Controller
{

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(Request $request)
    {
        $bctUser = env('BO_CONG_THUONG_ACCOUNT_USER', '');
        $bctPassword = env('BO_CONG_THUONG_ACCOUNT_PASSWORD', '');
        $userName = $request->input('UserName');
        $password = $request->input('Password');
    
        if ($userName !== $bctUser || $password !== $bctPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        $analyticsData  = $this->analyticsService->index();
        $reportData = new Report();
        $reportData->soLuongTruyCap = $analyticsData['totalScreenPageViews'];
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
