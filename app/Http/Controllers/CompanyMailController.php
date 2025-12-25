<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyEmail;
use Webklex\PHPIMAP\ClientManager;

class CompanyMailController extends Controller
{
    private function getCompanyOrDefault(){
        $companyId = current_company_id(); // your helper
        if($companyId){
            return Company::findOrFail($companyId);
        }

        // Superadmin or no company → fallback to default .env
        return (object)[
            'mail_host' => env('MAIL_HOST'),
            'mail_port' => env('MAIL_PORT'),
            'mail_username' => env('MAIL_USERNAME'),
            'mail_password' => env('MAIL_PASSWORD'),
            'mail_encryption' => env('MAIL_ENCRYPTION'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS'),
            'mail_from_name' => env('MAIL_FROM_NAME'),
            'name' => 'Default Email'
        ];
    }

    private function connect($company){
        $cm = new ClientManager();
    
        $client = $cm->make([
            'host'          => $company->mail_host ?: env('IMAP_HOST', 'mail.conceptmedicalpm.ie'),
            'port'          => $company->mail_port ?: env('IMAP_PORT', 993),
            'encryption'    => $company->mail_encryption ?: env('IMAP_ENCRYPTION', 'ssl'),
            'validate_cert' => true,
            'username'      => $company->mail_username ?: env('MAIL_USERNAME'),
            'password'      => $company->mail_password ?: env('MAIL_PASSWORD'),
            'protocol'      => 'imap',
        ]);
    
        try {
            $client->connect();
        } catch (\Webklex\PHPIMAP\Exceptions\ConnectionFailedException $e) {
            dd("IMAP Connection Failed: ".$e->getMessage());
        }
    
        return $client;
    }
    
    
    
    

    public function folders(){
        $company = $this->getCompanyOrDefault();
        $client = $this->connect($company);
        $folders = $client->getFolders();
        return view('companies.mail.index', compact('folders','company'));
    }

    public function folder($folder){
        $company = $this->getCompanyOrDefault();
        $client = $this->connect($company);
        $mailFolder = $client->getFolder($folder);
        $messages = $mailFolder->messages()->all()->get();
        $folders = $client->getFolders();
        return view('companies.mail.index', compact('messages','folder','company','folders'));
    }

    public function message($id){
        $company = $this->getCompanyOrDefault();
        $client = $this->connect($company);
        $folders = $client->getFolders();
        $message = null;

        foreach($folders as $folder){
            try {
                $msg = $folder->messages()->getMessageById($id);
                if($msg){
                    $message = $msg;
                    break;
                }
            } catch (\Exception $e){
                continue;
            }
        }

        if(!$message){
            abort(404, "Message not found");
        }

        return view('companies.mail.index', compact('message','company','folders'));
    }

    public function send(Request $request){
        $company = $this->getCompanyOrDefault();

        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        config([
            'mail.mailers.smtp.host' => $company->mail_host,
            'mail.mailers.smtp.port' => $company->mail_port,
            'mail.mailers.smtp.username' => $company->mail_username,
            'mail.mailers.smtp.password' => $company->mail_password,
            'mail.mailers.smtp.encryption' => $company->mail_encryption,
        ]);

        Mail::to($request->to)->send(new CompanyEmail($request->subject, $request->body));

        return back()->with('success','Email sent successfully!');
    }
}
