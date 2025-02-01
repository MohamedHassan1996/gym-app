<?php

namespace App\Http\Controllers\Api\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientDocument\AllClientDocumentCollection;
use App\Http\Resources\Client\ClientDocument\ClientDocumentResource;
use App\Models\Client\ClientDocument;
use App\Utils\PaginateCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\SwitchDbConnection;

class ClientDocumentController extends Controller
{
    use SwitchDbConnection;

    public function __construct()
    {
        $this->middleware('auth:api');
        /*$this->middleware('permission:all_clients', ['only' => ['index']]);
        $this->middleware('permission:create_client', ['only' => ['create']]);
        $this->middleware('permission:edit_client', ['only' => ['edit']]);
        $this->middleware('permission:update_client', ['only' => ['update']]);
        $this->middleware('permission:delete_client', ['only' => ['delete']]);
        $this->middleware('permission:change_client_status', ['only' => ['changeStatus']]);*/

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->switchDatabase();

        $allClientDocuments = ClientDocument::where('client_id', $request->clientId)->get();

       return response()->json(
            new AllClientDocumentCollection(PaginateCollection::paginate($allClientDocuments, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

     public function create(Request $request)
     {
         try {
             DB::connection('tenant')->beginTransaction();

             $this->switchDatabase();

             $data = $request->all();

            ClientDocument::create([
                'client_id' => $data['clientId'],
                'document_type_id' => $data['documentTypeId']??null,
                'start_at' => $data['startAt'],
                'end_at' => $data['endAt'],
            ]);

             DB::connection('tenant')->commit();

             return response()->json([
                'message' => __('messages.success.created')
             ], 200);

         } catch (\Exception $e) {
             DB::rollBack();
             return response()->json([
                 'error' => $e->getMessage(),
             ], 500);
         }
     }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $this->switchDatabase();

        $clientDocument = ClientDocument::find($request->clientDocumentId);

        return new ClientDocumentResource($clientDocument);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        try {
            DB::connection('tenant')->beginTransaction();
            $data = $request->all();

            $this->switchDatabase();

            $clientDocument = ClientDocument::find($request->clientDocumentId);
            $clientDocument->update([
                'start_at' => $data['startAt'],
                'end_at' => $data['endAt'],
                'document_type_id' => $data['documentTypeId']??null,
            ]);
            DB::connection('tenant')->commit();
            return response()->json([
                'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            $this->switchDatabase();

            DB::connection('tenant')->beginTransaction();
            $document = ClientDocument::find($request->clientDocumentId);
            $document->delete();
            DB::connection('tenant')->commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

}
