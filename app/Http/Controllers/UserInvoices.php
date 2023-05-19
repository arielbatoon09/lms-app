<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use App\Models\Invoices;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class UserInvoices extends Controller
{

    // Get the specific user invoice list
    public function index()
    {
        try {
            $user_id = auth()->user()->id;

            return Inertia::render('Invoices', [
                'invoice' => Invoices::with('book')
                    ->where('user_id', $user_id)
                    ->get()
                    ->map(function ($book) {
                        return [
                            'id' => $book->id,
                            'user_id' => $book->user_id,
                            'book_id' => $book->book_id,
                            'action' => $book->action,
                            'book_fees' => $book->book->book_fees,
                            'book_name' => $book->book->book_name,
                        ];
                    })
            ]);
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    // Handling PayPal Payment
    public function handlePayment(Request $request)
    {
        try {
            if ($request->mop == 'Paypal') {
                // Perform necessary validations on the request data
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('invoices.user'),
                        "cancel_url" => route('invoices.user'),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => "PHP",
                                "value" => $request->book_fees,
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    foreach ($response['links'] as $links) {
                        
                        if ($links['rel'] == 'approve') {
                            // Construct the approval URL with the access token
                            $approvalUrl = $links['href'];

                            // Add CORS headers to the response
                            $response = new Response();
                            $response->headers->set('Access-Control-Allow-Origin', '*');
                            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
                            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');

                            // Update the Invoices model with action = 1
                            $invoice = Invoices::where('id', $request->id)->where('action', 0)->first();
                            if ($invoice) {
                                // Update the action column to 1 (paid)
                                $invoice->action = 1;
                                $invoice->save();
                            }

                            // Redirect the user to the PayPal checkout page
                            return $response->setContent('<script>window.location.href = "' . $approvalUrl . '";</script>');
                        }
                    }
                    return redirect()->route('cancel.payment')->with('error', 'Something went wrong.');
                } else {
                    $errorMessage = isset($response['message']) ? implode(' ', $response['message']) : 'Something went wrong.';
                    return redirect()->route('invoices.user')->with('error', $errorMessage);
                }
            } else if ($request->mop == 'Over-The-Counter') {
                // Update the Invoices model with action = 1
                $invoice = Invoices::where('id', $request->id)->where('action', 0)->first();
                if ($invoice) {
                    $invoice->action = 2;
                    $invoice->save();
                }
                return redirect()->back()
                    ->with('message', '202');
            } else {
                return 'Not Found Mode of Payment';
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
