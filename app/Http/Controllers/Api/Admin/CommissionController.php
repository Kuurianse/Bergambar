<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Http\Resources\Admin\CommissionResource;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);
        $commissions = Commission::with(['user', 'service'])
            ->latest()
            ->paginate($limit);

        return CommissionResource::collection($commissions);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \App\Http\Resources\Admin\CommissionResource
     */
    public function show(Commission $commission)
    {
        $commission->load(['user', 'service']);
        return new CommissionResource($commission);
    }
}