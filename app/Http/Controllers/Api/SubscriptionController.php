<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Subscription\SubscriptionResource;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends ApiBaseController
{
    private $service;

    public function __construct(SubscriptionService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * Displays the active subscription if any.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscription = Subscription::where('user_id', Auth::id())->where('status', Subscription::STATUS_ACTIVE)
            ->first();
            
        if (empty($subscription)) {
            return $this->success(['data' => []], 'No Active Subscription', Response::HTTP_OK);
        }

        $response['data'] =  new SubscriptionResource($subscription);

        return $this->success($response, 'Active Subscription', Response::HTTP_OK);
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
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $this->service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new SubscriptionResource($this->service->subscription);

        return $this->success($response, 'Subscription purchased successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
