<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\NewMail;

class MailController extends Controller
{
    public function send(Request $request)
	{
	    $details = [
	    	'to' => $request->email,
	    	'from' => 'noreply@cadorim.com',
	    	'subject' => $request->subject,
	        'title' => $request->subject,
	        "body" 	=> $request->body
	    ];
   
    	//\Mail::to($request->to)->send(new \App\Mail\NewMail($details));

	    /*if (Mail::failures()) {
			return view('email/send', [
	      	'status'  => false,
				'data'    => $details,
				'message' => 'Nnot sending mail.. retry again...'
	    	]);
	    }*/

		return view('email/send', [
	      	'status'  => true,
			'data'    => $details,
			'message' => 'Your details mailed successfully'
	    ]);
	}
}
