<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['rank', 'team_rank', 'startList', 'signList', 'rajd']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->admin)
            return view('admin.dashboard');
        
        $forms = \App\SignForm::where('active', 1)->get();

        $lists = \App\SignForm::where('visible', 1)->with('round.file', 'round.race')->get();

        $races = \App\Race::latest()->get();

        $now = \Carbon\Carbon::now();

        $start_lists = \App\StartList::whereHas('round', function ($query) use ($now) {
            $query->whereDate('date', '>', $now);
        })->get();

        return view('dashboard', compact('forms', 'races', 'closest', 'lists', 'start_lists'));
    }

    public function startList($id)
    {
        $round = \App\Round::where('id', $id)->first();

        if($round && $round->startList){
            $start_list_id = $round->startList->id;
            $is_someone = $round->startPositions($start_list_id)->count();
            $class = $round->klasy($start_list_id)->toArray();

            $order = explode(',', $round->order);

            usort($class, function ($a, $b) use ($order) {
              $pos_a = array_search($a, $order);
              $pos_b = array_search($b, $order);
              return $pos_a - $pos_b;
            });

            $accreditations = \App\PressSign::where('round_id', $round->id)->get()->groupBy('user_id');
            
            return view('startList', compact('round', 'is_someone', 'class', 'start_list_id', 'accreditations'));
        }

        return back()->with('warning', 'Lista startowa nie istnieje');
    }

    public function signList($id)
    {
        $round = \App\Round::where('id', $id)->first();
        $minTeam = $round->race->minTeam;

        if($round && $round->form->visible){
            $signs = $round->signs()->load('user.profile.file', 'car.file');
            $klasy = $signs->sortBy('klasa')->pluck('klasa', 'klasa')->toArray();

            // $order = array('k4', 'k7', 'k3', 'k2', 'k1', 'k6', 'k5');
            $order = explode(',', $round->order);

            usort($klasy, function ($a, $b) use ($order) {
              $pos_a = array_search($a, $order);
              $pos_b = array_search($b, $order);
              return $pos_a - $pos_b;
            });

            $class = [];

            foreach ($signs as $key => $sign) {
                $class[$sign->klasa][$key]['sign'] = $sign;
            }

            $teams = [];

            foreach ($signs as $sign) {
                if($sign->team){
                    if(!array_key_exists($sign->team->id, $teams)){
                        $teams[$sign->team->id]['team'] = $sign->team;
                        $teams[$sign->team->id]['count'] = 1;
                    }
                    else{
                        $teams[$sign->team->id]['count']++;
                    }
                }
            }

            usort($teams, function ($a, $b){
              return $b['count'] - $a['count'];
            });

            $accreditations = \App\PressSign::where('round_id', $round->id)->get()->groupBy('user_id');

            return view('signList', compact('round', 'klasy', 'class', 'teams', 'accreditations', 'minTeam'));
        }

        return back()->with('warning', 'Lista zgłoszeń nie jest dostępna');
    }

    public function register_form($id)
    {
        $form = \App\SignForm::where('id', $id)->first();

        if($form){
            if(auth()->user()->driver == 1)
                $data = $form->signs->where('user_id', auth()->user()->id)->first();
            else if(auth()->user()->driver == 0)
                $data = $form->signs->where('pilot_id', auth()->user()->id)->first();
            
            $pdf = PDF::loadView('pdf.form', compact('data', 'form'));
            return $pdf->download('formularz.pdf');
        }

        return back()->with('warning', 'Lista nie istnieje');
    }

    public function rank($id)
    {
        $race = \App\Race::where('id', $id)->with('rounds')->first();
        $klasy = $race->klasy();
        $race_id = $id;
        if($race)
            return view('rank', compact('race', 'klasy', 'race_id'));

        return back()->with('warning', 'Rajd nie istnieje');
    }

    public function team_rank($id)
    {
        $race = \App\Race::where('id', $id)->with('rounds')->first();
        
        if($race){
            $teams = \App\Team::get();

            foreach ($teams as $key => $team) {
                $results = $team->race_results($id);
                if($results['rounds']){
                    $team['results'] = $results;
                    $team['points'] = $results['points'];
                }
                else{
                    $teams->forget($key);
                }
                
            }

            $sorted_teams = $teams->sortByDesc(function ($team, $key) {
                return $team['points'];
            });

            return view('team_rank', compact('race', 'sorted_teams'));
        }

        return back()->with('warning', 'Rajd nie istnieje');
    }

    public function getKlasa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:cars',
        ]);

        $car = \App\Car::where('id', $request->id)->first();
        $ccm = $car->ccm;

        // if($car->turbo && $car->rwd)
        //     return '<option value="k4">K4</option><option value="k7">K7</option>';

        // if($car->turbo)
        //     return '<option value="k4">K4</option>';

        if($car->turbo){
            if($car->diesel)
                $ccm = $ccm * 1.4;
            else
                $ccm = $ccm * 1.7;
        }

        if($car->rwd)
            if($ccm < 800)
                return '<option value="k5">Fiat 126p z silnikiem markowym</option><option value="k7">K7</option><option value="open">Open</option>';
            else
                return '<option value="k7">K7</option><option value="open">Open</option>';

        if($ccm <= 1400)
            return '<option value="k1">K1</option><option value="k6">Fiat SC i CC z silnikiem do poj. 1242 cm3 8v</option><option value="open">Open</option>';

        if($ccm <= 1600)
            return '<option value="k2">K2</option><option value="open">Open</option>';

        if($ccm <= 2000)
            return '<option value="k3">K3</option><option value="open">Open</option>';

        return '<option value="k4">K4</option><option value="open">Open</option>';
    }

    public function getKlasaByNr(Request $request)
    {
        $this->validate($request, [
            'car' => 'required|exists:cars,nr_rej',
        ]);

        $car = \App\Car::where('nr_rej', $request->car)->first();
        $ccm = $car->ccm;

        // if($car->turbo && $car->rwd)
        //     return '<option value="k4">K4</option><option value="k7">K7</option>';

        // if($car->turbo)
        //     return '<option value="k4">K4</option>';

        if($car->turbo){
            if($car->diesel)
                $ccm = $ccm * 1.4;
            else
                $ccm = $ccm * 1.7;
        }

        if($car->rwd)
            if($ccm < 800)
                return '<option value="k5">Fiat 126p z silnikiem markowym</option><option value="k7">K7</option><option value="open">Open</option>';
            else
                return '<option value="k7">K7</option><option value="open">Open</option>';

        if($ccm <= 1400)
            return '<option value="k1">K1</option><option value="k6">Fiat SC i CC z silnikiem do poj. 1242 cm3 8v</option><option value="open">Open</option>';

        if($ccm <= 1600)
            return '<option value="k2">K2</option><option value="open">Open</option>';

        if($ccm <= 2000)
            return '<option value="k3">K3</option><option value="open">Open</option>';

        return '<option value="k4">K4</option><option value="open">Open</option>';
    }

    public function getPilot(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:pilots',
        ]);

        $pilot = \App\Pilot::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        
        if($pilot){
            return response()->json($pilot);
        }

        return back()->with('warning', 'Pilot nie istnieje');
    }

    public function getPilotById(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:users',
        ]);

        $pilot = \App\User::where('id', $request->id)->where('driver', 0)->where('active', 1)->first();

        if($pilot){
            $profile = \App\Driver::where('user_id', $pilot->id)->first();
            $profile->uid = $pilot->uid;
            return response()->json($profile);
        }
        else
            return 'blad';


        return back()->with('warning', 'Pilot nie istnieje');
    }

    public function getPilotByName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|exists:pilots,name',
            'lastname' => 'required|exists:pilots,lastname',
        ]);

        $pilot = \App\Pilot::where('name', $request->name)->where('lastname', $request->lastname)->where('user_id', auth()->user()->id)->first();
        
        if($pilot){
            return response()->json($pilot);
        }

        return back()->with('warning', 'Pilot nie istnieje');
    }

    public function getDriver(Request $request)
    {
        $driver = \App\User::where('uid', $request->uid)->where('driver', 1)->where('active', 1)->first();

        if($driver){
            $sign = \App\Sign::where('user_id', $driver->id)->where('form_id', $request->form)->first();

            if(!$sign){
                $profile = \App\Driver::where('user_id', $driver->id)->first();
                if($profile){
                    $profile->email = $driver->email;
                    $profile->cars = $driver->cars;

                    $saved = \App\SavedId::where('user_id', auth()->user()->id)->where('uid', $request->uid)->first();
                    if(!$saved){
                        $save = new \App\SavedId;
                        $save->user_id = auth()->user()->id;
                        $save->uid = $request->uid;
                        $save->name = $profile->name . " " . $profile->lastname;
                        $save->save();
                    }
                    return response()->json($profile);
                }
                else
                    return 'blad';
            }
            else
                return 'blad';
        }
        else
            return 'blad';
    }

    public function getDriverById(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:users',
        ]);

        $driver = \App\User::where('id', $request->id)->where('driver', 1)->where('active', 1)->first();

        if($driver){
            $profile = \App\Driver::where('user_id', $driver->id)->first();
            $profile->uid = $driver->uid;
            $profile->email = $driver->email;
            $profile->cars = $driver->cars;

            return response()->json($profile);
        }
        else
            return 'blad';

        return back()->with('warning', 'Kierowca nie istnieje');
    }

    public function getPilotUid(Request $request)
    {
        $pilot = \App\User::where('uid', $request->uid)->where('driver', 0)->where('active', 1)->first();

        if($pilot){
            $sign = \App\Sign::where('pilot_id', $pilot->id)->where('form_id', $request->form)->first();

            if(!$sign){
                $profile = \App\Driver::where('user_id', $pilot->id)->first();
                if($profile){
                    $profile->email = $pilot->email;

                    $saved = \App\SavedId::where('user_id', auth()->user()->id)->where('uid', $request->uid)->first();
                    if(!$saved){
                        $save = new \App\SavedId;
                        $save->user_id = auth()->user()->id;
                        $save->uid = $request->uid;
                        $save->name = $profile->name . " " . $profile->lastname;
                        $save->save();
                    }

                    return response()->json($profile);
                }
                else
                    return 'blad';
            }
            else
                return 'blad';
        }
        else
            return 'blad';
    }

    public function getCar(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:cars',
        ]);

        $car = \App\Car::where('id', $request->id)->first();
        
        if($car){
            return response()->json($car);
        }

        return back()->with('warning', 'Samochód nie istnieje');
    }

    public function getCarByNr(Request $request)
    {
        $this->validate($request, [
            'car' => 'required|exists:cars,nr_rej',
        ]);

        $car = \App\Car::where('nr_rej', $request->car)->first();
        
        if($car){
            return response()->json($car);
        }

        return back()->with('warning', 'Samochód nie istnieje');
    }

    public function banner()
    {
        $banner = \App\SiteInfo::where('name', 'banner')->first();

        return view('admin.banner', compact('banner'));
    }

    public function saveBanner(Request $request)
    {
        $banner = \App\SiteInfo::where('name', 'banner')->first();

        if(!$banner){
            $banner = new \App\SiteInfo;
            $banner->name = 'banner';
        }

        $banner->active = isset($request->showBanner)?1:0;
        $banner->value = $request->info;

        $banner->save();

        return back()->with('success', 'Komunikat został zapisany');
    }

    public function terms()
    {
        $terms = \App\SiteInfo::where('name', 'terms')->first();

        return view('admin.terms', compact('terms'));
    }

    public function saveTerms(Request $request)
    {
        $terms = \App\SiteInfo::where('name', 'terms')->first();

        if(!$terms){
            $terms = new \App\SiteInfo;
            $terms->name = 'terms';
        }

        $terms->value = $request->terms;

        $terms->save();

        return back()->with('success', 'Regulamin został zapisany');
    }

    public function policy()
    {
        $policy = \App\SiteInfo::where('name', 'policy')->first();

        return view('admin.policy', compact('policy'));
    }

    public function savePolicy(Request $request)
    {
        $policy = \App\SiteInfo::where('name', 'policy')->first();

        if(!$policy){
            $policy = new \App\SiteInfo;
            $policy->name = 'policy';
        }

        $policy->value = $request->policy;

        $policy->save();

        return back()->with('success', 'Polityka prywatności została zapisana');
    }

    public function edit_promoted()
    {
        $promoted_race = \App\SiteInfo::where('name', 'promoted_race')->first();

        return view('admin.promoted', compact('promoted_race'));
    }

    public function save_promoted(Request $request)
    {
        $promoted_race = \App\SiteInfo::where('name', 'promoted_race')->first();

        if(!$promoted_race){
            $promoted_race = new \App\SiteInfo;
            $promoted_race->name = 'promoted_race';
        }
        $promoted_race->value = $request->promoted;
        $promoted_race->save();

        return back()->with('success', 'Dane zostały zapisane');
    }

    public function edit_live_video()
    {
        $liveVideo = \App\SiteInfo::where('name', 'live_video')->first();

        return view('admin.liveVideo', compact('liveVideo'));
    }

    public function save_live_video(Request $request)
    {
        $liveVideo = \App\SiteInfo::where('name', 'live_video')->first();

        if(!$liveVideo){
            $liveVideo = new \App\SiteInfo;
            $liveVideo->name = 'live_video';
        }
        $liveVideo->value = $request->live_video;
        $liveVideo->save();

        return back()->with('success', 'Dane zostały zapisane');
    }

    public function edit_live_wyniki()
    {
        $liveWyniki = \App\SiteInfo::where('name', 'live_wyniki')->first();

        return view('admin.liveWyniki', compact('liveWyniki'));
    }

    public function save_live_wyniki(Request $request)
    {
        $liveWyniki = \App\SiteInfo::where('name', 'live_wyniki')->first();

        if(!$liveWyniki){
            $liveWyniki = new \App\SiteInfo;
            $liveWyniki->name = 'live_wyniki';
        }
        $liveWyniki->value = $request->live_wyniki;
        $liveWyniki->save();

        return back()->with('success', 'Dane zostały zapisane');
    }

    public function contactInfo()
    {
        $contactFirstName = \App\SiteInfo::where('name', 'kontakt_first_name')->first();
        $contactFirstTel = \App\SiteInfo::where('name', 'kontakt_first_tel')->first();
        $contactSecondName = \App\SiteInfo::where('name', 'kontakt_second_name')->first();
        $contactSecondTel = \App\SiteInfo::where('name', 'kontakt_second_tel')->first();
        $contactEmail= \App\SiteInfo::where('name', 'kontakt_email')->first();
        $contactMediaTel= \App\SiteInfo::where('name', 'kontakt_media_tel')->first();

        return view('admin.contactInfo', compact('contactFirstName', 'contactFirstTel', 'contactSecondName', 'contactSecondTel', 'contactEmail', 'contactMediaTel'));
    }

    public function saveInfo(Request $request)
    {
        $contactFirstName = \App\SiteInfo::where('name', 'kontakt_first_name')->first();
        $contactFirstTel = \App\SiteInfo::where('name', 'kontakt_first_tel')->first();
        $contactSecondName = \App\SiteInfo::where('name', 'kontakt_second_name')->first();
        $contactSecondTel = \App\SiteInfo::where('name', 'kontakt_second_tel')->first();
        $contactEmail= \App\SiteInfo::where('name', 'kontakt_email')->first();
        $contactMediaTel= \App\SiteInfo::where('name', 'kontakt_media_tel')->first();

        if(!$contactFirstName){
            $contactFirstName = new \App\SiteInfo;
            $contactFirstName->name = 'kontakt_first_name';
        }
        $contactFirstName->value = $request->kontakt_first_name;
        $contactFirstName->save();

        if(!$contactFirstTel){
            $contactFirstTel = new \App\SiteInfo;
            $contactFirstTel->name = 'kontakt_first_tel';
        }
        $contactFirstTel->value = $request->kontakt_first_tel;
        $contactFirstTel->save();

        if(!$contactSecondName){
            $contactSecondName = new \App\SiteInfo;
            $contactSecondName->name = 'kontakt_second_name';
        }
        $contactSecondName->value = $request->kontakt_second_name;
        $contactSecondName->save();

        if(!$contactSecondTel){
            $contactSecondTel = new \App\SiteInfo;
            $contactSecondTel->name = 'kontakt_second_tel';
        }
        $contactSecondTel->value = $request->kontakt_second_tel;
        $contactSecondTel->save();

        if(!$contactEmail){
            $contactEmail = new \App\SiteInfo;
            $contactEmail->name = 'kontakt_email';
        }
        $contactEmail->value = $request->kontakt_email;
        $contactEmail->save();

        if(!$contactMediaTel){
            $contactMediaTel = new \App\SiteInfo;
            $contactMediaTel->name = 'kontakt_media_tel';
        }
        $contactMediaTel->value = $request->kontakt_media_tel;
        $contactMediaTel->save();

        return back()->with('success', 'Dane zostały zapisane');
    }

    public function rajd($id)
    {
        $round = \App\Round::where('id', $id)->with('race')->first();

        if($round)
            return view('rajd', compact('round'));

        return back()->with('warning', 'Rajd nie istnieje');
    }

    public function teams()
    {
        $teams = \App\Team::get();

        return view('admin.teams', compact('teams'));
    }
}
