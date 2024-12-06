<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Building;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = DB::table('announcements')->orderByDesc('id')->get();

        // Decode the JSON
        foreach ($announcements as $announcement) {
            $announcement->target = json_decode($announcement->target, true);
        }

        $departments = Department::latest()->get(['id', 'name']);
        $buildings = Building::latest()->get(['id', 'name']);
        $subjects = Subject::latest()->get(['id', 'name']);
        return view('admin.pages.announcement', compact([
            'departments',
            'buildings',
            'subjects',
            'announcements',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $targets = $request->input('targets');
        $description = $request->input('description');

        DB::table('announcements')->insert([
            'title' => $title,
            'description' => $description,
            'target' => json_encode($targets)
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $title = $request->input('title');
        $targets = $request->input('targets');
        $description = $request->input('description');

        DB::table('announcements')->where('id', $id)->update([
            'title' => $title,
            'description' => $description,
            'target' => json_encode($targets)
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // delete the announcements
        DB::table('announcements')->where('id', $id)->delete();
        return back();
    }

    //show to prog head
    public function showAnnouncementToProgramHead()
    {
        $userDepartment = session('userDepartment');

        $announcements = DB::table('announcements')
            ->whereJsonContains('target', $userDepartment)
            ->orWhereJsonContains('target', 'all')
            ->orderByDesc('id')
            ->get();

        foreach ($announcements as $announcement) {
            $announcement->target = json_decode($announcement->target, true);
        }
        return view('programhead.pages.announcement', compact('announcements'));
    }
}
