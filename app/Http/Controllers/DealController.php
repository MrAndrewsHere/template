<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\StoreDealData;
use App\Data\UpdateDealData;
use App\Http\Requests\DealIndexRequest;
use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Http\Resources\DealResource;
use App\Models\Deal;
use App\Service\Interfaces\DealServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DealController extends Controller
{
    public function __construct(private readonly DealServiceInterface $dealService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(DealIndexRequest $request): AnonymousResourceCollection
    {
        return DealResource::collection(
            $this->dealService->index($request->getProductId())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return DealResource
     */
    public function store(StoreDealRequest $request)
    {
        return DealResource::make(
            $this->dealService->create(
                StoreDealData::fromRequest($request)
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal): DealResource
    {
        return new DealResource(
            $this->dealService->get($deal)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDealRequest $request, Deal $deal): DealResource
    {
        $this->dealService->update(
            $deal,
            UpdateDealData::fromRequest($request)
        );

        return new DealResource(
            $this->dealService->get($deal)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deal $deal): JsonResponse
    {
        $this->dealService->delete($deal);

        return response()->json(null, 204);
    }
}
