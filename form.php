<?php
require __DIR__.'/inc/db-connect.inc.php';
require __DIR__.'/inc/functions.inc.php';

$stmt = $pdo->prepare('SELECT * FROM `entries`');
$stmt-> execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


if(!empty($_POST)) {
  $title = (string) $_POST['title'] ?? '';
  $date = (string) $_POST['date'] ?? '';
  $message = (string) $_POST['message'] ?? '';

  $stmt = $pdo->prepare('INSERT INTO `entries`(`title`,`date`,`message`) VALUES
  (:title,:date,:message)');

  $stmt->bindValue(':title',$title);
  $stmt->bindValue(':date',$date);
  $stmt->bindValue(':message',$message);
  $stmt->execute();

  echo '<a href="index.php">Go to Entries</a>';
  die();

}

?>
<?php require __DIR__.'/view/header.view.php';?>


<h1>New Entry</h1>
<form
  action="form.php"
  method="POST"
  enctype="multipart/form-data"
  class="entryForm"
>
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input
      type="text"
      class="form-control"
      id="title"
      name="title"
      required
    />
  </div>
  <div class="mb-3">
    <label for="date" class="form-label">Date</label>
    <input
      class="form-control"
      type="date"
      id="date"
      name="date"
    />
  </div>
  <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input
      class="form-control"
      type="file"
      id="image"
      name="image"
      accept="image/*"
    />
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Message</label>
    <textarea
      class="form-control"
      id="message"
      name="message"
      rows="5"
      required
    ></textarea>
  </div>
  <button type="submit" class="btnContainer" >
      <figure>
          <img src="images/icons/send.svg" alt="save icon" />
      </figure>
      <p>Save</p>
  </button>
</form>
<?php require __DIR__.'/view/footer.view.php';?>

