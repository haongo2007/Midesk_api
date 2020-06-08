<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $req = $request->all();
        $customer = Customer::getDefault($req);
        return response()->json(['status' => true,'message' => 'Successfully','data' => $customer]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::ShowOne($id);
        if (!$customer) {
            return response()->json(['status' => false,'message' => 'This resource was not found','data' => null]);
        }
        return response()->json(['status' => true,'message' => 'Successfully','data' => $customer]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['status' => false,'message' => 'This resource was not found','data' => null]);
        }
        if ($customer->is_delete != null) {
            return response()->json(['status' => false,'message' => 'This resource has been deleted']);
        }
        $customer->is_delete = Customer::DELETED; 
        $customer->is_delete_date = Carbon::now(); 
        $customer->is_delete_creby = auth::user()->id;
        $customer->save();
        return response()->json(['status' => true,'message' => 'Deleted Successfully']);
    }
}
