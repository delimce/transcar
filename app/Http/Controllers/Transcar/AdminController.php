<?php

/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 8/13/2018
 * Time: 12:01 AM
 */

namespace App\Http\Controllers\Transcar;


use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Validator;
use Log;
use DB;
use App\Models\User;
use App\Models\Config;
use App\Models\Area;
use App\Models\Role;
use App\Models\Table;
use App\Models\Line;
use App\Models\Person;
use App\Models\Bonus;
use App\Models\Layoff;


class AdminController extends BaseController
{

    private $user;


    public function __construct(Request $req)
    {
        $this->middleware('profiles:1'); ///perfiles requeridos
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser)) {
            $this->user = User::findOrFail($myUser->id);
        }
    }


    public function indexUsers()
    {
        $users = User::where("id", "!=", $this->user->id)->with('profile')->get();

        return view('pages.system', ["users" => $users]);
    }


    public function indexConfig()
    {
        $config = Config::first();

        return view('pages.config', ["config" => $config]);
    }


    public function saveConfig(Request $req)
    {

        $config                 = Config::find(1); ///only reg
        $config->empresa_nombre = $req->input('empresa_nombre');
        $config->empresa_cuenta = $req->input('empresa_cuenta');
        $config->empresa_rif    = $req->input('empresa_rif');
        $config->iva            = $req->input('iva');
        $config->caja_paleta    = $req->input('cajas');
        $config->save();
        UserController::saveUserActivity(
            $this->user->id, "Guardando configuración del sistema:$config ", "Administrativo"
        );

        return response()->json(['status' => 'ok', 'message' => 'Configuracion guardada con éxito']);
    }


    public function areaRoleIndex()
    {
        $areas = Area::all();
        $roles = Role::all();

        return view('pages.areaRole', ["areas" => $areas, "roles" => $roles]);
    }


    public function tableLineIndex()
    {
        $tables = Table::all();
        $lines  = Line::all();

        return view('pages.tableLine', ["tables" => $tables, "lines" => $lines]);
    }


    public function personIndex()
    {
        $persons = Person::all();

        return view('pages.persons', ["persons" => $persons]);
    }


    public function bonusIndex()
    {
        $bonus = Bonus::all();

        return view('pages.bonus', ["bonus" => $bonus]);
    }


    /****************areas method***************** */


    public function getAreas()
    {

        $areas = Area::all();

        return response()->json(['status' => 'ok', 'list' => $areas->toArray()]);

    }


    public function getAreaById($area_id)
    {

        $area = Area::find($area_id);

        return response()->json(['status' => 'ok', 'area' => $area]);
    }


    public function createOrUpdateArea(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'nombre'      => 'required|min:3',
            'descripcion' => 'required|min:3',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $area = new Area();
        if ($req->has('area_id')) {
            $area = Area::findOrFail($req->input('area_id'));
        }

        $area->nombre      = $req->input('nombre');
        $area->descripcion = $req->input('descripcion');
        $area->save();
        UserController::saveUserActivity($this->user->id, "Guardando datos del departamento:$area ", "Administrativo");

        return response()->json(['status' => 'ok', 'message' => 'Area guardada con éxito']);

    }


    public function deleteAreaById($area_id)
    {

        try {
            $area      = Area::findOrFail($area_id);
            $areaTitle = $area->titulo;
            $area->delete();
            UserController::saveUserActivity($this->user->id, "Borrando departamento:$area->titulo", "Administrativo");

            return response()->json(['status' => 'ok', 'message' => "Area: $areaTitle borrada con éxito"]);
        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());

            return response()->json(
                ['status' => 'error', 'message' => 'Imposible eliminar, posee cargos asociados'], 500
            );
        }
    }


    /*************roles methods ********************/

    public function getRoles()
    {
        $roles      = Role::all();
        $rolesArray = [];
        $roles->each(
            function ($item) use (&$rolesArray) {
                $rolesArray[] = [
                    "id"          => $item->id,
                    "nombre"      => $item->nombre,
                    "descripcion" => $item->descripcion,
                    "area"        => $item->area->nombre,
                    "unidad"      => $item->produccion_unidad,
                    "produccion"  => $item->produccion_tipo
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $rolesArray]);
    }


    public function getRolesByArea($area_id)
    {
        $roles      = Role::where("area_id", $area_id)->get();
        $rolesArray = [];
        $roles->each(
            function ($item) use (&$rolesArray) {
                $rolesArray[] = [
                    "id"          => $item->id,
                    "nombre"      => $item->nombre,
                    "descripcion" => $item->descripcion,
                    "area"        => $item->area->titulo
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $rolesArray]);
    }


    public function getRoleById($role_id)
    {

        $role = Role::find($role_id);

        return response()->json(['status' => 'ok', 'role' => $role]);
    }


    public function createOrUpdateRole(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'nombre'      => 'required|min:3',
            'descripcion' => 'required|min:3',
            'sueldo'      => 'required|regex:/^\d*(\.\d{1,2})?/',
            'produccion'  => 'regex:/^\d*(\.\d{1,2})?/',
            'asistencia'  => 'regex:/^\d*(\.\d{1,2})?/',
            'hora_extra'  => 'regex:/^\d*(\.\d{1,2})?/',
            'bono_extra'  => 'regex:/^\d*(\.\d{1,2})?/',
            'area'        => 'required|numeric',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
                'max'      => 'El campo :attribute debe ser maximo :max',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $role = new Role();
        if ($req->has('role_id')) {
            $role = Role::findOrFail($req->input('role_id'));
        }

        if ($req->has('asistencia')) {
            $role->asistencia = str_replace(",", "", $req->input('asistencia'));
        }

        $role->produccion_tipo = null;
        if ($req->filled('produccion_tipo')) {
            $role->produccion_tipo = $req->input('produccion_tipo');
        }

        if ($req->has('unidad')) {
            $role->produccion_unidad = $req->input('unidad');
        }

        if ($req->has('produccion')) {
            $role->produccion = str_replace(",", "", $req->input('produccion'));
        }

        if ($req->has('hora_extra')) {
            $role->hora_extra = str_replace(",", "", $req->input('hora_extra'));
        }

        if ($req->has('bono_extra')) {
            $role->bono_extra = str_replace(",", "", $req->input('bono_extra'));
        }

        $role->nombre      = $req->input('nombre');
        $role->descripcion = $req->input('descripcion');
        $role->sueldo      = str_replace(",", "", $req->input('sueldo'));
        $role->area_id     = $req->input('area');
        $role->save();
        UserController::saveUserActivity($this->user->id, "Guardando datos del cargo:$role", "Administrativo");

        return response()->json(['status' => 'ok', 'message' => 'Cargo guardado con éxito']);

    }


    public function deleteRoleById($role_id)
    {
        try {
            $item  = Role::findOrFail($role_id);
            $title = $item->nombre;
            $item->delete();
            UserController::saveUserActivity($this->user->id, "Borrando el cargo:$title", "Administrativo");

            return response()->json(['status' => 'ok', 'message' => "Cargo: $title borrado con éxito"]);

        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());

            return response()->json(
                ['status' => 'error', 'message' => 'Imposible eliminar, posee registros asociados'], 500
            );
        }
    }


    /*************table methods ********************/


    public function getTables()
    {

        $tables = Table::all();
        $list   = $tables->map(
            function ($item, $key) {
                $item->activo = ($item->activo) ? "SI" : "NO";

                return $item;
            }
        );

        return response()->json(['status' => 'ok', 'list' => $list]);

    }


    public function getTableById($table_id)
    {

        $table = Table::find($table_id);

        return response()->json(['status' => 'ok', 'table' => $table]);
    }


    public function createOrUpdateTable(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'titulo'    => 'required|min:3',
            'ubicacion' => 'required|min:3',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $table = new Table();
        if ($req->has('table_id')) {
            $table = Table::findOrFail($req->input('table_id'));
        }

        $table->titulo    = $req->input('titulo');
        $table->ubicacion = $req->input('ubicacion');
        $table->activo    = ($req->has('activo')) ? 1 : 0;
        UserController::saveUserActivity($this->user->id, "Creando la mesa:$table", "Administrativo");
        $table->save();

        return response()->json(['status' => 'ok', 'message' => 'Mesa guardada con éxito']);

    }


    public function deleteTableById($table_id)
    {
        try {
            $item  = Table::findOrFail($table_id);
            $title = $item->titulo;
            $item->delete();
            UserController::saveUserActivity($this->user->id, "Borrando la mesa:$title", "Administrativo");

            return response()->json(['status' => 'ok', 'message' => "Mesa: $title borrado con éxito"]);
        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());

            return response()->json(
                ['status' => 'error', 'message' => 'Imposible eliminar, posee líneas asociadas'], 500
            );
        }
    }


    /****************lines method***************** */

    public function getLines()
    {
        $items      = Line::all();
        $linesArray = [];
        $items->each(
            function ($item) use (&$linesArray) {
                $linesArray[] = [
                    "id"          => $item->id,
                    "titulo"      => $item->titulo,
                    "descripcion" => $item->descripcion,
                    "mesa"        => $item->table->titulo,
                    "activo"      => ($item->activo) ? 'SI' : 'NO'
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $linesArray]);
    }


    public function getLinesByTable($table_id)
    {
        $lines      = Line::where("mesa_id", $table_id)->get();
        $linesArray = [];
        $lines->each(
            function ($item) use (&$linesArray) {
                $linesArray[] = [
                    "id"          => $item->id,
                    "titulo"      => $item->titulo,
                    "descripcion" => $item->descripcion,
                    "mesa"        => $item->table->titulo
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $linesArray]);
    }


    public function getLineById($line_id)
    {
        $item = Line::find($line_id);

        return response()->json(['status' => 'ok', 'line' => $item]);
    }


    public function createOrUpdateLine(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'titulo'      => 'required|min:3',
            'descripcion' => 'required|min:3',
            'mesa'        => 'required|numeric',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $line = new Line();
        if ($req->has('line_id')) {
            $line = Line::findOrFail($req->input('line_id'));
        } else {
            $max = Line::whereMesaId($req->input('mesa'))->count();
            if ($max == 2) {
                return response()->json(
                    ['status' => 'error', 'message' => "Imposible guardar mas de 2 lineas por mesa"], 400
                );
            }
        }

        $line->titulo      = $req->input('titulo');
        $line->descripcion = $req->input('descripcion');
        $line->mesa_id     = $req->input('mesa');
        $line->activo      = ($req->has('activo2')) ? 1 : 0;
        UserController::saveUserActivity($this->user->id, "Guardando la linea:$line", "Administrativo");
        $line->save();

        return response()->json(['status' => 'ok', 'message' => 'Linea guardada con éxito']);

    }


    public function deleteLineById($table_id)
    {
        try {
            $item  = Line::findOrFail($table_id);
            $title = $item->titulo;
            $item->delete();
            UserController::saveUserActivity($this->user->id, "Borrando la linea:$title", "Administrativo");

            return response()->json(['status' => 'ok', 'message' => "Linea: $title borrada con éxito"]);

        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());

            return response()->json(
                ['status' => 'error', 'message' => 'Imposible eliminar, posee produccion asociada'], 500
            );
        }

    }


    /****************employee method***************** */

    public function getPersons()
    {
        $persons     = Person::all();
        $personArray = [];
        $persons->each(
            function ($item) use (&$personArray) {
                $personArray[] = [
                    "id"      => $item->id,
                    "nombre"  => $item->nombre . ' ' . $item->apellido,
                    "cedula"  => $item->cedula,
                    "codigo"  => $item->codigo,
                    "ingreso" => $item->fecha_ingreso,
                    "cargo"   => $item->role->nombre,
                    "activo"  => ($item->activo) ? 'SI' : 'NO'
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $personArray]);
    }


    public function getPersonById($person_id)
    {

        $item = Person::find($person_id);

        return response()->json(['status' => 'ok', 'person' => $item]);
    }


    public function createOrUpdatePerson(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'nombre'        => 'required|min:3',
            'apellido'      => 'required|min:3',
            'area'          => 'required|numeric',
            'cargo'         => 'required|numeric',
            'cedula'        => 'required|min:3',
            'sexo'          => 'required',
            'codigo'        => 'required',
            'fecha_nac'     => 'required|date',
            'fecha_ingreso' => 'required|date',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
                'date'     => 'El campo :attribute no es una fecha correcta',
                'unique'   => 'El valor del campo :attribute ya esta registrado',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $person = new Person();
        if ($req->has('person_id')) {
            $person = Person::findOrFail($req->input('person_id'));
        }

        $person->nombre        = $req->input('nombre');
        $person->apellido      = $req->input('apellido');
        $person->cedula        = $req->input('cedula');
        $person->codigo        = $req->input('codigo');
        $person->fecha_nac     = $req->input('fecha_nac');
        $person->fecha_ingreso = $req->input('fecha_ingreso');
        $person->sexo          = $req->input('sexo');
        $person->area_id       = $req->input('area');
        $person->cargo_id      = $req->input('cargo');
        $person->activo        = ($req->has('activo')) ? 1 : 0;

        ///validate inactive
        if ($person->activo === 0 && !$req->filled('reason')) {
            return response()->json(
                ['status' => 'error', 'message' => "debe colocar una razon para inactivar al Empleado"], 400
            );
        }

        ///get role and validate if location matches
        $role = Role::findOrFail($person->cargo_id);

        Log::info($role->produccion_tipo);

        if ($role->produccion_tipo == "mesa") {
            if ($req->filled('mesa')) {
                $person->mesa_id = $req->input('mesa');
            } else {
                return response()->json(['status' => 'error', 'message' => "debe ingresar una mesa"], 400);
            }
        } else if ($role->produccion_tipo == "linea") {
            if ($req->filled('linea')) {
                $person->mesa_id  = $req->input('mesa');
                $person->linea_id = $req->input('linea');
            } else {
                return response()->json(['status' => 'error', 'message' => "debe ingresar una linea"], 400);
            }
        } else {
            $person->mesa_id  = '';
            $person->linea_id = '';
            Log::info("nada");
        }
        ///end validation


        if ($req->has('email')) {
            $person->email = $req->input('email');
        }

        if ($req->has('telefono')) {
            $person->telefono = $req->input('telefono');
        }

        if ($req->has('account')) {
            $person->cuenta_bancaria = $req->input('account');
        }

        if ($req->has('titular')) {
            $person->titular = $req->input('titular');
        }

        $person->razon_inactivo = (!$person->activo) ? $req->input('reason') : "";

        if ($req->has('titular_doc')) {
            $person->titular_doc = $req->input('tipo_doc') . $req->input('titular_doc');
        }

        if ($req->filled('banco')) {
            $person->banco_id = $req->input('banco');
        }

        try {
            $person->save();
            UserController::saveUserActivity($this->user->id, "Guardando el Empleado:$person", "Administrativo");
        } catch (\PDOException $e) {
            return response()->json(
                ['status' => 'error', 'message' => "el codigo del empleado ya se encuentra en uso"], 400
            );
        }


        return response()->json(['status' => 'ok', 'message' => 'Empleado guardado con éxito']);

    }


    public function deletePersonById($table_id)
    {
        try {
            $item  = Person::findOrFail($table_id);
            $title = $item->nombre;
            $item->delete();

            return response()->json(['status' => 'ok', 'message' => "Empleado: $title borrado con éxito"]);

        } catch (\PDOException $ex) {
            Log::error($ex->getMessage());

            return response()->json(
                ['status' => 'error', 'message' => 'Imposible eliminar, posee registros asociados'], 500
            );
        }

    }


    /****************bonus method***************** */

    public function getBonus()
    {
        $bonus      = Bonus::all();
        $bonusArray = [];
        $bonus->each(
            function ($item) use (&$bonusArray) {
                $bonusArray[] = [
                    "id"     => $item->id,
                    "titulo" => $item->titulo,
                    "tipo"   => $item->tipo,
                    "fecha"  => $item->fecha,
                    "monto"  => $item->monto,
                    "detail" => $item->getDetail()
                ];
            }
        );

        return response()->json(['status' => 'ok', 'list' => $bonusArray]);

    }


    public function getBonusById($bonus_id)
    {
        $bonus = Bonus::find($bonus_id);

        return response()->json(['status' => 'ok', 'bonus' => $bonus]);
    }


    public function createOrUpdateBonus(Request $req)
    {

        $validator = Validator::make(
            $req->all(), [
            'titulo'       => 'required|min:3',
            'tipo'         => 'required',
            'beneficiario' => 'required|numeric',
            'monto'        => 'required|regex:/^\d*(\.\d{1,2})?/',
            'fecha'        => 'required|date',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        $bonus = new Bonus();
        if ($req->has('bonus_id')) {
            $bonus = Bonus::findOrFail($req->input('bonus_id'));
        }

        $bonus->titulo       = $req->input('titulo');
        $bonus->tipo         = $req->input('tipo');
        $bonus->beneficiario = $req->input('beneficiario');
        $bonus->monto        = str_replace(",", "", $req->input('monto'));
        $bonus->fecha        = $req->input('fecha');
        $bonus->save();
        UserController::saveUserActivity($this->user->id, "Guardando el Bono:$bonus", "Administrativo");

        return response()->json(['status' => 'ok', 'message' => 'Bono guardado con éxito']);

    }


    public function deleteBonusById($bonus_id)
    {
        $item  = Bonus::findOrFail($bonus_id);
        $title = $item->titulo;
        $item->delete();
        UserController::saveUserActivity($this->user->id, "Borrando el Bono:$title", "Administrativo");

        return response()->json(['status' => 'ok', 'message' => "Bono: $title borrado con éxito"]);

    }


    /******************** layoffs module ***************************/
    public function getLayoffs()
    {
        $list = Layoff::orderBy('created_at', 'desc')->get();

        return view('pages.layoffs', ["list" => $list]);
    }

    public function getLayoffsAll()
    {
        $list = Layoff::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'ok', 'list' => $list]);
    }


    public function getLayoff($id)
    {
        $item = Layoff::findOrFail($id);

        return response()->json(['status' => 'ok', 'info' => $item]);
    }


    /**
     * @param Request $req
     * restore layoff
     *
     * @return mixed
     */
    public function restoreLayoff(Request $req)
    {
        $validator = Validator::make(
            $req->all(), [
            'layoff_id' => 'required|numeric',
        ], [
                'required' => 'El campo :attribute es requerido',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        try {
            DB::beginTransaction();
            $item   = Layoff::findOrFail($req->input("layoff_id"));
            $emp = $item->empleado_id;
            $name = $item->nombre;
            $item->delete();
            Person::withTrashed()->find($emp)->restore();
            UserController::saveUserActivity($this->user->id, "Reingreso del empleado: $name");
            DB::commit();
            return response()->json(['status' => 'ok', 'message' => "Reingreso de personal exitoso"]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => "Falla de registro egreso"], 500);
        }



    }


    public function saveLayoff(Request $req)
    {
        $validator = Validator::make(
            $req->all(), [
            'motivo'    => 'required|min:3',
            'person_id' => 'required|numeric',
            'fecha'     => 'required|date',
        ], [
                'required' => 'El campo :attribute es requerido',
                'min'      => 'El campo :attribute debe ser mayor a :min',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json(['status' => 'error', 'message' => $error], 400);
        }

        try {
            DB::beginTransaction();

            $emp   = Person::findOrFail($req->input("person_id"));
            $title = $emp->nombre . ' ' . $emp->apellido;
            ///register person layoff
            $layoff              = new Layoff();
            $layoff->nombre      = $title;
            $layoff->cargo       = $emp->role->nombre;
            $layoff->empleado_id = $emp->id;
            $layoff->fecha       = $req->input('fecha');
            $layoff->motivo      = $req->input('motivo');
            $layoff->save();
            //deleting person
            $emp->delete();
            UserController::saveUserActivity($this->user->id, "Registro de Egreso del empleado: $title");
            DB::commit();

            return response()->json(['status' => 'ok', 'message' => "Registro de Egreso exitoso"]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => "Falla de registro egreso"], 500);
        }


    }


}