<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;
use Illuminate\Support\Facades\Redirect;

class RoomController extends Controller
{
    //
    public function index()
    {
        $buildings = Building::latest()->get();
        $rooms = Room::latest()->get();
        return view('programhead.pages.room')->with(['buildings' => $buildings, 'rooms' => $rooms]);
    }


    public function store(Request $request)
    {

        $rooms = new Room();
        $rooms->name = $request->get('name');
        $rooms->building_id = $request->get('building_id');
        $rooms->save();

        return Redirect::to('/programhead/room');
    }

    public function update(Request $request, $id)
    {
        // Find the room by its ID
        $rooms = Room::findOrFail($id);

        // Update room with the new data
        $rooms->update([
            'name' => $request->get('name'),
            'building_id' => $request->get('building_id'),
        ]);

        // Redirect to the  room list or other relevant page
        return Redirect::to('/programhead/room');
    }


    public function destroy(Room $room)
    {
        $room->delete();
        return Redirect::to('/programhead/room');
    }


}
