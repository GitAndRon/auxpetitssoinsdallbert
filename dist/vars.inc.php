
<?php
error_reporting(0);
ini_set('default_charset', 'iso-8859-1');
$villes=array("Bowman","Chénéville","Duhamel","Fasset","Lac des Plages","Lac Simon","Montebello","Montpellier","Namur","Notre-Dame de la Paix",
"Papineauville","Plaisance","Ripon","St-André Avellin","St-Émile de Suffolk","Thurso");

$accueil = array();
$accueil["bienvenue"] = "Bienvenue";
$accueil["nom"] = "Aux petits soins d'AllBert'";
$accueil["base"] = "allbert";

$actionsGenerales= array();
$actionsGenerales["admin"]=array();
$actionsGenerales["commandes"]=array("Ajouter","Rechercher");
$actionsGenerales["adresses"]=array("Ajouter","Rechercher");
$actionsGenerales["depenses"]=array("Ajouter","Rechercher");
$actionsGenerales["deplacements"]=array("Ajouter","Rechercher");
$actionsGenerales["factures"]=array("Rechercher");
$actionsGenerales["medecins"]=array("Ajouter","Rechercher");
$actionsGenerales["notes_vaccins"]=array("Rechercher");
$actionsGenerales["pages"]=array("Ajouter","Rechercher");
$actionsGenerales["pressions"]=array("Rechercher");
$actionsGenerales["prescriptions"]=array("Rechercher");
$actionsGenerales["soins"]=array("Ajouter","Rechercher");
$actionsGenerales["frais"]=array("Ajouter","Rechercher");
//$actionsGenerales["ventes"]=array();
$actionsGenerales["visites"]=array("Rechercher");

$actionsSurEnr= array();
$actionsSurEnr["admin"]=array("Modifier"=>"admin");
$actionsSurEnr["adresses"]=array("Modifier"=>"adresses","+ visite"=>"visites");
$actionsSurEnr["commandes"]=array("Modifier"=>"commandes", "Imprimer commande"=>"commandes","Annuler"=>"commandes");
$actionsSurEnr["depenses"]=array("Modifier"=>"depenses","Annuler"=>"depenses");
$actionsSurEnr["deplacements"]=array("Modifier"=>"deplacements","Annuler"=>"deplacements");
$actionsSurEnr["factures"]=array("Imprimer facture"=>"visites","Imprimer recu"=>"visites","Annuler"=>"factures");
$actionsSurEnr["medecins"]=array("Modifier"=>"medecins");
$actionsSurEnr["notes_vaccins"]=array("Modifier"=>"notes_vaccins","Annuler"=>"notes_vaccins");
$actionsSurEnr["pages"]=array("Modifier"=>"pages");
$actionsSurEnr["pressions"]=array("Modifier"=>"pressions","Annuler"=>"pressions");
$actionsSurEnr["prescriptions"]=array("Modifier"=>"prescriptions","Imprimer prescription"=>"prescriptions");
$actionsSurEnr["soins"]=array("Modifier"=>"soins");
$actionsSurEnr["frais"]=array("Modifier"=>"frais");
//$actionsSurEnr["ventes"]=array("Annuler"=>"ventes");
$actionsSurEnr["visites"]=array("Annuler"=>"visites");

$annulable=array("deplacements","visites","factures");

$reactions= array();
$reactions["adresses"]["categorie"]=array("champ" => "iu_nom", "actionSurChamp"=>"inactif");
$reactions["adresses"]["ville"]=array("champ" => "iu_cp");
//$reactions["factures"]["frais_deplacement"]=array("champ" => "total");
$reactions["visites"]["soins_id"]=array("champ" => "prix");
//$reactions["ventes"]["produits_id"]=array("champ" => "prix");
$reactions["visites"]["qte"]=array("champ" => "prix", "prix"=>"");
$reactions["medecins"]["ville"]=array("champ" => "iu_cp");



//sert à déterminer si le caractère sera en gras
$actifOuNon = array("non","oui");

