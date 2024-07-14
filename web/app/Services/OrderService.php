<?php
namespace App\Services;

use App\Http\Requests\OrderRequest;

class OrderService
{
    public function __construct(
        protected ExchangeRateService $exchangeRateService
    ) {
    }

    /**
     * 將非台幣的價格轉換成台幣
     *
     * @param OrderRequest $request
     *
     * @return array
     */
    public function transform(OrderRequest $request): array
    {
        $validated = $request->validated();

        if ($validated['currency'] === 'USD') {
            $validated['price']    = $validated['price'] * $this->exchangeRateService->getRate($validated['currency']);
            $validated['currency'] = 'TWD';
        }

        return $validated;
    }
}
