<?php
namespace App;


use Carbon\Carbon;

class Statistics {


    public function countBills(RecordIssuer $billorg){
        return $billorg->records()->temporary(false)->count();
    }

    public function countCurrMonthBills(RecordIssuer $billorg){
        $records = $billorg ->records()->temporary(false);
        $from  = DateHelper::firstDayOfCurrMonth();
        $until = DateHelper::lastDayOfCurrMonth();
        return $records->whereBetween('issue_date',array($from, $until))->count();
    }

    public function getNumOfPastMonthsBills(RecordIssuer $billorg, $months){
        if ((int)$months === 0) return $this->countCurrMonthBills($billorg);
        $records = $billorg ->records()->temporary(false);
        $until = DateHelper::lastDayOfCurrMonth();
        $from  = $until->copy()->subMonth($months);

        return $records->whereBetween('issue_date',array($from, $until))->count();
    }

    public function getBillsTotalAmountForCurrMonth(RecordIssuer $billorg){
        return $billorg->records()->temporary(false)->currMonthBills()->sum('amount');
    }

    public function getBillsForPastMonths(RecordIssuer $billorg, $months){
        return $billorg->records()->temporary(false)->pastMonthsBills($months)
            ->select( 'issue_date', 'amount')->orderBy('issue_date')->get();
    }

    public function getBillsTotalAmountForPastMonths(RecordIssuer $billorg, $months){
        return $billorg->records()->temporary(false)->pastMonthsBills($months)->sum('amount');
    }
}