$affichage =array();
$affichage["index.php"]=array();
$affichage["index.php"]["erreur utilisateur"]="<div id=\"main-wrapper\">
        <div id=\"main\" class=\"clearfix\">
			<div id=\"content-wrapper\">
			<div class=\"messages error\">
			 <ul>
			  <li>Le nom d'utilisateur ou le mot de passe est invalide.</li>
			  <li>Impossible de se connecter.</li>
			 </ul>

			</div>
				<div id=\"content\">
				<div id=\"content-inner\">
                                <h1 class=\"title\">Compte administrateur</h1>
                                <div id=\"content-content\">
										<form action=\"admin.php\"   method=\"get\" id=\"a_validere\">
											<div>
												<div class=\"form-item\" id=\"edit-name-wrapper\">
													<label for=\"edit-name\">Nom d'utilisateur : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													<input type=\"text\" maxlength=\"20\" name=\"qui\" id=\"edit-name\" size=\"20\" value=\"\" class=\"form-text required\" />
													<div class=\"description\">Saisissez votre nom d'utilisateur pour Aux petits soins d'AllBert.</div>
												</div>
												<div class=\"form-item\" id=\"edit-pass-wrapper\">
													 <label for=\"edit-pass\">Mot de passe : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													 <input type=\"password\" name=\"passe\" id=\"edit-pass\"  maxlength=\"20\"  size=\"20\"  class=\"form-text required\" />
													 <div class=\"description\">Saisissez le mot de passe correspondant à votre nom d'utilisateur.</div>
												</div>
												<input type=\"submit\" name=\"op\" id=\"edit-submit\" value=\"Se connecter\"  class=\"form-submit\" />
												<input type=\"hidden\" name = \"table\" value = \"adresses\" />
												<input type=\"hidden\" name = \"action\" value = \"Rechercher\" />
											</div>
										</form>
								</div><!-- /content-content -->
              </div><!-- /content-inner -->
            </div><!-- /content -->

		</div><!-- /main -->
      </div><!-- /main-wrapper -->";
 $affichage["index.php"]["premier acces"] ="<div id=\"main-wrapper\">
        <div id=\"main\" class=\"clearfix\">
			<div id=\"content-wrapper\">
			
				<div id=\"content\">
				<div id=\"content-inner\">
                                <h1 class=\"title\">Compte administrateur</h1>
                                <div id=\"content-content\">
										<form action=\"admin.php\"   method=\"get\" id=\"a_valider\">
											<div>
												<div class=\"form-item\" id=\"edit-name-wrapper\">
													<label for=\"edit-name\">Nom d'utilisateur : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													<input type=\"text\" maxlength=\"20\" name=\"qui\" id=\"edit-name\" size=\"20\" value=\"\" class=\"form-text required\"  />
													<div class=\"description\">Saisissez votre nom d'utilisateur pour Aux petits soins d'AllBert.</div>
												</div>
												<div class=\"form-item\" id=\"edit-pass-wrapper\">
													 <label for=\"edit-pass\">Mot de passe : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													 <input type=\"password\" name=\"passe\" id=\"edit-pass\"  maxlength=\"20\"  size=\"20\"  class=\"form-text required\"  />
													 <div class=\"description\">Saisissez le mot de passe correspondant à votre nom d'utilisateur.</div>
												</div>
												<input type=\"submit\" name=\"soumettre\" id=\"edit-submit\" value=\"Se connecter\"  class=\"form-submit\" />
												<input type=\"hidden\" name = \"table\" value = \"adresses\" />
												<input type=\"hidden\" name = \"action\" value = \"Rechercher\" />
											</div>
										</form>
								</div><!-- /content-content -->
              </div><!-- /content-inner -->
            </div><!-- /content -->

		</div><!-- /main -->
      </div><!-- /main-wrapper -->";

//champ affichà à la place d'un autre
// ex.: pour un lien
$affiche["documents"]["fichier"]="description";
$affiche["liens"]["url"]="description";
$affiche["c_documents"]["fichier"]="description";
$affiche["c_liens"]["url"]="description";

$asc_desc_post= Array();
$asc_desc_post[0]= "ASC";
$asc_desc_post["ASC"]= "DESC";
$asc_desc_post["DESC"]= "ASC";

$pourVoir = "chacun";
$ppourVoir = "c6g4jzh9tZ";

//champs dont la valeur à transmettre est identifié par un autre champ
// ex.adresses_id identifié par Concat(adresses.nom_resp,' ', adresses.prenom_resp,' ',adresses.adresse,' ',adresses.ville)
//champ_association doit être "as association"
$champs_associes = array();
//$champs_associes["ventes"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\"),\"<br/>\",IFNULL(adresses.distance,0)) as association");
$champs_associes["visites"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\"),\"<br/>\",IFNULL(adresses.distance,0)) as association");
$champs_associes["factures"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\"),\"<br/>\",IFNULL(adresses.distance,0)) as association");
$champs_associes["pressions"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\")) as association");
$champs_associes["prescriptions"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\")) as association");
$champs_associes["notes_vaccins"]["adresses_id"]=array("table"=>"adresses","champ_val"=>"id","champ_association"=>"CONCAT(IFNULL(nom_resp,\"\"),\" \",IFNULL(prenom_resp,\"\"),\"<br/>\",IFNULL(adresses.adresse,\"\"),\"<br/>\",IFNULL(adresses.ville,\"\")) as association");


//champs non éditables dans ajouter 
//les champs id et auteur sont inéditables par défaut
$champs_ineditables = array();
$champs_ineditables["admin"]= array();
$champs_ineditables["adresses"]= array();
$champs_ineditables["commandes"]= array();
$champs_ineditables["depenses"]= array("nul");
$champs_ineditables["deplacements"]= array("nul");
$champs_ineditables["factures"]= array("adresses_id","client_id","nul");
$champs_ineditables["medecins"]= array();
$champs_ineditables["notes_vaccins"]= array("visites_id","adresses_id","la_date");
$champs_ineditables["pages"]= array();
$champs_ineditables["pressions"]= array("visites_id","adresses_id","la_date");
$champs_ineditables["prescriptions"]= array("adresses_id");
$champs_ineditables["soins"]= array();
$champs_ineditables["frais"]= array();
$champs_ineditables["visites"]= array("adresses_id","facture_id","nul");


//champs pour vérifier les doublons
$champs_doublons = array();
$champs_doublons["admin"]= array();
$champs_doublons["adresses"]= array("champs"=>array("id","categorie","nom","nom_resp","prenom_resp"),"categorie");
$champs_doublons["commandes"]= array();
$champs_doublons["depenses"]= array();
$champs_doublons["deplacements"]= array();
$champs_doublons["factures"]= array();
$champs_doublons["medecins"]= array();
$champs_doublons["notes_vaccins"]= array();
$champs_doublons["pages"]= array();
$champs_doublons["pressions"]= array();
$champs_doublons["prescriptions"]= array();
$champs_doublons["soins"]= array();
$champs_doublons["frais"]= array();
$champs_doublons["visites"]= array();


