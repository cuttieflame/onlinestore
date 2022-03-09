@include('baza')
<body>
<section class="header-main bg-white">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-lg-2 col-sm-4 col-4">
                <a href="http://bootstrap-ecommerce.com" class="navbar-brand">
                    <img class="logo" height="40" src="{{asset('storage/images/1.png')}}">
                </a> <!-- brand end.// -->
            </div>
            <div class="order-lg-last col-lg-5 col-sm-8 col-8">
                <div class="float-end">

                    <a href="{{url('login')}}" class="btn btn-light">
                        <i class="fa fa-user"></i>  <span class="ms-1 d-none d-sm-inline-block">Sign in  </span>
                    </a>
                    <a href="#" class="btn btn-light">
                        <i class="fa fa-heart"></i>  <span class="ms-1 d-none d-sm-inline-block">Wishlist</span>
                    </a>
                    <a data-bs-toggle="offcanvas" href="#offcanvas_cart" class="btn btn-light">
                        <i class="fa fa-shopping-cart"></i> <span class="ms-1">My cart </span>
                    </a>
                </div>
            </div> <!-- col end.// -->
            <div class="col-lg-5 col-md-12 col-12">
                <form action="#" class="">
                    <div class="input-group">
                        <select class="form-select">
                            <option value="">All type</option>
                            <option value="codex">Special</option>
                            <option value="comments">Only best</option>
                            <option value="content">Latest</option>
                        </select>
                        <input type="search" class="form-control" style="width:55%" placeholder="Search">

                        <button class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div> <!-- input-group end.// -->
                </form>
            </div> <!-- col end.// -->

        </div> <!-- row end.// -->
    </div> <!-- container end.// -->
</section>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-dark navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Online Store<span class="badge bg-primary"></span></a>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
                <div class="hamburger-toggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbar-content">
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown dropdown-mega position-static">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">Megamenu</a>
                        <div class="dropdown-menu shadow">
                            <div class="mega-content px-4">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-sm-4 col-md-3">
                                            <h5>Категории</h5>
                                            <div style="min-width:300px;" class="list-group">
                                                @foreach($categories as $category)
                                                <a style="border:none;" class="list-group-item" href="#">{{$category->title}}</a>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</div>

<section class="section-intro pt-3">
    <div class="container">

        <div class="row gx-3">
            <main class="col-lg-9">
                <article class="card-banner p-5 bg-primary" style="height: 350px">
                    <div style="max-width: 500px">
                        <h2 class="text-white">Great products with <br> best deals </h2>
                        <p class="text-white">No matter how far along you are in your sophistication as an amateur astronomer, there is always one.</p>
                        <a href="#" class="btn btn-warning"> View more </a>
                    </div>
                </article>
            </main>
            <aside  class="col-lg-3">
                <article style="background-color:red;" class="card-banner h-100">
                    <div class="card-body text-center">
                        <h5 class="mt-3 text-white">Amazing Gifts</h5>
                        <p class="text-white">No matter how far along you are in your sophistication</p>
                        <a href="#" class="btn btn-outline-light"> View more </a>
                    </div>
                </article>
            </aside>
        </div> <!-- row //end -->

    </div> <!-- container end.// -->
</section>

<section class="padding-top">
    <div class="container">

        <header class="section-heading">
            <h3 class="section-title">New products</h3>
        </header>

        <div class="row">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-6 col-sm-6">
                <figure class="card card-product-grid">
                    <a href="#" class="img-wrap">
{{--                        @if ($loop->first)--}}
{{--                            <span class="topbar"> <b class="badge bg-success"> Offer </b> </span>--}}
{{--                        @endif--}}
                        <img style="width:240px;height:240px;margin-left:30px;" src="{{asset('storage/'.$product->image)}}">
                    </a>
                    <figcaption class="info-wrap border-top">

                        <div>
                        <a class="title text-truncate"> {{\Illuminate\Support\Str::limit($product->name,35)}}</a>
                        <p> {{\Illuminate\Support\Str::limit($product->content,50)}}</p>
                        <p>Цена-{{$product->price}}</p>
                            <button class="btn btn-primary"><a style="text-decoration:none;color:white;" href="#">В корзину <i class="fa fa-shopping-cart fa-lg"></i> </a></button>
                            <button class="btn btn-light border"><a href="#"> <i class="fa fa-heart fa-lg"></i> </a></button>
                        </div>
                    </figcaption>
                </figure>
            </div> <!-- col end.// -->
            @endforeach
        </div>


    </div>

