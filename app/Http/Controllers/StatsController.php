<?php

namespace App\Http\Controllers;

use App\RecordIssuer;
use App\Statistics;
use Illuminate\Database\Eloquent\Collection;
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
                'total' => $this->stats->getBillsTotalAmountForPastMonths($record_issuer, $month),
                'data' => $this->getBillData($record_issuer, $month)
            ]
        );
    }

    private function getBillData(RecordIssuer $recordIssuer, $month){
        $data = $this->stats->getBillsForPastMonths($recordIssuer, $month);
        $chunkSize = $this->calculateChunkSize($data,7);
        $data = $this->breakDataIntoChunks($data, $chunkSize);
        $data = $this->reduceChunks($data);
        $data = $this->formatDataForChart($data);
        return $data;
    }

    private function breakDataIntoChunks(Collection $data , $howManyInAChunk){
        $processed = $data->chunk($howManyInAChunk);
        return $processed;
    }

    private function reduceChunks(Collection $data) {
        $processed = $data->mapWithKeys(function(Collection $chunk){
            $key = $chunk->first()->issue_date->format('d/m/Y');
            $value = $chunk->sum('amount');
            return [$key => $value];
        });
        return $processed;
    }

    private function calculateChunkSize(Collection $data, $minimumSize){
       $numOfRecords = count($data);
       $optimumChunkSize = $numOfRecords <= $minimumSize ? 1 : (int)($numOfRecords/$minimumSize);
       return $optimumChunkSize;
    }

    private function formatDataForChart(Collection $data){
        $labels = $data->keys();
        $data = $data->values();
        return compact('labels', 'data');

    }
}
