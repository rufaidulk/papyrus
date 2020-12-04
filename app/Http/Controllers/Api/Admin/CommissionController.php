<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiBaseController;
use Exception;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Commission\CommissionResource;
use App\Http\Resources\Commission\CommissionCollection;

class CommissionController extends ApiBaseController
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
        $response['data'] =  (new CommissionCollection(Commission::paginate()))->response()->getData(true);

        return $this->success($response, 'Commission List', Response::HTTP_OK);
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
            'clap_count' => ['required', 'integer', 'min:1', 'unique:commissions'],
            'amount' => ['required', 'integer', 'min:1'],
            'status' => [
                'required',
                Rule::in(array_keys(config('params.commission.status')))
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $commission = new Commission();
            $commission->clap_count = $request->clap_count;
            $commission->amount = $request->amount;
            $commission->status = $request->status;

            $commission->save();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new CommissionResource($commission);

        return $this->success($response, 'New commission created', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(Commission $commission)
    {
        $response['data'] = new CommissionResource($commission);

        return $this->success($response, 'Commission details', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission)
    {
        $validator = Validator::make($request->all(), [
            'clap_count' => ['required', 'integer', 'min:1', Rule::unique('commissions')->ignore($commission->id)],
            'amount' => ['required', 'integer', 'min:1'],
            'status' => [
                'required',
                Rule::in(array_keys(config('params.commission.status')))
            ],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $commission->clap_count = $request->clap_count;
            $commission->amount = $request->amount;
            $commission->status = $request->status;

            $commission->save();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response['data'] = new CommissionResource($commission);

        return $this->success($response, 'Commission updated', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission)
    {
        //
    }
}
