<?php

namespace Database\Seeders;

use App\Models\DropDown;
use App\Models\DropDownValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DropDownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = DropDown::create(['name' => 'Title']);
        $preferred = DropDown::create(['name' => 'Preferred Contact']);

        $titles = ['Dr.', 'Mr', 'Mrs', 'Fr', 'Prof.', 'Sr', 'Ms'];
        foreach ($titles as $val) {
            DropDownValue::create(['drop_down_id' => $title->id, 'value' => $val]);
        }

        $contacts = ['sms', 'email', 'letter'];
        foreach ($contacts as $val) {
            DropDownValue::create(['drop_down_id' => $preferred->id, 'value' => $val]);
        }
    }
}
