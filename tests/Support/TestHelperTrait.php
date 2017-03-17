<?php
namespace Tests\Support;

use App\Record;
use App\RecordIssuer;
use App\RecordIssuerType;
use App\User;
use Illuminate\Contracts\Console\Kernel;
use Mockery\CountValidator\Exception;

trait TestHelperTrait
{
    // return s array of users
    private function generateUsersInDatabase($count = 1) {
        return factory(User::class, $count)->create();
    }

    private function getBillOrgTypeId() {
        return RecordIssuerType::type(RecordIssuerType::BILLORG_TYPE_NAME)->first()->id;
    }

    private function createRecordIssuerModel($organizationName) {
        $recordIssuer = new RecordIssuer();
        $recordIssuer->name = $organizationName;
        $recordIssuer->type = $this->getBillOrgTypeId();
        return $recordIssuer;
    }

    // precondition: user must exist
    private function generateOrganizationWithFactoryForUser($orgTypeName, User $user){
        $org = $this->generateOrganizationsWithFactoryForUser($orgTypeName, $user, 1)[0];
        return $org;
    }

    private function generateBillOrgs($user, $count){
        $orgTypeName = RecordIssuerType::BILLORG_TYPE_NAME;
        return $this->generateOrganizationsWithFactoryForUser($orgTypeName, $user, $count);
    }

    private function generateBanks($user, $count) {
        $orgTypeName = RecordIssuerType::BANK_STATEMENT_TYPE_NAME;
        return $this->generateOrganizationsWithFactoryForUser($orgTypeName,$user,$count);
    }

    private function generateOrganizationsWithFactoryForUser($orgTypeName, User $user, $count){
        $orgs = factory(RecordIssuer::class, $orgTypeName, $count)->make();
        $user->record_issuers()->saveMany($orgs);
        return $orgs;
    }
    private function prepareDbForTests() {
        $this->seed('RecordIssuerTypesSeeder');
    }
    private function generateBills(RecordIssuer $billOrg, $count) {
        $user = $billOrg->user;
        $bills = factory(\App\Record::class, RecordIssuerType::BILLORG_TYPE_NAME,$count)->make()->each(function(Record $bill) use ($user){
            $bill->user_id = $user->id;
        });
        $billOrg->records()->saveMany($bills);
        return $bills;
    }

}
