<div class="toolBarZone">
    <span></span>

    <div class="toolBar" id="toolBar">
        <?php

        if ((new User($bdd, $_COOKIE['idUser']))->getRole()->getRole() == "admin") {
        ?>
            <a href="../form/AdminUser.php">
                <p class="toolBarIcone">
                    <i class="fa-solid fa-user"></i>
                </p>
                <p>
                    Nouveau utilisateur
                </p>
            </a>
        <?php
        }
        ?>

        <a href="../form/ajouter_produit.php">
            <p class="toolBarIcone">
                <i class="fa-solid fa-paperclip"></i>
            </p>
            <p>
                Nouveau Produit
            </p>
        </a>

        <a href="../form/user.php">
            <p class="toolBarIcone">
                <i class="fa fa-users"></i>
            </p>
            <p>
                Nouveau fournisseur
            </p>
        </a>

        <a href="../form/ajouter_vente.php">
            <p class="toolBarIcone">
                <i class="fa-solid fa-cart-shopping"></i>
            </p>
            <p>
                Nouvelle Vente
            </p>
        </a>

        <a href="../form/ajouter_dette.php">
            <p class="toolBarIcone">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </p>
            <p>
                Nouvelle Dette
            </p>
        </a>

        <a href="../extern_public/ajouter_commande.php?idBoutique=<?= $_COOKIE['idBoutique'] ?>">
            <p class="toolBarIcone">
                <i class="fa-solid fa-truck-fast"></i>
            </p>
            <p>
                Nouvelle commande
            </p>
        </a>

        <a href="../form/ajouter_proformat.php">
            <p class="toolBarIcone">
                <i class="fa-solid fa-receipt"></i>
            </p>
            <p>
                Nouveau proformat
            </p>
        </a>

    </div>

    <span class="toolBarCloser" id="toolBarCloser" data-isOpen="true" title="Cliquez pour afficher la bar d'aide">
        <i class="fa fa-arrow-up"></i>
    </span>
</div>