<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>King's Motor</title>
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="/style/navbar.css">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/style/home.css">

</head>

<body>
  <div class="wrapper">
    <aside id="sidebar">
      <div class="d-flex">
        <button class="toggle-btn" type="button">
          <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
          <a href="#">King's motor</a>
        </div>
      </div>
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <a href="{{ route('inventory.dashboard')}}" class="sidebar-link">
            <i class="lni lni-dashboard"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('inventory.showInventories')}}" class="sidebar-link">
            <i class="lni lni-agenda"></i>
            <span>Inventories</span>
          </a>

        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth"
            aria-expanded="false" aria-controls="auth">
            <i class="lni lni-upload"></i>
            <span>Outgoing Distribution</span>
          </a>
          <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
              <a href="{{ route('inventory.index')}}" class="sidebar-link">Outgoing Item List</a>
            </li>
            <li class="sidebar-item">
              <a href="{{ route('inventory.showOutgoingForm')}}" class="sidebar-link">Out Distribution Item</a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth"
            aria-expanded="false" aria-controls="auth">
            <i class="lni lni-archive"></i>
            <span>Inbound Distribution</span>
          </a>
          <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
              <a href="{{ route('inventory.listIngoingItem')}}" class="sidebar-link">Incoming Item List</a>
            </li>
            <li class="sidebar-item">
              <a href="{{ route('inventory.showIngoingForm')}}" class="sidebar-link">Inbound Distribution Item</a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth"
            aria-expanded="false" aria-controls="auth">
            <i class="lni lni-more-alt"></i>
            <span>ETC</span>
          </a>
          <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
              <a href="{{ route('inventory.listCategory')}}" class="sidebar-link">Category</a>
            </li>
            <li class="sidebar-item">
              <a href="{{ route('inventory.listLocation')}}" class="sidebar-link">Location</a>
            </li>
            <li class="sidebar-item">
              <a href="{{ route('inventory.listBrands')}}" class="sidebar-link">Brands</a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#auth"
            aria-expanded="false" aria-controls="auth">
            <i class="lni lni-cart-full"></i>
            <span>Cart</span>
          </a>
          <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
              <a href="{{ route('cash_register.listInvoices')}}" class="sidebar-link">List Invoice</a>
            </li>
            <li class="sidebar-item">
              <a href="{{ route('cash_register.create')}}" class="sidebar-link">Cashier</a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('account.index')}}" class="sidebar-link">
            <i class="lni lni-users"></i>
            <span>Account</span>
          </a>
        </li>
        <div class="sidebar-footer">
          <a href="{{ route('account.logout')}}" class="sidebar-link">
            <i class="lni lni-exit"></i>
            <span>Logout</span>
          </a>
        </div>
    </aside>
    <div class="content">@yield('container')</div>


  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  @yield('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script>
    const hamBurger = document.querySelector(".toggle-btn");

    hamBurger.addEventListener("click", function () {
      document.querySelector("#sidebar").classList.toggle("expand");
    });
  </script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- QuaggaJS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
</body>

</html>