<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf; // use Pdf, not PhpOffice

class ReportController extends Controller
{
    public function entireDayReport(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = $request->input('date');

        // Load clinics and appointments data
        $clinics = Clinic::with(['appointments' => function($q) use ($date) {
            $q->whereDate('appointment_date', $date)->with('patient','patient.consultant',
                'patient.doctor');
        }])->companyOnly() ->get();

        return view('reports.entire-day', compact('clinics', 'date'));
    }

    public function emailEntireDayReport(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'email' => 'required|email',
            'attach_pdf' => 'sometimes|boolean',
        ]);

        $date = $request->input('date');
        $email = $request->input('email');
        $attachPdf = $request->boolean('attach_pdf');

        $clinics = Clinic::with(['appointments' => function($q) use ($date) {
            $q->whereDate('appointment_date', $date)
            ->with('patient', 'patient.consultant', 'patient.doctor');
        }])
        ->companyOnly() 
        ->get();

        $htmlContent = view('reports.entire-day', compact('clinics', 'date'))->render();

        try {
            if ($attachPdf) {
                // $pdf = Pdf::loadView('reports.entire-day', compact('clinics', 'date'))->output();
                $pdf = Pdf::loadView('reports.entire-day', ['clinics' => $clinics, 'date' => $date, 'pdfExport' => true])->output();

                Mail::send([], [], function ($message) use ($email, $htmlContent, $pdf, $date) {
                    $message->to($email)
                            ->subject("Entire Day Report for {$date}")
                            ->html($htmlContent)  // Use this, not setBody
                            ->attachData($pdf, "entire_day_report_{$date}.pdf", [
                                'mime' => 'application/pdf',
                            ]);
                });

            } else {
                Mail::send([], [], function ($message) use ($email, $htmlContent, $date) {
                    $message->to($email)
                            ->subject("Entire Day Report for {$date}")
                            ->setBody($htmlContent, 'text/html');
                });
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send email: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to send email.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}
