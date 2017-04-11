<?php
namespace Tests\Support;

use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Mockery\CountValidator\Exception;

trait TestHelperTrait
{

    private function generateUserInDb(){
        return $this->createRandUsers(1)[0];
    }

    // return s array of users
    private function createRandUsers($count = 1) {
        return factory(User::class, $count)->create();
    }

    private function makeRecordIssuerWoFactory($organizationName) {
        $recordIssuer = new RecordIssuer();
        $recordIssuer->name = $organizationName;
        $recordIssuer->type = $this->getBillOrgTypeId();
        return $recordIssuer;
    }

    private function createRandBillOrg($user) {
        return $this->createRandBillOrgs($user, 1)[0];
    }

    private function createRandBillOrgs($user, $count){
        $orgTypeName = RecordIssuerType::BILLORG_TYPE_NAME;
        return $this->createRandOrganizations($orgTypeName, $user, $count);
    }

    private function createRandStatementIssuer($user){
        return $this->createRandStatementIssuers($user, 1)[0];
    }

    private function createRandStatementIssuers($user, $count) {
        $orgTypeName = RecordIssuerType::BANK_STATEMENT_TYPE_NAME;
        return $this->createRandOrganizations($orgTypeName,$user,$count);
    }
    
    private function createRandOrganization($orgTypeName, User $user){
        return $this->createRandOrganizations($orgTypeName, $user, 1)[0];
    }

    private function createRandOrganizations($orgTypeName, User $user, $count){
        $orgs = factory(RecordIssuer::class, $orgTypeName, $count)->make();
        $user->record_issuers()->saveMany($orgs);
        return $orgs;
    }

    private function createRandStatement($billOrg) {
        return $this->createRandStatements($billOrg, 1)[0];
    }

    private function createRandStatements(RecordIssuer $statementIssuer, $count){
        $statements = $this->makeRandStatements($statementIssuer, $count);
        $statementIssuer->records()->saveMany($statements);
        return $statements;
    }

    private function makeRandStatements(RecordIssuer $statementIssuer, $count){
       $user = $statementIssuer->user;
       return $this->makeRandRecords($statementIssuer, $user, $count);
    }

    private function createRandBill(RecordIssuer $billOrg) {
        return $this->createRandBills($billOrg, 1)[0];
    }

    private function createRandBills(RecordIssuer $billOrg, $count) {
        $bills = $this->makeRandBills($billOrg, $count);
        $billOrg->records()->saveMany($bills);
        return $bills;
    }

    private function makeRandBills(RecordIssuer $billOrg, $count) {
        $user = $billOrg->user;
        return $this->makeRandRecords($billOrg,$user, $count);
    }


    private function makeRandRecords(RecordIssuer $issuer, $user, $count){
        $issuerType = $issuer->issuer_type->type;
        return factory(\App\Record::class,$issuerType , $count)
            ->make()
            ->each(function (Record $bill) use ($user) {
                $bill->user_id = $user->id;
            });
    }

    private function makeNonRandomRecordWoFactory(RecordIssuer $orgModel) {
        $user = $orgModel->user;
        $record = new Record();
        $record->temporary = false;
        $record->issue_date = Carbon::now();
        $record->period = $record->issue_date;
        $record->amount = 300.32;
        $record->path_to_file = "/sroage/com/example/1/file123.pdf";
        $record->user_id = $user->id;
        $record->record_issuer_id = $orgModel->id;
        // TODO: Should record's template be initialized?
        $record->template_id = factory(\App\Template::class)->create([
            'record_issuer_id' => $orgModel->id
        ])->id;
        return $record;
    }

    private function getBillOrgTypeId() {
        return RecordIssuerType::type(RecordIssuerType::BILLORG_TYPE_NAME)->first()->id;
    }

    private function prepareDbForTests() {
        $this->seed('RecordIssuerTypesSeeder');
    }
}
