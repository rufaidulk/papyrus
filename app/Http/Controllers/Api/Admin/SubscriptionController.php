<?php

namespace App\Http\Controllers\Api\Admin;

use Exception;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Http\Resources\Subscription\SubscriptionCollection;

class SubscriptionController extends ApiBaseController
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
        $response['data'] =  (new SubscriptionCollection(Subscription::paginate()))->response()->getData(true);

        return $this->success($response, 'Subscription List', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        $response['data'] = new SubscriptionResource($subscription);

        return $this->success($response, 'Subscription details', Response::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                Rule::in(array_keys(config('params.subscription.status')))
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $subscription->status = $request->status;
            $subscription->save();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new SubscriptionResource($subscription);

        return $this->success($response, 'Subscription updated', Response::HTTP_CREATED);
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
