<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Carbon\Carbon;
use Auth;
use DB;

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
    public function store(CustomerRequest $request)
    {
        $customer;
        $customer['groupid']  = auth::user()->groupid;
        $customer['firstname']= $request->firstname;
        $customer['lastname'] = $request->lastname;
        $customer['fullname'] = $request->firstname.' '.$request->lastname;
        $customer['phone']   = $request->phone ?? null;
        $customer['email']   = $request->email ?? null;
        $customer['address']   = $request->address ?? null;
        $customer['province']   = $request->province ?? null;
        $customer['createby']   = auth::user()->id;
        $customer['datecreate']   = time();
        $customer['channel']   = 'api';
        DB::beginTransaction();
        try {
            $ctm = Customer::create($customer);

        DB::commit();
            return response()->json(['status' => true,'message' => 'Successfully','data' => $ctm]);
        } catch (\Exception $ex) {

        DB::rollback();
            return response()->json(['status' => false,'message' => $ex->getMessage()], 500);
        }
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request,$id)
    {
        $customer = Customer::find($id);
        if (!$customer || $customer->is_delete == 1) {
            return response()->json(['status' => false,'message' => 'This resource was not found']);
        }
        $new_customer = $request->all();
        if ($request->firstname && $request->lastname) {
            $new_customer['fullname'] = $request->firstname.' '.$request->lastname;
        }
        $new_customer['dateupdate'] = time();
        $new_customer['createby_update'] = auth::user()->id;
        dd($new_customer);
        DB::beginTransaction();
        try {
            $customer->update($new_customer);
        DB::commit();
            return response()->json(['status' => true,'message' => 'Updated successfully']);
        } catch (\Exception $ex) {
        DB::rollback();
            return response()->json(['status' => false,'message' => $ex->getMessage()], 500);
        }
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
