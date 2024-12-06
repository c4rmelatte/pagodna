<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Support\Facades\Redirect;

class BuildingController extends Controller
{
    // create building

    public function store(Request $request) {

        $buildings = new Building();
        $buildings->name = $request->get('name');
        $buildings->description = $request->get('description');
        $buildings->save();

        return Redirect::to('/admin/building');
    }

    public function index()
    {
        $building = Building::latest()->get();
        return $building;
    }

    public function destroy(Building $building)
    {
        // $buildingTarg = Building::where('id','==',$building)->get('id');
        // return Redirect::to('/admin/building');
        // DB::statement('PRAGMA foreign_keys = OFF;');

        $building -> delete();
        return Redirect::to('/admin/building');
    }

    public function update(Request $request, $id)
    {
        // $name = $request->get('name');
        // $description = $request->get('description');
        
        $buildings = Building::findOrFail($id);

        $buildings->update([
        $buildings->name = $request->get('name'),
        $buildings->description = $request->get('description'),
        ]);

        $buildings->save();
        return Redirect::to('/admin/building');
    }

    //para daw makita yung mga rooms
    public function showRooms(Room $room, Building $building){
        $building = Building::findOrFail($building->id);

        $rooms = Room::where('building_id', $building->id)->latest()->get('name');
        $roomCount = $rooms->count();

        return view('admin.building.components.showrooms')->with([
            'building' => $building,
            'rooms' => $rooms,
            'count' => $roomCount,
        ]);
    }
}