<?php $tache = $view['datas']['item']; ?>

<h1>Mes choses à faire</h1>
<p><a href="index.php?route=deconnexion">Me déconnecter</a></p>
<h2>Modifier la tache <?= $_GET['idTache'] ?></h2>
<form action="index.php?route=modify_tache&idTache=<?= $_GET['idTache'] ?>" method="POST">
    <div>
        <input type="text" name="description" placeholder="Entrez votre chose à faire">
    </div>
    <div>
        <label for="date_limite">Deadline</label>
        <input type="date" name="date_limite" id="date_limite">
    </div>
    <div>
        <input type="submit" value="Modifier">
    </div>
</form>