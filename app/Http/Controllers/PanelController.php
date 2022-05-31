<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;

class PanelController extends Controller
{
    public function getDashboard ()
    {
        return view ('dashboard');
    }

    public function getAllUsersList (Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role',2)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success">Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger">Blocked</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-danger" data-id="'.$row->id.'" >Block</a>';
                    if($row->status == 0) {
                        $action = '<a href="javascript:void(0)" class="badge bg-success" data-id="'.$row->id.'" >Unblock</a>';
                    }
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }
    





}
