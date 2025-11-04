<?php
require __DIR__.'/inc/db-connect.inc.php';
require __DIR__.'/inc/functions.inc.php';

$stmt = $pdo->prepare('SELECT * FROM `entries`');
$stmt-> execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Handle form submission

if(!empty($_POST)) {
// Retrieve and sanitize form inputs
  $title = (string) $_POST['title'] ?? '';
  $date = (string) $_POST['date'] ?? '';
  $message = (string) $_POST['message'] ?? '';
  $imageName=null;
// handle image upload
    if (!empty($_FILES) && !empty($_FILES['image'])) {
        if ($_FILES['image']['error'] === 0 && $_FILES['image']['size'] !== 0) {
            $nameWithoutExtension = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $name = preg_replace('/[^a-zA-Z0-9]/', '', $nameWithoutExtension);
    
            $originalImage = $_FILES['image']['tmp_name'];
            $imageName = $name . '-' . time() . '.jpg';
            $destImage = __DIR__ . '/uploads/' . $imageName;

            $imageSize = getimagesize($originalImage);
            // handle error if image size is false or not valid image like (file.php)
            if(!empty($imageSize)) {  
    
              [$width, $height] = $imageSize;
              
              $maxDim = 400;

              $scaleFactor = $maxDim / max($width, $height);
              $newWidth = $width * $scaleFactor;
              $newHeight = $height * $scaleFactor;
              
              $type = mime_content_type($originalImage);


              switch ($type) {
                  case 'image/jpeg':
                      $im = @imagecreatefromjpeg($originalImage);
                      break;
                  case 'image/png':
                      $im = @imagecreatefrompng($originalImage);
                      break;
                  case 'image/gif':
                      $im = @imagecreatefromgif($originalImage);
                      break;
                  default:
                      die('Unsupported image type' . '<a href="index.php">Go to Entries</a>');
              }

              // handle error if image extension is not supported or image is corrupted
              if(!empty($im)) {
                  $newImg = imagecreatetruecolor($newWidth, $newHeight);
                  imagecopyresampled($newImg, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                  imagejpeg($newImg, $destImage);
                  var_dump($imageName);
              }
            }
        }
    }

// Insert into database
  $stmt = $pdo->prepare('INSERT INTO `entries`(`title`,`date`,`message`,`image`) VALUES
  (:title,:date,:message,:image)');

  $stmt->bindValue(':title',$title);
  $stmt->bindValue(':date',$date);
  $stmt->bindValue(':message',$message);
  $stmt->bindValue(':image',$imageName);
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

