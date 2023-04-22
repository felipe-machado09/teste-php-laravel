<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessDocumentImport;

class ImportFileController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('doc');

        if ($file->isValid() && $file->getClientMimeType() === 'application/json') {
            $content = $file->get();
            $json = json_decode($content, true);

            $validator = Validator::make($json, [
                'documentos' => 'required|array',
            ]);

            if ($validator->fails()) {
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            $this->processDocuments($json['documentos']);

            return redirect('/process')->with('message', 'Documento importado com sucesso!');
        }

        return redirect('/')->withErrors(['doc' => 'O arquivo precisa ser um JSON válido']);
    }

    /**
     * @param array $documents
     * @return void
     */
    public function processDocuments(array $documents): void
    {
        collect($documents)->chunk(100)->each(function ($chunk) {
            foreach ($chunk as $document) {
                ProcessDocumentImport::dispatch($document);
            }
        });
    }
}
