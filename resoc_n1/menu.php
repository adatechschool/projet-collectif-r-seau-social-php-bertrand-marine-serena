<nav id="menu">
    <a href="news.php">Actualités</a>
    <a href="wall.php?user_id=<?php $_SESSION ?>">Mur</a>
    <a href="feed.php?user_id=$_SESSION">Flux</a>
    <a href="tags.php?tag_id=$_SESSION">Mots-clés</a>
</nav>
<nav id="user">
    <a href="#">Profil</a>
        <ul>
            <li><a href="settings.php?user_id=$_SESSION">Paramètres</a></li>
            <li><a href="followers.php?user_id=$_SESSION">Mes suiveurs</a></li>
            <li><a href="subscriptions.php?user_id=$_SESSION">Mes abonnements</a></li>
        </ul>
</nav>