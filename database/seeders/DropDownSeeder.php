<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DropDown;
use App\Models\DropDownValue;

class DropDownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = DropDown::firstOrCreate(['code' => 'Title','name' => 'Title']);
        $preferred = DropDown::firstOrCreate(['code' => 'Preferred_Contact','name' => 'Preferred Contact']);
        $contactType = DropDown::firstOrCreate(['code' => 'Contact_Type','name' => 'Contact Type']);
        $paymentMethod = DropDown::firstOrCreate(['code' => 'Payment_Method','name' => 'Payment Method']);
        $visitCategory = DropDown::firstOrCreate(['code' => 'Visit_Category','name' => 'Visit Category']);
        $narrative = DropDown::firstOrCreate(['code' => 'Narrative','name' => 'Narrative']);
        $chargeGroupType = DropDown::firstOrCreate(['code' => 'Charge_Group_Type','name' => 'Charge Group Type']);

        $titles = ['Dr.', 'Mr', 'Mrs', 'Fr', 'Prof.', 'Sr', 'Ms'];
        foreach ($titles as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $title->id,
                'value' => $val
            ]);
        }

        $contacts = ['sms', 'email', 'letter'];
        foreach ($contacts as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $preferred->id,
                'value' => $val
            ]);
        }

        $contactTypes = ['Doctor', 'Referral', 'Solicitor', 'Other'];
        foreach ($contactTypes as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $contactType->id,
                'value' => $val
            ]);
        }

        $paymentMethods = ['Cash', 'Cheque', 'Credit Card', 'Debit Card'];
        foreach ($paymentMethods as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $paymentMethod->id,
                'value' => $val
            ]);
        }

        $visitCategories = ['General', 'Follow-up', 'Specialist'];
        foreach ($visitCategories as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $visitCategory->id,
                'value' => $val
            ]);
        }

        $narratives = ['Narrative'];
        foreach ($narratives as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $narrative->id,
                'value' => $val
            ]);
        }

        $chargeGroupTypes = ['Consultation', 'Procedure'];
        foreach ($chargeGroupTypes as $val) {
            DropDownValue::firstOrCreate([
                'drop_down_id' => $chargeGroupType->id,
                'value' => $val
            ]);
        }

    }
}
