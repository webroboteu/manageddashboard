@extends('plugins/webrobotdashboard::layouts.skeleton')
@section('content')
 
  <div class="dashboard crop-avatar">
    <div>
      <div >
      <div>
        <div class="tabs">
          <input type="radio" id="tab1" name="tab-control" checked>
    
        <ul>
          <li title="Profile"><label for="tab1" role="button"><svg viewBox="0 0 24 24"><path d="M2,10.96C1.5,10.68 1.35,10.07 1.63,9.59L3.13,7C3.24,6.8 3.41,6.66 3.6,6.58L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.66,6.72 20.82,6.88 20.91,7.08L22.36,9.6C22.64,10.08 22.47,10.69 22,10.96L21,11.54V16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V10.96C2.7,11.13 2.32,11.14 2,10.96M12,4.15V4.15L12,10.85V10.85L17.96,7.5L12,4.15M5,15.91L11,19.29V12.58L5,9.21V15.91M19,15.91V12.69L14,15.59C13.67,15.77 13.3,15.76 13,15.6V19.29L19,15.91M13.85,13.36L20.13,9.73L19.55,8.72L13.27,12.35L13.85,13.36Z" />
        </svg><br><span>Profile</span></label></li>
     
        </ul>
        <div class="slider"><div class="indicator"></div></div>
        <div class="content">
        <section>
          <h2>Profile</h2>
          <div class="row">
          <div class="col-md-3 mb-3 dn db-ns">
          <div class="mb3">
            <div class="sidebar-profile">
              <div class="avatar-container mb-2">
                <div class="profile-image">
                  <div class="avatar-view mt-card-avatar mt-card-avatar-circle" style="max-width: 150px">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="br-100" style="width: 150px;">
                    <div class="mt-overlay br2">
                      <span><i class="fa fa-edit"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="f4 b">{{ $user->name }}</div>
              <div class="f6 mb3 light-gray-text">
                <i class="fas fa-envelope mr2"></i><a href="mailto:{{ $user->email }}" class="gray-text">{{ $user->email }}</a>
              </div>
              <div class="mb3">
                <div class="light-gray-text mb2">
                  <i class="fas fa-calendar-alt mr2"></i>{{ trans('plugins/webrobotdashboard::dashboard.joined_on', ['date' => $user->created_at->format('F d, Y')]) }}
                </div>
                @if ($user->dob)
                  <div class="light-gray-text mb2">
                    <i class="fas fa-child mr2"></i>{{ trans('plugins/webrobotdashboard::dashboard.dob', ['date' => $user->dob]) }}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class = "col-md-3 mb-3 dn db-ns" >
            {!! apply_filters(MEMBER_TOP_STATISTIC_FILTER, null) !!}
              @if (is_plugin_active('blog'))
                  @include('plugins/webrobotdashboard::components.statistic')
              @endif
            <activity-log-component default-active-tab="activity-logs"></activity-log-component>
        </div>
        </div>
        </section>
       
      </div>
    </div>
</div>
</div>
    @include('plugins/webrobotdashboard::modals.avatar')
  </div>
@endsection
