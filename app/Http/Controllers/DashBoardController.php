<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DashBoardController extends Controller
{

    function DashboardPage(): View {
        return view('pages.dashboard.dashboard-page');
    }

}
