<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function getDocument(DocumentRequest $request)
    {
        $pdf = Pdf::loadView('document.pdf', [
            'provider' => $request->provider,
            'provider_inn' => $request->provider_inn,
            'provider_kpp' => $request->provider_kpp,
            'company_logo' => $request->company_logo,
            'customer_full_name' => $request->customer_full_name,
            'customer_inn' => $request->customer_inn,
            'products' => $request->products,
        ])->setPaper('a4', 'landscape');
        return $pdf->download('document.pdf');
    }
}
