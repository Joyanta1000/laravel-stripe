<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Stripe\Stripe as Stripe;
use Stripe\Account as StripeAccount;
use Stripe\Charge as StripeCharge;
use Stripe\Token as StripeToken;
use Stripe\Coupon as StripeCoupon;
use Stripe\Customer as StripeCustomer;
use Stripe\Error as StripeError;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Stripe::setApiKey(\Config::get('stripe.key'));

        $account = StripeAccount::all([
            'limit' => 10
        ]);

        /*echo '<pre>';
        print_r($account->data);
        echo '</pre>';*/

        return view('accounts.index', ['accounts' => $account->data]);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('accounts.create');      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        Stripe::setApiKey(\Config::get('stripe.key'));

        $email = $request->input('email');        
        $country = $request->input('country');        
        $managed = $request->input('managed');   

        try {

            $account = StripeAccount::create([
                "managed" => $managed,
                "country" => $country,
                "email" => $email 
            ]);

            return redirect('/accounts');

        } catch (StripeError\Base $e) {

            return $e->getMessage();
        }     
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($accountId)
    {
        Stripe::setApiKey(\Config::get('stripe.key'));

        try {

            $stripeAccount = StripeAccount::retrieve($accountId);

            /*echo '<pre>';
            print_r($stripeAccount);
            echo '</pre>';*/

            return view('accounts.show', ['account' => $stripeAccount]);    

        } catch (StripeError\Base $e) {

            return $e->getMessage();
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        Stripe::setApiKey(\Config::get('stripe.key'));

        try {

            $stripeAccount = StripeAccount::retrieve($id);

            return view('accounts.edit')->with('account', $stripeAccount);

        } catch (StripeError\Base $e) {

            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($accountId = '')
    {
        Stripe::setApiKey(\Config::get('stripe.key'));
        
        try {
            $account = StripeAccount::retrieve($accountId);
            $result = $account->delete();
        } catch (Stripe\Error\Base $e) {
            echo($e->getMessage());
        }

        return redirect('/accounts');
    }
}
