<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;
use Carbon\Carbon;


class DocumentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $templateBody = <<<EOT
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>{{ \$template_title ?? 'Document' }}</title>
                <style>
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        line-height: 1.6;
                        padding: 30px;
                        color: #333;
                    }
                    .header, .footer {
                        margin-bottom: 30px;
                    }
                    .header h2, .footer h4 {
                        margin: 0;
                    }
                    .section {
                        margin-bottom: 25px;
                    }
                    .bold {
                        font-weight: bold;
                    }
                    .info-table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .info-table td {
                        padding: 6px 10px;
                        vertical-align: top;
                    }
                    .divider {
                        border-top: 1px solid #ccc;
                        margin: 25px 0;
                    }
                </style>
            </head>
            <body>

                {{-- ▶ Header --}}
                <div class="header">
                    <h2>{{ \$consultant_name ?? 'Consultant Name' }}</h2>
                    <p>{!! nl2br(e(\$hospital_address ?? 'Hospital Address')) !!}</p>
                    <p><strong>Phone:</strong> {{ \$phone_number ?? 'Phone Number' }}</p>
                    <p><strong>Email:</strong> {{ \$email ?? 'Email' }}</p>
                </div>

                <p><strong>Date:</strong> {{ \$date ?? now()->format('d/m/Y') }}</p>

                {{-- ▶ Recipient --}}
                <div class="section">
                    <p><strong>To:</strong></p>
                    <p>Dr {{ \$doctor_name ?? 'Doctor Name' }}</p>
                    <p>{!! nl2br(e(\$doctor_address ?? 'Doctor Address')) !!}</p>
                </div>

                {{-- ▶ Patient Info --}}
                <div class="section">
                    <p><strong>Re: {{ \$patient_name ?? 'Patient Name' }}</strong></p>
                    <table class="info-table">
                        <tr>
                            <td class="bold">DOB:</td>
                            <td>{{ \$patient_dob ?? '01/01/1970' }}</td>
                            <td class="bold">Phone:</td>
                            <td>{{ \$patient_phone ?? '---' }}</td>
                        </tr>
                        <tr>
                            <td class="bold">Mobile:</td>
                            <td>{{ \$patient_mobile ?? '---' }}</td>
                            <td class="bold">BRC #:</td>
                            <td>{{ \$brc_number ?? '---' }}</td>
                        </tr>
                        <tr>
                            <td class="bold">Age:</td>
                            <td colspan="3">{{ \$patient_age ?? '---' }}</td>
                        </tr>
                        <tr>
                            <td class="bold">Address:</td>
                            <td colspan="3">{{ \$patient_address ?? '---' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- ▶ Main Letter Body --}}
                <div class="divider"></div>
                <div class="section">
                    <p>Dear Dr {{ \$doctor_first_name ?? \$doctor_name ?? 'Doctor' }},</p>
                    <p>{!! nl2br(e(\$letter_body ?? 'Letter body goes here.')) !!}</p>
                    <p>Kind regards,</p>
                    <p><strong>{{ \$consultant_name ?? 'Consultant' }}</strong></p>
                </div>

                {{-- ▶ CC --}}
                @if(!empty(\$patient_name) && !empty(\$patient_address))
                <div class="footer">
                    <h4>cc: {{ \$patient_name }}</h4>
                    <p>{{ \$patient_address }}</p>
                </div>
                @endif

            </body>
            </html>
            EOT;

        DocumentTemplate::firstOrCreate([
            'name' => 'GP Letter',
            'type' => 'letter',
            'template_body' => $templateBody,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $formTemplate = <<<EOT
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <title>BRC Booking Form</title>
            <style>
                body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                color: #333;
                padding: 30px;
                }
                h2 {
                text-align: center;
                margin-bottom: 30px;
                }
                table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                }
                th, td {
                border: 1px solid #ccc;
                padding: 8px;
                vertical-align: top;
                }
                th {
                background: #f0f0f0;
                text-align: left;
                }
                .section-title {
                margin: 30px 0 10px;
                font-weight: bold;
                font-size: 16px;
                }
                .checkbox {
                font-family: DejaVu Sans, sans-serif;
                }
            </style>
            </head>
            <body>

            <h2>BRC Booking Form</h2>

            <div class="section-title">Admission Details</div>
            <table>
                <tr>
                <th><strong>Date of Admission</strong></th>
                <td>{{ \$admission_date ?? '' }}</td>
                <th><strong>Time of Admission</strong></th>
                <td>{{ \$admission_time ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Hospital Name</strong></th>
                <td>{{ \$hospital_name ?? '' }}</td>
                <th><strong>Estimated Length of Stay</strong></th>
                <td>{{ \$stay_length ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Consultant</strong></th>
                <td>{{ \$consultant_name ?? '' }}</td>
                <th><strong>Previous In-Patient</strong></th>
                <td>{{ \$previous_inpatient ?? '' }}</td>
                </tr>
            </table>

            <div class="section-title">Patient Details</div>
            <table>
                <tr>
                <th><strong>Surname</strong></th>
                <td>{{ \$patient_surname ?? '' }}</td>
                <th><strong>First Name</strong></th>
                <td>{{ \$patient_first_name ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Date of Birth</strong></th>
                <td>{{ \$patient_dob ?? '' }}</td>
                <th><strong>Title</strong></th>
                <td>{{ \$patient_title ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Sex</strong></th>
                <td colspan="3">
                    <span class="checkbox">{!! \$sex_male ? '&#x2611;' : '&#x2610;' !!} Male</span> &nbsp;
                    <span class="checkbox">{!! \$sex_female ? '&#x2611;' : '&#x2610;' !!} Female</span>
                </td>
                </tr>
                <tr>
                <th><strong>Mobile</strong></th>
                <td>{{ \$patient_mobile ?? '' }}</td>
                <th><strong>Email</strong></th>
                <td>{{ \$patient_email ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Home Address</strong></th>
                <td colspan="3">{{ \$patient_address ?? '' }}</td>
                </tr>
            </table>

            <div class="section-title">Insurance Details</div>
            <table>
                <tr>
                <th><strong>Insurer</strong></th>
                <td>{{ \$insurance_name ?? '' }}</td>
                <th><strong>Policy Number</strong></th>
                <td>{{ \$insurance_policy_number ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Plan</strong></th>
                <td>{{ \$insurance_plan ?? '' }}</td>
                <th><strong>Verified</strong></th>
                <td>
                    <span class="checkbox">{{ \$insurance_verified ? '&#x2611;' : '&#x2610;' }} Yes</span>
                </td>
                </tr>
            </table>

            <div class="section-title">Procedure Details</div>
            <table>
                <tr>
                <th><strong>Procedure</strong></th>
                <td>{{ \$procedure_name ?? '' }}</td>
                <th><strong>Code</strong></th>
                <td>{{ \$procedure_code ?? '' }}</td>
                </tr>
                <tr>
                <th><strong>Date</strong></th>
                <td>{{ \$procedure_date ?? '' }}</td>
                <th><strong>Time</strong></th>
                <td>{{ \$procedure_time ?? '' }}</td>
                </tr>
            </table>

            <div class="section-title">Dietary Requirements</div>
            <p>
                <span class="checkbox">{{ \$diet_normal ? '&#x2611;' : '&#x2610;' }} Normal</span> &nbsp;
                <span class="checkbox">{{ \$diet_diabetic ? '&#x2611;' : '&#x2610;' }} Diabetic</span> &nbsp;
                <span class="checkbox">{{ \$diet_coeliac ? '&#x2611;' : '&#x2610;' }} Coeliac</span> &nbsp;
                <span class="checkbox">{{ \$diet_other ? '&#x2611;' : '&#x2610;' }} Other</span>
            </p>

            <div class="section-title">Doctor's Signature</div>
            <p>Doctor’s Signature: ______________________________</p>
            <p>Date: {{ \$signature_date ?? '' }}</p>

            </body>
            </html>
            EOT;

        DocumentTemplate::firstOrCreate([
            'name' => 'BRC Booking Form',
            'type' => 'form',
            'template_body' => $formTemplate,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}