<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Company Created</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; margin: 20px auto; font-family: Arial, sans-serif;">
    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 20px; background-color: #004085; text-align: center;">
                            <img src="{{ asset('theme/assets/img/logo.png') }}" alt="Company Logo" width="150" style="display: block; margin: 0 auto;">
                        </td>
                    </tr>
                
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333333;">Hello {{ $patient->first_name }} {{ $patient->surname }},</h2>
                            <p style="color: #555555; font-size: 16px; line-height: 1.5;">
                                {!! $messageContent ?? 'Please find your documents attached.' !!}
                            </p>
                
                            @if(!empty($documents))
                            <p style="margin-top: 20px; color: #555555; font-size: 16px;">The following documents are attached:</p>
                            <ul style="color: #555555; font-size: 16px;">
                                @foreach($documents as $doc)

                                    <li>{{ $doc->name }}: {{ basename($doc->file_path) }}</li>
                                @endforeach
                            </ul>
                            @endif
                
                            <p style="margin-top: 20px;">
                                Thank you,<br>
                                Concept Medical System
                            </p>
                        </td>
                    </tr>
                
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px; background-color: #e9ecef; text-align: center; font-size: 13px; color: #6c757d;">
                            <p style="margin: 0;">This is an automated message from the system.</p>
                            <p style="margin: 5px 0 0;">&copy; {{ date('Y') }} {{ env('APP_NAME', 'Concept Medical') }}. All rights reserved.</p>
                        </td>
                    </tr>
                
                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>