//mots affichàs à la place des noms de champ
$champs_visibles_affiches = array();
$champs_visibles_affiches["cedule"]= array("la_date" => "date","endroit" => "arena", "debut" => "début","h_fin" => "fin", "categorie" => "categorie", "categorie2" => "categorie2","partie_pratique" => "activité","no_partie" => "no","visiteur"=>"visiteur","vis"=>"pt v","receveur" => "receveur","loc" =>"pt r");

//champs visibles dans admin.php
//si pas défini tous les champs affichés
$champs_visibles_admin = array();
$champs_visibles_admin["adresses"]= array("adresse","ville","cp","tel","cell");
$champs_visibles_admin["commandes"]= array("la_date","produit","forme","qte","auteur");
$champs_visibles_admin["depenses"]= array("la_date","montant","raison","compte","auteur");
$champs_visibles_admin["deplacements"]= array("la_date","endroit","raison","km","auteur");
$champs_visibles_admin["notes_vaccins"]= array("la_date","visites.id","adresses_id","auteur","nom_vaccin","no_lot","la_date_exp","dose");
$champs_visibles_admin["pages"]= array("titre","rang","actif");
$champs_visibles_admin["pressions"]= array("la_date","visites.id","adresses_id","auteur","bras_gauche_haut","bras_gauche_bas","bras_droit_haut","bras_droit_bas","note");
$champs_visibles_admin["prescriptions"]= array("la_date","adresses_id","auteur","produit","forme");
$champs_visibles_admin["soins"]= array("description","prix");
$champs_visibles_admin["frais"]= array("description_frais","prix_frais");
//$champs_visibles_admin["ventes"]= array("ventes.id","facture_id","la_date","auteur","adresses_id","client","produits_id","produits.nom");
$champs_visibles_admin["visites"]= array("visites.id","facture_id","la_date","auteur","adresses_id","client","soins_id","type","soins.nom");

//champs spécifiques dans admin.php pour afficher comme titre

$champs_titres = array();
$champs_titres["admin"]= array("nom",array());
$champs_titres["adresses"]= array("categorie",array("nom","nom_resp","prenom_resp"));
$champs_titres["commandes"]= array("produit",array("la_date","forme"));
$champs_titres["depenses"]= array("raison",array("la_date","raison"));
$champs_titres["deplacements"]= array("endroit",array("la_date", "auteur"));
$champs_titres["factures"]= array("id",array("id","la_date","auteur"));
$champs_titres["medecins"]= array("",array("nom","prenom"));
$champs_titres["notes_vaccins"]= array("la_date",array("id"));
$champs_titres["pages"]= array("titre",array("rang"));
$champs_titres["pressions"]= array("la_date",array("id"));
$champs_titres["prescriptions"]= array("la_date",array("id"));
$champs_titres["soins"]= array("nom",array("id"));
$champs_titres["frais"]= array("nom",array("id"));
$champs_titres["visites"]= array("nom",array("id"));

//champs spécifiques dans admin.php pour afficher 

