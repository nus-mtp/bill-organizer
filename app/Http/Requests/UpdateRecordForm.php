<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use \App\Record;

class UpdateRecordForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Record $record)
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->prepare_requests();

        return [

        ];
    }

    private function prepare_requests()
    {
        $this->format_dates_to_database_compatible();
        $this->change_request_names_to_database_column_name();
        $this->filter_empty_fields($this->all());
        $this->sanitize_inputs();
    }

    private function format_dates_to_database_compatible()
    {
        $this->issue_date    = $this->format_date($this->issue_date, 'd/m/Y');
        $this->due_date      = $this->format_date($this->due_date, 'd/m/Y');
        $this->record_period = $this->format_date($this->period, 'M/Y');
    }

    private function format_date($date, $format_string)
    {
        return empty($date)? $date : Carbon::createFromFormat($format_string, $date);
    }

    private function change_request_names_to_database_column_name() {

        $this->record_period = $this->period;
        unset($this->record_period);

        $this->amount = $this->amount_due;
        unset($this->amount_due);
    }

    private function sanitize_inputs()
    {
       $this->amount = filter_var($this->amount, FILTER_SANITIZE_NUMBER_FLOAT);
    }
    private function filter_empty_fields($fields){
        $fileds = array_filter($fields);
        $this->replace($fileds);
    }
}
