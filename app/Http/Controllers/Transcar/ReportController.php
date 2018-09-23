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
            $table = 4;
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

}