<?php

/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\Appearance;
use App\Models\Area;
use App\Models\NonAppearance;
use App\Models\Production;
use App\Models\User;
use App\Models\Person;
use DB;
use Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class OperativeController extends BaseController
{

    private $user;
    private $currentdate;

    public function __construct(Request $req)
    {
        $this->middleware('profiles:1|2|3'); ///perfiles requeridos
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
        $this->currentdate = Carbon::today();

    }

    public function appearanceIndex(Request $req)
    {

        if ($req->filled('appear_date')) {
            $myDate = $req->input('appear_date');
        } else {
            $myDate = Carbon::today()->setTimezone('America/Caracas')->format('Y-m-d');
        }

        $this->getAppearances($myDate, $filtered, $nonAppeareance);
        $personList = $this->getPersons($filtered);
        $nonAppear = $this->getNonAppear($nonAppeareance);
        $areas = Area::all();

        return view(
            'pages.appearance',
            [
                "persons" => $personList,
                "areas" => $areas,
                "date" => $myDate,
                "nonAppear" => $nonAppear
            ]
        );
    }

    /**get appearances
     * @param $date
     * @param $filtered
     * @param $nonAppeareance
     */
    private function getAppearances($date, &$filtered, &$nonAppeareance)
    {

        $persons = Person::with('table', 'line')->whereActivo(1)
            ->leftJoin('tbl_asistencia as a', function ($join) use ($date) {
                $join->on('a.empleado_id', '=', 'tbl_empleado.id');
                $join->on("a.fecha", "=", DB::raw("'" . $date . "'"));
            })->select("tbl_empleado.*", "a.hora_entrada", "a.hora_salida", "a.hora_extra")->get();

        $nonAppeareance = NonAppearance::whereFecha($date)->with('person')->get();

        //filtering
        $filtered = $persons->filter(function ($item) use ($nonAppeareance) {
            return !$nonAppeareance->contains('empleado_id', $item->id);
        });
    }

    public function prodIndex()
    {
        $prods = Production::whereRaw(DB::raw("DATE(fecha) = DATE('$this->currentdate')"))->with('line', 'table')->get();

        return View(
            'pages.checkprod',
            [
                "date" => Carbon::today()->setTimezone('America/Caracas')->format('d/m/Y'),
                "prod" => $this->setProduction($prods)
            ]
        );
    }


    //************************* services *********************//

    public function getPersonsByEntity($entity, $value)
    {
        $persons = null;
        if ($value == "All") {
            $persons = Person::with('table', 'line')->get();
        } else {
            if ($entity == "role") {
                $persons = Person::whereCargoId($value)->get();
            } else if ($entity == "area") {
                $persons = Person::with('table', 'line')->whereHas('role', function ($query) use ($value) {
                    $query->whereAreaId($value);
                })->get();
            }
        }
        $personArray = $this->getPersons($persons);
        return response()->json(['status' => 'ok', 'list' => $personArray]);

    }


    public function getAppearByDate($date)
    {
        $this->getAppearances($date, $filtered, $nonAppeareance);
        $personList = $this->getPersons($filtered);

        return response()->json(['status' => 'ok', 'list' => $personList]);
    }

    /**get persons
     * @param $persons
     * @return array
     */
    private function getPersons($persons)
    {
        $personArray = array();
        $persons->each(function ($item) use (&$personArray) {
            $personArray[] = $this->setPerson($item);
        });
        return $personArray;
    }

    /**
     * person info
     */
    private function setPerson($person)
    {

        $location = "N/A";
        if (isset($person->table->titulo)) {
            $location = $person->table->titulo;
            if (isset($person->line->titulo)) {
                $location .= ', ' . $person->line->titulo;
            }
        }

        $in = '';
        if (isset($person->hora_entrada)) {
            $in = $person->hora_entrada;
        }

        $out = '';
        if (isset($person->hora_salida)) {
            $out = $person->hora_salida;
        }

        $extra = '';
        if (isset($person->hora_extra)) {
            $extra = ($person->hora_extra) ? 'SI' : 'NO';
        }

        return array(
            "id" => $person->id,
            "ubicacion" => $location,
            "nombre" => $person->nombre . ' ' . $person->apellido,
            "cedula" => $person->cedula,
            "ingreso" => $person->fecha_ingreso,
            "entrada" => $in,
            "salida" => $out,
            "extra" => $extra,
            "cargo" => $person->role->nombre
        );

    }

    /**non appear
     * @param $nonAppear
     * @return array
     */
    private function getNonAppear($nonAppear)
    {
        $personArray = array();
        $nonAppear->each(function ($item) use (&$personArray) {
            $location = "N/A";
            if (isset($item->person->table->titulo)) {
                $location = $item->person->table->titulo;
                if (isset($item->person->line->titulo)) {
                    $location .= ', ' . $item->person->line->titulo;
                }
            }

            $personArray[] = array(
                "id" => $item->id,
                "ubicacion" => $location,
                "fecha" => $item->date(),
                "nombre" => $item->person->nombre . ' ' . $item->person->apellido,
                "cedula" => $item->person->cedula,
                "ingreso" => $item->person->fecha_ingreso,
                "cargo" => $item->person->role->nombre
            );
        });
        return $personArray;

    }

    private function getTurn($time)
    {
        $turn = '';
        $time = explode(':', $time);
        $hour = intval($time[0]);
        if ($hour > 0 && $hour <= 12) {
            $turn = 'mañana';
        } else if ($hour > 12 && $hour <= 18) {
            $turn = 'tarde';
        } else {
            $turn = 'noche';
        }

        return $turn;
    }

    public function saveAppearance(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'type' => 'required',
            'person' => 'required',
            'date' => 'required',
        ], ['required' => 'El campo :attribute es requerido',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        try {

            $action = 1; ///0 non appear, 1 insert appear (hour of arrive) 2 set appear (hour of exit)
            $note = '';
            $info = array();
            $emp = Person::findOrFail($req->input('person'));

            if ($req->filled('note')) {
                $note = $req->input('note');
            }

            //type = 1 appear, 0 non appear
            if ($req->input('type')) {

                $appear = new Appearance();
                $appear->empleado_id = $emp->id;
                $appear->cargo_id = $emp->cargo_id;
                $appear->sueldo = $emp->role->sueldo;
                $appear->fecha = $req->input('date');
                $appear->comentario = $note;
                if ($emp->mesa_id != null) {
                    $appear->mesa_id = $emp->mesa_id;
                    $appear->linea_id = $emp->linea_id;
                }

                if ($req->has('in_hour') && !empty($req->input('in_hour'))) { //if hour of arrived
                    $appear->hora_entrada = $req->input('in_hour');
                    $appear->turno = $this->getTurn($req->input('in_hour'));
                } else {
                    return response()->json(['status' => 'error', 'message' => 'por favor registre la hora de llegada'], 401);
                }

                $appear->save();
                $action = 1;
                $info = $this->setPerson($emp);
                $info['entrada'] = $req->input('in_hour');
                UserController::saveUserActivity($this->user->id, "Registrando asistencia del dia: $appear->fecha, al empleado: $emp->nombre $emp->apellido, $emp->codigo");

            } else { //non appear
                $non = new NonAppearance();
                $non->empleado_id = $req->input('person');
                $non->fecha = $req->input('date');
                $non->save();
                UserController::saveUserActivity($this->user->id, "Registrando Inasistencia del dia: $non->fecha, al empleado: $emp->nombre $emp->apellido, $emp->codigo");
                $action = 0;
                $info = $this->setPerson($emp);
                $info['non_id'] = $non->id;
                $info['fecha'] = Carbon::parse($non->fecha)->format("d/m/Y");
            }

        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());
            return response()->json(['status' => 'error', 'message' => 'El empleado ya esta registrado en la lista de hoy'], 500);
        }

        $response = array("action" => $action, "detail" => $info);

        return response()->json(['status' => 'ok', 'info' => $response]);


    }


    public function saveOutHour(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'out_hour' => 'required',
            'person' => 'required',
            'date' => 'required',
        ], ['required' => 'El campo :attribute es requerido',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $appear = Appearance::with('person')->whereEmpleadoId($req->input('person'))->whereFecha($req->input('date'))->first();
        $appear->hora_salida = $req->input('out_hour');
        if ($req->filled('note')) {
            $appear->comentario = $req->input('note');
        }
        $appear->hora_extra = ($req->input('extras')) ? 1 : 0;
        $appear->save();
        $emp = $appear->person;
        $info = $this->setPerson($emp);
        $info['entrada'] = $appear->hora_entrada;
        $info['salida'] = $appear->hora_salida;
        $info['extra'] = ($appear->hora_extra) ? 'SI' : 'NO';
        UserController::saveUserActivity($this->user->id, "Registrando Hora de salida el dia: $appear->fecha, hora:$appear->hora_salida al empleado: $emp->nombre $emp->apellido, $emp->codigo");

        return response()->json(['status' => 'ok', 'info' => $info]);

    }


    public function deleteAppear($person_id, $date)
    {
        $appear = Appearance::with('person')->whereEmpleadoId($person_id)->whereFecha($date)->first();

        $emp = $appear->person;
        $info = $this->setPerson($emp);
        $appear->delete();
        UserController::saveUserActivity($this->user->id, "Borrando asistencia del dia: $appear->fecha, al empleado: $emp->nombre $emp->apellido, $emp->codigo");
        return response()->json(['status' => 'ok', 'info' => $info]);

    }

    public function deleteNonAppear($appear_id)
    {
        $item = NonAppearance::findOrFail($appear_id);
        $info = array();
        $emp = $item->person;
        $item->delete();
        UserController::saveUserActivity($this->user->id, "Borrando Inasistencia el dia: $appear->fecha al empleado: $emp->nombre $emp->apellido, $emp->codigo");
        $info = $this->setPerson($emp);
        return response()->json(['status' => 'ok', 'info' => $info]);
    }


    /**
     * @param $myDate
     */
    public function registerAppearBatch($date)
    {
        ///all people less non appear
        $persons = Person::whereActivo(1)->with('role')
            ->leftJoin('tbl_inasistencia as i', function ($join) use ($date) {
                $join->on('i.empleado_id', '!=', 'tbl_empleado.id');
                $join->on("i.fecha", "=", DB::raw("'" . $date . "'"));
            })->select("tbl_empleado.*")->distinct()->get();

        ///now register appears with arrive hour and exit hour

        try {
            DB::beginTransaction();

            ///deleting all appearance of date
            Appearance::whereFecha($date)->delete();

            $persons->each(function ($item) use ($date) {
                $appear = new Appearance();
                $appear->empleado_id = $item->id;
                $appear->cargo_id = $item->cargo_id;
                $appear->sueldo = $item->role->sueldo;
                $appear->fecha = $date;
                $appear->mesa_id = $item->mesa_id;
                $appear->linea_id = $item->linea_id;
                $appear->hora_entrada = '07:00:00';
                $appear->hora_salida = '16:00:00';
                $appear->turno = $this->getTurn('07:00:00');
                $appear->comentario = "Registrado por lotes";
                $appear->save();
            });

            UserController::saveUserActivity($this->user->id, "Registro de asistencia masivo para el personal");

            DB::commit();
            return response()->json(['status' => 'ok', 'message' => "Registro de asistencia exitoso"]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => "Falla de registro masivo"], 500);
        }


    }


    /**********************************production services *************************************/

    private function setProduction($prods)
    {

        $prodArray = array();
        $prods->each(function ($item) use (&$prodArray) {
            $prodArray[] = array(
                "id" => $item->id,
                "fecha" => $item->date(),
                "hora" => $item->time(),
                "mesa" => $item->table->titulo,
                "linea" => $item->line->titulo,
                "cajas" => $item->cajas,
            );
        });
        return $prodArray;
    }

    public function getProduction()
    {
        $prods = Production::whereRaw(DB::raw("DATE(fecha) = DATE('$this->currentdate')"))->with('line', 'table')->get();
        return response()->json(['status' => 'ok', 'list' => $this->setProduction($prods)]);

    }

    /**create production
     * @param Request $req
     * @return mixed
     */
    public function createOrUpdateProd(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'mesa' => 'required|numeric',
            'hora' => 'required',
            'linea' => 'required|numeric',
            'cajas' => 'required|numeric',
        ], [
            'required' => 'El campo :attribute es requerido',
            'numeric' => 'El campo :attribute debe ser numerico',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $prod = new Production();
        if ($req->has('prod_id')) {
            $prod = Production::findOrFail($req->input('prod_id'));
        }

        $my_date = $req->input('fecha') . ' ' . $req->input('hora');
        $prod->fecha = $my_date;
        $prod->cajas = $req->input('cajas');
        $prod->mesa_id = $req->input('mesa');
        $prod->linea_id = $req->input('linea');
        $prod->usuario_id = $req->session()->get('myUser')->id;
        $prod->save();
        UserController::saveUserActivity($this->user->id, "Guardando datos de producción:$prod");

        return response()->json(['status' => 'ok', 'message' => 'Producción registrada con éxito']);

    }

    public function deleteProd($prod_id)
    {

        $item = Production::findOrFail($prod_id);
        $item->delete();
        UserController::saveUserActivity($this->user->id, "Borrando datos de producción:$item");
        return response()->json(['status' => 'ok', 'message' => "producción borrada"]);

    }


}