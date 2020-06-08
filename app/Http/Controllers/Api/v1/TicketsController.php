<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tickets;
use App\Models\TicketDetail;
use App\Http\Requests\TicketsRequest;
use Auth;
use DB;
use Carbon\Carbon;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $req = $request->all();
        $tickets = Tickets::getDefault($req);
        return response()->json(['status' => true,'message' => 'Successfully','data' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Tickets $ticket , TicketsRequest $request)
    {
        $ticket = $ticket->getFillable();
        foreach ($ticket as $k => $v) {
            foreach ($request->all() as $key => $value) {
                if ($v == $key) {
                    if (is_array($value)) {
                        $ticket[$v] = end($value);
                    }else{
                        $ticket[$v] = $value;
                    }
                    unset($ticket[$k]);
                }
            }
        }
        $ticket['groupid']       = auth::user()->groupid;
        $ticket['createby']      = auth::user()->id;
        $ticket['channel']       = 'api';
        $ticket['requester_type']= 'customer';
        $ticket['status']        = $ticket['status'] ?? 'new';
        $ticket['priority']      = $ticket['priority'] ?? 3 ;
        $ticket['datecreate']    = time();
        DB::beginTransaction();
        try {
            $tk = Tickets::create($ticket);
            /*
             * insert new record for detail ticket
             */ 
            $ticket_detail['ticket_id'] = $tk->id;
            $ticket_detail['title']     = $tk->title;
            $ticket_detail['content']   = $request->content;
            $ticket_detail['groupid']   = $tk->groupid;
            $tkd = TicketDetail::create($ticket_detail);
            $tk['ticket_detail'] = $tkd;
        DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => false,'message' => $ex->getMessage()], 500);
        }
        return response()->json(['status' => true,'message' => 'Successfully','data' => $tk]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {      
        $ticket = Tickets::ShowOne($id);
        if (!$ticket) {
            return response()->json(['status' => false,'message' => 'This resource was not found','data' => null]);
        }
        return response()->json(['status' => true,'message' => 'Successfully','data' => $ticket]);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Tickets::find($id);
        if (!$ticket) {
            return response()->json(['status' => false,'message' => 'This resource was not found']);
        }
        if (!$ticket->is_delete) {
            $ticket->is_delete = 1; 
            $ticket->is_delete_date = Carbon::now(); 
            $ticket->is_delete_creby = auth::user()->id;
            $ticket->save();
            return response()->json(['status' => true,'message' => 'Deleted successfully']);
        }else{
            return response()->json(['status' => false,'message' => 'This resource has been deleted']);
        }
    }
}
