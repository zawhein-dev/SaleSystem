<?php require_once("./storage/db.php"); ?>

<?php require_once("./storage/user_crud.php");
require_once("./storage/branch_crud.php");
require_once("./storage/category_crud.php");
require_once("./storage/branch_product_crud.php");
require_once("./server_cart.php");

if (isset($_COOKIE['user'])) {
    // Decode the JSON string into a PHP associative array
    $userData = json_decode($_COOKIE['user'], associative: true);

    // Check if the 'username' key exists and display it
    if (isset($userData['user_id'])) {
        $user_id = $userData['user_id'];
        $currentUser = get_user_with_id($mysqli, $user_id);
    }
}
if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];
    $branch = get_branch_with_id($mysqli, $branch_id);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>System</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!--  -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      /* Card Hover Effects */
      .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
      }

      .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      }

      /* Add shake effect on hover */
      .card:hover {
        animation: shake 0.5s ease-in-out;
      }

      /* Shake animation */
      @keyframes shake {
        0% {
          transform: translateX(0);
        }
        25% {
          transform: translateX(-5px);
        }
        50% {
          transform: translateX(5px);
        }
        75% {
          transform: translateX(-5px);
        }
        100% {
          transform: translateX(0);
        }
      }

      /* Icon container that appears on hover */
      .card .icon-container {
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        flex-direction: column;
        align-items: center;
      }

      .card:hover .icon-container {
        display: flex;
        opacity: 1;
      }

      .icon-container .icon {
        font-size: 1.6rem;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 50%;
        padding: 10px;
        margin: 5px;
        cursor: pointer;
        transition: transform 0.3s ease, background-color 0.3s ease;
      }
      .icon-container a {
        color: #fff;
        margin-top: 5px;
        cursor: pointer;
        transition: transform 0.3s ease, background-color 0.3s ease;
      }
      .icon-container .icon:hover {
        transform: scale(1.2);
        background-color: #96b7da; 
      }

      .card-body {
        padding: 20px;
      }
      .btn-primary {
        transition: background-color 0.3s ease, transform 0.3s ease;
      }

      .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
      }

      .sidebar {
        min-height: 100vh;
        background-color: rgb(252, 241, 241);
        padding: 20px;
      }

      .sidebar a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
      }

      .sidebar a:hover {
        color: #007bff;
      }
    </style>
  </head>
  <body style="background-color: white">
    <nav class="navbar navbar-expand-lg bg-light">
      <div class="container">
        <a class="navbar-brand fw-bolder" href="#"><?php echo $branch['branch_name'] . " Branch" ?></a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
          aria-controls="navbarContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <form class="d-flex mx-auto w-50" role="search">
            <input
              class="form-control me-2"
              name="search"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-outline-success" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </form>
          <div class="d-flex align-items-center">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <i class="bi bi-person-circle fs-4"></i>
                </a>
              </li>
              <li class="nav-item position-relative">
                <a class="nav-link" href="#">
                  <i class="bi bi-cart fs-4"></i>
                  <span
                    class="position-absolute top-2 start-100 translate-middle badge rounded-pill bg-danger"
                  >
                  <?= count($item_array) ?>
                  </span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <div class="container-fluid d-flex flex-column flex-lg-row">
    <!-- Left Sidebar: Category list -->
    <div class="col-12 col-lg-3 p-3 sidebar">
        <h5>Categories</h5>
        <ul class="list-group">
            <?php 
            $categories = get_category($mysqli);
            while ($category = $categories->fetch_assoc()) { ?>
                <li class="list-group-item my-3 rounded p-3 text-center border-0">
                    <a href="?category_id=<?= htmlspecialchars($category['category_id']) ?>&branch_id=<?= htmlspecialchars($branch_id) ?>" 
                       class="text-decoration-none text-black">
                        <?= htmlspecialchars($category['category_name']) ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Right Side: Product cards -->
    <div class="col-12 col-lg-9 p-3">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            $product_in_branch = get_product_in_branch($mysqli, $branch_id);
            if (isset($_GET['category_id'])) {
                $product_in_branch = get_branch_product_with_category_id($mysqli, $_GET['category_id'], $_GET['branch_id']);
            }
            while ($product = $product_in_branch->fetch_assoc()) { ?>
                <div class="col">
                    <div class="card">
                        <div class="position-relative">
                            <img src="./assets/product/<?= htmlspecialchars($product['photo']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($product['product_name']) ?>" />
                            <div class="icon-container">
                                <a href="#" class="mb-4 mt-2">
                                    <i class="bi bi-eye icon fs-6" title="View Details"></i>
                                </a>
                                <a href="#">
                                    <i class="bi bi-cart-plus icon fs-6" title="Add to Cart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="card-title"><?= htmlspecialchars($product['product_name']) ?></span>
                            <div class="d-flex justify-content-between">
                                <span class="card-text">
                                    <?= $product['qty'] == 0 ? "Out of Stock" : "In stock: " . htmlspecialchars($product['qty']) ?>
                                </span>
                                <span class="card-text"><?= htmlspecialchars($product['price']) ?> MMK</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasScrolling"
        aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Content dynamically loaded here -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Check if the URL contains the 'show_offcanvas=true' parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('show_offcanvas')) {
                // Show the offcanvas
                const offcanvasElement = document.querySelector('#offcanvasScrolling');
                if (offcanvasElement) {
                    const bootstrapOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                    bootstrapOffcanvas.show();

                    // Optionally fetch and update the offcanvas content dynamically
                    const offcanvasBody = document.querySelector('#offcanvasScrolling .offcanvas-body');
                    fetch('get_cart_content.php')
                        .then(response => response.text())
                        .then(html => {
                            offcanvasBody.innerHTML = html;
                        })
                        .catch(error => console.error('Error fetching cart content:', error));

                    // Remove the 'show_offcanvas' parameter from the URL to prevent reopening on refresh
                    urlParams.delete('show_offcanvas');
                    window.history.replaceState({}, document.title, window.location.pathname + '?' + urlParams.toString());

                    // Hide the offcanvas after 15 seconds
                    setTimeout(() => {
                        bootstrapOffcanvas.hide();
                    }, 15000);
                }
            }
        });
    </script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
     <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.min.js"></script>
  </body>
</html>