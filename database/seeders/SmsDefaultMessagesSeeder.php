<?php

namespace Database\Seeders;

use App\Models\SmsDefaultMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsDefaultMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        SmsDefaultMessage::firstOrCreate([
            'title' => 'Appointment Reminder',
            'description' => 'An appointment has been made for you to see Dr. Test on [AptDate] at [AptTime]. Please contact the office to confirm.'
        ]);

        SmsDefaultMessage::firstOrCreate([
            'title' => 'Clinic Cancellation',
            'description' => 'Unfortunately the clinic dated [AptDate] has had to be cancelled. Please contact the office on 01-123456.'
        ]);
    }
}
