<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
        'record_issuer_id', 'template_id'];

    protected static function boot() {
        parent::boot();

        static::deleting(function($record) {
            DB::transaction(function () use ($record) {
                $record->pages()->delete();

                if(Storage::exists($record->path_to_file)) {
                    Storage::delete($record->path_to_file);
                }

                // TODO: paths should be handled by filehandler helper
                // TODO: TempRecord and Record has different attr name that refers to RecordIssuer
                $user_id = auth()->id();
                $record_issuer = $record->issuer;
                $record_dir = "users/{$user_id}/record_issuers/{$record_issuer->id}/records/{$record->id}/";

                if(Storage::exists($record_dir)) {
                    Storage::deleteDirectory($record_dir);
                }
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

    public function setPeriodAttribute($value) {
        $this->attributes['period'] = Carbon::parse($value);
    }

    public function setIssueDateAttribute($value) {
        $this->attributes['issue_date'] = Carbon::parse($value);
    }

    public function setDueDateAttribute($value) {
        $this->attributes['due_date'] = Carbon::parse($value);
    }
}
