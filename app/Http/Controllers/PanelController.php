<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Categories;
use DataTables;
use Illuminate\Validation\Validator;

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
                        $status = '<span class="badge bg-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('process', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-danger blockUnblockUser" data-action="block" data-id="'.$row->id.'" >Block</a>';
                    if($row->status == 0) {
                        $action = '<a href="javascript:void(0)" class="badge bg-success blockUnblockUser" data-action="unblock" data-id="'.$row->id.'" >Unblock</a>';
                    }
                    return $action;
                })
                ->rawColumns(['status','process'])
                ->make(true);
        }
    }

    public function userBlockUnblock (Request $request)
    {
        $action = User::where('id',$request->id)->where('role',2)->first();
        if($action) {
            if($request->action === 'block') {
                User::where('id',$request->id)->where('role',2)->update(['is_blocked' => 1, 'status' => 0]);
            }
            if($request->action === 'unblock') {
                User::where('id',$request->id)->where('role',2)->update(['is_blocked' => 0, 'status' => 1]);
            }
            return (['status' => true, 'message' => 'User '.$request->action.'ed successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to User '.$request->action.'ed.']);
    }
    
    public function createNewCategory (Request $request)
    {
        $request->validate([
            'category_name' =>  'required|string|unique:categories,name'
        ],[
            'category_name.required'    =>  'Category name should not be blank.',
            'category_name.unique'      =>  'Category name is alredy exist, Try another.'
        ]);
        try {
            if($request->editCategoryId <= 0) {
                if(Categories::insert([
                    'name' => ucwords($request->category_name),
                    'status'=>  1,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ])) 
                    return back()->with('success',"Category added successfully.");

                else 
                    return back()->with('error',"Failed to add category, Try again.");
            }
            else if($request->editCategoryId > 0) {
                if(Categories::where('id',$request->editCategoryId)->update([
                    'name' => ucwords($request->category_name),
                    'updated_at'    =>  date('Y-m-d H:i:s')
                ])) 
                    return back()->with('success',"Category updated successfully.");

                else 
                    return back()->with('error',"Failed to update category, Try again.");
            }
        } catch (\Exception $e) {
            return back()->with('error',$e->getMessage());
        }
    }

    public function getAllCategoryList (Request $request)
    {
        if ($request->ajax()) {
            $data = Categories::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success">Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="remove-category" data-id="'.$row->id.'" ><i class="bi bi-trash text-danger"></i></a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-category" data-id="'.$row->id.'" ><i class="bi bi-pencil-square text-primary"></i></a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function deleteSelectedCategory (Request $request)
    {
        $cate = Categories::find($request->id);
        if($cate) {
            $cate->delete();
            return (['status' => true, 'message' => 'Category deleted successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to delete category.']);
    }

    public function editSelectedCategory (Request $request)
    {
        $cate = Categories::find($request->id);
        if($cate) {
            return (['status' => true, 'message' => 'Record found.', 'data'=>$cate]);
        }
        return (['status' => false, 'message' => 'No Record found.', 'data'=>[] ]);
    }

    public function createNewVendor (Request $request)
    {
        $cate = Categories::where('status',1)->get();
        return view ('vendor-create')->with('category',$cate);
    }




}
