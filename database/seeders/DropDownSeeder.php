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
        $title = DropDown::firstOrCreate(['name' => 'Title']);
        $preferred = DropDown::firstOrCreate(['name' => 'Preferred Contact']);
        $contactType = DropDown::firstOrCreate(['name' => 'Contact Type']);
        $paymentMethod = DropDown::firstOrCreate(['name' => 'Payment Method']);
        $visitCategory = DropDown::firstOrCreate(['name' => 'Visit Category']);

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
    }
}
