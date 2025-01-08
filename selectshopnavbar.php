 
 
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
      <a class="navbar-brand fw-bolder" href="./index.php"><?php echo $branch['branch_name'] . " Branch" ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <form class="d-flex mx-auto w-50" role="search" method="get">
          <input type="hidden" value="<?= $branch_id ?>" name="branch_id">
          <!-- <input type="hidden" value="<?= $category_id ?>" name="search_category_id"> -->
          <input class="form-control me-2" name="search_data" type="text" placeholder="Search" aria-label="Search" />
          <button class="btn btn-outline-success" name="search">
            <i class="bi bi-search"></i>
          </button>
        </form>
        <div class="d-flex align-items-center">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item position-relative mt-2 me-3">
              <a class="nav-link" href="./cart.php?branch_id=<?= $_GET['branch_id'] ?>">
                <i class="bi bi-cart fs-4"></i>
                <span class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger">
                  <?= count($item_array) ?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span class="d-flex">
                  <span class="<?php if (isset($currentUser)) {
                    echo "nav-link";
                  } ?>">
                    <?php if (isset($currentUser)) {
                      echo "<span class='mt-4'>" . $currentUser['user_name'] . "</span>";
                    } else {
                      echo "<a href='./login.php' class='btn btn-sm btn-success mb-2'>Login</a>";
                    } ?>
                  </span>
                  <span>
                    <?php if (isset($currentUser)) { ?>
                      <img src="./assets/userProfile/<?= $currentUser['profile'] ?>"
                        style="width: 50px; height: 50px; border-radius: 50%;" id="profileImage" alt="Image">
                    <?php } else {
                      echo "<a href='./register.php' class='btn btn-sm btn-info mb-2'>Register</a>";
                    } ?>
                  </span>
                </span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>