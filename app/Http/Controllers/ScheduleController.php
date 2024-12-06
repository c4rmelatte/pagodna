<?php
// paayos nga netong page guys, kung ano ano na nangyari, sisihin nyo si michael T.Y.
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room; //para maimport ang model ng Room
use App\Models\Building;

class ScheduleController extends Controller
{
    public function index() // pangalan ng function, kayo na bahala... pag di kaya, kausapin ang front-end dev para maimplement
    {
        $buildings = Building::latest()->get();// eto yung ginagamit para makuha yung mga query sa loob ng "Building" Model
        $rooms = Room::latest()->get();  // dito kinukuha yung query sa ROOM model, paki import muna 

        return view('programhead.pages.room')->with(['buildings' => $buildings, 'rooms' => $rooms]); // dito binibigay ng function yung value sa page
        //sample function, paki bago po
    }

    public function showSchedule(Room $room, Building $building){
        $building = Building::findOrFail($building->id);
        $rooms = Room::where('building_id', $building->id)->latest()->get('name');

        return view('admin.building.components.showrooms')->with([
            'building' => $building,
            'rooms' => $rooms,
            'count' => $roomCount
        ]);
        // ello, di pa to yon
    }
}
