<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/css/form/categorie.css ">
    <title>Ajout D'Un Nouveau Type</title>
</head>
<body>
<div class="container">
  <form action="" > <h2 style="text-align: center;">Nouveau Type</h2>
    <div class="row">
      <div class="col-25">
        <label for="nom">Nom</label>
      </div>
      <div class="col-75">
        <input type="text" id="nom" name="nom" placeholder="Nom du type..." required>
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-25">
        <label for="photo">Photo</label>
      </div>
      <div class="col-75">
        <input type="file" id="photo" name="photo" placeholder="photo...">
      </div>
    </div> -->
    <!-- <div class="row">
      <div class="col-25">
        <label for="selection">selection</label>
      </div>
      <div class="col-75">
        <select id="selection" name="selection">
          <option value="01">01</option>
          <option value="02">02</option>
          <option value="03">03</option>
        </select>
      </div>
    </div> -->
    <div class="row">
      <div class="col-25">
        <label for="description">Description</label>
      </div>
      <div class="col-75">
        <textarea id="description" name="description" placeholder="Une description.." style="height:200px"></textarea>
      </div>
    </div>

    <div class="row">
      <input type="submit" value="Valider">
      <input type="reset" class="reset">  
      <input type="button" onclick="window.location.href = '../dashboard/produit.php';" value="Annuler"/>
    </div>

  </form>
</div>
</body>
</html>