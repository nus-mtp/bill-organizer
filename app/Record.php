<?php

namespace App;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Helpers\StorageHelper;

class Record extends Model
{

    /**
     * Cast attr => data_type only on get. This doesn't apply on set
     * @var array
     */
    protected $casts = [
        'period' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date'
    ];

    public $fillable = ['issue_date', 'due_date', 'period', 'amount', 'path_to_file',
        'record_issuer_id', 'template_id', 'temporary'];

    protected static function boot() {
        parent::boot();

        static::deleting(function($record) {
            DB::transaction(function () use ($record) {
                $record->pages()->delete();

                StorageHelper::deleteRecordFiles($record);
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issuer() {
        return $this->belongsTo(RecordIssuer::class, 'record_issuer_id');
    }

    // return RecordIssuerType Object
    public function issuer_type()
    {
        return $this->issuer->issuer_type();
    }

    public function issuer_name()
    {
        return $this->issuer->name;
    }

    // return RecordIssuerType name in String
    public function issuer_type_name()
    {
        return $this->issuer_type->type;
    }

    public function is_issuer_type_bill()
    {
        return $this->issuer_type_name() === RecordIssuerType::BILLORG_TYPE_NAME;
    }

    public function pages()
    {
        return $this->hasMany(RecordPage::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function scopeCurrMonthBills($query) {
        $from  = DateHelper::firstDayOfCurrMonth();
        $until = DateHelper::lastDayOfCurrMonth();
        return $query->whereBetween('issue_date',[$from, $until]);
    }

    public function scopePastMonthsBills($query, $months){
        if ((int)$months === 0) {return self::scopeCurrMonthBills($query);}
        $until = DateHelper::lastDayOfCurrMonth();
        $from = $until->copy()->subMonth($months);
        return $query->whereBetween('issue_date', [$from, $until]);
    }

    public function scopeTemporary($query, $bool) {
        return $query->where('temporary', $bool);
    }

    public function setPeriodAttribute($value) {
        try {
            $this->attributes['period'] =  Carbon::parse($value);
        } catch (Exception $e) {
            $this->attributes['period'] = null;
        }
    }

    public function setIssueDateAttribute($value) {
        try {
            $this->attributes['issue_date'] = Carbon::parse($value);
        } catch (Exception $e) {
            $this->attributes['issue_date'] = null;
        }
    }

    public function setDueDateAttribute($value) {
        try {
            $this->attributes['due_date'] = Carbon::parse($value);
        } catch (Exception $e) {
            $this->attributes['due_date'] = null;
        }
    }

    public function setAmountAttribute($value) {
        // trim $ if any
        $value = str_replace('$', '', $value);
        try {
            if (!is_numeric($value)) {
                throw new \League\Flysystem\Exception("Amount is not numeric");
            }
            $this->attributes['amount'] = $value;
        } catch (Exception $e) {
            $this->attributes['amount'] = null;
        }
    }
}
