<?php

namespace App;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// TODO: Do we actually need a TempRecord?? Or is Record sufficient?
class TempRecord extends Model
{
    public $fillable = ['path_to_file', 'record_issuer_id', 'template_id', 'issue_date', 'issue_date', 'due_date',
        'period', 'amount'];

    public $casts = [
        'period' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($temp_record) {
            DB::transaction(function () use ($temp_record) {
                $temp_record->pages()->delete();

                if(Storage::exists($temp_record->path_to_file)) {
                    Storage::delete($temp_record->path_to_file);
                }

                // TODO: paths should be handled by filehandler helper
                // Delete everything in the temp_record dir
                $user_id = auth()->id();
                $record_issuer = $temp_record->record_issuer;
                $temp_images_dir_path = "tmp/users/{$user_id}/record_issuers/{$record_issuer->id}/records/" .
                    "{$temp_record->id}/";
                if(Storage::exists($temp_images_dir_path)) {
                    Storage::deleteDirectory($temp_images_dir_path);
                }
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function record_issuer()
    {
        return $this->belongsTo(RecordIssuer::class);
    }

    public function issuer_type()
    {
        return $this->record_issuer->issuer_type();
    }

    public function pages()
    {
        return $this->hasMany(TempRecordPage::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    // TODO: clean up this copy-pasted code
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
            $this->attributes['amount'] = $value;
        } catch (Exception $e) {
            $this->attributes['amount'] = null;
        }
    }
}
