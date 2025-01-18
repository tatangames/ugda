<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function vistaEjemplo()
    {
        return view('backend.admin.dashboard.vistadashboard');
    }
}
