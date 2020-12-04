<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\SubscriptionPlan\SubscriptionPlanResource;
use App\Http\Resources\SubscriptionPlan\SubscriptionPlanCollection;

class SubscriptionPlanController extends ApiBaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response['data'] =  (new SubscriptionPlanCollection(SubscriptionPlan::paginate()))->response()
            ->getData(true);

        return $this->success($response, 'Subscription Plan List', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:subscription_plans'],
            'num_of_installments' => 'required|integer|min:1',
            'installment_amount' => 'required|integer|min:1',
            'installment_period_days' => 'required|integer|min:1',
            'period_label' => 'required|string|max:255',
            'status' => [
                'required',
                Rule::in(array_keys(config('params.subscriptionPlan.status')))
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $subscriptionPlan = new SubscriptionPlan();
            $subscriptionPlan->name = $request->name;
            $subscriptionPlan->num_of_installments = $request->num_of_installments;
            $subscriptionPlan->installment_amount = $request->installment_amount;
            $subscriptionPlan->installment_period_days = $request->installment_period_days;
            $subscriptionPlan->period_label = $request->period_label;
            $subscriptionPlan->status = $request->status;

            $subscriptionPlan->save();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new SubscriptionPlanResource($subscriptionPlan);

        return $this->success($response, 'New subscription plan created', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $response['data'] = new SubscriptionPlanResource($subscriptionPlan);

        return $this->success($response, 'Subscription plan details', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 
                Rule::unique('subscription_plans')->ignore($subscriptionPlan->id)
            ],
            'num_of_installments' => 'required|integer|min:1',
            'installment_amount' => 'required|integer|min:1',
            'installment_period_days' => 'required|integer|min:1',
            'period_label' => 'required|string|max:255',
            'status' => [
                'required',
                Rule::in(array_keys(config('params.subscriptionPlan.status')))
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $subscriptionPlan->name = $request->name;
            $subscriptionPlan->num_of_installments = $request->num_of_installments;
            $subscriptionPlan->installment_amount = $request->installment_amount;
            $subscriptionPlan->installment_period_days = $request->installment_period_days;
            $subscriptionPlan->period_label = $request->period_label;
            $subscriptionPlan->status = $request->status;

            $subscriptionPlan->save();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new SubscriptionPlanResource($subscriptionPlan);

        return $this->success($response, 'Subscription plan updated', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        //
    }
}
