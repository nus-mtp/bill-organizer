<?php
namespace App;


use Carbon\Carbon;

class Statistics {


    public function countBills(RecordIssuer $billorg){
        return $billorg->records()->count();
    }

    public function countCurrMonthBills(RecordIssuer $billorg){
        $records = $billorg ->records();
        $from  = new Carbon('first day of this month');
        $until = new Carbon('last day of this month');
        return $records->whereBetween('issue_date',array($from, $until))->count();
    }

    public function getNumOfPastMonthsBills(RecordIssuer $billorg, $months){
        if ((int)$months === 0) return $this->countCurrMonthBills($billorg);
        $records = $billorg ->records();
        $until = new Carbon('last day of this month');
        $from  = $until->copy()->subMonth($months);

        return $records->whereBetween('issue_date',array($from, $until))->count();
    }

    public function getBillsTotalAmountForCurrMonth(RecordIssuer $billorg){
        return $billorg->records()->currMonthBills()->sum('amount');
    }

    public function getBillsForPastMonths(RecordIssuer $billorg, $months){
        return $billorg->records()->pastMonthsBills($months)
            ->select( 'issue_date', 'amount')->orderBy('issue_date')->get();
    }

    public function getBillsTotalAmountForPastMonths(RecordIssuer $billorg, $months){
        return $billorg->records()->pastMonthsBills($months)->sum('amount');
    }
}