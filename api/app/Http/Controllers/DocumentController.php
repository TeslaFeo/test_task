<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;
use PDF;

class DocumentController extends Controller
{
    public function getDocument(DocumentRequest $request)
    {
        $pdf = PDF::loadView('document.pdf', []);
        return $pdf->download('invoice.pdf');
    }
}
