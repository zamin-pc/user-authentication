<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    
<section class="vh-100 bg-image"
style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
<div class="mask d-flex align-items-center h-100 gradient-custom-3">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-9 col-lg-7 col-xl-6">
        <div class="card" style="border-radius: 15px;">
          <div class="card-body p-5">
            <h2 class="text-uppercase text-center mb-5">Create an account</h2>


            <form method="POST" action="{{ route('user.register') }}">
            @error('name')
                   <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @csrf
              <div class="form-outline mb-4">
                <input type="text" id="form3Example1cg" name="name" class="form-control form-control-lg" />
                <label class="form-label" for="form3Example1cg">Your Name</label>
                @error('name')
                   <span class="" >hello</span>
                @enderror
              </div>

              <div class="form-outline mb-4">
                <input type="email" id="form3Example3cg" name="email" class="form-control form-control-lg" />
                <label class="form-label" for="form3Example3cg">Your Email</label>
                @error('email')
                  
                @enderror
              </div>

              <div class="form-outline mb-4">
                <input type="tel" id="form3Example3cg" name="phone" class="form-control form-control-lg" />
                <label class="form-label" for="form3Example3cg">Contact No.</label>
                @error('phone')
                  
                @enderror
              </div>

              <div class="form-outline mb-4">
                <input type="password" id="form3Example4cg" name="password" class="form-control form-control-lg" />
                <label class="form-label" for="form3Example4cg">Password</label>
                @error('password')
                  
                @enderror
              </div>

              <div class="d-flex justify-content-center">
                <button type="submit"
                  class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
              </div>

              <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="{{ route('login.form') }}"
                  class="fw-bold text-body"><u>Login here</u></a></p>

            <!-- </form> -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
</body>
</html>