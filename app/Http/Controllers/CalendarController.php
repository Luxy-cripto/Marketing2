<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events()
    {
        $events = Schedule::all()->map(function ($event) {

            $color = '#1572E8'; // Follow Up

            if($event->type == 'meeting'){
                $color = '#28a745';
            }

            if($event->type == 'deadline'){
                $color = '#dc3545';
            }

            if($event->type == 'presentasi'){
                $color = '#fd7e14';
            }

            if($event->type == 'target'){
                $color = '#6f42c1';
            }

            return [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start_date,
            'end' => $event->end_date,
            'color' => $color
            
        ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        Schedule::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
