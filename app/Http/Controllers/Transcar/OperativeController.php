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
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
        $this->currentdate = Carbon::today();

    }

    public function appearanceIndex()
    {
        $persons = Person::with('table', 'line')
            ->leftJoin('tbl_asistencia as a', function ($join) {
                $join->on('a.empleado_id', '=', 'tbl_empleado.id');
                $join->on("a.fecha", "=", DB::raw("'" . $this->currentdate . "'"));
            })->select("tbl_empleado.*", "a.hora_entrada", "a.hora_salida")->get();

        $areas = Area::all();
        $nonAppeareance = NonAppearance::whereFecha($this->currentdate)->with('person')->get();

        //filtering
        $filtered = $persons->filter(function ($item) use ($nonAppeareance) {
            return !$nonAppeareance->contains('empleado_id', $item->id);
        });

        $personList = $this->getPersons($filtered);
        $nonAppear = $this->getNonAppear($nonAppeareance);

        return view(
            'pages.appearance',
            [
                "persons" => $personList,
                "areas" => $areas,
                "date" => Carbon::today()->setTimezone('America/Caracas')->format('d/m/Y'),
                "nonAppear" => $nonAppear
            ]
        );
    }

    public function prodIndex()
    {
        $prods =  Production::whereRaw(DB::raw("DATE(fecha) = DATE('$this->currentdate')"))->with('line', 'table')->get();

        return View('pages.checkprod',
            [
                "date" => Carbon::today()->setTimezone('America/Caracas')->format('d/m/Y'),
                "prod" => $this->setProduction($prods)
            ]);
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

    /**get persons
     * @param $persons
     * @return array
     */
    private function getPersons($persons)
    {
        $personArray = array();
        $persons->each(function ($item) use (&$personArray) {
            $location = "N/A";
            if (isset($item->table->titulo)) {
                $location = $item->table->titulo . ', ' . $item->line->titulo;
            }

            $in = '';
            if (isset($item->hora_entrada)) {
                $in = $item->hora_entrada;
            }

            $out = '';
            if (isset($item->hora_salida)) {
                $out = $item->hora_salida;
            }


            $personArray[] = array(
                "id" => $item->id,
                "ubicacion" => $location,
                "nombre" => $item->nombre . ' ' . $item->apellido,
                "cedula" => $item->cedula,
                "ingreso" => $item->fecha_ingreso,
                "entrada" => $in,
                "salida" => $out,
                "cargo" => $item->role->nombre
            );
        });
        return $personArray;
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
                $location = $item->person->table->titulo . ', ' . $item->person->line->titulo;
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
        ], ['required' => 'El campo :attribute es requerido',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        try {

            $action = 1; ///0 non appear, 1 insert appear (hour of arrive) 2 set appear (hour of exit)
            $info = array();
            $emp = Person::findOrFail($req->input('person'));
            //type = 1 appear, 0 non appear
            if ($req->input('type')) {

                $appear = new Appearance();
                $appear->empleado_id = $emp->id;
                $appear->cargo_id = $emp->cargo_id;
                $appear->sueldo = $emp->role->sueldo;
                $appear->fecha = Carbon::now();
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
                $info['entrada'] = $req->input('in_hour');

            } else { //non appear
                $non = new NonAppearance();
                $non->empleado_id = $req->input('person');
                $non->fecha = Carbon::now();
                $non->save();
                $action = 0;
                $info['non_id'] = $non->id;
                $info['fecha'] = Carbon::parse($non->created_at)->format("d/m/Y");
                $info['person'] = intval($req->input('person'));
            }

        } catch (\PDOException $ex) {
            return response()->json(['status' => 'error', 'message' => 'El empleado ya esta registrado en la lista de hoy'], 500);
        }

        $info['person_id'] = $emp->id;
        $info['nombre'] = $emp->nombre . ' ' . $emp->apellido;
        $info['cedula'] = $emp->cedula;
        $info['cargo'] = $emp->role->nombre;

        $response = array("action" => $action, "detail" => $info);

        return response()->json(['status' => 'ok', 'info' => $response]);


    }


    public function saveOutHour(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'out_hour' => 'required',
            'person' => 'required',
        ], ['required' => 'El campo :attribute es requerido',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $appear = Appearance::with('person')->whereEmpleadoId($req->input('person'))->whereFecha($this->currentdate)->first();
        $appear->hora_salida = $req->input('out_hour');
        $appear->save();

        $info = array();
        $emp = $appear->person;
        $info['person_id'] = $emp->id;
        $info['nombre'] = $emp->nombre . ' ' . $emp->apellido;
        $info['cedula'] = $emp->cedula;
        $info['cargo'] = $emp->role->nombre;
        $info['entrada'] = $appear->hora_entrada;
        $info['salida'] = $appear->hora_salida;

        return response()->json(['status' => 'ok', 'info' => $info]);

    }


    public function deleteAppear($person_id)
    {

        $appear = Appearance::with('person')->whereEmpleadoId($person_id)->whereFecha($this->currentdate)->first();

        $info = array();
        $emp = $appear->person;
        $appear->delete();
        $info['person_id'] = $emp->id;
        $info['nombre'] = $emp->nombre . ' ' . $emp->apellido;
        $info['cedula'] = $emp->cedula;
        $info['cargo'] = $emp->role->nombre;

        return response()->json(['status' => 'ok', 'info' => $info]);

    }

    public function deleteNonAppear($appear_id)
    {
        $item = NonAppearance::findOrFail($appear_id);
        $info = array();
        $emp = $item->person;
        $item->delete();

        $info['person_id'] = $emp->id;
        $info['nombre'] = $emp->nombre . ' ' . $emp->apellido;
        $info['cedula'] = $emp->cedula;
        $info['cargo'] = $emp->role->nombre;

        return response()->json(['status' => 'ok', 'info' => $info]);
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
        $prods =  Production::whereRaw(DB::raw("DATE(fecha) = DATE('$this->currentdate')"))->with('line', 'table')->get();
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
        ], ['required' => 'El campo :attribute es requerido',
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

        return response()->json(['status' => 'ok', 'message' => 'Producción registrada con éxito']);

    }

    public function deleteProd($prod_id){

        $item = Production::findOrFail($prod_id);
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => "producción borrada"]);

    }


}