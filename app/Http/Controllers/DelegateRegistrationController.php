<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDelegateDraftRequest;
use App\Models\Delegate;
use Illuminate\Support\Str;

class DelegateRegistrationController extends Controller
{
    /**
     * Store delegate registration as a DRAFT.
     *
     * Rules:
     * - Always creates a new row
     * - No UUID / form_no / reference_id here
     * - Same email/phone allowed
     * - Files stored in storage/app/public
     */


    public function create()
    {
        
        return view('delegate.register');
    }
    public function store(StoreDelegateDraftRequest $request)
    {
        /*
         |------------------------------------------------------------
         | 1. Create delegate draft (exclude files)
         |------------------------------------------------------------
         */
        $delegate = Delegate::create(
            $request->safe()->except(['id_document', 'photo'])
        );

        /*
         |------------------------------------------------------------
         | 2. Store files (MVP: public disk)
         |------------------------------------------------------------
         */
        $basePath = "delegates/{$delegate->id}";

        // ID Document
        $idDocument = $request->file('id_document');
        $idDocumentPath = $idDocument->storeAs(
            $basePath,
            'id_' . Str::uuid() . '.' . $idDocument->extension(),
            'public'
        );

        // Passport Photo
        $photo = $request->file('photo');
        $photoPath = $photo->storeAs(
            $basePath,
            'photo_' . Str::uuid() . '.' . $photo->extension(),
            'public'
        );

        /*
         |------------------------------------------------------------
         | 3. Update delegate with document metadata
         |------------------------------------------------------------
         */
        $delegate->update([
            'id_document_path' => $idDocumentPath,
            'id_document_mime' => $idDocument->getClientMimeType(),
            'id_document_size' => $idDocument->getSize(),

            'photo_path' => $photoPath,
            'photo_mime' => $photo->getClientMimeType(),
            'photo_size' => $photo->getSize(),
        ]);

        /*
         |------------------------------------------------------------
         | 4. Redirect to payment placeholder
         |------------------------------------------------------------
         | NOTE:
         | - Payment logic comes later
         | - For now this confirms draft creation
         */
        return redirect()->route('payment.start', $delegate->id);
    }
}
