<?php
require __DIR__.'/inc/db-connect.inc.php';
require __DIR__.'/inc/functions.inc.php';

date_default_timezone_set('Africa/Cairo');


$perPage =4; // Number of entries per page
// $page = 1, $offset => 0
// $page = 2, $offset => $perPage
// $page = 3, $offset => $perPage * 2

$page = (int) ($_GET['page'] ?? 1);
if($page<1) $page=1;
$offset = ($page-1)*$perPage ;

$stmtCount = $pdo->prepare('SELECT COUNT(*) AS `count` FROM  `entries`');
$stmtCount->execute();
$count=$stmtCount->fetch(pdo::FETCH_ASSOC)['count'];

$numPages = ceil($count/$perPage);

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
      <?php if(!empty($result['image'])): ?>
      <div class="col-4 cardImage" >
        <img class="card__image" src="uploads/<?php echo e($result['image']); ?>" alt="">
      </div>
      <?php endif; ?>
      <div class="col-8">
        <div class="card-body">
          <?php 
          $explodeddate = explode('-', $result['date']);
          $timestamp = mktime(12,0,0,$explodeddate[1],$explodeddate[2],$explodeddate[0]);

          $formattedDate = date('d M.Y', $timestamp);
          ?>
          <h5 class="card-title"><?php echo e($formattedDate);?></h5>
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
    <?php if($page>1): ?>
    <li class="page-item">
      <a class="page-link" href="index.php?<?php echo http_build_query(['page'=>$page-1]); ?>" aria-label="Previous">
        <span aria-hidden="true">⏴</span>
      </a>
    </li>
    <?php endif; ?>
    <?php for($x=1;$x<=$numPages;$x++): ?>
    <li class="page-item ">
      <a class="page-link <?php if($x === $page): ?>active<?php endif; ?>" href="index.php?<?php echo http_build_query(['page'=>$x]); ?>">
        <?php echo e($x);?>
      </a>
    </li>
    <?php endfor?>
    <?php if($page<$numPages): ?>
    <li class="page-item">
      <a class="page-link" href="index.php?<?php echo http_build_query(['page'=>$page+1]); ?>" aria-label="Next">
        <span aria-hidden="true">⏵</span>
      </a>
    </li>
    <?php endif; ?>
  </ul>
</div>
<?php require __DIR__.'/view/footer.view.php';?>