$champs_liste = array();
$champs_liste["admin"]= array("champs"=>array("id","nom","taux_frais_deplacement","tel","fax","neq"),"req"=>"");
$champs_liste["adresses"]= array("champs"=>array("adresses.id","naissance","categorie","nom","nom_resp","prenom_resp","ville"),"req"=>"");
$champs_liste["commandes"]= array("champs"=>array("commandes.id","la_date","fournisseur_id","CONCAT(adresses.nom,\" - \",adresses.ville) as fournisseur","produit","forme","qte","auteur","commandes.nul"),"req"=>" FROM commandes, adresses WHERE adresses.id = fournisseur_id  ");
$champs_liste["depenses"]= array("champs"=>array("depenses.id","depenses.la_date","raison","CONCAT(adresses.id,\"@\",IFNULL(adresses.prenom_resp,\" \"),\" \",IFNULL(adresses.nom_resp,\" \")) as client","compte","montant","depenses.auteur","depenses.nul"),"req"=>" FROM depenses LEFT JOIN visites ON depenses.visite_id=visites.id LEFT JOIN adresses ON visites.adresses_id=adresses.id ");
//$champs_liste["depenses"]= array("champs"=>array("id","la_date","raison","compte","auteur","montant"),"req"=>"");
$champs_liste["deplacements"]= array("champs"=>array("id","la_date","endroit","raison","km","auteur","nul"),"req"=>"");
$champs_liste["factures"]= array("champs"=>array("factures.id","la_date","adresses_id","CONCAT(adresses.id,\"@\",IFNULL(adresses.nom_resp,\" \"),\" \",IFNULL(adresses.prenom_resp,\" \")) as client","total","auteur","nul"),"req"=>" FROM factures,adresses WHERE adresses.id = factures.adresses_id  ORDER BY la_date DESC");
$champs_liste["pages"]= array("champs"=>array("pages.id","titre","rang", "actif"),"req"=>"");
$champs_liste["pressions"]= array("champs"=>array("pressions.id","visites_id","la_date","adresses_id","CONCAT(adresses.id,\"@\",adresses.prenom_resp,\" \",adresses.nom_resp) as client","CONCAT(bras_gauche_haut,\" / \",bras_gauche_bas) as bras_gauche","CONCAT(bras_droit_haut,\" / \",bras_droit_bas) as bras_droit","note","auteur","nul"),"req"=>" FROM pressions, adresses WHERE adresses.id=pressions.adresses_id ");
$champs_liste["prescriptions"]= array("champs"=>array("prescriptions.id","la_date","adresses_id","CONCAT(adresses.id,\"@\",adresses.prenom_resp,\" \",adresses.nom_resp) as client","qte","produit","forme","auteur"),"req"=>" FROM prescriptions, adresses WHERE adresses.id=prescriptions.adresses_id ");
$champs_liste["soins"]= array("champs"=>array("soins.id","type","nom","description","CONCAT (frais.id,\" - \",nom_frais) as frais","prix"),"req"=>" FROM soins LEFT JOIN  frais ON soins.frais_id=frais.id ");
$champs_liste["frais"]= array("champs"=>array("id","type_frais","nom_frais","description_frais","prix_frais"),"req"=>"");
$champs_liste["medecins"]= array("champs"=>array("medecins.id","nom","prenom","ville","tel"),"req"=>"");
$champs_liste["notes_vaccins"]= array("champs"=>array("notes_vaccins.id","visites_id","la_date","adresses_id","CONCAT(adresses.id,\"@\",adresses.prenom_resp,\" \",adresses.nom_resp) as client","nom_vaccin","no_lot","la_date_exp","dose","auteur","nul"),"req"=>" FROM notes_vaccins, adresses WHERE adresses.id=notes_vaccins.adresses_id ");
//$champs_liste["ventes"]= array("champs"=>array("ventes.id","facture_id","la_date","auteur","adresses_id","CONCAT(adresses.id,\"@\",adresses.prenom_resp,\" \",adresses.nom_resp) as client","produits_id","produits.nom","nul"),"req"=>" FROM ventes,adresses,produits WHERE adresses.id = ventes.adresses_id AND produits.id = ventes.produits_id ORDER BY ventes.la_date DESC");
$champs_liste["visites"]= array("champs"=>array("visites.id","facture_id","la_date","adresses_id","CONCAT(adresses.id,\"@\",IFNULL(adresses.prenom_resp,\" \"),\" \",IFNULL(adresses.nom_resp,\" \")) as client","soins_id","type","soins.nom","auteur","nul"),"req"=>" FROM visites,adresses,soins WHERE adresses.id = visites.adresses_id AND soins.id = visites.soins_id ORDER BY visites.la_date DESC");



//champs ou on veut utiliser heredoc
$champs_heredoc= array();
$champs_heredoc["commandes"]=array("fournisseur");



$collapsed = array();
$collapsed["admin"]= "";
$collapsed["adresses"]= "collapsed";
$collapsed["commandes"]= "";
$collapsed["depenses"]= "";
$collapsed["deplacements"]= "";
$collapsed["factures"]= "";
$collapsed["medecins"]= "";
$collapsed["notes_vaccins"]= "";
$collapsed["pages"]= "";
$collapsed["pressions"]= "";
$collapsed["prescriptions"]= "";
$collapsed["soins"]= "";
$collapsed["frais"]= "";
$collapsed["visites"]= "";
$collapsed["notes"]= "";


$charset = 'iso-8859-1';

$datefmt = '%A %d %B %Y é %H:%M';

/*descriptions pour remplir les champs*/
$description = array();

$description ["adresses"]["id"]='no d\'identification attribué par le système';
$description ["adresses"]['categorie'] = 'pour regrouper les adresses ex.: résidence, Pharmacie, client,etc.';
$description ["adresses"]['tel'] = 'ex.: 8198883322 (10 chiffres seulement)';
$description ["adresses"]['poste'] = ' inscrire le no de poste ici (chiffres seulement)';
$description ["adresses"]['cell'] = 'ex.: 8198883322 (10 chiffres seulement)';
$description ["adresses"]['fax'] = 'ex.: 8198883322 (10 chiffres seulement)';
$description ["adresses"]['courriel'] = 'xxxxxx<b>@</b>xxxxxx<b>.</b>xxx';
$description ["adresses"]['naissance'] = 'AAAA-MM-JJ';
$description ["adresses"]['nom'] = 'ne rien inscrire ici pour un client';
$description ["adresses"]['cp'] = 'A1B2C3  sans espace ni tiret';

$description ["soins"]["frais_id"] = "choisir un frais qui est compris dans le prix et qui sera inscrit automatiquement aux dépenses";

$description ["pages"]["id"] = "no d'identification attribué par le système";
$description ["pages"]["date_ins"] = "AAAA.MM.JJ";
$description ["pages"]["texte"] = "surveille l'orthographe, le monde entier peut lire ton message<br/>
          <br/>".htmlspecialchars("<br/>")." <i>pour changer de ligne</i>
          <br/><b>".htmlspecialchars("<b>")."<b>ton mot</b>".htmlspecialchars("</b>")."</b><i>  pour mettre 'ton mot' en caractères gras</i>
          <br/>".htmlspecialchars("<i>")."<i>ton mot</i>".htmlspecialchars("</i>")."<i>  pour mettre 'ton mot' en italique</i>
          <br/><b>".htmlspecialchars("<b><i>")."<i>ton mot</i>".htmlspecialchars("</i></b>")."</b><i>  pour mettre 'ton mot' en italique et en gras</i>
          <br/><b><center>".htmlspecialchars("<center>")."ton mot".htmlspecialchars("</center>")."</center></b><i>  pour mettre 'ton mot' au centre</i>
          <br/><font color=blue>".htmlspecialchars("<font color=blue>mot en bleu</font>")."</font> <i>   pour mettre 'mot en bleu' en bleu</i>
          <br/><font color=red>".htmlspecialchars("<font color=red>mot en rouge</font>")."</font> <i>   pour mettre 'mot en rouge' en rouge</i>
          <br/><font color=green>".htmlspecialchars("<font color=green>mot en vert</font>")."</font> <i>   pour mettre 'mot en vert' en vert</i>
          <br/><b>".htmlspecialchars("<'courriel:nom@auxpetitssoinsdallbert.com'>nom</a>")."</b><br/><i> pour insérer un lien vers l'adresse courriel de nom </i>";
