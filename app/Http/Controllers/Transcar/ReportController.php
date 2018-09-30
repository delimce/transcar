<?php
/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 9/17/2018
 * Time: 9:41 PM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\Appearance;
use App\Models\Production;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Log;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;


class ReportController extends BaseController
{

    private $user;
    private $currentdate;

    public function __construct(Request $req)
    {
        $this->middleware('profiles:1|3'); ///perfiles requeridos
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser))
            $this->user = User::findOrFail($myUser->id);
        $this->currentdate = Carbon::today();

    }

    public function report1Index(Request $req)
    {

        $now = Carbon::now();

        if ($req->filled('desde')) {
            $start = $req->input('desde');
        } else {
            $start = $now->startOfWeek()->format('Y-m-d'); //monday
        }

        if ($req->filled('hasta')) {
            $end = $req->input('hasta');
        } else {
            $end = $now->startOfWeek()->addDays(4)->format('Y-m-d'); //friday
        }

        if ($req->filled('mesa')) {
            $table = $req->input('mesa');
        } else {
            $table = '';
        }

        $ap = new Appearance();
        $pro = new Production();
        $records = $ap->getAppearance($start, $end, $table);
        $production = $pro->getProduction($start, $end, $table);
        $tableInfo = Table::find($table);

        return view('pages.report01',
            ["init" => $start,
                "end" => $end,
                "table" => $table,
                "tableInfo" => $tableInfo,
                "results" => $records,
                "production" => json_decode(json_encode($production), True),
                "days" => $this->getDaysBetween($start, $end)]);
    }

    public function report2Index(Request $req)
    {

        $now = Carbon::now();
        $end = Carbon::parse($now->format('Y-m-d'))->daysInMonth;
        $months = array();
        $months[] = array("number" => $now->month, "name" => self::nameOfMonth($now->month));
        $last_month = $now->subMonth();
        $months[] = array("number" => $last_month->month, "name" => self::nameOfMonth($last_month->month));


        return view('pages.report02', ["months" => $months, "days" => $end]);
    }


    static function findDateinAppearance($dates, $hours, $date)
    {
        $index = array_search($date, $dates);
        return ($index === false) ? '<b>NO</b>' : $hours[$index] . 'h';
    }

    static function findProdByDate($date, $production, $key)
    {
        $index = array_search($date, array_column($production, 'fecha'));
        return ($index === false) ? '' : $production[$index][$key];
    }

    private function getDaysBetween($initDate, $endDate)
    {
        $dateInit = Carbon::parse("$initDate 00:00:00");
        $dateEnd = Carbon::parse("$endDate 00:00:00");
        $dates = array();

        while (!$dateInit->gt($dateEnd)) {
            $temp = array("number" => $dateInit->dayOfWeekIso, "name" => self::nameOfDay($dateInit->dayOfWeek), "date" => $dateInit->format('Y-m-d'), "day" => $dateInit->day);
            $dates[] = $temp;
            $dateInit->addDays(1);
        }

        return $dates;
    }


    public function getNominaHtml(Request $req)
    {

        $month = $req->input("month");
        $type = intval($req->input("quincena")); ///quincena
        $dt = Carbon::now();
        $dt->month = $month; // would force month

        if ($type == 1) {
            $dt->day = 1;
            $start = Carbon::parse($dt->format('Y-m-d'));
            $dt->day = 15;
            $end = Carbon::parse($dt->format('Y-m-d'));
        } else {
            $dt->day = 15;
            $start = Carbon::parse($dt->format('Y-m-d'));
            $dt->day = Carbon::parse($dt->format('Y-m-d'))->daysInMonth;
            $end = Carbon::parse($dt->format('Y-m-d'));
        }

        Log::info($start);
        Log::info($end);

        $results = $this->getNominaResult($start, $end);

        return view('pages.parts.nomina_detail', ["results" => $results]);


    }


    static function nameOfDay($day)
    {
        switch ($day) {

            case 1:
                return "Lunes";
                break;
            case 2:
                return "Martes";
                break;
            case 3:
                return "Miércoles";
                break;
            case 4:
                return "Jueves";
                break;
            case 5:
                return "Viernes";
                break;
            case 6:
                return "Sabado";
                break;
            default:
                return "Domingo";
                break;
        }
    }

    static function nameOfMonth($month)
    {
        switch ($month) {

            case 1:
                return "Enero";
                break;
            case 2:
                return "Febrero";
                break;
            case 3:
                return "Marzo";
                break;
            case 4:
                return "Abril";
                break;
            case 5:
                return "Mayo";
                break;
            case 6:
                return "Junio";
                break;
            case 7:
                return "Julio";
                break;
            case 8:
                return "Agosto";
                break;
            case 9:
                return "Septiembre";
                break;
            case 10:
                return "Octubre";
                break;
            case 11:
                return "Noviembre";
                break;
            default:
                return "Diciembre";
                break;
        }
    }

    private function getNominaResult($start, $end)
    {

        $query = "SELECT
                    e.id,
                   	e.cedula,
	                concat(e.nombre,' ',e.apellido) as nombre,
                    round( c.sueldo / 2 ) AS base,
                    c.bono_extra,
                    IF	(	( SELECT count( * ) FROM tbl_inasistencia i WHERE i.empleado_id = e.id AND i.fecha BETWEEN '$start' And '$end' ),0,c.asistencia) AS asistencia,
                        (SELECT	sum(ABS( IF ( isnull( a.hora_salida ), 16, HOUR ( a.hora_salida ) ) - HOUR ( a.hora_entrada ) ) - 8 ) 
                    FROM tbl_asistencia a WHERE a.empleado_id = e.id and a.fecha BETWEEN '$start' And '$end' and
                        ABS( IF ( isnull( a.hora_salida ), 16, HOUR ( a.hora_salida ) ) - HOUR ( a.hora_entrada ) ) > 8 
                        )* c.hora_extra AS extra,
                      -- produccion
                    c.produccion_tipo as type,
                    CASE c.produccion_tipo
                     when 'total' then 
                        (SELECT
                            sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha)
                            WHERE
                            a.empleado_id = e.id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))
                      when 'mesa'  then
                            (SELECT	sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha)
                            WHERE
                            a.empleado_id = e.id and p.mesa_id = e.mesa_id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))
                      when 'linea'  then
                            (SELECT	sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha)
                            WHERE
                            a.empleado_id = e.id and p.mesa_id = e.mesa_id and p.linea_id = e.linea_id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))	
                        ELSE 0	
                    END as ncajas,
                    c.produccion
                    -- produccion
                    FROM
                        tbl_cargo AS c
                        INNER JOIN tbl_empleado AS e ON e.cargo_id = c.id 
                    WHERE	e.activo = 1 AND e.deleted_at IS NULL";

        return DB::select(DB::raw($query));
    }

    static function totalSalary($base, $bonus, $appear, $extra, $prod)
    {

        return round(floatval($base) + floatval($bonus) + floatval($appear) + floatval($extra) + floatval($prod), 2);

    }

}