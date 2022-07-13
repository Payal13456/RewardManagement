<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <!-- <a href="{{URL::route('/')}}"><img src="assets/images/logo/logo.png" alt="Logo" srcset=""></a> -->
                    <a href="javascript:void(0);">Rewards</a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{!in_array(Request::segment(1), array('reports-and-feedback','users-list','message-to-users','category','vendor','vendor-list','offers','subscription-plans','push-notification','reedem-request')) ? 'active' : ''}}">
                    <a href="{{URL::route('/')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub {{in_array(Request::segment(1), array('users-list','message-to-users')) ? 'active' : ''}}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person"></i>
                        <span>User</span>
                    </a>
                    <ul class="submenu " style="{{in_array(Request::segment(1), array('users-list','message-to-users')) ? 'display: block' : 'display:none'}}" >
                        <li class="submenu-item {{Request::segment(1) === 'users-list' ? 'active' : ''}}">
                            <a href="{{URL::route('users-list')}}">List User</a>
                        </li>
                        <li class="submenu-item {{Request::segment(1) === 'message-to-users' ? 'active' : ''}}">
                            <a href="{{URL::route('message-to-users')}}">Message to User</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="component-alert.html">Referal Code</a>
                        </li>

                    </ul>
                </li>

                <li class="sidebar-item  has-sub {{in_array(Request::segment(1), array('category','vendor','vendor-list','offers')) ? 'active':''}}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person-badge"></i>
                        <span>Vendor</span>
                    </a>
                    <ul class="submenu " style="{{in_array(Request::segment(1), array('category','vendor','vendor-list','offers')) ? 'display: block' : 'display:none'}}">
                        <li class="submenu-item {{Request::segment(1) === 'category' ? 'active' : ''}}">
                            <a href="{{URL::route('category')}}">Category</a>
                        </li>
                        <li class="submenu-item {{Request::segment(1) === 'vendor' ? 'active' : ''}}">
                            <a href="{{URL::route('vendor')}}">Add Vendor</a>
                        </li>
                        <li class="submenu-item {{Request::segment(1) === 'vendor-list' ? 'active' : ''}}">
                            <a href="{{URL::route('vendor-list')}}">Vendor List</a>
                        </li>
                        <li class="submenu-item {{Request::segment(1) === 'offers' ? 'active' : ''}}">
                            <a href="{{URL::route('offers')}}">Offers</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{Request::segment(1) === 'subscription-plans' ? 'active' : ''}}">
                    <a href="{{URL::route('subscription-plans')}}" class='sidebar-link'>
                        <i class="fa fa-tasks"></i>
                        <span>Subsctiption Plan</span>
                    </a>
                </li>

                <li class="sidebar-item {{Request::segment(1) === 'reports-and-feedback' ? 'active' : ''}}">
                    <a href="{{URL::route('reports-and-feedback')}}" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Reports / Feedback</span>
                    </a>
                </li>

                <li class="sidebar-item {{Request::segment(1) === 'push-notification' ? 'active' : ''}}">
                    <a href="{{URL::route('push-notification')}}" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Push Notification</span>
                    </a>
                </li>

                <li class="sidebar-item {{Request::segment(1) === 'reedem-request' ? 'active' : ''}}">
                    <a href="{{URL::route('reedem-request')}}" class='sidebar-link'>
                        <i class="bi bi-hexagon-fill"></i>
                        <span>Reedem Request</span>
                    </a>
                </li>
                
                <li class="sidebar-item" id="confirmLogout">
                    <a href="javascript:void(0)" class='sidebar-link'>
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>