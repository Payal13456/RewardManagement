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
use App\Models\ShopCoverImage;
use App\Models\PlanCategory;
use App\Models\Feedback;
use App\Models\ReferralAmt;
use DataTables;
use Illuminate\Validation\Validator;

class PanelController extends Controller
{
    public function getDashboard ()
    {
        $users = User::where('role',2)->count();
        $vendor = Vendors::where('status',1)->count();
        $blockedUsers = User::where('role',2)->where('is_blocked',1)->count();

        $countryArr = [];
        $userCountArr = [];
        $country = CountryCode::select('country_codes.country_name','country_codes.phone_code')
                ->join('users', 'country_codes.phone_code','=','users.country_code')
                ->where('users.role',2)
                ->groupBy('country_codes.country_name')->get();
        if(count($country) > 0) {
            foreach($country as $ctry) {
                $userCount = User::where('country_code',$ctry->phone_code)->where('role',2)->count();
                array_push($countryArr, $ctry->country_name);
                array_push($userCountArr, $userCount);
            }
        }
        
        return view ('dashboard', compact('users','vendor','blockedUsers','countryArr','userCountArr'));
    }

// Get all users list function
    public function getAllUsersList (Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('users.*', 'subscription.expiry_date')
                    ->leftjoin('subscription','users.id','=','subscription.user_id')    
                    ->where('users.role',2)->latest()->get();
                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('location', function($row){
                    $location = '';
                    if(!empty($row->location)) {
                        $location = $row->location.', <br>lat : '.$row->latitude.', long : '.$row->longitude;
                    }
                    return $location;
                })
                ->addColumn('name', function($row){
                    $name = $row->name.' '.$row->last_name;
                    return $name;
                })
                ->addColumn('mobile_no', function($row){
                    $mobileNo = $row->country_code.' '.$row->mobile_no;
                    return $mobileNo;
                })
                ->addColumn('dob', function($row){
                    $dob = date('d-m-Y', strtotime($row->dob));
                    return $dob;
                })
                ->addColumn('membershipExpiry', function ($row) {
                    $expiryDate = '';
                    if(!empty($row->expiry_date)) {
                        $expiryDate = date('d M, Y', strtotime($row->expiry_date));
                    }
                    return $expiryDate;
                })
                ->addColumn('blockStatus', function($row){
                    if($row->is_blocked == 0) {
                        $action = '<span class="badge bg-success" data-action="unblock" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Unblock User">
                            <i class="fa fa-check-circle">&nbsp;&nbsp;</i>
                            Unblock
                        </span>';
                    }
                    if($row->is_blocked == 1) {
                        $action = '<span class="badge bg-danger" data-action="block" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Block User">
                            <i class="fa fa-ban">&nbsp;&nbsp;</i>
                            Block
                        </span>';
                    }
                    return $action;
                })
                ->addColumn('process', function($row){
                    if($row->is_blocked == 0) {
                        $action = '<span class="badge bg-danger blockUnblockUser cursor-point" data-action="block" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Block User">
                        <i class="fa fa-ban">&nbsp;&nbsp;</i>
                            Block
                        </span>';
                    }
                    if($row->is_blocked == 1) {
                        $action = '<span class="badge bg-success blockUnblockUser cursor-point" data-action="unblock" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Unblock User">
                            <i class="fa fa-check-circle">&nbsp;&nbsp;</i>
                            Unblock
                        </span>';
                    }
                    $action .= '&nbsp;&nbsp;
                    <span class="badge bg-warning viewDetailsUser cursor-point" data-action="view" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                    <i class="fa fa-street-view">&nbsp;&nbsp;</i>
                        View
                    </span>';
                    return $action;
                })
                ->rawColumns(['membershipExpiry','location','dob','mobile_no','status','process','blockStatus','name'])
                ->make(true);
        }
    }

