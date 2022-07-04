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
use App\Models\Offers;
use App\Models\Vendors;
use App\Models\ShopEmail;
use App\Models\ShopLandline;
use App\Models\ShopMobileNo;
use App\Models\shopCoverImage;
use App\Models\PlanCategory;
use DataTables;
use Illuminate\Validation\Validator;

class PanelController extends Controller
{
    public function getDashboard ()
    {
        $users = User::where('role',2)->count();
        $vendor = User::where('role',3)->count();
        $blockedUsers = User::where('role',2)->where('is_blocked',1)->count();

        $countryArr = [];
        $country = CountryCode::select('country_name')->orderBy('country_name','ASC')->get();
        if(count($country) > 0) {
            foreach($country as $ctry) {
                array_push($countryArr, $ctry->country_name);
            }
        }
        return view ('dashboard', compact('users','vendor','blockedUsers','countryArr'));
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
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('process', function($row){
                    $action = '<span class="badge bg-danger blockUnblockUser cursor-point" data-action="block" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Block User">
                        <i class="fa fa-ban">&nbsp;&nbsp;</i>
                        Block
                    </span> &nbsp;&nbsp; 
                    <span class="badge bg-warning viewDetailsUser cursor-point" data-action="view" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                    <i class="fa fa-street-view">&nbsp;&nbsp;</i>
                        View
                    </span>';
                    if($row->status == 0) {
                        $action = '<span class="badge bg-success blockUnblockUser cursor-point" data-action="unblock" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Unblock User">
                        <i class="fa fa-check-circle">&nbsp;&nbsp;</i>
                            Unblock
                        </span> &nbsp;&nbsp;
                        <span class="badge bg-warning viewDetailsUser cursor-point" data-action="view" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                        <i class="fa fa-street-view">&nbsp;&nbsp;</i>
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
            'category_name' =>  'required|string',
            'category_img' =>  'required|mimes:png,jpg,jpeg,svg'
        ],[
            'category_name.required'    =>  'Category name should not be blank.',
            'category_name.unique'      =>  'Category name is alredy exist, Try another.',
            'category_img.required'     =>  'Please select image for Category',
            'category_img.mimes'     =>  'Image type must be like png,jpg,jpeg,svg'
        ]);
        try {
            if($request->editCategoryId <= 0) {
                $getCate = Categories::where('name',$request->category_name)->count();
                if($getCate <= 0) {
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
                else {
                    return back()->with('error',"Category name is alredy exist, Try another..");
                }
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
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Inactive</span>';
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
                    $action = '<a href="javascript:void(0)" class="remove-category badge bg-danger" data-id="'.$row->id.'" ><i class="bi bi-trash">&nbsp;&nbsp;</i>Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-category badge bg-success" data-id="'.$row->id.'" ><i class="bi bi-pencil-square">&nbsp;&nbsp;</i>Edit</a>';
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

    public function submitNewVendorDetails (Request $request)
    {
        $request->validate([
            'name'              =>  'required|string',
            'mobile_no_code'    =>  'required',
            'mobile_no'         =>  'required',
            'email'             =>  'required|unique:vendor,email',
            'category_id'       =>  'required',
            'shop_name'         =>  'required',
            'shop_website'      =>  'required|url',
            'shop_landline_code'=>  'required',
            'shop_landline'     =>  'required',
            'shop_mob_code'     =>  'required',
            'shop_mobile'       =>  'required',
            'shop_email'        =>  'required',  
            'location'          =>  'required',
            'latitude'          =>  'required',
            'longitude'         =>  'required',
            'cover_img'         =>  'required',
            'shop_logo'         =>  'required',
            'description'       =>  'required',
        ], [
            'name.required'     =>  'Vendor name must be required.',
            'mobile_no_code.required'   =>  'Mobile Phone code must be required.',
            'mobile_no.required'    =>  'Mobile Number must be required.',
            'email.required'     =>  'Email must be required.',
            'category_id.required'  =>  'Please select Category Id.',
            'shop_name.required'    =>  'Shop Name must be required.',
            'shop_website.required' =>  'Shop website url must be required.',
            'shop_website.url'      =>  'Website url must be in valide url formt.',
            'shop_landline_code.required'   =>  'Landline Phone code must be required.',
            'shop_landline.required'    =>  'Landline must be required.',
            'shop_mob_code.required'    =>  'Mobile Phone code must be required.',
            'shop_mobile.required'      =>  'Mobile Number must be required.',
            'shop_email.required'       =>  'Shop Email must be required.',  
            'location.required'         =>  'Location must be required.',
            'latitude.required'         =>  'Location latitude must be required.',
            'longitude.required'        =>  'Location longitude must be required.',
            'cover_img.required'        =>  'Cover Image must be required.',
            'shop_logo.required'        =>  'Shop Logo must be required.',
            'description.required'      =>  'Short Description must be required.',
        ]);
        try {
            \DB::beginTransaction();
            $shopLogo = null;
            if($request->hasFile('shop_logo')) {
                $shopLogo = \Str::random().'.'.time().'.'.$request->shop_logo->getClientOriginalExtension();
                $request->shop_logo->move(public_path('/uploads/shop/logo/'), $shopLogo);
            }
            $detailArr = array(
                'name'      =>  ucwords($request->name),
                'phone_code'=>  $request->mobile_no_code,
                'mobile_no' =>  $request->mobile_no,
                'email'     =>  $request->email,
                'shop_name' =>  ucwords($request->shop_name),
                'website'   =>  $request->shop_website,
                'description'   =>  $request->name,
                'category_id'   =>  $request->category_id,
                'location'  =>  $request->location,
                'lat'       =>  $request->latitude,
                'long'      =>  $request->longitude,
                'shop_logo' =>  $shopLogo,
                'description' =>  $request->description,
                'status'    =>  1,
                'is_blocked'=>  1,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
            );
            $vendorId = Vendors::insertGetId($detailArr);
            if($vendorId) {
                if(count($request->cover_img) > 0) {
                    for($x=0; $x<count($request->cover_img); $x++) {
                        $shopCoverLogo = null;
                        if(!empty($request->cover_img[$x])) {
                            if($request->hasFile('cover_img')) {
                                $shopCoverLogo = \Str::random().'.'.time().'.'.$request->cover_img[$x]->getClientOriginalExtension();
                                $request->cover_img[$x]->move(public_path('/uploads/shop/cover/'), $shopCoverLogo);
                            }
                            $coverImgArr = array(
                                'vendor_id' =>  $vendorId,
                                'cover_image'   =>  $shopCoverLogo,
                                'status'    =>  1,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            );
                            shopCoverImage::insert($coverImgArr);
                        }
                    }
                }
                if(count($request->shop_landline) > 0) {
                    for($i=0; $i<count($request->shop_landline); $i++) {
                        if(!empty($request->shop_landline[$i])) {
                            $landLineArr = array(
                                'vendor_id' =>  $vendorId,
                                'phone_code'    =>  $request->shop_landline_code[$i],
                                'landline_no'   =>  $request->shop_landline[$i],
                                'status'    =>  1,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            );
                            ShopLandline::insert($landLineArr);
                        }
                    }
                }
                if(count($request->shop_mobile) > 0) {
                    for($j=0; $j<count($request->shop_mobile); $j++) {
                        if(!empty($request->shop_mobile[$j])) {
                            $mobileArr = array(
                                'vendor_id' =>  $vendorId,
                                'phone_code'    =>  $request->shop_mob_code[$j],
                                'mobile_no'    =>  $request->shop_mobile[$j],
                                'status'    =>  1,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            );
                            ShopMobileNo::insert($mobileArr);
                        }
                    }
                }
                if(count($request->shop_email) > 0) {
                    for($a=0; $a<count($request->shop_email); $a++) {
                        if(!empty($request->shop_email[$a])) {
                            $emailArr = array(
                                'vendor_id' =>  $vendorId,
                                'shop_email'   =>  $request->shop_email[$a],
                                'status'    =>  1,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            );
                            ShopEmail::insert($emailArr);
                        }
                    }
                }
                \DB::commit();
                return back()->with('success','Vendor Details added successfully.');
            }
            else {
                return back()->with('error','Failed to add Vendor Details.');
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    public function getAllVendorList (Request $request)
    {
        if ($request->ajax()) {
            $data = Vendors::select('vendor.name','vendor.phone_code','vendor.mobile_no','vendor.email','vendor.shop_name','vendor.website','categories.name as cate_name','vendor.location','vendor.lat','vendor.long','vendor.status','vendor.is_blocked')
                    ->join('categories','vendor.category_id','=','categories.id')
                    ->orderBy('vendor.id','desc')
                    ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('mobileNo', function($row){
                    $mobileNo = '<center>--</center>';
                    if(!empty($row->mobile_no)) { $mobileNo = '+'.$row->phone_code.'-'.$row->mobile_no; }
                    return $mobileNo;
                })
                ->addColumn('website', function($row){
                    $website = '<center>--</center>';
                    if(!empty($row->website)) { $website = '<a href="'.$row->website.'" target="_blank">'.$row->website.'</a>'; }
                    return $website;
                })
                ->addColumn('location', function($row){
                    $location = '<center>--</center>';
                    if(!empty($row->location)) {
                        $location = $row->location.', <br>lat : '.$row->lat.', long : '.$row->long;
                    }
                    return $location;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="action-request badge bg-warning" data-action="approve" data-id="'.$row->id.'" ><i class="fa fa-check">&nbsp;&nbsp;</i>Approve</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="action-request badge bg-danger" data-action="reject" data-id="'.$row->id.'" ><i class="fa fa-ban">&nbsp;&nbsp;</i>Reject</a>
                    <a href="javascript:void(0)" class="action-request badge bg-primary" data-action="reject" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i>Edit</a>';
                    return $action;
                })
                ->rawColumns(['website','location','status','mobileNo','action'])
                ->make(true);
        }
    }

    public function editSelectedVendorDetails (Request $request, $id)
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

    public function getCateForSubscriptionPlan (Request $request) 
    {
        $cate = Categories::where('status',1)->get();        
        return view ('subscription-plans')->with('category',$cate);
    }

    public function getAllSubscriptionPlanList (Request $request)
    {
        if ($request->ajax()) {
            $data = Plans::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="remove-plans badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-plans badge bg-success" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i> Edit</a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function createNewSubscriptionPlanSubmit (Request $request)
    {
        $request->validate([
            'category_id'   =>  'required',
            'plan_name'     =>  'required|string',
            'plan_validity' =>  'required',
            'plan_amount'   =>  'required',
            'plan_tax'      =>  'required',
            'plan_total'    =>  'required'
        ],[
            'category_id.required'  =>  'Please select Category.',
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
                $planId = Plans::insertGetId($planArr);
                if($planId) {
                    if(count($request->category_id) > 0) {
                        for($x = 0; $x < count($request->category_id); $x++) {
                            PlanCategory::insert([
                                'plan_id'   =>  $planId,
                                'category_id'   =>  $request->category_id[$x],
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                    return back()->with('success','Plan added successfully.');
                }
                else {
                    return back()->with('error','Failed to add Plan, Try again.');
                }
            } 
            else {
                $getSelectedCate = PlanCategory::where('plan_id',$request->editPlansId)->count();
                if($getSelectedCate > 0) {
                    PlanCategory::where('plan_id',$request->editPlansId)->delete();
                }
                $planArr = array(
                    'name'          =>  ucwords($request->plan_name),
                    'validity'      =>  $request->plan_validity,
                    'amount'        =>  $request->plan_amount,
                    'tax'           =>  $request->plan_tax,
                    'total'         =>  $request->plan_total,
                    'updated_at'    =>  date('Y-m-d H:i:s')
                );
                if(Plans::where('id',$request->editPlansId)->update($planArr)) {
                    if(count($request->category_id) > 0) {
                        for($x = 0; $x < count($request->category_id); $x++) {
                            PlanCategory::insert([
                                'plan_id'   =>  $request->editPlansId,
                                'category_id'   =>  $request->category_id[$x],
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s')
                            ]);
                        }
                    }
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
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Inactive</span>';
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
                    $action = '<a href="javascript:void(0)" class="remove-plans badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-plans badge bg-success" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i> Edit</a>';
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
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="action-request badge bg-success" data-action="approve" data-id="'.$row->id.'" ><i class="fa fa-check">&nbsp;&nbsp;</i>Approve</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="action-request badge bg-danger" data-action="reject" data-id="'.$row->id.'" ><i class="fa fa-ban">&nbsp;&nbsp;</i>Reject</a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function getReedemApprovedList (Request $request)
    {
        if ($request->ajax()) {
            $data = Redemption::select('redeem_req.id','redeem_req.user_id','users.name as username','redeem_req.is_approved','redeem_req.amount','redeem_req.status')
                    ->join('users','redeem_req.user_id','=','users.id')
                    ->where('redeem_req.is_approved',1)
                    ->orderBy('redeem_req.id','desc')->get();                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-success" ><i class="fa fa-check">&nbsp;&nbsp;</i>Approved</a>';
                    return $action;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function getReedemRejectedList (Request $request)
    {
        if ($request->ajax()) {
            $data = Redemption::select('redeem_req.id','redeem_req.user_id','users.name as username','redeem_req.is_approved','redeem_req.amount','redeem_req.status')
                    ->join('users','redeem_req.user_id','=','users.id')
                    ->where('redeem_req.is_approved',2)
                    ->orderBy('redeem_req.id','desc')->get();                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Active</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-danger" ><i class="fa fa-ban">&nbsp;&nbsp;</i>Rejected</a>';
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
