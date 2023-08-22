<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function getDocument(DocumentRequest $request)
    {
        $filePath = 'pdf/document-' . time() . '.' . $request->company_logo->getClientOriginalExtension();

        Storage::disk('public')->put($filePath, file_get_contents($request->company_logo));

        $companyLogo = storage_path('app/public/' . $filePath);

        $productsCollection = collect($request->products)->map(function ($product) {
            return [
                'name' => $product['name'],
                'count' => $product['count'],
                'price' => $product['price'],
                'amount' => $product['count'] * $product['price'],
            ];
        });

        $pdf = Pdf::loadView('document.pdf', [
            'provider' => $request->provider,
            'provider_inn' => $request->provider_inn,
            'provider_kpp' => $request->provider_kpp,
            'company_logo' => $companyLogo,
            'customer_full_name' => $request->customer_full_name,
            'customer_inn' => $request->customer_inn,
            'products' => $productsCollection,
            'total_amount' => $productsCollection->pluck('amount')->sum(),
        ])->setPaper('a4', 'landscape');
        return $pdf->download('document.pdf');
    }
}
