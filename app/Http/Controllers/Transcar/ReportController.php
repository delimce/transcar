<?php

/**
 * Created by PhpStorm.
 * User: delimce
 * Date: 9/17/2018
 * Time: 9:41 PM
 */

namespace App\Http\Controllers\Transcar;

use App\Models\Appearance;
use App\Models\Config;
use App\Models\Production;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use Log;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\UserLog;


class ReportController extends BaseController
{

    private $user;
    private $currentdate;


    public function __construct(Request $req)
    {
        $this->middleware('profiles:1|3'); ///perfiles requeridos
        $myUser = $req->session()->get("myUser");
        if (!is_null($myUser)) {
            $this->user = User::findOrFail($myUser->id);
        }
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

        return view(
            'pages.report01',
            [
                "init" => $start,
                "end" => $end,
                "table" => $table,
                "tableInfo" => $tableInfo,
                "results" => $records,
                "production" => json_decode(json_encode($production), true),
                "days" => $this->getDaysBetween($start, $end)
            ]
        );
    }


    public function report2Index(Request $req)
    {

        $now = Carbon::now();
        $end = Carbon::parse($now->format('Y-m-d'))->daysInMonth;
        $months = [];
        $months[] = ["number" => $now->month, "name" => self::nameOfMonth($now->month)];
        $last_month = $now->subMonth();
        $months[] = ["number" => $last_month->month, "name" => self::nameOfMonth($last_month->month)];


        $month = 12;
        $type = 1; ///quincena
        $dt = Carbon::now();
        $dt->month = $month; // would force month

        if ($type == 1) {
            $dt->day = 1;
            $start = Carbon::parse($dt->format('Y-m-d'));
            $dt->day = 15;
            $end2 = Carbon::parse($dt->format('Y-m-d'));
        } else {
            $dt->day = 15;
            $start = Carbon::parse($dt->format('Y-m-d'));
            $dt->day = Carbon::parse($dt->format('Y-m-d'))->daysInMonth;
            $end2 = Carbon::parse($dt->format('Y-m-d'));
        }

        $results = $this->getNominaResult($start, $end2);
        $factor = Config::select("caja_paleta")->first();

        return view('pages.report02', ["months" => $months, "days" => $end, "factor" => $factor, "results" => $results]);
    }


