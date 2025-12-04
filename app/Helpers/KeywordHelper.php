<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class KeywordHelper
{
    /**
     * Replace placeholders (e.g. [FirstName]) with real runtime values.
     *
     * @param  string  $content
     * @param  object|null  $patient
     * @return string
     */
    public static function replaceKeywords($filePath, $patient = null): void
    {
        if (!$patient) {
            return;
        }

        $appointment = optional($patient->appointments()->first());

        self::preprocessSmartQuotes($filePath);

        $template = new TemplateProcessor($filePath);

        // ------------------------------------------------------------
        // 🧩 1️⃣ Collect all available data safely
        // ------------------------------------------------------------
        $data = [

            // 🩺 Patient Info
            'Title'             => optional($patient->title)->value,
            'FirstName'         => $patient->first_name ?? '',
            'SurName'           => $patient->surname ?? '',
            'PatientName'       => $patient->full_name ?? '',
            'PatientAddress'    => $patient->address ?? '',
            'PatientAddress1'   => $patient->address ?? '',
            'PatientSignature'  => asset('storage/' . $patient->patient_signature),//$patient->signature_url ??
            // 'PatientAddress2'   => $patient->address2 ?? '',
            // 'PatientAddress3'   => $patient->address3 ?? '',
            // 'PatientAddress4'   => $patient->address4 ?? '',
            'DOB'               => optional($patient->dob)->format('d/m/Y'),
            'Age'               => $patient->dob ? Carbon::parse($patient->dob)->age : '',
            'PatientType'       => $patient->patient_type ?? '',
            'Occupation'        => $patient->occupation ?? '',
            'PatientNeeds'      => optional($patient->appointments)->patient_need ?? '',
            'PatientNotes'      => $patient->patient_notes ?? '',
            'Physical'          => $patient->physical ?? '',
            'History'           => $patient->history ?? '',
            'PatientBalance'    => $patient->balance ?? '',
            'PostCode'          => $patient->postcode ?? '',
            'HomePhone'         => $patient->home_phone ?? '',
            'WorkPhone'         => $patient->work_phone ?? '',
            'Mobile'            => $patient->mobile ?? '',
            'Email'             => $patient->email ?? '',
            'Gender'            => $patient->gender ?? '',

            // 🧾 Account / Insurance
            'AccountTo'         => $patient->account_to ?? '',
            'AccountAddress1'   => $patient->account_address1 ?? '',
            'AccountAddress2'   => $patient->account_address2 ?? '',
            'AccountAddress3'   => $patient->account_address3 ?? '',
            'AccountAddress4'   => $patient->account_address4 ?? '',
            'Insurance'         => $patient->insurance ?? '',
            'InsurancePlan'     => $patient->insurance_plan ?? '',
            'InsuranceAddress'  => $patient->insurance_address ?? '',
            'PolicyNo'          => $patient->policy_no ?? '',

            // Next of Kin
            'NextOfKin'         => $patient->next_of_kin ?? '',
            'NextOfKinEmail'    => $patient->kin_email ?? '',
            'NextOfKinContact'  => $patient->kin_contact_no ?? '',
            'NOKRelationship'   => $patient->relationship ?? '',
            'NOKAddress'        => $patient->kin_address ?? '',

            // Doctor / Consultant
            'DoctorName'        => optional($patient->doctor)->name,
            'DoctorTitle'       => optional($patient->doctor)->salutation,
            'DoctorSignature'  =>  asset('storage/' . $patient->doctor->doctor_signature),//$patient->signature_url ??

            // 'DoctorFirstName'   => optional($patient->doctor)->first_name,
            // 'DoctorSurName'     => optional($patient->doctor)->surname,
            'DoctorAddress1'    => optional($patient->doctor)->address,
            'DoctorAddress2'    => optional($patient->doctor)->postcode,
            // 'DoctorAddress3'    => optional($patient->doctor)->address3,
            // 'DoctorAddress4'    => optional($patient->doctor)->address4,
            'DoctorEmail'       => optional($patient->doctor)->email,
            'SalutionDoctor'    => optional($patient->doctor)->salutation,
            'GPPhone'           => optional($patient->doctor)->phone,

            //Referral
            'ReferralName'      => optional($patient->referralDoctor)->name,
            'ReferralAddress1'  => optional($patient->referralDoctor)->address,
            'ReferralAddress2'  => optional($patient->referralDoctor)->postcode,
            // 'ReferralAddress3'  => optional($patient->referralDoctor)->address3,
            // 'ReferralAddress4'  => optional($patient->referralDoctor)->address4,
            // 'ReferralRef'       => optional($patient->referralDoctor)->ref,
            'ReferralEmail'     => optional($patient->referralDoctor)->email,
            'SalutionReferral'  => optional($patient->referralDoctor)->salutation,

            // Legal
            'LegalName'         => optional($patient->solicitorDoctor)->name,
            'LegalAddress1'     => optional($patient->solicitorDoctor)->address,
            'LegalAddress2'     => optional($patient->solicitorDoctor)->postcode,
            // 'LegalAddress3'     => optional($patient->solicitorDoctor)->address3,
            // 'LegalAddress4'     => optional($patient->solicitorDoctor)->address4,
            // 'LegalRef'          => optional($patient->solicitorDoctor)->ref,
            'LegalEmail'        => optional($patient->solicitorDoctor)->email,
            'SalutionLegal'     => optional($patient->solicitorDoctor)->salutation,

            // Other Contact
            'OtherTitle'        => optional($patient->otherDoctor)->title,
            // 'OtherFirstName'    => optional($patient->otherDoctor)->first_name,
            // 'OtherSurName'      => optional($patient->otherDoctor)->surname,
            'OtherName'         => optional($patient->otherDoctor)->name,
            'OtherAddress1'     => optional($patient->otherDoctor)->address,
            'OtherAddress2'     => optional($patient->otherDoctor)->postcode,
            // 'OtherAddress3'     => optional($patient->otherDoctor)->address3,
            // 'OtherAddress4'     => optional($patient->otherDoctor)->address4,
            // 'OtherRef'          => optional($patient->otherDoctor)->ref,
            'OtherEmail'        => optional($patient->otherDoctor)->email,

            // Appointment / Operation
            'AppDate' => $appointment->appointment_date 
                ? Carbon::parse($appointment->appointment_date)->format('d/m/Y') 
                : '',
            'AppTime' => $appointment->start_time,
            'AppType' => $appointment->appointment_type,
            'AppLocation' => optional($appointment->clinic)->name,
            'AptFootNote' => $appointment->appointment_note,
               
                
                'OpDate'            => optional($patient->operation)->date
                                    ? Carbon::parse($patient->operation->date)->format('d/m/Y') : '',
            'OpDateLong'        => optional($patient->operation)->date
                                    ? Carbon::parse($patient->operation->date)->format('l, d F Y') : '',
            'OpType'            => optional($patient->operation)->type,
            'OpTime'            => optional($patient->operation)->time,
            'OpLocation'        => optional($patient->operation)->location,
            'OpCode'            => optional($patient->operation)->code,
            'OpDescription'     => optional($patient->operation)->description,
            'AdmDate'           => optional($patient->admission)->date
                                    ? Carbon::parse($patient->admission->date)->format('d/m/Y') : '',
            'AdmTime'           => optional($patient->admission)->time,
            'DischargeDate'     => optional($patient->discharge_date)
                                    ? Carbon::parse($patient->discharge_date)->format('d/m/Y') : '',

            // Invoice / Financial
            'InvoiceDescription'    => optional($patient->invoice)->description,
            'InvoiceFee'            => optional($patient->invoice)->fee,
            'VAT'                   => optional($patient->invoice)->vat,
            'NET'                   => optional($patient->invoice)->net,
            'InvoiceTotal'          => optional($patient->invoice)->total,
            'InvoiceRef'            => optional($patient->invoice)->ref,
            'InvoiceCode'           => optional($patient->invoice)->code,
            'InvoiceAdmissionDate'  => optional($patient->invoice)->admission_date,
            'InvoiceProcedureDate'  => optional($patient->invoice)->procedure_date,
            'AmountReceived'        => optional($patient->invoice)->amount_received,

            // Medical / Lab
            'Diagnosis'         => $patient->diagnosis ?? '',
            'ClinicDiagnosis'   => $patient->clinic_diagnosis ?? '',
            'WT'                => $patient->wt ?? '',
            'WP'                => $patient->wp ?? '',
            'GLU'               => $patient->glu ?? '',
            'HBA1C'             => $patient->hba1c ?? '',
            'CHOL'              => $patient->chol ?? '',
            'LDL'               => $patient->ldl ?? '',
            'TGS'               => $patient->tgs ?? '',
            'HDL'               => $patient->hdl ?? '',
            'CR'                => $patient->cr ?? '',
            'MICROLAB'          => $patient->microlab ?? '',
            'GGT'               => $patient->ggt ?? '',
            'AST'               => $patient->ast ?? '',
            'TSH'               => $patient->tsh ?? '',
            'OTHERDIAB'         => $patient->otherdiab ?? '',

            // Misc
            'AltContact'        => $patient->alt_contact ?? '',
            'Label'             => $patient->label ?? '',
            'Envelope'          => $patient->envelope ?? '',
            'pin'               => $patient->pin ?? '',
            'AptTest'           => $patient->apt_test ?? '',
            'Date'              => now()->format('d/m/Y'),
            'CurrentDate'       => now()->format('d/m/Y'),


            // Consultant
            'ConsultantName'   => optional($patient->consultant)->name ?? '',
            'ConsultantAddress'=> optional($patient->consultant)->address ?? '',
            'ConsultantPhone'  => optional($patient->consultant)->phone ?? '',
            'ConsultantFax'    => optional($patient->consultant)->fax ?? '',
            'ConsultantEmail'  => optional($patient->consultant)->email ?? '',
        ];

        $signaturePath = public_path('assets/img/banner-lief-img.png');
        // $signaturePath = $patient->signature_file
        // ? public_path('storage/patient_pictures/' . $patient->signature_file)
        // : public_path('assets/img/banner-lief-img.png');
    

        // ------------------------------------------------------------
        // 3️⃣ Replace placeholders dynamically
        // ------------------------------------------------------------
        foreach ($data as $key => $value) {
            if ($key !== 'PatientSignature' && $key !== 'DoctorSignature'  ) { // skip signature here
                $template->setValue($key, $value ?? '');
            }
        }

       // Paths for signatures
        $patientSignaturePath = $patient->patient_signature 
            ? storage_path('app/public/' . $patient->patient_signature) 
            : public_path('assets/img/banner-lief-img.png');

        $doctorSignaturePath = optional($patient->doctor)->doctor_signature
            ? storage_path('app/public/' . $patient->doctor->doctor_signature)
            : public_path('assets/img/banner-lief-img.png');

        // Replace placeholders with images using local paths
        if (file_exists($patientSignaturePath)) {
            $template->setImageValue('PatientSignature', [
                'path' => $patientSignaturePath,
                'width' => 150,
                'height' => 100,
                'ratio' => true
            ]);
        }

        if (file_exists($doctorSignaturePath)) {
            $template->setImageValue('DoctorSignature', [
                'path' => $doctorSignaturePath,
                'width' => 150,
                'height' => 100,
                'ratio' => true
            ]);
        }


        $template->saveAs($filePath);

        self::convertPlaceholdersToBrackets($filePath);

    }

    protected static function preprocessSmartQuotes(string $filePath): void
    {
        $zip = new ZipArchive;
        $tmp = $filePath . '_tmp.zip';
        copy($filePath, $tmp);

        if ($zip->open($tmp) === true) {
            $content = $zip->getFromName('word/document.xml');
            if ($content !== false) {
                // Replace smart quotes «...» and normal brackets [...] with ${...}
                $content = preg_replace(['/«(.*?)»/', '/\[(.*?)\]/'], ['${$1}', '${$1}'], $content);
                $zip->addFromString('word/document.xml', $content);
            }
            $zip->close();

            copy($tmp, $filePath);
            unlink($tmp);
        }
    }

    protected static function convertPlaceholdersToBrackets(string $filePath): void
    {
        $zip = new ZipArchive;
        $tmp = $filePath . '_brackets.zip';
        copy($filePath, $tmp);

        if ($zip->open($tmp) === true) {
            $content = $zip->getFromName('word/document.xml');
            if ($content !== false) {
                // Convert ${Something} → [Something]
                $content = preg_replace('/\$\{([^}]+)\}/', '[$1]', $content);
                $zip->addFromString('word/document.xml', $content);
            }
            $zip->close();

            copy($tmp, $filePath);
            unlink($tmp);
        }
    }

}
