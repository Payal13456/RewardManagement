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

                <li class="sidebar-item active ">
                    <a href="{{URL::route('/')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person"></i>
                        <span>User</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="{{URL::route('users-list')}}">List User</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="component-alert.html">Message to User</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="component-alert.html">Referal Code</a>
                        </li>

                    </ul>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person-badge"></i>
                        <span>Vendor</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="{{URL::route('category')}}">Category</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="{{URL::route('vendor-create')}}">Add Vendor</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="component-alert.html">Vendor List</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="component-alert.html">Offers</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item ">
                    <a href="index.html" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Subsctiption</span>
                    </a>
                </li>
                
                <li class="sidebar-item ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-collection-fill"></i>
                        <span>Reports / Feedback </span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Push Notification</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-hexagon-fill"></i>
                        <span>Reedem Request</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>