</section>

<section class="padding-top">
    <div class="container">
        <div class="row gy-4">
            <aside class="col-lg-6">
                <article style="
                background:url('https://sun9-74.userapi.com/impg/qHQ7qcG_pbR1vm5AAefINgzuZCeb1PPsQ8N6pA/8QIV2g5pELw.jpg?size=258x209&quality=96&sign=8031b50a5691bab6be0391e028450cd0&type=album') no-repeat;
                background-size:cover;
                "
                 class="card-banner bg-gray h-100" style="min-height: 200px">
                    <div class="p-3 p-lg-5" style="max-width:70%">
                        <h3> Best products &amp; brands in our store at 80% off</h3>
                        <p>That's true but not always</p>
                        <a class="btn btn-warning" href="#"> Claim offer </a>
                    </div>
                </article>
            </aside> <!-- col.// -->
            <aside class="col-lg-6">

                <div class="row mb-4">
                    <div class="col-6">
                        <article class="card bg-primary" style="min-height: 200px">
                            <div class="card-body">
                                <h5 class="text-white">Gaming toolset</h5>
                                <p class="text-white-50">Technology for cyber sport  </p>
                                <a class="btn btn-outline-light btn-sm" href="#">Learn more</a>
                            </div>
                        </article>
                    </div>
                    <div class="col-6">
                        <article class="card bg-primary" style="min-height: 200px">
                            <div class="card-body">
                                <h5 class="text-white">Quality sound</h5>
                                <p class="text-white-50">All you need for music</p>
                                <a class="btn btn-outline-light btn-sm" href="#">Learn more</a>
                            </div>
                        </article>
                    </div>
                </div> <!-- row.// -->

                <article class="card bg-success" style="min-height: 200px">
                    <div class="card-body">
                        <h5 class="text-white">Buy 2 items, With special gift</h5>
                        <p class="text-white-50" style="max-width:400px;">Buy one, get one free marketing strategy helps your business improves the brand by sharing the profits </p>
                        <a class="btn btn-outline-light btn-sm" href="#">Learn more</a>
                    </div>
                </article>

            </aside> <!-- col.// -->
        </div> <!-- row.// -->
    </div> <!-- container end.// -->
</section>

<section class="padding-top">
    <div class="container">

        <header class="section-heading">
            <h3 class="section-title">Recently viewed</h3>
        </header>

        <div class="row gy-3">
            @foreach($recently_viewed as $view)
            <div class="col-lg-2 col-md-4 col-4">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div style="height:200px;min-width:200px !important;" class="hovereffect">
                        <img style="height:200px;min-width:200px !important;" src="{{asset('storage/'.$view->image)}}" alt="">
                        <div class="overlay">
                            <p style="color:white;">{{$view->name}}</p>
                            <a class="info" href="">link here</a>
                        </div>
                    </div>
                </div>
            </div>
          @endforeach
        </div>

    </div>
</section>


