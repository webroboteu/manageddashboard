@extends('plugins/webrobot-dashboard::layouts.skeleton')
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card login-form">
          <div class="card-body">
              <h4 class="text-center">{{ trans('plugins/webrobot-dashboard::dashboard.login-title') }}</h4>
              <br>
            <form method="POST" action="{{ route('public.member.login') }}">
              @csrf
              <div class="form-group mb-3">
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ trans('plugins/webrobot-dashboard::dashboard.email') }}" name="email" value="{{ old('email') }}" autofocus>
                  @if ($errors->has('email'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
                </span>
                  @endif
                </div>
              <div class="form-group mb-3">
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ trans('plugins/webrobot-dashboard::dashboard.password') }}" name="password">
                  @if ($errors->has('password'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
                </span>
                  @endif
              </div>
              <div class="form-group mb-3">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('plugins/webrobot-dashboard::dashboard.remember-me') }}
                    </label>
                </div>
              </div>
              <div class="form-group mb-0">
                  <button type="submit" class="btn btn-blue btn-full fw6">
                      {{ trans('plugins/webrobot-dashboard::dashboard.login-cta') }}
                  </button>
                  <div class="text-center">
                      <a class="btn btn-link" href="{{ route('public.member.password.request') }}">
                          {{ trans('plugins/webrobot-dashboard::dashboard.forgot-password-cta') }}
                      </a>
                  </div>
              </div>

                <div class="text-center">
                    {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\webrobotdashboard\Models\Member::class) !!}
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script type="text/javascript" src="{{ asset('vendor/core/core/js-validation/js/js-validation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\webrobotdashboard\Http\Requests\LoginRequest::class); !!}
@endpush
