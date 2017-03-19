<?php

namespace App\Http\Controllers;

use App\RecordIssuer;
use App\Statistics;
use Illuminate\Http\Request;

class StatsController extends Controller
{
   private $stats;

    public function __construct() {
        $this->stats = new Statistics();
    }

    public function index(RecordIssuer $record_issuer){

        return response()->json([
            "hello"=>"world"
        ]);
    }

    public function show(RecordIssuer $record_issuer, $month){
        return response()->json([
               'billCount' => $this->stats->getNumOfPastMonthsBills($record_issuer, $month),
                'amount' => $this->stats->getBillsTotalAmountForPastMonths($record_issuer, $month)
            ]
        );
    }
}
