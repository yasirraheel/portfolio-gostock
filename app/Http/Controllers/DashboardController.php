<?php

namespace App\Http\Controllers;

use App\Helper;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

  // Dashboard
  public function dashboard()
  {
    // Portfolio metrics instead of stock photo sales
    $totalPortfolioItems = 0; // Will be updated when portfolio functionality is added
    $totalViews = 0; // Portfolio views
    $totalLikes = auth()->user()->likes()->count();

    // Chart data for portfolio activity (likes, views, etc.) over last 30 days
    $monthsData = [];
    $portfolioActivitySum = [];
    $dailyViewsData = [];

    for ($i = 0; $i <= 30; ++$i) {
      $date = date('Y-m-d', strtotime('-' . $i . ' day'));

      // Portfolio activity last 30 days (placeholder for now)
      $dailyActivity = 0; // This can be likes, comments, or views per day
      $dailyViews = 0; // Daily portfolio views

      // Format Date on Chart
      $formatDate = Helper::formatDateChart($date);
      $monthsData[] =  "'$formatDate'";

      // Daily activity
      $portfolioActivitySum[] = $dailyActivity;
      $dailyViewsData[] = $dailyViews;
    }

    // Portfolio activity stats (replacing earnings with portfolio metrics)
    $stat_activity_today = 0;
    $stat_activity_yesterday = 0;
    $stat_activity_week = 0;
    $stat_activity_last_week = 0;
    $stat_activity_month = 0;
    $stat_activity_last_month = 0;

    $label = implode(',', array_reverse($monthsData));
    $data = implode(',', array_reverse($portfolioActivitySum));
    $datalastSales = implode(',', array_reverse($dailyViewsData));

    $photosPending = 0; // No pending photos in portfolio
    $totalImages = $totalPortfolioItems;
    $totalSales = 0; // No sales in portfolio

    return view('dashboard.dashboard', [
      'earningNetUser' => 0, // No earnings in portfolio
      'label' => $label,
      'data' => $data,
      'datalastSales' => $datalastSales,
      'photosPending' => $photosPending,
      'totalImages' => $totalImages,
      'totalSales' => $totalSales,
      'stat_revenue_today' => $stat_activity_today,
      'stat_revenue_yesterday' => $stat_activity_yesterday,
      'stat_revenue_week' => $stat_activity_week,
      'stat_revenue_last_week' => $stat_activity_last_week,
      'stat_revenue_month' => $stat_activity_month,
      'stat_revenue_last_month' => $stat_activity_last_month
    ]);
  } //<--- End Method

}