$description ["pages"]["titre"] = "apparaét en gras avant l'article";
$description ["pages"]["actif"] = "visible si 1, invisible si 0";
$description ["pages"]["lien"] = "<b>ne pas mettre http://</b><br/>ex.:index.php?titre=Tarifs <i>  pour un lien vers la page qui a comme titre Tarifs</i>";
$description ["pages"]["rang"] = "L'ordre dans lequel les onglets du menu apparaîtront";
$description["commandes"]["fournisseur"]="écrire".htmlspecialchars("<br/>")."pour changer de ligne";
$description ["visites"]["note"]="la modification de la note sera ajoutée à la note originale<br/><font color=\"blue\">insérer ".htmlspecialchars("<br/>") ." pour changer de ligne</font>";



//enumérations utilisées dans rechercher

$enumerations = array();
$enumerations["adresses"]["valide"]=array("0","1");
$enumerations["adresses"]["categorie"]= array("faire_la_liste" => "SELECT DISTINCT(categorie) as categorie
          FROM adresses
          ORDER BY categorie","cle" => "categorie","val" => "categorie");
/*$enumerations["factures"]["adresses_id"]= array("faire_la_liste" => "SELECT adresses.id  as id, CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM factures, adresses
          WHERE adresses.id IN (SELECT adresses_id FROM factures) 
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");*/ //changé pour suivant 2018.01.13 RG
/* erreur ORDER BY clause is not in SELECT list  $enumerations["factures"]["adresses_id"]= array("faire_la_liste" => "SELECT DISTINCT(adresses.id)  as id, CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM factures, adresses
          WHERE adresses.id = factures.adresses_id 
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");*/
$enumerations["factures"]["adresses_id"]= array("faire_la_liste" => "SELECT DISTINCT(adresses.id)  as id, CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM factures, adresses
          WHERE adresses.id = factures.adresses_id
          ORDER BY unClient","cle" => "id","val" => "unClient");
$enumerations["deplacements"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["factures"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["visites"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["depenses"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["pressions"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["notes_vaccins"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
$enumerations["commandes"]["nul"]=array("tout"=>"","valides"=>"0","annulés"=>"1");
//$enumerations["ventes"]["nul"]=array("","0","1");
//$enumerations["ventes"]["adresses_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom,nom_resp,\" \",prenom_resp) as unClient
//          FROM adresses
//          ORDER BY nom,nom_resp,prenom_resp","cle" => "id","val" => "unClient");
$enumerations["visites"]["adresses_id"]= array("faire_la_liste" => "SELECT id , IFNULL(CONCAt(nom_resp,\" \", prenom_resp, \" \",naissance),CONCAt(nom_resp,\" \", prenom_resp)) as unClient
          FROM adresses
          WHERE categorie=\"client\"
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");
$enumerations["pressions"]["adresses_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM adresses
          WHERE categorie=\"client\"
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");
$enumerations["prescriptions"]["adresses_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM adresses
          WHERE categorie=\"client\"
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");
$enumerations["notes_vaccins"]["adresses_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom_resp,\" \",prenom_resp) as unClient
          FROM adresses
          WHERE categorie=\"client\" 
          ORDER BY nom_resp,prenom_resp","cle" => "id","val" => "unClient");
          
//$enumerations["ventes"]["produits_id"]= array("faire_la_liste" => "SELECT id , nom
//          FROM produits
//          ORDER BY nom","cle" => "id","val" => "nom");          

$enumerations["visites"]["soins_id"]= array("faire_la_liste" => "SELECT id , nom, type
          FROM soins
          ORDER BY type, nom","cle" => "id","val" => "nom");
//$enumerations["ventes"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
//          FROM ventes
//          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["visites"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM visites
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["visites"]["type"]= array("faire_la_liste" => "SELECT DISTINCT(type) as type
          FROM soins
          ORDER BY type","cle" => "type","val" => "type");

$enumerations["factures"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM factures
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
      
$enumerations["pressions"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM pressions
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["prescriptions"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM prescriptions
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["notes_vaccins"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
			FROM notes_vaccins
			ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["notes_vaccins"]["nom_vaccin"]= array("faire_la_liste" => "SELECT DISTINCT(nom_vaccin) as nom_vaccin
			FROM notes_vaccins
			ORDER BY nom_vaccin","cle" => "nom_vaccin","val" => "nom_vaccin");
$enumerations["deplacements"]["endroit"]= array("faire_la_liste" => "SELECT DISTINCT(endroit) as endroit
          FROM deplacements
          ORDER BY endroit","cle" => "endroit","val" => "endroit");
$enumerations["deplacements"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM deplacements
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["deplacements"]["raison"]= array("faire_la_liste" => "SELECT DISTINCT(raison) as raison
          FROM deplacements
          ORDER BY raison","cle" => "raison","val" => "raison");
$enumerations["commandes"]["produit"]= array("faire_la_liste" => "SELECT DISTINCT(produit) as produit
          FROM commandes
          ORDER BY produit","cle" => "produit","val" => "produit");
$enumerations["commandes"]["fournisseur_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom,\" - \",ville) as nom
          FROM adresses
          WHERE categorie=\"pharmacie\" AND (adresses.id IN (SELECT DISTINCT fournisseur_id FROM commandes)) 
          ORDER BY nom","cle" => "id","val" => "nom");
$enumerations["commandes"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM commandes
          ORDER BY auteur","cle" => "auteur","val" => "auteur");          
$enumerations["depenses"]["compte"]= array("faire_la_liste" => "SELECT DISTINCT(compte) as compte
          FROM depenses
          ORDER BY compte","cle" => "compte","val" => "compte");
$enumerations["depenses"]["auteur"]= array("faire_la_liste" => "SELECT DISTINCT(auteur) as auteur
          FROM depenses
          ORDER BY auteur","cle" => "auteur","val" => "auteur");
$enumerations["depenses"]["raison"]= array("faire_la_liste" => "SELECT DISTINCT(raison) as raison
          FROM depenses
          ORDER BY raison","cle" => "raison","val" => "raison");
$enumerations["soins"]["nom"]= array("faire_la_liste" => "SELECT DISTINCT(nom) as nom
          FROM soins
          ORDER BY nom","cle" => "nom","val" => "nom");
$enumerations["soins"]["type"]= array("faire_la_liste" => "SELECT DISTINCT(type) as type
          FROM soins
          ORDER BY type","cle" => "type","val" => "type");
$enumerations["frais"]["nom_frais"]= array("faire_la_liste" => "SELECT DISTINCT(nom_frais) as nom_frais
          FROM frais
          ORDER BY nom_frais","cle" => "nom_frais","val" => "nom_frais");
$enumerations["frais"]["type_frais"]= array("faire_la_liste" => "SELECT DISTINCT(type_frais) as type_frais
          FROM frais
          ORDER BY type_frais","cle" => "type_frais","val" => "type_frais");

//extensions des fichiers acceptés dans le rep images
$ext_images = array("jpg","jpeg","gif","JPG");



//les options à choisir pour produire le select
//apparaissent dans cet ordre et doivent avoir un $filtreTexte correspondant
$filtre = array();
$filtre["adresses"]= array("categorie" => "toutes");

//les textes pour options dans filtre
$filtreTexte = array();
$filtreTexte["adresses"]= array("categorie" => "une catégorie");

//background
$fonds= array("pair","impair");

//index définisdans mysql et utilisés dans rechercher
$index=array();
$index["pages"]= array("titre","texte");
$index["adresses"]= array("nom","prenom_resp","nom_resp","ville");
$index["medecins"]= array("nom","prenom","ville");
//$index["produits"]= array("nom","description");

$index["soins"]= array("nom","description");
$index["frais"]= array("nom_frais","description_frais");



//informations en lien à un enregistrement
//apparaissant en bas de cet enregistrement
//dans un fieldset collapsible
//chaque élément doit être une table de la base de données
$infosSupp= array();
$infosSupp["adresses"][]=array("titre"=>"visites","table"=>"visites","champs"=> array("id","la_date", "auteur","nom","prix","facture_id"),"requete"=>"SELECT visites.id,visites.la_date, visites.auteur, soins.nom ,  visites.prix, visites.facture_id , note FROM visites,soins ","actions"=>array("+ note","+ pression","+ note vaccin","Annuler"),"boutons"=>array("+ visite"));
//$infosSupp["adresses"][]=array("titre"=>"ventes","table"=>"ventes","requete"=>"SELECT ventes.id,ventes.la_date, ventes.auteur, produits.nom ,  ventes.prix, ventes.facture_id FROM ventes,produits ","actions"=>array(),"boutons"=>array("+vente"));
$infosSupp["adresses"][]=array("titre"=>"notes","table"=>"visites","champs"=> array("id","la_date", "auteur","nom","note","nul"),"requete"=>"SELECT visites.id, visites.la_date, visites.auteur, soins.nom, visites.note,visites.nul  FROM visites, soins ","actions"=>array("Modifier la note", "Imprimer note"),"boutons"=>array());
$infosSupp["adresses"][]=array("titre"=>"pressions","table"=>"pressions","champs"=> array("id","la_date", "auteur","bras_gauche","bras_droit","note"),"requete"=>"SELECT id, pressions.la_date, pressions.auteur, CONCAT(bras_gauche_haut,\" / \",bras_gauche_bas) as bras_gauche, CONCAT(bras_droit_haut,\" / \",bras_droit_bas) as bras_droit, pressions.note  FROM pressions ","actions"=>array("Modifier"),"boutons"=>array(""));
$infosSupp["adresses"][]=array("titre"=>"prescriptions","table"=>"prescriptions","champs"=> array("id","la_date", "auteur","qte","produit","forme"),"requete"=>"SELECT id, prescriptions.la_date, prescriptions.auteur, qte, produit, forme  FROM prescriptions ","actions"=>array("Modifier"),"boutons"=>array("+ prescription"));
$infosSupp["adresses"][]=array("titre"=>"notes_vaccins","table"=>"notes_vaccins","champs"=> array("id","visites_id","la_date", "auteur","nom_vaccin","dose","la_date_exp","note"),"requete"=>"SELECT id,visites_id, notes_vaccins.la_date, notes_vaccins.auteur, notes_vaccins.note  FROM notes_vaccins ","actions"=>array("Modifier"),"boutons"=>array(""));
$infosSupp["admin"] = array();
$infosSupp["commandes"] = array();
$infosSupp["depenses"] = array();
$infosSupp["deplacements"] = array();
$infosSupp["factures"] = array();
$infosSupp["notes_vaccins"] =array();
$infosSupp["visites"] =array();
$infosSupp["pressions"] = array();
$infosSupp["prescriptions"] = array();
$infosSupp["soins"] = array();
$infosSupp["frais"] = array();
$infosSupp["medecins"]= array();
$infosSupp["pages"] = array();

$items_documents= array();
$items_documents[]=array("nom"=>"Fiche de vaccination","action"=>"fiche_vaccination.php");


$items_actions= array();
$items_actions[]=array("nom"=>"Changer le mot de passe","action"=>"changer passe");
$items_actions[]=array("nom"=>"Rapport annuel","action"=>"rapport annuel");


$jours = array('Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=> 'Mardi','Wednesday'=> 'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi');

$largeurTable ="800px";

//le tri original de la table 
//il doit y en avoir un de défini pour chaque table
$le_tri= array();
$le_tri["admin"] = "id" ;
$le_tri["adresses"] = "categorie, nom,nom_resp,prenom_resp" ;
$le_tri["commandes"] = "la_date DESC" ;
$le_tri["depenses"] = "la_date DESC" ;
$le_tri["deplacements"] = "la_date DESC" ;
$le_tri["factures"] = "factures.la_date DESC" ;
$le_tri["notes_vaccins"] = "notes_vaccins.la_date DESC" ;
$le_tri["visites"] = "visites.la_date DESC" ;
$le_tri["pressions"] = "la_date DESC" ;
$le_tri["prescriptions"] = "la_date DESC, id DESC" ;
$le_tri["soins"] = "nom" ;
$le_tri["frais"] = "nom_frais" ;
$le_tri["medecins"]= "nom, prenom";
$le_tri["pages"] = "rang" ;

//listes pour formulaire ajouter
//item "faire_la_liste" indique à formatter_input_pour la requete pour trouver les items du select
//item "nouveau" indique à formatter_input_pour si un input doit etre ajouté pour permettre d'insérer une nouvelle valeur dans la table pour ce champ
//item "defaut" indique à formatter_input_pour quelle valeur sera sélectionnée
//item "groupe" indique à faire_la_liste le regroupement à faire
$listes=array();
$listes["adresses"]["categorie"]= array("faire_la_liste" => "SELECT DISTINCT(categorie) as categorie
          FROM adresses
          ORDER BY categorie","cle" => "categorie","val" => "categorie","nouveau"=>"oui","defaut"=>"client");
$listes["adresses"]["ville"]= array("faire_la_liste" => "SELECT DISTINCT(ville) as ville
          FROM adresses
          ORDER BY ville","cle" => "ville","val" => "ville","nouveau"=>"oui");
$listes["adresses"]["medecin_id"]= array("faire_la_liste" => "SELECT id, CONCAT(nom,\"  \",prenom) as unDoc
          FROM medecins
          ORDER BY unDoc ASC","cle" => "id","val" => "unDoc");
/* les prix seront extraits dans validate.js */
//erreur is not in GROUP BY clause     $listes["visites"]["soins_id"]= array("faire_la_liste" => "SELECT DISTINCT(id) , type, CONCAT(nom,\" $\",prix) as unSoin FROM soins GROUP BY type ,unSoin","cle" => "id","val" => "unSoin", "groupe"=>"type");
$listes["visites"]["soins_id"]= array("faire_la_liste" => "SELECT DISTINCT(id) , type, CONCAT(nom,\" $\",prix) as unSoin FROM soins GROUP BY id,type ORDER BY type DESC,unSoin ,unSoin","cle" => "id","val" => "unSoin", "groupe"=>"type");
$listes["commandes"]["fournisseur_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom,\" - \",ville) as unFournisseur  
          FROM adresses WHERE categorie = \"pharmacie\" 
          ORDER BY  unFournisseur",  
          "cle" => "id","val" => "unFournisseur");          
//$listes["ventes"]["produits_id"]= array("faire_la_liste" => "SELECT id , CONCAT(nom,\" $\",prix) as unProduit
//          FROM produits
//          ORDER BY nom","cle" => "id","val" => "unProduit");
$listes["medecins"]["ville"]= array("faire_la_liste" => "SELECT DISTINCT(ville) as ville
          FROM medecins
          ORDER BY ville","cle" => "ville","val" => "ville","nouveau"=>"oui");
$listes["depenses"]["compte"]=array("repas et frais de représentation","assurances","frais de bureau","fournitures","frais comptable","téléphone et internet","carburant et huile","assurance auto","immatriculation auto","entretien et réparations","dons");
asort($listes["depenses"]["compte"]);
$listes["soins"]["frais_id"]= array("faire_la_liste" => "SELECT DISTINCT(id) , type_frais, CONCAT(nom_frais,\" $\",prix_frais) as unFrais FROM frais GROUP BY type_frais ,unFrais","cle" => "id","val" => "unFrais", "groupe"=>"type_frais");


$logo="horizontal_couleur.jpg";

$g_adresse="";
 
$g_messages=array();
$g_messages["Ajouter"]=array("succes"=>"Cet enregistrement a été ajouté avec succès.","echec"=>"L'enregistrement a échoué.");//le mot succès important car il est utilisé pour vérifier
$g_messages["Ajouter deplacement"]=array("succes"=>"Un déplacement  a  aussi été ajouté avec succès.","echec"=>"L'enregistrement du déplacement a échoué.","endroit"=>"Aucun déplacement n'a été enregitré car il n'y a pas de ville pour ce client.");//le mot succès important car il est utilisé pour vérifier
$g_messages["Ajouter frais"]=array("succes"=>"Une dépense a  aussi été ajoutée avec succès.","echec"=>"L'enregistrement de la dépense pour le frais a échoué.","frais"=>"Aucune dépense n'a été enregitrée car il n'y a pas de frais pour ce soin.");//le mot succès important car il est utilisé pour vérifier


$mois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


$piedDePage = "<div id=\"footer\" class=\"clearfix\">\n
<div class=\"block-wrapper odd\">\n
<div id=\"block-menu-secondary-links\" class=\"block block-menu\">\n
 <div class=\"content\">\n
	<ul class=\"menu\"><li class=\"leaf first last active-trail\"><a href=\"index.php\" title=\"\" class=\"active\">Accueil</a></li></ul>\n          
</div>\n
</div>\n
</div>\n
<div class=\"block-wrapper even\">\n
<div id=\"block-system-0\" class=\"block block-system\">\n
<div class=\"content\">\n
<!--<a href=\"http://drupal.org\"><img src=\"/allbert0/misc/powered-blue-80x15.png\" alt=\"Créé avec l&#039;aide de Drupal, un systéme de gestion de contenu &quot;opensource&quot;\" title=\"Créé avec l&#039;aide de Drupal, un système de gestion de contenu &quot;opensource&quot;\" width=\"80\" height=\"15\" /></a>  -->\n
</div>\n
</div>\n
</div>\n
Profitez de nos services<br/>cellulaire 819.983.8642\n
	</div><!-- /footer -->\n";

//les groupements
$regrouperPourVoir= array();

$regrouperPourVoir["adresses"] = "categorie" ;
/*
$regrouperPourVoir["c_documents"] = "categorie" ;
$regrouperPourVoir["c_liens"] = "categorie" ;
$regrouperPourVoir["documents"] = "categorie" ;
$regrouperPourVoir["liens"] = "categorie" ;
*/




//messages résultant des requetes
$requete_echec = "Erreur : votre requète n'a pu être exécutée.";
$requete_succes = "Votre requète a été exécutée avec succès.";
$requete_existant = "Cet enregistrement existe déjà.";
$requete_passe_echec = "Le mot de passe n'a pu être changé.";
$requete_passe_succes = "Le mot de passe a été modifié.";

/*contient le champ et le critère par défaut pour utiliser la fonction : trouver_un_enregistrement($une_table,$un_champ,$un_critere)
MAX donnera le dernier enregistrement
*/
$g_tables= array();
$g_tables["adresses"]=array("id","MIN");
$g_tables["commandes"]=array("id","MIN");
$g_tables["depenses"]=array("id","MIN");
$g_tables["deplacements"]=array("id","MIN");
$g_tables["factures"]=array("id","MIN");
$g_tables["medecins"]=array("id","MIN");
$g_tables["pages"]=array("id","MIN");
$g_tables["pressions"]=array("id","MIN");
$g_tables["prescriptions"]=array("id","MIN");
$g_tables["soins"]=array("id","MIN");
$g_tables["frais"]=array("id","MIN");
$g_tables["notes_vaccins"]=array("id","MIN");
$g_tables["visites"]=array("auteur","MIN");


//recherche de date
$recherche_date= array();
$recherche_date["adresses"] = "naissance";
$recherche_date["factures"] = "la_date";
$recherche_date["visites"] = "la_date";
$recherche_date["depenses"] = "la_date";
$recherche_date["deplacements"] = "la_date";
$recherche_date["pages"] = "la_date";
$recherche_date["pressions"] = "la_date";
$recherche_date["prescriptions"] = "la_date";
$recherche_date["soins"] = "la_date";
$recherche_date["frais"] = "la_date";
$recherche_date["medecins"] = "la_date";
$recherche_date["notes_vaccins"] = "la_date";
$recherche_date["commandes"] = "la_date";


$img_tri = Array();
$img_tri["ASC"]="images/triup.gif";
$img_tri["DESC"]="images/tridown.gif";

//dimensions max des images selon la page
$img_max_width = array("index"=>200,"photo"=>1024,"equipe"=>500,"liens"=>110);



//les conditions pour voir une table
$wherePourVoir= array();
/*
$wherePourVoir["adresses"] = " 1" ;

$wherePourVoir["factures"] = "" ;
$wherePourVoir["medecins"] = " 1" ;
$wherePourVoir["pages"] = " 1" ;
$wherePourVoir["soins"] = " 1" ;
$wherePourVoir["visites"] = " 1" ;
*/

$zeroResultat = "aucune information disponible présentement";
$zeroTrouve = "aucun enregistrement correspondant";

?>

