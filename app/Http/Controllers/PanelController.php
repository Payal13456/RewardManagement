<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plans;
use App\Models\Categories;
use App\Models\Subscription;
use App\Models\Redemption;
use App\Models\ReferalBonus;
use App\Models\Notification;
use App\Models\CountryCode;
use App\Models\Vendors;
use App\Models\Offers;
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
                ->addColumn('location', function($row){
                    $location = $row->location.', <br>lat : '.$row->latitude.', long : '.$row->longitude;
                    return $location;
                })
                ->addColumn('mobile_no', function($row){
                    $mobileNo = '+'.$row->phone_code.'-'.$row->mobile_no;
                    return $mobileNo;
                })
                ->addColumn('dob', function($row){
                    $dob = date('d-m-Y', strtotime($row->dob));
                    return $dob;
                })
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success">Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('process', function($row){
                    $action = '<span class="badge bg-danger blockUnblockUser cursor-point" data-action="block" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Block User">
                        Block
                    </span> &nbsp;&nbsp; 
                    <span class="badge bg-warning viewDetailsUser cursor-point" data-action="view" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                        View
                    </span>';
                    if($row->status == 0) {
                        $action = '<span class="badge bg-success blockUnblockUser cursor-point" data-action="unblock" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Unblock User">
                            Unblock
                        </span> &nbsp;&nbsp;
                        <span class="badge bg-warning viewDetailsUser cursor-point" data-action="view" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                            View
                        </span>';
                    }
                    return $action;
                })
                ->rawColumns(['location','dob','mobile_no','status','process'])
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
            'category_name' =>  'required|string|unique:categories,name',
            'category_img' =>  'required|mimes:png,jpg,jpeg,svg'
        ],[
            'category_name.required'    =>  'Category name should not be blank.',
            'category_name.unique'      =>  'Category name is alredy exist, Try another.',
            'category_img.required'     =>  'Please select image for Category',
            'category_img.mimes'     =>  'Image type must be like png,jpg,jpeg,svg'
        ]);
        try {
            if($request->editCategoryId <= 0) {
                $cateImg = null;
                if($request->hasFile('category_img')) {
                    $cateImg = \Str::random().'.'.time().'.'.$request->category_img->getClientOriginalExtension();
                    $request->category_img->move(public_path('/uploads/category/'),$cateImg);
                }
                if(Categories::insert([
                    'name' => ucwords($request->category_name),
                    'image' =>  $cateImg,
                    'status'=>  1,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ])) 
                    return back()->with('success',"Category added successfully.");

                else 
                    return back()->with('error',"Failed to add category, Try again.");
            }
            else if($request->editCategoryId > 0) {
                $cateImg = $request->editCategoryimg;
                if($request->hasFile('category_img')) {
                    $cateImg = \Str::random().'.'.time().'.'.$request->category_img->getClientOriginalExtension();
                    $request->category_img->move(public_path('/uploads/category/'),$cateImg);
                }
                if(Categories::where('id',$request->editCategoryId)->update([
                    'name' => ucwords($request->category_name),
                    'image' =>  $cateImg,
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
                ->addColumn('image', function($row){
                    $image = '--';
                    if($row->image !== NULL) {
                        $image = '<a href="'.Categories::getCategoryImagePath($row->image).'" target="_blank">'.$row->image.'</a>';
                    }
                    return $image;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="remove-category" data-id="'.$row->id.'" ><i class="bi bi-trash text-danger"></i></a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-category" data-id="'.$row->id.'" ><i class="bi bi-pencil-square text-primary"></i></a>';
                    return $action;
                })
                ->rawColumns(['status','action','image'])
                ->make(true);
        }
    }

    public function deleteSelectedCategory (Request $request)
    {
        $cate = Categories::find($request->id);
        if($cate) {
            if($cate->image !== null) {
                if(\File::exists(public_path('/uploads/category/'.$cate->image))) {
                    unlink(public_path('/uploads/category/'.$cate->image));
                }
            }
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
        $countryCode = CountryCode::select('phone_code')->groupBy('phone_code')->orderBy('phone_code','ASC')->get();
        
        return view ('vendor-create')->with('category',$cate)->with('countryCode',$countryCode);
    }

    public function getUsersAllDetails(Request $request)
    {
        $usersDtl = User::find($request->userId);
        if($usersDtl) {
            if($usersDtl->dob !== null) { $usersDtl->dob = date("d M, Y",strtotime($usersDtl->dob)); }

            $subscript = Subscription::select('subscription.plan_id','plan.name as plan_name','subscription.transaction_id','subscription.expiry_date','subscription.is_expired','subscription.status')->join('plan','subscription.plan_id','=','plan.id')->where('subscription.user_id',$request->userId)->orderBy('subscription.id','DESC')->get();
            if(count($subscript) > 0) {
                foreach($subscript as $sub) {
                    $sub->expiry_date = date('d M, Y', strtotime($sub->expiry_date));
                    if($sub->is_expired == 0) { $sub->is_expired = '<span class="badge bg-success">Not Expired</span>'; }
                    else if($sub->is_expired == 1) { $sub->is_expired = '<span class="badge bg-danger">Expired</span>'; }
                    
                    if($sub->status == 0) { $sub->status = '<span class="badge bg-danger">Inactive</span>'; }
                    else if($sub->status == 1) { $sub->status = '<span class="badge bg-success">Active</span>'; }
                }
            }

            $redem = Redemption::select('user_id','is_approved','amount','status','created_at as req_date')->where('user_id',$request->userId)->orderBy('id','DESC')->get();
            if(count($redem) > 0) {
                foreach($redem as $red) {
                    if($red->is_approved == 0) { $red->is_approved = '<span class="badge bg-warning">Pending</span>'; }
                    else if($red->is_approved == 1) { $red->is_approved = '<span class="badge bg-success">Approved</span>'; }
                    else if($red->is_approved == 2) { $red->is_approved = '<span class="badge bg-danger">Rejected</span>'; }
                    $red->req_date = date('d M, Y',strtotime($red->req_date));
                }
            }

            $referral = ReferalBonus::select('referal_code','amount','status','created_at as ref_date')->where('user_id',$request->userId)->orderBy('id','DESC')->get();
            if(count($referral) > 0) {
                foreach($referral as $ref) {
                    $ref->ref_date = date('d M, Y', strtotime($ref->ref_date));
                    if($ref->status == 0) { $ref->status = '<span class="badge bg-danger">Inactive</span>'; }
                    else if($ref->status == 1) { $ref->status = '<span class="badge bg-success">Active</span>'; }
                }
            }

            return (['status' => true, 'message' => 'Record Found', 'userDt' => $usersDtl, 'subscript' => $subscript, 'redem' => $redem, 'referral' => $referral ]);
        }
        else {
            return (['status' => false, 'message' => 'No User Found', 'user' => [] ]);
        }
    }

    public function messageToUsersList (Request $request)
    {
        $userList = User::where('is_blocked',0)->where('role',2)->whereIn('status', ['1', '2'])->orderBy('name','ASC')->get();
        return view('message-to-user')->with('userList',$userList);
    }

    public function messageToUsersSubmit (Request $request)
    {
        $request->validate([
            'users' =>  'required',
            'message'   =>  'required'
        ],[
            'users.required'    =>  'Please select at least one User',
            'message.required'  =>  'Please enter the message'
        ]);
        try {
            $notiArr = array(
                'type'  =>  'message',
                'msg'   =>  $request->message,
                'user_id'   =>  \Auth::user()->id,
                'received_id'   =>  implode(',',$request->users),
                'is_read'   =>  0,
                'status'    =>  1,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
            );
            if(Notification::insert($notiArr)) {
                return back()->with('success', 'Message successfully send to Users');
            } 
            else {
                return back()->with('error', 'Failed to send message to Users');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getAllSubscriptionPlanList (Request $request)
    {
        if ($request->ajax()) {
            $data = Plans::latest()->get();
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
                    $action = '<a href="javascript:void(0)" class="remove-plans" data-id="'.$row->id.'" ><i class="bi bi-trash text-danger"></i></a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-plans" data-id="'.$row->id.'" ><i class="bi bi-pencil-square text-primary"></i></a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function createNewSubscriptionPlanSubmit (Request $request)
    {
        $request->validate([
            'plan_name'     =>  'required|string',
            'plan_validity' =>  'required',
            'plan_amount'   =>  'required',
            'plan_tax'      =>  'required',
            'plan_total'    =>  'required'
        ],[
            'plan_name.required'    =>  'Plan name should not be blank.',
            'plan_validity.required'    =>  'Plan validity should not be blank.',
            'plan_amount.required'  =>  'Plan amount should not be blank.',
            'plan_tax.required'     =>  'Tax amount should not be blank.',
            'plan_total.required'   =>  'Total amount should not be blank.'
        ]);
        try {
            if(empty($request->editPlansId)) {
                $planArr = array(
                    'name'          =>  ucwords($request->plan_name),
                    'validity'      =>  $request->plan_validity,
                    'amount'        =>  $request->plan_amount,
                    'tax'           =>  $request->plan_tax,
                    'total'         =>  $request->plan_total,
                    'status'        =>  1,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s')
                );
                if(Plans::insert($planArr)) {
                    return back()->with('success','Plan added successfully.');
                }
                else {
                    return back()->with('error','Failed to add Plan, Try again.');
                }
            } 
            else {
                $planArr = array(
                    'name'          =>  ucwords($request->plan_name),
                    'validity'      =>  $request->plan_validity,
                    'amount'        =>  $request->plan_amount,
                    'tax'           =>  $request->plan_tax,
                    'total'         =>  $request->plan_total,
                    'updated_at'    =>  date('Y-m-d H:i:s')
                );
                if(Plans::where('id',$request->editPlansId)->update($planArr)) {
                    return back()->with('success','Plan updated successfully.');
                }
                else {
                    return back()->with('error','Failed to update Plan, Try again.');
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editSelectedSubscriptionPlan (Request $request)
    {
        $plan = Plans::find($request->id);
        if($plan) {
            return (['status' => true, 'message' => 'Record found.', 'data'=>$plan]);
        }
        return (['status' => false, 'message' => 'No Record found.', 'data'=>[] ]);
    }

    public function deleteSelectedSubscriptionPlan (Request $request)
    {
        $plan = Plans::find($request->id);
        if($plan) {
            $currDate = date('Y-m-d');
            $sub = Subscription::where('plan_id',$request->id)->where('expiry_date','>=',$currDate)->count();
            if($sub == 0) {
                $plan->delete();
                return (['status' => true, 'message' => 'Subscription Plan deleted successfully.']);
            }
            else if($sub > 0) {
                return (['status' => false, 'message' => 'Unable to delete this Subscription Plan. '.$sub.' Users are subscribed with this plan.']);
            }
        }
        return (['status' => false, 'message' => 'Failed to delete Subscription Plan.']);
    }

    public function createNewOffers (Request $request) 
    {
        $vendor = Vendors::select('id','shop_name','name')->where('status',1)->where('is_blocked',1)->get();
        return view('offers')->with('vendor',$vendor);
    }

    public function newOffersDetailSubmit (Request $request)
    {
        $request->validate([
            'vendor_id'         =>  'required',
            'start_date'        =>  'required',
            'end_date'          =>  'required',
            'offer_description' =>  'required'
        ],[
            'vendor_id.required'    =>  'Please select any one Vendor',
            'start_date.required'   =>  'Please select offer start date',
            'end_date.required'     =>  'Please select offer end date',
            'offer_description.required'     =>  'Please enter offer description',
        ]);
        try {
            if(empty($request->editOffersId)) {
                if($request->end_date >= $request->start_date) {
                    if(Offers::insert([
                        'vendor_id'     =>  $request->vendor_id,
                        'offer_desc'    =>  ucfirst($request->offer_description),
                        'start_date'    =>  date('Y-m-d', strtotime($request->start_date)),
                        'end_date'      =>  date('Y-m-d', strtotime($request->end_date)),
                        'status'        =>  1,
                        'created_at'    =>  date('Y-m-d H:i:s'),
                        'updated_at'    =>  date('Y-m-d H:i:s')	
                    ])) {
                        return back()->with('success','Offers added successfully.');
                    } 
                    else {
                        return back()->with('error','Failed to add Offers, Try again.');
                    }
                }
                else {
                    return back()->with('error','Offer end date should be equal to or greater than start date.');
                }
            }
            else {
                if($request->end_date >= $request->start_date) {
                    if(Offers::where('id',$request->editOffersId)->update([
                        'vendor_id'     =>  $request->vendor_id,
                        'offer_desc'    =>  ucfirst($request->offer_description),
                        'start_date'    =>  date('Y-m-d', strtotime($request->start_date)),
                        'end_date'      =>  date('Y-m-d', strtotime($request->end_date)),
                        'status'        =>  1,
                        'created_at'    =>  date('Y-m-d H:i:s'),
                        'updated_at'    =>  date('Y-m-d H:i:s')	
                    ])) {
                        return back()->with('success','Offer updated successfully.');
                    } 
                    else {
                        return back()->with('error','Failed to update Offer, Try again.');
                    }
                }
                else {
                    return back()->with('error','Offer end date should be equal to or greater than start date.');
                }
            }
        } catch (\Exception $e) {
            return back()->with('error',$e->getMessage());
        }
    }
    
    public function getAllOffersList (Request $request)
    {
        if ($request->ajax()) {
            $data = Offers::select('offers.id','offers.vendor_id','vendor.shop_name','offers.offer_desc','offers.start_date','offers.end_date','offers.status')
                    ->join('vendor','offers.vendor_id','=','vendor.id')
                    ->orderBy('offers.id','desc')->get();
            // echo "<pre>";
            // print_r($data);
            // exit();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success">Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('start_date', function($row){
                    $startDate = date('d-m-Y',strtotime($row->start_date));
                    return $startDate;
                })
                ->addColumn('end_date', function($row){
                    $endDate = date('d-m-Y',strtotime($row->end_date));
                    return $endDate;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="remove-plans" data-id="'.$row->id.'" ><i class="bi bi-trash text-danger"></i></a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-plans" data-id="'.$row->id.'" ><i class="bi bi-pencil-square text-primary"></i></a>';
                    return $action;
                })
                ->rawColumns(['status','start_date','end_date','action'])
                ->make(true);
        }
    }
    
    public function getReedemRequestList (Request $request)
    {
        if ($request->ajax()) {
            $data = Redemption::select('redeem_req.id','redeem_req.user_id','users.name as username','redeem_req.is_approved','redeem_req.amount','redeem_req.status')
                    ->join('users','redeem_req.user_id','=','users.id')
                    ->where('redeem_req.is_approved',0)
                    ->orderBy('redeem_req.id','desc')->get();
                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-warning">Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="action-request badge bg-success" data-action="approve" data-id="'.$row->id.'" >Approve</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="action-request badge bg-danger" data-action="reject" data-id="'.$row->id.'" >Reject</a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function reedemRequestAction (Request $request)
    {
        try {
            $action = 2;
            if($request->action == 'approve') { $action = 1; }

            if(Redemption::where('id', $request->actionId)->update([
                'is_approved'   =>  $action,
                'updated_at'    =>  date('Y-m-d H:i:s')
            ])) {
                return ['status' => true, 'message' => 'Reedem Request has been '.$request->action.'ed'];
            }
            else {
                return ['status' => false, 'message' => 'Failed to '.$request->action.' payment, Try again'];
            }
        } 
        catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }






}
