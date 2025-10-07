<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeeNote;
use App\Models\Patient;

class BillingController extends Controller
{
    public function index(Patient $patient)
    {
        return view('billing.step1_fee_notes', [
            'patient' => $patient,
            'feeNotes' => FeeNote::where('patient_id', $patient->id)->where('is_invoice',false)->get()
        ]);
    }

    public function store(Request $request, Patient $patient)
    {
        // Save selected fee notes to session
        session([
            'invoice_fee_notes' => $request->fee_notes,
            'bill_to' => $request->bill_to
        ]);

        return redirect()->route('invoice.preview', $patient);
    }

    public function invoicePreview(Patient $patient)
    {
        $noteIds = session('invoice_fee_notes', []);
        $notes = FeeNote::whereIn('id', $noteIds)->get();
        $total = $notes->sum('line_total');

        return view('billing.step2_invoice_preview', compact('patient', 'notes', 'total'));
    }

    public function submitInvoice(Request $request, Patient $patient)
    {
        // Here you would normally store an invoice record.
        session(['invoice_paid' => $request->payment]);

        return redirect()->route('payment.page', $patient);
    }

    public function paymentPage(Patient $patient)
    {
        $noteIds = session('invoice_fee_notes', []);
        $notes = FeeNote::whereIn('id', $noteIds)->get();
        $total = $notes->sum('line_total');
        $paid = session('invoice_paid', 0);
        $owing = $total - $paid;

        return view('billing.step3_payment', compact('patient', 'notes', 'total', 'paid', 'owing'));
    }

    public function savePayment(Request $request, Patient $patient)
    {
        $noteIds = session('invoice_fee_notes', []);
        if (!empty($noteIds)) {
            FeeNote::whereIn('id', $noteIds)->update(['is_invoice' => true]);
        }
        
        session()->forget(['invoice_fee_notes', 'bill_to', 'invoice_paid']);

        return redirect()->route('fee-notes.index', $patient)->with('success', 'Payment and invoice recorded!');
    }
}

