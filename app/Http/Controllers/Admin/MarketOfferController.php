<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarketOfferRequest as ObjRequest;
use App\Models\MarketOffer as ObjModel;
use App\Models\MarketOffer;
use App\Services\Admin\MarketOfferService as ObjService;
use Illuminate\Http\Request;

class MarketOfferController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {

        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $market_offer)
    {
        return $this->objService->edit($market_offer);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }
    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request, 'status', StatusEnum::values());
    }
    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }
}
