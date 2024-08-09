<div class="sidebar">
    <?php
    require_once("../../script/connexion_bd.php");
    require_once("../../class/Boutique.php");
    require_once("../../class/User.php");
    $boutique_name = "Easy Managment";
    if (isset($_COOKIE['idBoutique'])) {
        $boutique_nav = new Boutique($bdd, $_COOKIE['idBoutique']);
        $boutique_name = ($boutique_nav->getExiste()) ? $boutique_nav->getNomBoutique() : "Easy Managment";
    }
    ?>
    <div class="logo-details">
        <!-- <i class='bx bxl-c-plus-plus'></i> -->
        <span class="logo_name"> <?php echo $boutique_name ?> </span>
    </div>


    <ul class="nav-links">
        <li>
            <a href="boutique.php">
                <i class="fa-solid fa-shop"></i>
                <span class="link_name">Boutique</span>
            </a>
            <ul class="sub-menu blank">
                <li><a class="link_name" href="boutique.php">Boutique</a></li>
            </ul>
        </li>

        <?php
        if (isset($_COOKIE['idBoutique'])) {
        ?>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-paperclip"></i>
                        <span class="link_name">Gestion Produit</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Gestion Produit</a></li>
                    <li><a href="infoProduit.php">Infos produit</a></li>
                    <li><a href="produit.php">Liste produit</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="link_name">Gestion Vente</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Gestion Vente</a></li>
                    <li><a href="vente.php">Vente Direct</a></li>
                    <li><a href="dette.php">Dette</a></li>
                    <li><a href="commande.php">Commande</a></li>
                    <li><a href="ProFormat.php">Proformat</a></li>
                </ul>
            </li>

            <li>
                <a href="depenses.php">
                    <i class="fa-solid fa-circle-dollar-to-slot"></i>
                    <span class="link_name">Depenses</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="depenses.php">Depenses</a></li>
                </ul>
            </li>

            <li>
                <a href="statistique.php">
                    <i class="fa fa-chart-line"></i>
                    <span class="link_name">Statistiques</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="statistique.php">Statistiques</a></li>
                </ul>
            </li>


            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-warehouse"></i>
                        <span class="link_name">Gestion Entrepot</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Gestion Entrepot</a></li>
                    <li><a href="entrepot.php">Produit Entrepot</a></li>
                    <li><a href="achat.php">Commande Entrepot</a></li>
                    <li><a href="sousTraitance.php">sousTraitance</a></li>
                </ul>
            </li>

            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span class="link_name">Gestion Monnetaire</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Gestion Monnetaire</a></li>
                    <li><a href="caisse.php">Caisse</a></li>
                    <li><a href="banque.php">Banque</a></li>
                </ul>
            </li>

            <li>
                <a href="client.php">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="link_name">Client</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="client.php">Client</a></li>
                </ul>
            </li>

            <li>
                <a href="user.php">
                    <i class="fa-solid fa-user"></i>
                    <span class="link_name">Fournisseurs</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="user.php">Fournisseurs</a></li>
                </ul>
            </li>

        <?php
        }
        if ((new User($bdd, $_COOKIE['idUser']))->getRole()->getRole() == "admin") {
        ?>

            <li>
                <a href="AdminUser.php">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="link_name">Utilisateurs</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="AdminUser.php">Utilisateurs</a></li>
                </ul>
            </li>

            <li>
                <a href="site_autorise.php">
                    <i class="fa fa-globe"></i>
                    <span class="link_name">Site Autoriser</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="site_autorise.php">Site Autoriser</a></li>
                </ul>
            </li>

        <?php
        }
        ?>


        <li>
            <div class="profile-details">

                <div class="name-job">
                    <div class="profile_name">Deconnexion</div>
                </div>
                <a href="../../index.php?deconnexion=1"><i class='bx bx-log-out'></i></a>
            </div>
        </li>
    </ul>
</div>
