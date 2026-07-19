<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:activity_logs_read']);
    }
    public function index()
    {
        return view('dashboard.activity_logs.index')->withActivityLogs(Activity::orderBy('created_at', 'desc')->get());
    }
}
