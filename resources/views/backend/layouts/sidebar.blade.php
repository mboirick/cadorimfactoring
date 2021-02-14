  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../{{ Auth::user()->username}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <!-- Optionally, you can add icons to the links -->
        <li>
            <a href="/" style="color: #fff;">
                <i class="fa fa-home"></i>
                <span>@lang('lang.home')</span>
            </a>
        </li>
        @can('admin-Cash-Flow')
          <li class="treeview">
            <li><a href="{{ route('cash.flow.home') }}" style="color: #fff;"><i class="fa fa-money"></i> Gestion du Cashs</a></li>
          </li>
        @endcan
        @can('admin-Agency')
          <li class="treeview">
            <li><a href="{{ url('agencies/home') }}" style="color: #fff;"><i class="fa fa-user"></i> Agences</a></li>
          </li>
        @endcan 
        @can('admin-Cash-Flow')
          <li class="treeview">
            <a href="#" style="color: #fff;">
              <i class="fa fa-shopping-cart"></i> <span>Gestion de paiements</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('payement.clients') }}" style="color: #fff;"><i class="fa fa-user"></i> Clients</a></li>
              <li><a href="{{ route('payement.waiting') }}" style="color: #fff;"><i class="fa fa-clock-o"></i> Paiement en attente</a></li>
              <li><a href="{{ url('paiement-management/story') }}" style="color: #fff;"><i class="fa fa-archive"></i> historique de paiement</a></li>
            </ul>
          </li>
        @endcan

        @can('admin-Cash-out')
          <li><a href="{{ route('cash.out.home') }}" style="color: #fff;"><i class="fa fa-paper-plane"></i> <span>Transfert</span></a></li>

          <li><a href="{{ url('virement-management') }}" style="color: #fff;"><i class="fa fa-share"></i> <span>Les virements</span></a></li>
        @endcan

        @can('parrainage')
          <li><a href="{{ route('subscribers.home') }}" style="color: #fff;"><i class="fa fa-users"></i> <span>@lang('lang.subscribers')</span></a></li>
          <li>
              <a href="{{ route('sponsoring.home') }}" style="color: #fff;">
                  <i class="fa fa-users"></i> 
                  <span>Parrainage</span>
              </a>
          </li>
        @endcan

        @can('admin-agence')
          <li>
            <a href="{{ route('cash.out.operator') }}" style="color: #fff;">
              <i class="fa fa-exchange"></i> <span>Opérateur Transfert</span>
            </a>
          </li>
        @endcan
        @can('admin-user')
          <li><a href="{{ route('users') }}" style="color: #fff;"><i class="fa fa-user"></i> <span>Gestion administrateurs</span></a></li>
        @endcan
        
        @can('admin-role')
          <li><a href="{{ route('roles.index') }}" style="color: #fff;"><i class="fa fa-user"></i> <span>Gestion des rôles</span></a></li>
        @endcan
        @can('cash-out-operator')
              <li>
                <a href="{{ route('cash.out.operator') }}" style="color: #fff;">
                  <i class="fa fa-exchange"></i> <span>{{Auth::user()->firstname}} Transfer</span>
                </a>
              </li>
        @endcan

        @can('atlpay')
          <li>
            <a href="{{ route('atlpay.home') }}" style="color: #fff;">
              <i class="fa fa-calculator"></i> 
              <span>ATLPAY</span>
            </a>
          </li>
        @endcan
      
        @can('sondage')
          <li class="treeview">
            <a href="{{ route('survey.home') }}" style="color: #fff;">
              <i class="fa fa-bar-chart"></i> <span>Les sondages</span>
            </a>
          </li>
        @endcan

        @can('coupon')
          <li class="treeview">
            <a href="{{route('reduce.index')}}" style="color: #fff;">
              <i class="fa fa-tag"></i> <span>Coupon Promo</span>
            </a>
          </li>
        @endcan
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>