// User block/Unblock function
    public function userBlockUnblock (Request $request)
    {
        $action = User::where('id',$request->id)->where('role',2)->first();        
        if($action) {
            if($request->action === 'block') {
                User::where('id',$request->id)->where('role',2)->update(['is_blocked' => 1]);
            }
            if($request->action === 'unblock') {
                User::where('id',$request->id)->where('role',2)->update(['is_blocked' => 0]);
            }
            return (['status' => true, 'message' => 'User '.$request->action.'ed successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to User '.$request->action.'ed.']);
    }

    public function userActiveDeactive (Request $request)
    {
        $action = User::where('id',$request->id)->where('role',2)->first();
        if($action) {
            if($request->action === 'activate') {
                User::where('id',$request->id)->where('role',2)->update(['status' => 2]);
            }
            if($request->action === 'deactivate') {
                User::where('id',$request->id)->where('role',2)->update(['status' => 0]);
            }
            return (['status' => true, 'message' => 'User '.$request->action.'d successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to User '.$request->action.'d.']);
    }
    
    public function createNewCategory (Request $request)
    {
        if($request->editCategoryId <= 0) {
            $request->validate([
                'category_name' =>  'required|string',
                'category_img' =>  'required|mimes:png,jpg,jpeg,svg'
            ],[
                'category_name.required'    =>  'Category name should not be blank.',
                'category_name.unique'      =>  'Category name is alredy exist, Try another.',
                'category_img.required'     =>  'Please select image for Category',
                'category_img.mimes'     =>  'Image type must be like png,jpg,jpeg,svg'
            ]);
        } else if($request->editCategoryId > 0) {
            $request->validate([
                'category_name' =>  'required|string',
            ],[
                'category_name.required'    =>  'Category name should not be blank.',
                'category_name.unique'      =>  'Category name is alredy exist, Try another.',
            ]);
        }
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
                $cateImg = $request->editCategoryImg; 
                if($request->hasFile('category_img')) {
                    $cateImg = \Str::random().'.'.time().'.'.$request->category_img->getClientOriginalExtension();
                    if(\File::exists(public_path('/uploads/category/'.$request->editCategoryImg))) {
                        unlink(public_path('/uploads/category/'.$request->editCategoryImg));
                    }
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
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</span>';
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
                    $action = '<a href="javascript:void(0)" class="remove-category badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i>Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-category badge bg-success" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i>Edit</a>';
                    
                    if($row->status == 0) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="activate" data-id="'.$row->id.'" class="activeDeactiveCategory badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</a>';
                    } else if($row->status == 1) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="deactivate" data-id="'.$row->id.'" class="activeDeactiveCategory badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</a>';
                    }
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

    public function activeDeactiveSelectedCategory (Request $request)
    {
        $action = Categories::where('id',$request->id)->first();
        if($action) {
            if($request->action === 'activate') {
                Categories::where('id',$request->id)->update(['status' => 1]);
            }
            if($request->action === 'deactivate') {
                Categories::where('id',$request->id)->update(['status' => 0]);
            }
            return (['status' => true, 'message' => 'Category '.$request->action.'d successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to Category '.$request->action.'d.']);
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
            // 'shop_website'      =>  'required|url',
            'shop_landline_code'=>  'required',
            'shop_landline'     =>  'required',
            'shop_mob_code'     =>  'required',
            'shop_mobile'       =>  'required',
            'shop_email'        =>  'required',  
            'location'          =>  'required',
            'latitude'          =>  'required',
            'longitude'         =>  'required',
            // 'cover_img'         =>  'required',
            'shop_logo'         =>  'required',
            'opening_time'      =>  'required',
            'closing_time'      =>  'required',
            'shop_logo'         =>  'required',
            'description'       =>  'required',
        ], [
            'name.required'     =>  'Vendor name must be required.',
            'mobile_no_code.required'   =>  'Mobile Phone code must be required.',
            'mobile_no.required'    =>  'Mobile Number must be required.',
            'email.required'     =>  'Email must be required.',
            'category_id.required'  =>  'Please select Category Id.',
            'shop_name.required'    =>  'Shop Name must be required.',
            // 'shop_website.required' =>  'Shop website url must be required.',
            // 'shop_website.url'      =>  'Website url must be in valide url formt.',
            'shop_landline_code.required'   =>  'Landline Phone code must be required.',
            'shop_landline.required'    =>  'Landline must be required.',
            'shop_mob_code.required'    =>  'Mobile Phone code must be required.',
            'shop_mobile.required'      =>  'Mobile Number must be required.',
            'shop_email.required'       =>  'Shop Email must be required.',  
            'location.required'         =>  'Location must be required.',
            'latitude.required'         =>  'Location latitude must be required.',
            'longitude.required'        =>  'Location longitude must be required.',
            // 'cover_img.required'        =>  'Cover Image must be required.',
            'shop_logo.required'        =>  'Shop Logo must be required.',
            'opening_time.required'     =>  'Opening Time must be required',
            'closing_time.required'     =>  'Closing Time must be required',
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
                'opening_time' =>  $request->opening_time,
                'closing_time' =>  $request->closing_time,
                'description' =>  $request->description,
                'status'    =>  1,
                'is_blocked'=>  1,
                'created_at'    =>  date('Y-m-d H:i:s'),
                'updated_at'    =>  date('Y-m-d H:i:s'),
            );
            $vendorId = Vendors::insertGetId($detailArr);
            if($vendorId) {
                if(isset($request->cover_img) && count($request->cover_img) > 0) {
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
                            ShopCoverImage::insert($coverImgArr);
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
                return \Redirect::to('/vendor-list')->with('success','Vendor Details added successfully.');
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
            $data = Vendors::select('vendor.id','vendor.name','vendor.phone_code','vendor.mobile_no','vendor.email','vendor.shop_name','vendor.website','categories.name as cate_name','vendor.location','vendor.lat','vendor.long','vendor.status','vendor.is_blocked')
                    ->join('categories','vendor.category_id','=','categories.id')
                    ->orderBy('vendor.id','desc')
                    ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('mobileNo', function($row){
                    $mobileNo = '<center>--</center>';
                    if(!empty($row->mobile_no)) { $mobileNo = '+'.$row->phone_code.' '.$row->mobile_no; }
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
                    $action = '<a href="javascript:void(0)" class="actionRequestVendor badge bg-danger" data-action="delete" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i>Delete</a> &nbsp;&nbsp;
                    <a href="'.url('/vendor/update').'/'.encrypt($row->id).'" class="badge bg-success" ><i class="fa fa-edit">&nbsp;&nbsp;</i>Edit</a> &nbsp;&nbsp
                    <a href="javascript:void(0)" class="viewDetailsVendor badge bg-dark" data-action="view" data-id="'.$row->id.'" ><i class="fa fa-street-view">&nbsp;&nbsp;</i>View</a>';
                    
                    if($row->status == 0) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="activate" data-id="'.$row->id.'" class="actionRequestVendor badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</a>';
                    } else if($row->status == 1) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="deactivate" data-id="'.$row->id.'" class="actionRequestVendor badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</a>';
                    }
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
        $vendor = Vendors::find(decrypt($id));
        $coverImg = ShopCoverImage::select('id','vendor_id','cover_image')->where('vendor_id',decrypt($id))->get();
        $landLine = ShopLandline::select('id','vendor_id','phone_code','landline_no')->where('vendor_id',decrypt($id))->get();
        $mobileNo = ShopMobileNo::select('id','vendor_id','phone_code','mobile_no')->where('vendor_id',decrypt($id))->get();
        $emails = ShopEmail::select('id','vendor_id','shop_email')->where('vendor_id',decrypt($id))->get();

        return view ('vendor-update',compact('cate','countryCode','vendor','coverImg','landLine','mobileNo','emails'));
    }

    public function actionRequestVendorDetails (Request $request)
    {
        if($request->action === 'delete') {
            $vendor = Vendors::find($request->id);
            $coverImg = ShopCoverImage::where('vendor_id', $request->id);
            $landLine = ShopLandline::where('vendor_id', $request->id);
            $mobileNo = ShopMobileNo::where('vendor_id', $request->id);
            $emails = ShopEmail::where('vendor_id', $request->id);
            if($vendor) {
                $vendor->delete();
                $coverImg->delete();
                $landLine->delete();
                $mobileNo->delete();
                $emails->delete();
                return (['status' => true, 'message' => 'Vendor & details deleted successfully.']);
            }
            else {
                return (['status' => false, 'message' => 'Failed to delete Vendor, Try again.']);
            }
        }
        if($request->action === 'activate') {
            $vendor = Vendors::find($request->id);
            if($vendor) {
                $vendor = Vendors::where('id',$request->id)->update(['status' => 1]);
                $coverImg = ShopCoverImage::where('vendor_id', $request->id)->update(['status' => 1]);
                $landLine = ShopLandline::where('vendor_id', $request->id)->update(['status' => 1]);
                $mobileNo = ShopMobileNo::where('vendor_id', $request->id)->update(['status' => 1]);
                $emails = ShopEmail::where('vendor_id', $request->id)->update(['status' => 1]);
                return (['status' => true, 'message' => 'Vendor & details Activated successfully.']);
            }
            else {
                return (['status' => false, 'message' => 'Failed to active Vendor, Try again.']);
            }
        }
        if($request->action === 'deactivate') {
            $vendor = Vendors::find($request->id);
            if($vendor) {
                $vendor = Vendors::where('id',$request->id)->update(['status' => 0]);
                $coverImg = ShopCoverImage::where('vendor_id', $request->id)->update(['status' => 0]);
                $landLine = ShopLandline::where('vendor_id', $request->id)->update(['status' => 0]);
                $mobileNo = ShopMobileNo::where('vendor_id', $request->id)->update(['status' => 0]);
                $emails = ShopEmail::where('vendor_id', $request->id)->update(['status' => 0]);
                return (['status' => true, 'message' => 'Vendor & details Deactivated successfully.']);
            }
            else {
                return (['status' => false, 'message' => 'Failed to deactive Vendor, Try again.']);
            }
        }
    }

    public function getVendorDetails (Request $request)
    {
        $vendor = Vendors::find($request->vendorId);
        if($vendor) {
            $coverImg = ShopCoverImage::where('vendor_id', $request->vendorId)->get();
            $landLine = ShopLandline::where('vendor_id', $request->vendorId)->get();
            $mobileNo = ShopMobileNo::where('vendor_id', $request->vendorId)->get();
            $emails = ShopEmail::where('vendor_id', $request->vendorId)->get();

            return (['status' => true, 'vendor'=>$vendor, 'coverImg'=>$coverImg, 'landLine'=>$landLine, 'mobileNo'=>$mobileNo, 'emails'=>$emails]);
        }
        else {
            return (['status' => false, 'message' => 'Vendor not found.']);
        }
    }

    public function removeVendorCoverImage (Request $request)
    {
        $coverImg = ShopCoverImage::find($request->imgId);
        if($coverImg) {
            if (\File::exists(public_path('/uploads/shop/cover/'.$coverImg->cover_image))) {
                unlink(public_path('/uploads/shop/cover/'.$coverImg->cover_image));
            }
            $coverImg->delete();
            return (['status' => true, 'message' => 'Removed successfully']);
        }
        return (['status' => false, 'message' => 'Failed to removed cover image']);
    }

    public function updateSelectedVendorDetails (Request $request)
    {
        $request->validate([
            'name'              =>  'required|string',
            'mobile_no_code'    =>  'required',
            'mobile_no'         =>  'required',
            'email'             =>  'required',
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
            'opening_time'      =>  'required',
            'closing_time'      =>  'required',
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
            'opening_time.required'     =>  'Opening Time must be required',
            'closing_time.required'     =>  'Closing Time must be required',
            'description.required'      =>  'Short Description must be required.',
        ]);
        try {
            \DB::beginTransaction();
            $shopLogo = $request->editShopLogo;
            if($request->hasFile('shop_logo')) {
                $shopLogo = \Str::random().'.'.time().'.'.$request->shop_logo->getClientOriginalExtension();
                if (\File::exists(public_path('/uploads/shop/logo/'.$request->editShopLogo))) {
                    unlink(public_path('/uploads/shop/logo/'.$request->editShopLogo));
                }
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
                'opening_time' =>  $request->opening_time,
                'closing_time' =>  $request->closing_time,
                'description' =>  $request->description,
                'status'    =>  1,
                'is_blocked'=>  1,
                'updated_at'    =>  date('Y-m-d H:i:s'),
            );
            Vendors::where('id',$request->editVendorId)->update($detailArr);
            if($request->editVendorId) {
                if(isset($request->cover_img) && count($request->cover_img) > 0) {
                    for($x=0; $x<count($request->cover_img); $x++) {
                        $shopCoverLogo = null;
                        if(!empty($request->cover_img[$x])) {
                            if($request->hasFile('cover_img')) {
                                $shopCoverLogo = \Str::random().'.'.time().'.'.$request->cover_img[$x]->getClientOriginalExtension();
                                $request->cover_img[$x]->move(public_path('/uploads/shop/cover/'), $shopCoverLogo);
                            }
                            $coverImgArr = array(
                                'vendor_id' =>  $request->editVendorId,
                                'cover_image'   =>  $shopCoverLogo,
                                'status'    =>  1,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            );
                            ShopCoverImage::insert($coverImgArr);
                        }
                    }
                }
                if(count($request->shop_landline) > 0) {
                    ShopLandline::where('vendor_id',$request->editVendorId)->delete();
                    for($i=0; $i<count($request->shop_landline); $i++) {
                        if(!empty($request->shop_landline[$i])) {
                            $landLineArr = array(
                                'vendor_id' =>  $request->editVendorId,
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
                    ShopMobileNo::where('vendor_id',$request->editVendorId)->delete();
                    for($j=0; $j<count($request->shop_mobile); $j++) {
                        if(!empty($request->shop_mobile[$j])) {
                            $mobileArr = array(
                                'vendor_id' =>  $request->editVendorId,
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
                    ShopEmail::where('vendor_id',$request->editVendorId)->delete();
                    for($a=0; $a<count($request->shop_email); $a++) {
                        if(!empty($request->shop_email[$a])) {
                            $emailArr = array(
                                'vendor_id' =>  $request->editVendorId,
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
                return \Redirect::to('/vendor-list')->with('success','Vendor Details updated successfully.');
            }
            else {
                return back()->with('error','Failed to update Vendor Details.');
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    public function getUsersAllDetails(Request $request)
    {
        $usersDtl = User::find($request->userId);
        if($usersDtl) {
            $usersDtl->membershipExpiry = null;
            if($usersDtl->dob !== null) { $usersDtl->dob = date("d M, Y",strtotime($usersDtl->dob)); }
            $usersDtl->userAdd = \DB::table('user_information')->select('user_id','address_line1','address_line2','landmark','state','city','pincode','country')->where('user_id', $request->userId)->first();

            $subscript = Subscription::select('subscription.plan_id','plan.name as plan_name','subscription.transaction_id','subscription.expiry_date','subscription.is_expired','subscription.status')->join('plan','subscription.plan_id','=','plan.id')->where('subscription.user_id',$request->userId)->orderBy('subscription.id','DESC')->get();
            if(count($subscript) > 0) {
                foreach($subscript as $sub) {
                    $sub->expiry_date = date('d M, Y', strtotime($sub->expiry_date));
                    if($sub->is_expired == 0) { $sub->is_expired = '<span class="badge bg-success">Not Expired</span>'; }
                    else if($sub->is_expired == 1) { $sub->is_expired = '<span class="badge bg-danger">Expired</span>'; }
                    
                    if($sub->status == 0) { $sub->status = '<span class="badge bg-danger">Deactivate</span>'; }
                    else if($sub->status == 1) { 
                        $sub->status = '<span class="badge bg-success">Activate</span>';
                        $usersDtl->membershipExpiry = date('d M, Y', strtotime($sub->expiry_date));
                    }
                }
            }

            $redem = Redemption::select('redeem_req.user_id','redeem_req.is_approved','redeem_req.amount','redeem_req.status','redeem_req.approval_date','redeem_req.created_at as req_date','bank_info.acc_no')
                        ->leftjoin('bank_info','redeem_req.bank_id','=','bank_info.id')
                        ->where('redeem_req.user_id',$request->userId)->orderBy('redeem_req.id','DESC')->get();
            if(count($redem) > 0) {
                foreach($redem as $red) {
                    if($red->is_approved == 0) { $red->is_approved = '<span class="badge bg-warning">Pending</span>'; }
                    else if($red->is_approved == 1) { $red->is_approved = '<span class="badge bg-success">Approved</span>'; }
                    else if($red->is_approved == 2) { $red->is_approved = '<span class="badge bg-danger">Rejected</span>'; }
                    $red->req_date = date('d M, Y',strtotime($red->req_date));
                    $red->approval_date = (!empty($red->approval_date)) ? date('d M, Y',strtotime($red->approval_date)) : '';
                }
            }

            $referral = ReferalBonus::select('referal_code','amount','status','created_at as ref_date')->where('user_id',$request->userId)->orderBy('id','DESC')->get();
            if(count($referral) > 0) {
                foreach($referral as $ref) {
                    $ref->ref_date = date('d M, Y', strtotime($ref->ref_date));
                    if($ref->status == 0) { $ref->status = '<span class="badge bg-danger">Deactivate</span>'; }
                    else if($ref->status == 1) { $ref->status = '<span class="badge bg-success">Activate</span>'; }
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
        $userList = User::where('is_blocked',0)->where('role',2)->orderBy('name','ASC')->get();
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
            $data = Plans::select('plan.id','plan.name','plan.validity','plan.amount','plan.tax','plan.total','plan.status')->orderBy('plan.id','DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('category', function($row){
                    $category = [];
                    $planCate = PlanCategory::select('category_id')->where('plan_id',$row->id)->get();
                    if(count($planCate) > 0) {
                        foreach($planCate as $pc) {
                            $cate = Categories::select('name')->where('id', $pc->category_id)->first();
                            $category[] = $cate->name;
                        }
                    }
                    $category = implode(', ',$category);
                    return $category;
                })
                ->addColumn('amount', function($row){
                    $amount = $row->amount. ' AED';
                    return $amount;
                })
                ->addColumn('total', function($row){
                    $total = $row->total. ' AED';
                    return $total;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="remove-plans badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-plans badge bg-success" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i> Edit</a>';
                    if($row->status == 0) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="activate" data-id="'.$row->id.'" class="activeDeactivePlans badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</a>';
                    } else if($row->status == 1) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="deactivate" data-id="'.$row->id.'" class="activeDeactivePlans badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</a>';
                    }
                    return $action;
                })
                ->rawColumns(['status','action','amount','total'])
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
            $plan->category = PlanCategory::select('category_id')->where('plan_id',$request->id)->get();
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

    public function activeDeactiveSubscriptionPlan (Request $request)
    {
        $action = Plans::where('id',$request->id)->first();
        if($action) {
            if($request->action === 'activate') {
                Plans::where('id',$request->id)->update(['status' => 1]);
            }
            if($request->action === 'deactivate') {
                Plans::where('id',$request->id)->update(['status' => 0]);
            }
            return (['status' => true, 'message' => 'Subscription Plan '.$request->action.'d successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to Subscription Plan '.$request->action.'d.']);
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
                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('offer_desc', function($row){
                    $offer_desc = strip_tags(html_entity_decode($row->offer_desc));
                    return $offer_desc;
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
                    $action = '<a href="javascript:void(0)" class="remove-offers badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i> Delete</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="edit-offers badge bg-success" data-id="'.$row->id.'" ><i class="fa fa-edit">&nbsp;&nbsp;</i> Edit</a>';
                    if($row->status == 0) {
                        $action .= '&nbsp;&nbsp;<span class="activeDeactiveOffers cursor-point badge bg-success" data-id="'.$row->id.'" data-action="activate"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    } else if($row->status == 1) {
                        $action .= '&nbsp;&nbsp;<span class="activeDeactiveOffers cursor-point badge bg-danger" data-id="'.$row->id.'" data-action="deactivate"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
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
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('amount', function($row){
                    $amount = $row->amount.' AED';
                    return $amount;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="action-request badge bg-success" data-action="approve" data-id="'.$row->id.'" ><i class="fa fa-check">&nbsp;&nbsp;</i>Approve</a> &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="action-request badge bg-danger" data-action="reject" data-id="'.$row->id.'" ><i class="fa fa-ban">&nbsp;&nbsp;</i>Reject</a>';
                    return $action;
                })
                ->rawColumns(['amount','status','action'])
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
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('amount', function($row){
                    $amount = $row->amount.' AED';
                    return $amount;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-success" ><i class="fa fa-check">&nbsp;&nbsp;</i>Approved</a>';
                    return $action;
                })
                ->rawColumns(['amount','status','action'])
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
                    $status = '<span class="badge bg-warning"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('amount', function($row){
                    $amount = $row->amount.' AED';
                    return $amount;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="badge bg-danger" ><i class="fa fa-ban">&nbsp;&nbsp;</i>Rejected</a>';
                    return $action;
                })
                ->rawColumns(['amount','status','action'])
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
                'approval_date' =>  date('Y-m-d H:i:s'),
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

    public function deleteSelectedOffers (Request $request)
    {
        $offers = Offers::find($request->id);
        if($offers) {
            $offers->delete();
            return (['status' => true, 'message' => 'Offers deleted successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to delete Offers.']);
    }

    public function editSelectedOffers (Request $request)
    {
        $offers = Offers::find($request->id);
        if($offers) {
            $offers->start_date = date('d-m-Y', strtotime($offers->start_date));
            $offers->end_date = date('d-m-Y', strtotime($offers->end_date));
            
            return (['status' => true, 'message' => 'Record found.', 'data'=>$offers]);
        }
        return (['status' => false, 'message' => 'No Record found.', 'data'=>[] ]);
    }
    
    public function activeDeactiveSelectedOffers (Request $request)
    {
        $action = Offers::where('id',$request->id)->first();
        if($action) {
            if($request->action === 'activate') {
                Offers::where('id',$request->id)->update(['status' => 1]);
            }
            if($request->action === 'deactivate') {
                Offers::where('id',$request->id)->update(['status' => 0]);
            }
            return (['status' => true, 'message' => 'Offers '.$request->action.'d successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to Offers '.$request->action.'d.']);
    }

    public function getPushNotificationList (Request $request)
    {
        if ($request->ajax()) {
            $data = Notification::latest()->get();                    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('msg', function($row){
                    $msg = strip_tags(html_entity_decode($row->msg));                    
                    return $msg;
                })
                ->addColumn('users', function($row){
                    $usernameArr = [];
                    $receiver = explode(',',$row->received_id);
                    
                    if(count($receiver) > 0) {
                        foreach($receiver as $rec) {
                            $username = User::getUserName($rec);
                            array_push($usernameArr, $username);
                        }
                    }
                    $users = implode(', ',$usernameArr);
                    return $users;
                })
                ->rawColumns(['status','users'])
                ->make(true);
        }
    }

    public function getReportAndFeedbackList (Request $request)
    {
        if ($request->ajax()) {
            $data = Feedback::select('feedback.title','feedback.user_id','users.name as username','feedback.description','feedback.created_at')
                ->join('users','feedback.user_id','=','users.id')->orderBy('feedback.id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('reportDate', function($row){
                    $date = date('d F, Y', strtotime($row->created_at));
                    return $date;
                })
                ->rawColumns(['reportDate'])
                ->make(true);
        }
    }



    public function createNewReferralAmount (Request $request)
    {
        $request->validate([
            'referral_amt' =>  'required',
        ],[
            'referral_amt.required'    =>  'Referral Amount should not be blank.',
        ]);
        try {
            if(ReferralAmt::where('status',1)->count() > 0) {
                ReferralAmt::where('status',1)->update(['status' => 0]);
                ReferralAmt::insert([
                    'referral_amt'  =>  $request->referral_amt,
                    'status'        =>  1,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ]);
                return back()->with('success',"Referral Amount added successfully.");
            }
            else {
                if(ReferralAmt::insert([
                    'referral_amt'  =>  $request->referral_amt,
                    'status'        =>  1,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ])) 
                    return back()->with('success',"Referral Amount added successfully.");

                else 
                    return back()->with('error',"Failed to add referral amount, Try again.");
            }
        } catch (\Exception $e) {
            return back()->with('error',$e->getMessage());
        }
    }

    public function getAllReferralAmountList (Request $request)
    {
        if ($request->ajax()) {
            $data = ReferralAmt::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = '<span class="badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</span>';
                    if($row->status == 0) {
                        $status = '<span class="badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</span>';
                    }
                    return $status;
                })
                ->addColumn('referral_amt', function($row){
                    $referral = $row->referral_amt.' AED';
                    return $referral;
                })
                ->addColumn('action', function($row){
                    $action = '<a href="javascript:void(0)" class="removeReferral badge bg-danger" data-id="'.$row->id.'" ><i class="fa fa-trash">&nbsp;&nbsp;</i>Delete</a>';
                    
                    if($row->status == 0) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="activate" data-id="'.$row->id.'" class="activeDeactiveReferral badge bg-success"><i class="fa fa-toggle-on">&nbsp;&nbsp;</i>Activate</a>';
                    } /*else if($row->status == 1) {
                        $action .= '&nbsp;&nbsp; <a href="javascript:void(0)" data-action="deactivate" data-id="'.$row->id.'" class="activeDeactiveReferral badge bg-danger"><i class="fa fa-toggle-off">&nbsp;&nbsp;</i>Deactivate</a>';
                    }*/
                    return $action;
                })
                ->rawColumns(['referral_amt','status','action'])
                ->make(true);
        }
    }

    public function deleteSelectedReferralAmount (Request $request)
    {
        $referral = ReferralAmt::find($request->id);
        if(($referral) && ($referral->status == 1)) {
            return (['status' => false, 'message' => 'Sorry, Before delete this Referral Amount, You need to active other one.']);
        }
        else if(($referral) && ($referral->status == 0)) {
            $referral->delete();
            return (['status' => true, 'message' => 'Referral Amount deleted successfully.']);
        }
        else {
            return (['status' => false, 'message' => 'Failed to delete category.']);
        }
    }

    public function activeDeactiveSelectedReferralAmount (Request $request)
    {
        if(ReferralAmt::where('id','!=',$request->id)->where('status',1)->count() > 0) 
        {
            ReferralAmt::where('status',1)->update(['status' => 0]);
            ReferralAmt::where('id',$request->id)->update(['status' => 1]);

            return (['status' => true, 'message' => 'Referral Amount '.$request->action.'d successfully.']);
        }
        return (['status' => false, 'message' => 'Failed to Category '.$request->action.'d.']);
    }

}
