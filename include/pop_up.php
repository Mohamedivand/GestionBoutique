<?php
        if(isset($_GET['popUpText'])){
                if($_GET['popUpText']==1){
                        $popUpText="Ok";
                }
                elseif($_GET['popUpText']==2){
                        $popUpText="Non";
                }
                elseif($_GET['popUpText']==3){
                        $popUpText="utilisateur non connecter ou boutique non selecetionner";
                }
                elseif($_GET['popUpText']==4){
                        $popUpText="Cette boutique n'existe pas";
                }
                elseif($_GET['popUpText']==5){
                        $popUpText="vous n'avez pas le droit";
                }
                elseif($_GET['popUpText']==6){
                        $popUpText="Manque d'information";
                }
                elseif($_GET['popUpText']==7){
                        $popUpText="Une erreur est survenue";
                }
                elseif($_GET['popUpText']==8){
                        $popUpText="Produit ajouter avec success";
                }
                elseif($_GET['popUpText']==9){
                        $popUpText="Erreur lors de l'ajoue. Veuillez reessayer";
                }
                elseif($_GET['popUpText']==10){
                        $popUpText="Produit introuvable";
                }
                elseif($_GET['popUpText']==11){
                        $popUpText="Produit modifier avec success";
                }
                elseif($_GET['popUpText']==12){
                        $popUpText="Marque ajouter avec success";
                }
                elseif($_GET['popUpText']==13){
                        $popUpText="Non ajouter";
                }
                elseif($_GET['popUpText']==14){
                        $popUpText="Type de fichier non supporter";
                }
                elseif($_GET['popUpText']==15){
                        $popUpText="modifier avec success";
                }
                elseif($_GET['popUpText']==16){
                        $popUpText="non modifier ";
                }
                elseif($_GET['popUpText']==17){
                        $popUpText="Cette marque n'existe pas";
                }
                elseif($_GET['popUpText']==18){
                        $popUpText="Cette marque existe deja";
                }
                elseif($_GET['popUpText']==19){
                        $popUpText="L'extension du tamponn est incorrecte";
                }
                elseif($_GET['popUpText']==20){
                        $popUpText="Envoyez au moin une images";
                }
                
?>
                <h1 class="pop_up"><?php echo($popUpText); ?></h1>
<?php
        }
?>