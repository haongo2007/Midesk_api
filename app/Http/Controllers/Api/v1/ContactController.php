<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Carbon\Carbon;
use Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $req = $request->all();
        $contact = Contact::getDefault($req);
        return response()->json(['status' => true,'message' => 'Successfully','data' => $contact]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "create";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::ShowOne($id);
        if (!$contact) {
            return response()->json(['status' => false,'message' => 'This resource was not found','data' => null]);
        }
        return response()->json(['status' => true,'message' => 'Successfully','data' => $contact]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['status' => false,'message' => 'This resource was not found','data' => null]);
        }
        if ($contact->is_delete != null) {
            return response()->json(['status' => false,'message' => 'This resource has been deleted']);
        }
        $contact->is_delete = Contact::DELETED; 
        $contact->is_delete_date = Carbon::now(); 
        $contact->is_delete_creby = auth::user()->id;
        $contact->save();
        return response()->json(['status' => true,'message' => 'Deleted Successfully']);
    }
}