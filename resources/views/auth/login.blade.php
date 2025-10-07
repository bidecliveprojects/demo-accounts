@include('includes._normalUserNavigation')
  <style>
    .help-block-c{
      height: 0px !important;
      top: unset !important;
    }
  </style>
  <section class="login-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="login-fwrp p1">
            <form action="{{ route('login.custom') }}" method="POST" class="form-signin">
              {{ csrf_field() }}
              <div class="l-logo">
                <img src="./assets/img/logo.png" style="width: 25%;">
              </div>
              <h3>Welcome To <span>Demo Accounts</span></h3>
              <p>Please Login your account and start the adventure</p>
              <span id="colorgraph"><hr class="colorgraph"></span>
              <label>Username</label>
              <div class="inner-addon left-addon">
                <span><i class="fal fa-envelope"></i></span>
                <input id="username" type="text" class="form-control singInput" name="username" value="{{ old('username') }}" autocomplete="off" placeholder="Username" />
                @if ($errors->has('username'))
                  <span class="help-block help-block-c">
                    <strong>{{ $errors->first('username') }}</strong>
                  </span>
                @endif
              </div>
              <br>
              <label>Password</label>
              <div class="inner-addon left-addon">
                <span><i class="fal fa-unlock-alt"></i></span>
                <input id="password" type="password" class="form-control singInput" name="password" autocomplete="off"  placeholder="Password" />
                @if ($errors->has('password'))
                  <span class="help-block help-block-c">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>
              <br>
              <button type="submit" class="btn login-btn" onclick="loader()">LOGIN  <i class="fal fa-arrow-right"></i></button>
              <div class="fga"><a href="{{route('forgetPasswordForm')}}">Forgot Password ?</a></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
