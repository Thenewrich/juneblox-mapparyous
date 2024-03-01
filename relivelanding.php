<?php
  include("SiT_3/reliveheader.php");
  if($loggedIn) {header("Location: /dashboard"); die();}
    ?>
<title>Relive 2019 with <?php echo $sitename; ?></title>
<div class="row">
    <div class="col-12">
        <div class="carousel-text">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Relive 2017.</h1>
                        <p><?php echo $sitename; ?> is a revival like no other. Multiple years, and free!</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="carouselIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://media.discordapp.net/attachments/1027562166452240475/1058374326266646639/079AAC1B-CA53-46C0-BF07-71664CFD6BFC.jpeg" class="c-home d-block w-100" alt="An in game screenshot">
                </div>
                <div class="carousel-item">
                    <img src="https://media.discordapp.net/attachments/1027562166452240475/1058374326577004615/1BFD535C-7E5D-48E8-8BBC-2DBEC645C301.png" class="c-home d-block w-100" alt="An in game screenshot">
                </div>
                <div class="carousel-item">
                    <img src="https://media.discordapp.net/attachments/1027562166452240475/1058375327191154708/868EB9B3-B2E3-4173-A001-EA9D9E852D11.jpeg" class="c-home d-block w-100" alt="An in game screenshot">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<div class="container container-one">
    <div class="row">
        <div class="col-12">
            <h3 class="text-center">How do I join?</h3>
            <p class="text-center">The only thing you have to do in order to join is register! Click the button below to register.</p>
            <p class="text-center">
                <a href="/auth/register" class="btn btn-primary">Register</a>
            </p>
        </div>
    </div>
</div>

<div class="container-one bg-dark text-light">
    <div class="container container-one">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-4">What makes this place different?</h3>
                <div class="row mt-4 mb-4">
                    <div class="col-2 col-md-1">
                        <h1 class="text-center">&#x1f511;</h1>
                    </div>
                    <div class="col-10 col-md-11">
                        <p><span class="fw-bolder">Invite Keys are required. They are sometimes given away by the founders of the site, however are difficult to acquire, as we intend on keeping this website private.</p>
                    </div>
                </div>

<div class="row mt-4 mb-4">
                    </div>

            </div>
        </div>
    </div>
</div>

 <div class="container container-one">
    <div class="row">
        <div class="col-12">
            <h3>Privacy Taken Seriously.</h3>
            <ul class="selling-points">
                <li>
                    <span class="fw-bolder">You don't have to provide any personal information. </span>The only thing we ask for during registration is your email address - this is only done to assist you if you forget your password.
                </li>
                <li>
                    <span class="fw-bolder">VPN use encouraged. </span>We highly recommend players use VPNs if they are able to - it helps you out a lot in the event that an exploit is discovered or our database is breached.
                </li>
            </ul>
        </div>
    </div>
</div> 
</main>
                          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
                          <?php include("SiT_3/relivefooter.php");