    static function findDateinAppearance($dates, $hours, $date)
    {
        $thours = 0; //hours of work
        foreach ($dates as $i => $dateIn) {
            if ($dateIn == $date) {
                $thours += $hours[$i];
            }
        }
        return ($thours == 0) ? '<b>NO</b>' : $thours . 'h';
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
        $dates = [];

        while (!$dateInit->gt($dateEnd)) {
            $temp = [
                "number" => $dateInit->dayOfWeekIso,
                "name" => self::nameOfDay($dateInit->dayOfWeek),
                "date" => $dateInit->format('Y-m-d'),
                "day" => $dateInit->day
            ];
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

        $results = $this->getNominaResult($start, $end);
        $factor = Config::select("caja_paleta")->first();
        $data_nomina = array();

        foreach ($results as $i => $res) {

              //total n# boxes + special boxes
            $ncajas = intval($res->ncajas) + intval($res->ncajas_especial);
            if ($res->unidad == 'paleta') {
                $total_unity = floor($ncajas / $factor->caja_paleta);
                $unity = 'P';
            } else {
                $total_unity = $ncajas;
                $unity = 'C';
            }
            $unity = ($total_unity > 0) ? $unity : '';

            $salary = self::prorateSalary($res->id, $res->base, $res->fecha_ingreso);
            $prod = $total_unity * $res->produccion;

            $temp = array(
                "nombre" => str_limit($res->nombre, 25),
                "codigo" => $res->codigo,
                "cedula" => $res->cedula,
                "cargo" => str_limit($res->cargo, 30),
                "salario" => '<b>' . $salary . '</b>',
                "bono_cargo" => '<b>' . $res->bono_extra . '</b>',
                "bono_asistencia" => '<b>' . $res->asistencia . '</b>',
                "horas_ex_dias" => $res->diashe,
                "horas_ex_costo" => '<b>' . number_format($res->extra, 2) . '</b>',
                "n_cajas" => $total_unity . $unity,
                "produccion" => '<b>' . number_format($prod, 2) . '</b>',
                "total" => '<b>' . number_format(self::totalSalary($salary, $res->bono_extra, $res->asistencia, $res->extra, $prod), 2) . '</b>'
            );

            $data_nomina[] = $temp;
        }

        return response()->json(['status' => 'ok', 'detail' => $data_nomina]);

    }


    static function nameOfDay($day)
    {
        switch ($day) {

            case 1:
                return "Lun.";
                break;
            case 2:
                return "Mar.";
                break;
            case 3:
                return "Mié.";
                break;
            case 4:
                return "Jue.";
                break;
            case 5:
                return "Vie.";
                break;
            case 6:
                return "Sab.";
                break;
            default:
                return "Dom.";
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


    static function getTypeOfDocument($type)
    {
        switch ($type) {

            case 'V':
                return "01";
                break;
            case 'P':
                return "02";
                break;
            default:
                return "08";
                break;
        }
    }


    private function getNominaResult($start, $end)
    {
        $start .= ' 00:00:00';
        $end .= ' 23:59:59';

        $query = "SELECT
                    e.id,
                   	e.codigo,
                    e.cedula,
       	            e.fecha_ingreso,
                   	e.titular,
                   	e.titular_doc,
                   	e.cuenta_bancaria,
	                concat(e.nombre,' ',e.apellido) as nombre,
	                c.nombre as cargo,
                    round( c.sueldo / 2 ) AS base,
                    c.bono_extra,
                    IF	(	( SELECT count( * ) FROM tbl_inasistencia i WHERE i.empleado_id = e.id AND i.justificada = 0 AND i.fecha BETWEEN '$start' And '$end' ),0,c.asistencia) AS asistencia,
                      -- bono horas extras
                    (select sum(a.hora_extra) FROM tbl_asistencia a WHERE a.empleado_id = e.id 
	                    and a.fecha BETWEEN '$start' AND '$end') as diashe, 
                    if((select sum(a.hora_extra) FROM tbl_asistencia a WHERE a.empleado_id = e.id 
                    and a.fecha BETWEEN '$start' AND '$end')>2,c.hora_extra,0) AS extra,
                      -- produccion
                    c.produccion_tipo as type,
                    CASE c.produccion_tipo
                     when 'total' then 
                        (SELECT
                            sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha) and a.especial = 0
                            WHERE
                            a.empleado_id = e.id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))
                      when 'mesa'  then
                            (SELECT	sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha) and a.especial = 0
                            WHERE
                            a.empleado_id = e.id and p.mesa_id = e.mesa_id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))
                      when 'linea'  then
                            (SELECT	sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha) and a.especial = 0
                            WHERE
                            a.empleado_id = e.id and p.mesa_id = e.mesa_id and p.linea_id = e.linea_id
                            and p.fecha BETWEEN '$start' and '$end'
                            and time(p.fecha) BETWEEN a.hora_entrada and IFNULL(a.hora_salida,'16:00'))	
                        ELSE 0	
                    END as ncajas,
                    -- cajas produccion especial
                    (SELECT	sum(p.cajas)
                            FROM
                            tbl_asistencia AS a
                            INNER JOIN tbl_produccion AS p ON a.fecha = date(p.fecha) and a.especial = 1
                            WHERE
                            a.empleado_id = e.id and p.mesa_id = a.mesa_id
                            and p.fecha BETWEEN '$start' and '$end'
                        and time(p.fecha) BETWEEN a.hora_entrada and a.hora_salida
                            ) as ncajas_especial,
                    c.produccion,
                    c.produccion_unidad as unidad
                    -- produccion
                    FROM
                        tbl_cargo AS c
                        INNER JOIN tbl_empleado AS e ON e.cargo_id = c.id 
                    WHERE	e.activo = 1 AND e.deleted_at IS NULL";

        return DB::select(DB::raw($query));
    }


    static function totalSalary($base, $bonus, $appear, $extra, $prod)
    {

        return (floatval($base) + floatval($bonus) + floatval($appear) + floatval($extra) + floatval($prod));

    }


    /**prorate salary
     *
     * @param $personId
     * @param $base
     * @param $date
     *
     * @return float|int
     */
    static function prorateSalary($personId, $base, $dateIn)
    {
        $date = Carbon::parse($dateIn);
        $now = Carbon::now();
        $diff = $date->diffInDays($now);
        if ($diff < 15) { //less of quincena
            $salary = number_format(($base * 2) / 30, 2);
            $days = Appearance::whereEmpleadoId($personId)->where("fecha", ">=", $dateIn)->count();
            $base = $salary * $days;
        }

        return number_format($base, 2);
    }


    public function getReportToBank(Request $req)
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
            $ref = $dt->format('Ym') . '1';
        } else {
            $dt->day = 15;
            $start = Carbon::parse($dt->format('Y-m-d'));
            $dt->day = Carbon::parse($dt->format('Y-m-d'))->daysInMonth;
            $end = Carbon::parse($dt->format('Y-m-d'));
            $ref = $dt->format('Ym') . '2';
        }

        $nomina = $this->getNominaResult($start, $end);
        $settings = Config::find(1);
        $body = '';
        $n = 0;
        $TOTAL = 0;
        foreach ($nomina as $item) {
            $n++;
            if ($item->unidad == 'paleta') {
                $total_unity = floor($item->ncajas / $settings->caja_paleta);
            } else {
                $total_unity = $item->ncajas;
            }

            $prod = $total_unity * $item->produccion;
            $doc_type = Str::substr($item->titular_doc, 0, 1); //type of document, V, P, E
            $doc = Str::substr($item->titular_doc, 1); //document

            $total = self::totalSalary($item->base, $item->bono_extra, $item->asistencia, $item->extra, $prod);
            $TOTAL += $total;
            $body .= self::getTypeOfDocument(
                $doc_type
            ) . ';' . $doc . ';' . $item->titular . ';' . $item->cuenta_bancaria . ';' . $total . ';' . $ref . $n;
            $body .= PHP_EOL;
        }

        $date = $end->format('d/m/Y');
        $header = $settings->empresa_rif . ';' . $settings->empresa_cuenta . ';' . count(
            $nomina
        ) . ';' . $TOTAL . ';' . $date . ';' . $ref . PHP_EOL;

        return response($header . $body, 200)
            ->header('Content-Type', 'text/plain');

    }


    /**
     * report of users's logs
     */
    public function getLogReport()
    {
        $list = UserLog::orderBy('created_at', 'desc')->get();

        return view('pages.reportLogs', ["list" => $list]);
    }


    public function getLogReportDetail($id)
    {

        $item = UserLog::findOrFail($id);
        $detail = [
            "usuario" => $item->user->info(),
            "ip" => $item->ip_acc,
            "fecha" => $item->created_at,
            "tipo" => $item->tipo,
            "detalle" => $item->actividad,
            "cliente" => $item->info_cliente
        ];

        return response()->json(['status' => 'ok', 'detail' => $detail]);

    }

}