<section style="margin-top:25px;" class="padding-y">
    <div class="container">
        <article style="height:150px;" class="card p-3 p-lg-5">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <figure class="icontext">
                        <div class="icon">
					<span style="width:42px;height:42px;" class="icon-sm bg-warning-light text-warning rounded">
						<span class="fa fa-thumbs-o-up"></span>
					</span>
                        </div>
                        <figcaption class="text">
                            <h6 class="title">Reasonable prices</h6>
                            <p>Have you ever finally just  </p>
                        </figcaption>
                    </figure> <!-- icontext // -->
                </div><!-- col // -->
                <div class="col-lg-3 col-md-6">
                    <figure class="icontext">
                        <div class="icon">
					<span class="icon-sm bg-warning-light text-warning rounded">
						<i class="fa fa-plane fa-lg"></i>
					</span>
                        </div>
                        <figcaption class="text">
                            <h6 class="title">Worldwide shipping</h6>
                            <p>Have you ever finally just  </p>
                        </figcaption>
                    </figure> <!-- icontext // -->
                </div><!-- col // -->
                <div class="col-lg-3 col-md-6">
                    <figure class="icontext">
                        <div class="icon">
					<span class="icon-sm bg-warning-light text-warning rounded">
						<i class="fa fa-star fa-lg"></i>
					</span>
                        </div>
                        <figcaption class="text">
                            <h6 class="title">Best ratings</h6>
                            <p>Have you ever finally just  </p>
                        </figcaption>
                    </figure> <!-- icontext // -->
                </div> <!-- col // -->
                <div class="col-lg-3 col-md-6">
                    <figure class="icontext">
                        <div class="icon">
					<span class="icon-sm bg-warning-light text-warning rounded">
						<i class="fa fa-phone fa-lg"></i>
					</span>
                        </div>
                        <figcaption class="text">
                            <h6 class="title">Help center</h6>
                            <p>Have you ever finally just  </p>
                        </figcaption>
                    </figure> <!-- icontext // -->
                </div> <!-- col // -->
            </div> <!-- row // -->
        </article>
    </div><!-- //container -->
</section>

</body>

<style>
    .hovereffect {
        width:100%;
        height:100%;
        float:left;
        overflow:hidden;
        position:relative;
        text-align:center;
        cursor:default;
    }

    .hovereffect .overlay {
        width:100%;
        height:100%;
        position:absolute;
        overflow:hidden;
        top:0;
        left:0;
        opacity:0;
        background-color:rgba(0,0,0,0.5);
        -webkit-transition:all .4s ease-in-out;
        transition:all .4s ease-in-out
    }

    .hovereffect img {
        display:block;
        position:relative;
        -webkit-transition:all .4s linear;
        transition:all .4s linear;
    }

    .hovereffect h2 {
        text-transform:uppercase;
        color:#fff;
        text-align:center;
        position:relative;
        font-size:17px;
        background:rgba(0,0,0,0.6);
        -webkit-transform:translatey(-100px);
        -ms-transform:translatey(-100px);
        transform:translatey(-100px);
        -webkit-transition:all .2s ease-in-out;
        transition:all .2s ease-in-out;
        padding:10px;
    }

    .hovereffect a.info {
        text-decoration:none;
        display:inline-block;
        text-transform:uppercase;
        color:#fff;
        border:1px solid #fff;
        background-color:transparent;
        opacity:0;
        filter:alpha(opacity=0);
        -webkit-transition:all .2s ease-in-out;
        transition:all .2s ease-in-out;
        margin:50px 0 0;
        padding:7px 14px;
    }

    .hovereffect a.info:hover {
        box-shadow:0 0 5px #fff;
    }

    .hovereffect:hover img {
        -ms-transform:scale(1.2);
        -webkit-transform:scale(1.2);
        transform:scale(1.2);
    }

    .hovereffect:hover .overlay {
        opacity:1;
        filter:alpha(opacity=100);
    }

    .hovereffect:hover h2,.hovereffect:hover a.info {
        opacity:1;
        filter:alpha(opacity=100);
        -ms-transform:translatey(0);
        -webkit-transform:translatey(0);
        transform:translatey(0);
    }

    .hovereffect:hover a.info {
        -webkit-transition-delay:.2s;
        transition-delay:.2s;
    }
</style>
