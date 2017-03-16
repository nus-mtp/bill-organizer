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
        // moving request authorization here is good practice.
        // remember there is Gate::
        // ps: there is a difference between ability and policy
        // policy is defined on models, ability on user.
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
            // form validation rules here
        ];
    }

    private function prepare_requests()
    {
        $this->format_dates_to_database_compatible();
        $this->change_request_names_to_database_column_name();
        $this->filter_empty_fields($this->all());
        $this->unset_unused();
        $this->sanitize_inputs();
    }

    private function format_dates_to_database_compatible()
    {
        $this['issue_date']    = $this->format_date($this->issue_date, 'd/m/Y');
        $this['due_date']      = $this->format_date($this->due_date, 'd/m/Y');
        $this['record_period'] = $this->format_date($this->record_period, 'M/Y');
    }

    // TODO: Bug: shouldn't be Carbon instance. Fix RecordController after fixing this
    private function format_date($date, $format_string)
    {
        return empty($date)? $date : Carbon::createFromFormat($format_string, $date);
    }

    private function change_request_names_to_database_column_name() {
        // mismatch between input name and database field name is good and intentional
        // having input names same as database name make it easier for hacker to launch
        // database related attack
        $this['period'] = $this->record_period;
        unset($this['record_period']);

        $this['amount'] = $this->amount_due;
        unset($this['amount_due']);
    }

    private function sanitize_inputs()
    {
    }
    private function filter_empty_fields($fields){
        $fields = array_filter($fields);
        $this->replace($fields);
    }

    private function unset_unused()
    {
        unset($this['_token']);
        unset($this['_method']);
    }
}
