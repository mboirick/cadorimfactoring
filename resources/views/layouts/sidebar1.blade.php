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
        <li  ><a href="/" style="color: #fff;"><i class="fa fa-home" ></i> <span>Accueil</span></a></li>
        
        @if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='cash'  )
        <li class="treeview">
          <a href="#"  style="color: #fff;" >
            <i class="fa fa-money"></i> <span>Cash & Clients</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" >
            
            <li ><a href="{{ url('cache-management') }}"   style="color: #fff;" ><i class="fa fa-money" ></i> Cash</a></li>
            <li><a href="{{ url('cache-management/clients') }}"  style="color: #fff;" ><i class="fa fa-user"></i> Clients</a></li>
          </ul>
        </li>
        @endif

        @if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='operateur'  )
        <li  ><a href="{{ route('cashout-management') }}"  style="color: #fff;"><i class="fa fa-paper-plane" ></i> <span>Transfert</span></a></li>

        <li   ><a href="{{ url('virement-management') }}"  style="color: #fff;"><i class="fa fa-share"></i> <span>Les virements</span></a></li>
        @endif
        @if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='client'  || Auth::user()->user_type=='operateur'  )
        <li  ><a href="{{ route('abonnes-management') }}"  style="color: #fff;"><i class="fa fa-users" ></i> <span>Les Abonnes</span></a></li>
        @endif

        @if(Auth::user()->user_type=='admin')
                    
        <li   ><a href="#"  style="color: #fff;" ><i class="fa fa-exchange" ></i> <span>Les frais Gaza</span></a></li> 

        <li  ><a href="{{ url('atlpay-management') }}"  style="color: #fff;" ><i class="fa fa-calculator" ></i> <span>ATLPAY</span></a></li>
      <li  ><a href="{{ route('user-management.index') }}"  style="color: #fff;"><i class="fa fa-user"></i> <span >Gestion administrateurs</span></a></li>
        

      <li class="treeview">
          <a href="#"  style="color: #fff;" >
            <i class="fa fa-line-chart"></i> <span>Les Statistiques</span>
            </a>
          
        </li>
        @endif
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>