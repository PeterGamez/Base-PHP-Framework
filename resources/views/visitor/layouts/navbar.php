<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>">
            <img src="<?= config('site.logo.128') ?>" alt="<?= config('site.name') ?> icon" class="rounded" width="40px" height="40px">
            <span class="ps-2">
                <?= config('site.name') ?>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbartoggler" aria-controls="navbartoggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbartoggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/') ?>">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0 justify-content-end">
                <?php
                if ($_SESSION['login'] == true) :
                ?>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $_SESSION['account']['avatar'] ?>" alt=" <?= $_SESSION['account']['username']; ?>" class="rounded" width="30px" height="30px">
                            <?= $_SESSION['account']['username']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">หลังบ้าน</a>
                            <a class="dropdown-item" href="#">ออกจากระบบ</a>
                        </div>
                    </li>
                <?php
                else :
                ?>
                    <li class="nav-item d-flex justify-content-right">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                <?php
                endif;
                ?>
            </ul>
        </div>
    </div>
</nav>