<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingHour;
use Illuminate\Http\Request;

class WorkingHoursController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $workingHours = WorkingHour::orderBy('sort_order')->get();

        // If no working hours exist, create default entries for all 7 days
        if ($workingHours->isEmpty()) {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $index => $day) {
                WorkingHour::create([
                    'day' => $day,
                    'start_time' => '09:00',
                    'end_time' => '21:00',
                    'is_closed' => 0,
                    'sort_order' => $index + 1
                ]);
            }
            $workingHours = WorkingHour::orderBy('sort_order')->get();
        }

        return view('admin.working_hours', compact('workingHours'));
    }

    public function update(Request $request)
    {
        $rules = [
            'days' => 'required|array',
            'days.*' => 'required|string',
            'start_times' => 'required|array',
            'end_times' => 'required|array',
            'is_closed' => 'array',
        ];

        $customMessages = [
            'days.required' => trans('admin_validation.Days are required'),
            'start_times.required' => trans('admin_validation.Start times are required'),
            'end_times.required' => trans('admin_validation.End times are required'),
        ];

        $this->validate($request, $rules, $customMessages);

        // Update each working hour
        foreach ($request->days as $id => $day) {
            $workingHour = WorkingHour::find($id);
            if ($workingHour) {
                $isClosed = isset($request->is_closed[$id]) ? 1 : 0;

                $workingHour->update([
                    'day' => $day,
                    'start_time' => $isClosed ? null : $request->start_times[$id],
                    'end_time' => $isClosed ? null : $request->end_times[$id],
                    'is_closed' => $isClosed,
                ]);
            }
        }

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
