<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\CalendarDay;

class CalendarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'dates' => 'required|array|min:1',
        ]);

        foreach ($request->dates as $date) {
            CalendarDay::updateOrCreate(
                ['clinic_id' => $request->clinic_id, 'date' => $date],
                ['is_active' => true]
            );
        }

        return response()->json(['success' => true, 'message' => 'Calendar days saved successfully!']);
    }

    public function fetchDays(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'clinic_id' => 'required|integer',
        ]);
    
        $days = CalendarDay::with('clinic:id,name,color')
            ->whereBetween('date', [$request->start, $request->end])
            ->where('clinic_id', $request->clinic_id)
            ->companyOnly()
            ->get();
    
        return response()->json(
            $days->map(function ($day) {
                return [
                    'start' => $day->date,
                    'end' => $day->date,
                    'display' => 'background',
                    'backgroundColor' => 'transparent', // or $day->clinic->color
                    'borderColor' => $day->clinic->color ?? '#3aa757',
                    'title' => '',
                ];
            })
        );
    }
    



}