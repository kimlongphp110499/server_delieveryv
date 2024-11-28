<?php

namespace App\Services;

use Illuminate\Http\Request;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class GoogleAnalyticsService
{
    public function index()
    {
        try {
            $startDate = Carbon::createFromFormat('Y-m-d', '2022-01-01');
            $endDate = Carbon::now();

            $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(
                Period::create($startDate, $endDate)
            );
            $totalActiveUsers = collect($analyticsData)->sum('activeUsers');
            $totalScreenPageViews = collect($analyticsData)->sum('screenPageViews');

            return compact('totalActiveUsers', 'totalScreenPageViews');
        } catch (\Exception $e) {
            return $e;
        }
    }
}
