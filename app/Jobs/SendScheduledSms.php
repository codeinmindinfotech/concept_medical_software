<?php

namespace App\Jobs;

use App\Models\Communication;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendScheduledSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $patientId,
        public string $message,
        public Carbon $scheduledAt
    ) {}

    public function handle()
    {
        // Send the SMS here
        // SmsService::sendToPatient($this->patientId, $this->message);

        // Mark communication as received
        Communication::where('patient_id', $this->patientId)
            ->where('message', $this->message)
            ->update(['received' => true]);
    }
}

