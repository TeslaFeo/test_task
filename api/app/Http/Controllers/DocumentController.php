<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function getDocument(DocumentRequest $request)
    {
        return 'Заебись!';
    }
}
