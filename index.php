<?php
require __DIR__.'/inc/db-connect.inc.php';
require __DIR__.'/inc/functions.inc.php';


$perPage =2; // Number of entries per page
// $page = 1, $offset => 0
// $page = 2, $offset => $perPage
// $page = 3, $offset => $perPage * 2

$page = (int) ($_GET['page'] ?? 1);
$offset = ($page-1)*$perPage ;


$stmt = $pdo->prepare('SELECT * FROM `entries` ORDER BY `date` DESC,`id` ASC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit',$perPage,PDO::PARAM_INT);
$stmt->bindValue(':offset',$offset,PDO::PARAM_INT);
$stmt-> execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require __DIR__.'/view/header.view.php';?>

<h1>Entries</h1>
<div class="cardsContainer mb-4">
  <?php foreach($results as $result): ?>
  <div class="card mb-4" >
    <div class="row g-0 ">
      <div class="col-4 cardImage" >
        <img class="card__image" src="images/pexels-canva-studio-3153199.jpg" alt="">
      </div>
      <div class="col-8">
        <div class="card-body">
          <h5 class="card-title"><?php echo e($result['date']);?></h5>
          <h2 class="card-title"><?php echo e($result['title']);?></h2>
          <hr />
          <p class="card-text">
            <?php echo e($result['message']);?>
          </p>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<div aria-label="Page navigation example">
  <ul class="pagination ">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">⏴</span>
      </a>
    </li>
    <li class="page-item"><a class="page-link " href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">⏵</span>
      </a>
    </li>
  </ul>
</div>
<?php require __DIR__.'/view/footer.view.php';?>
