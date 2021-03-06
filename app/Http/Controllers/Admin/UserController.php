<?php

namespace App\Http\Controllers\Admin;

use App\Detail;
use App\Http\Controllers\Controller;
use App\Message;
use App\Plan;
use App\Service;
use App\Specialization;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    protected $validation = ([
        'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'bio' => 'nullable|string',
        'address' => 'required|string|max:100',
        'phone' => 'nullable|string|max:25',
        // 'service_name' => 'nullable|string',
        // 'service_price' => 'nullable|numeric'
    ]);


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // prendo i dati del dottore registrato
        $doctor_id = Auth::id();

        $user = User::where('id', $doctor_id)->first();

        // accedo alle tabelle dei dettagli e dei piani correlati al dottore
        $details = Detail::where('id', $doctor_id)->first(); 
        
        $plan = Plan::all();

        // accedo all'ultima entry di questo dottore nella tabella pivot dei piani già selezionati per visualizzare in pagina la data di scadenza 
        $extendPlan = $user->plans()->get()->last();
        $now = Carbon::now('Europe/Rome');

        if ($extendPlan == null) {
            $sponsored = false;
            $currentExpireDate = 0;
        } else {

            $currentExpireDate = $extendPlan->pivot->expire_date;

            if ($currentExpireDate < $now) {
                $sponsored = false;
            } else {
                $sponsored = true;
            }
        }

        return view('admin.index', compact('user', 'details', 'plan', 'sponsored', 'currentExpireDate'));
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Service $services, Specialization $specializations, $id)
    {
        // prendo i dati del dottore registrato comprese specializzazioni, dettagli e servizi 
        $user_id = Auth::id();
        $details = Detail::where('user_id', $id)->first();
        $specializations = Specialization::all();
        $services = Service::where('user_id', $id)->get();

        if ($details->user_id != $user_id) {
            abort('403');
        }
        $doctor = User::where('id', $id)->first();



        return view('admin.edit', compact('doctor', 'details', 'specializations', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // prendo i dati del dottore registrato
        $user_id = Auth::id();
        
        // validazione dei dati inseriti
        $validation = $this->validation;
        $request->validate($validation);

        // prendo i dati che saranno soggetti ad eventuali modifiche da parte del dottore
        $data = $request->only('bio', 'address', 'phone', 'image');

        $doctor = User::where('id', $id)->first();

        // salvo l'immagine
        if (isset($data['image'])) {
            $data['image'] = Storage::disk('public')->put('images', $data['image']);
        }
        
        // salvataggio dei dati modificati
        $doctor->details->update($data);

        $service_name = $request->service_name;
        $service_price = $request->service_price;

        // ciclo su tutti gli input dei services
        for ($i = 0; $i < count($service_name); $i++) {
            if ($service_name[$i] && $service_price[$i]) {
                // salvataggio nella tabella service
                $newService = new Service();
                $newService->user_id = $user_id;
                $newService->service = $service_name[$i];
                $newService->price = $service_price[$i];
                $newService->save();
            }
        }

        // controllo specializzazioni
        $spec = $request->only('field');
        if ( !isset($spec['field']) ) {
            $spec['field'] = [];
        }
        $doctor->specializations()->sync($spec['field']);

        return redirect()->route('admin.profile.index', $doctor)->with('message', 'Il tuo profilo è stato modificato');

    }  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // funzione per eliminare il profilo 
        $user = User::where('id', $id)->first();
        $user->delete();

        return redirect()->route('homepage')->with('message', 'Il tuo profilo è stato eliminato!');
    }
}
