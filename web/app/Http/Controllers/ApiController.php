<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Services\OrderService;

class ApiController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {

    }

    /**
     * @param OrderRequest $request
     *
     * @return array
     */
    public function orders(OrderRequest $request): array
    {
        return $this->orderService->transform($request);
    }
}
