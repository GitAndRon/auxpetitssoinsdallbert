

<?php
/*
 fonction pour trouver les noms des tables
 retourne un tableau
 */
class Lien {
    
    var $un_lien;
    var $un_hote;
    
    function __construct($unNom, $unMot)
    {
        $this->un_hote = ($_SERVER['REMOTE_ADDR']=="127.0.0.1")?"127.0.0.1":"mysql-6.bluephyre.com";
        $this->un_lien = mysqli_connect($this->un_hote,$unNom, $unMot,"allbert")
            or die("Impossible de se connecter <br/>$unNom<br/>".mysqli_connect_errno().mysqli_connect_error() . PHP_EOL);
    }
    
    function getLien(){
        return $this->un_lien;
    }
    
    
} 
function trouver_les_tables(){
	//127.0.0.1
	//127.0.0.1
	/* $un_lien = mysqli_connect("127.0.0.1", $GLOBALS["pourVoir"], $GLOBALS["ppourVoir"],"allbert")
	or die("Impossible de se connecter <br/>".mysqli_connect_errno().mysqli_connect_error() . PHP_EOL); */
    $con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
    $un_lien= $con->getLien();
    mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
    $tables = array();
    $req = "SHOW TABLES;";
    $resultat = mysqli_query($un_lien,$req) or die(mysqli_error($un_lien).":: Erreur de requète :<br/>".$req);
   /* Entreposer les résultats dans le tableau */

   while ($ligne = mysqli_fetch_assoc($resultat)) {
         foreach($ligne as $cle => $val){
			 if(substr($val,0,5)=="piwik" ){//|| $val =="visites"){//la table visite et les tables piwikne sont pas listées dans lemenu de gauche
			 }
			 else{
				$tables[]=$val;}
		 }
		 
   }

   /* Libération des résultats */
   mysqli_free_result($resultat);
   mysqli_close($un_lien);
   unset($un_lien);
   return $tables;
}


/*
 fonction pour exécuter une requéte simple
 retourne un tableau nom de champ => valeur
 */
function requeteSelect($une_req){
	$association="";
	$con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
	$un_lien = $con->getLien();
	mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
    $association=isset($champ_association)?",  ".$champ_association:"";
    
	//echo $une_req."<br/>";
	$trouves = array();					
	$res_une_req = mysqli_query($un_lien,$une_req) or die(mysqli_error($un_lien)." ::<br/>$une_req");
     //$trouves = mysqli_fetch_array($res_une_req, MYSQL_ASSOC);
     $GLOBALS["g_adresse"]="";
     //echo $une_req."<br/> nb de lignes =".mysqli_num_rows($res_une_req)."<br/>";
     if(mysqli_num_rows($res_une_req)){
		 $i=0;
		 while ($ligne = mysqli_fetch_assoc($res_une_req)) {
			 $trouves[]=$ligne;
		}
	}
	  else{
		  
	  //rien
   }

    /* Libération des résultats */
    mysqli_free_result($res_une_req);

    //fermeture de la connexion
    mysqli_close($un_lien);
    unset($un_lien);
    
    //echo montrerUnTableau($trouves," 68");
    return $trouves;	
}



/*
 fonction pour exécuter une requéte simple
 retourne un tableau nom de champ => valeur
 
 */
function trouver_un_enregistrement($une_table,$un_champ,$un_critere,$champ_association){
	$association="";
	$con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
	$un_lien = $con->un_lien;
	mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
    $association=isset($champ_association)?",  ".$champ_association:"";
    If($un_critere == "MAX"){
		$une_req = "SELECT * , MAX($un_champ) as dernier $association
							FROM $une_table GROUP BY $un_champ";
		
	}
	elseif($un_critere == "MIN"){
		
		$une_req = "SELECT *  $association
							FROM $une_table
							WHERE $un_champ = 1";
	}
	else{
		$une_req = "SELECT *  $association
							FROM $une_table
							WHERE $un_champ = \"$un_critere\"";
	}
	//echo "trouver un enr fonctions.inc.php l 125 $une_req<br/>";
	$un_enr = array();					
    $res_une_req = mysqli_query($un_lien,$une_req) or die(mysqli_error($un_lien)." ::<br/>$une_req");
     //$un_enr = mysqli_fetch_array($res_une_req, MYSQL_ASSOC);
     $GLOBALS["g_adresse"]="";
     //echo $une_req."<br/> nb de lignes =".mysqli_num_rows($res_une_req)."<br/>";
     if(mysqli_num_rows($res_une_req)){
		 while ($ligne = mysqli_fetch_assoc($res_une_req)) {
			 if(mysqli_num_rows($res_une_req)){
				 foreach($ligne as $cle => $val){
					 $un_enr[$cle] = $val;
					 //echo "un_enr[$cle] = $un_enr[$cle] = $val<br/>";
				}
			}
		}
	}
	  else{
		  
	  $un_enr["id"]=NULL;
   }

    /* Libération des résultats */
    mysqli_free_result($res_une_req);

    //fermeture de la connexion
    mysqli_close($un_lien);
    unset($un_lien);
    return $un_enr;	
}


/*
valider un utilisateur
* retourne vrai ou faux

*/
function valideQui($unNom, $unMot){
    $con = new Lien($unNom, $unMot);
    $un_lien= $con->getLien();
	//$un_lien = mysqli_connect("127.0.0.1", $unNom, $unMot,"allbert");
	//fermeture de la connexion
	if($un_lien){
		mysqli_close($un_lien);
		$unlien= trouver_un_enregistrement("adresses","nom",$unNom,null);
	}
	
    return $un_lien;
}

function faire_une_liste( $descriptionDeListe ){
	$temp = $descriptionDeListe;
	$con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
	$un_lien= $con->getLien();
    mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
    $une_liste = array();
    $res_une_req = mysqli_query($un_lien,$descriptionDeListe["faire_la_liste"]) or die(mysqli_error($un_lien)." ::<br/>".$descriptionDeListe["faire_la_liste"]);
    /* Entreposer les résultats dans le tableau */
    $group=array_key_exists("groupe",$descriptionDeListe)?$descriptionDeListe["groupe"]:0;
    while ($line = mysqli_fetch_assoc($res_une_req)) {
		if ($group){
          if($descriptionDeListe["une_cle"]){
              $une_liste[$line[$group]][$line[$une_cle]]=$line[$une_val];
          }
          else{
              $une_liste[]=$line[$une_val];
          }
	  }
	  else{
		 if($descriptionDeListe["une_cle"]){
              $une_liste[$line[$une_cle]]=$line[$une_val];
          }
          else{
              $une_liste[]=$line[$une_val];
          } 
	  }
    }

    /* Libération des résultats */
    mysqli_free_result($res_une_req);

    //fermeture de la connexion
    mysqli_close($un_lien);
    unset($un_lien);
    return $une_liste;

}
function faire_la_liste( $une_req,$une_cle,$une_val,$unRegroupement){
    
    $con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
    $un_lien= $con->getLien();
    mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données".mysqli_error($un_lien) . PHP_EOL);
    $une_liste = array();
    $res_une_req = mysqli_query($un_lien,$une_req) or die(mysqli_error($un_lien)." ::<br/>$une_req");
  // echo $une_req." fonctions 189<br/>clé: $une_cle , val $une_val";
    /* Entreposer les résultats dans le tableau */
    if ($unRegroupement){
		while ($line = mysqli_fetch_assoc($res_une_req)) {
			  if($une_cle){
				  $une_liste[$line[$unRegroupement]][$line[$une_cle]]=$line[$une_val];
			  }
			  else{
				  $une_liste[$line[$unRegroupement]][]=$line[$une_val];
			  }
		}
	}
	else{
		while ($line = mysqli_fetch_assoc($res_une_req)) {
			  if($une_cle){
				  $une_liste[$line[$une_cle]]=$line[$une_val];
			  }
			  else{
				  $une_liste[]=$line[$une_val];
			  }
		}
	}

    /* Libération des résultats */
    mysqli_free_result($res_une_req);

    //fermeture de la connexion
    mysqli_close($un_lien);
    unset($un_lien);
    return $une_liste;

}

function show_colonnes($desGet){

/*pour trouver les infos de la table

param   une table , un no d'enregistrement

return  tableau */
    $vide = "";
    $min = 0;
    
    $con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
    $un_lien= $con->getLien();
       mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Connexion impossible");
       $req = "SHOW COLUMNS FROM ".$desGet["table"]."";
       //echo "req = $req<br>";
       $resultat = mysqli_query($un_lien,$req)or die($tab_show_colonnes['erreur'] = "Une erreur est survenue lors de la requéte : ".mysqli_errno($un_lien).$req);
       $i =0;
       while ($line = mysqli_fetch_assoc($resultat)) {
        foreach($line as $cle => $champ ){
                      $tab_show_colonnes[$i]['nom_du_champ'] = $line["Field"];
                      $tab_show_colonnes[$i]['type'] = $line["Type"];
                      //echo "champ: ".$line["Field"]." type: ".$line["Type"]."<br/>";
                      (substr($line["Type"],0,4) == 'Enum')?$tab_show_colonnes[$i]['options'] = preg_split("/[,'()]+/",$line["Type"]):$vide="";
                      (strlen($line["Null"])> 0)?$tab_show_colonnes[$i]['nul'] = $line["Null"]:$vide="";
                      (strlen($line["Key"]) > 0)?$tab_show_colonnes[$i]['key'] = $line["Key"]:$vide="";
                      (strlen($line["Default"]) > 0)?$tab_show_colonnes[$i]['defaut'] = $line["Default"]:$vide="";
                      (strlen($line["Default"]) > 0)?$tab_show_colonnes[$i]['valeur'] = $line["Default"]:$vide="";
                      //echo "champ: ".$line["Field"]." defaut: ".$line["Default"]."<br/>";
                      (strlen($line["Extra"]) > 0)?$tab_show_colonnes[$i]['extra'] = $line["Extra"]:$vide="";}
          $i++;
        }
    mysqli_free_result($resultat);
    if (isset($desGet["crit"])){$req = "SELECT * FROM ".$desGet["table"]."
               WHERE id = ".$desGet["crit"]."";}
    else {$req = "SELECT * FROM ".$desGet["table"]."";}
    $resultat = mysqli_query($un_lien,$req)or die($tab_show_colonnes['erreur'] = "Une erreur est survenue lors de la requéte 2 : $req".mysqli_errno($un_lien));
    $valeurs = mysqli_fetch_array($resultat);
    if($desGet["table"] == "adresses" && isset($desGet["crit"])){
		  $GLOBALS["g_adresse"] ="";
		  //echo "adresse avant = ".$GLOBALS["g_adresse"]." ";
		 $GLOBALS["g_adresse"] = str_replace(" ", "+", $valeurs["adresse"]).",+".str_replace(" ", "+", $valeurs["ville"]).",+".str_replace(" ", "+", $valeurs["cp"]);//rg 2015-08-06 ajuster é google
		  //echo "adresse aprés = ".$GLOBALS["g_adresse"]."<br/>";
	}
    $tab_show_colonnes['nb_champs'] = mysqli_num_fields($resultat);
       for ($i=0; $i < $tab_show_colonnes['nb_champs']; $i++) {
           $finfo = mysqli_fetch_field_direct($resultat, $i);
                      $tab_show_colonnes[$i]['longueur'] = $finfo->length;
                      (isset($desGet["crit"]) && $desGet["action"] <> "Ajouter")?$tab_show_colonnes[$i]['valeur'] = $valeurs[$i]:$vide="";
                     
                    }
   /* if (isset($_POST["code"])){   //présent lors d'un ajout de petite annonce
       for ($i=0; $i < $tab_show_colonnes['nb_champs']; $i++) {
           if ( array_key_exists($tab_show_colonnes[$i]['nom_du_champ'],$_POST)){
               $caracteres_decodes = utf8_decode($_POST[$tab_show_colonnes[$i]['nom_du_champ']]);
               $tab_show_colonnes[$i]['valeur'] = $caracteres_decodes;
           }

       }
       

    }
*/
    mysqli_free_result($resultat);
    $req = "SELECT MIN(id)as min FROM ".$desGet["table"]."";
    $resultat = mysqli_query($un_lien,$req)or die($tab_show_colonnes['erreur'] = "Une erreur est survenue lors de la requéte : ".mysqli_errno($un_lien).$req);
    while ($line = mysqli_fetch_assoc($resultat)) {
        $tab_show_colonnes["min"] =  $line["min"];
    }
    mysqli_free_result($resultat);
    if ($un_lien){mysqli_close($un_lien);}
    return $tab_show_colonnes;
}

function formatter_input_pour($tab_champs,$un,$classe,$desGet){

    /* formule un énoncé pour afficher les champs dans un formulaire selon leur type
       param    tableau avec les infos du champ, style dans lequel le formulaire est affiché
       return   string
    */
	date_default_timezone_set('America/New_York');
    $vide="" ;
    $description= "";
    $l_enonce="";
    $classe_validation=" class =\"form-text\"";
    $id = null;
    $align = "";
	//echo montrerUnTableau($tab_champs[$un], "$tab_champs[$un]");
    $un_objet ="{";
    foreach($tab_champs[$un] as $cle => $val ){
      $un_objet.=$cle.":\"".$val."\",";}
      $un_objet = substr($un_objet,0,strlen($un_objet)-1);
      $un_objet.="}";
      
      $sa_valeur=(isset($tab_champs[$un]['valeur']))?$tab_champs[$un]['valeur']:null;
      foreach($desGet as $cle => $val){
		  if($cle == "iu_".$tab_champs[$un]["nom_du_champ"] ){
			  $sa_valeur = $val;
			 //echo "$cle = $val<br/>";
		  }
		
	}
     //echo montrerUnTableau($tab_champs, "  309"); 
      $requis = ($tab_champs[$un]['nul'] =="NO")?"  class= \"form-text required\"  ":"";
      $classe_validation = ($requis)? " class =\"form-text required\"":"class= \"form-text\"";
      $flag_requis_ou_non = strlen($requis)?"<span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span>":"";
      $son_action="";
      if(isset($tab_champs[$un]["nom_du_champ"], $GLOBALS["reactions"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
		  foreach($GLOBALS["reactions"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]] as $cle => $val){
			  $son_action.=" $cle = \"$val\" ";
		  }
		  $classe_validation = substr($classe_validation,0,-1)." reaction\"";
		  
	  }
      
      if($tab_champs[$un]["nom_du_champ"] == "id"){ //ne doit pas étre modifié
		  $typeDInput= "hidden";
		  $l_enonce =(strlen($sa_valeur) > 0)?$l_enonce."<div class=\"form-item\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\">\n<label id=\"l-".$tab_champs[$un]["nom_du_champ"]."\" for=\"".$tab_champs[$un]["nom_du_champ"]."\">".$tab_champs[$un]["nom_du_champ"]."<input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n"
                                    .$sa_valeur."</label>":"" ;
                                    
        
      } 
      else {
		 $l_enonce.="<div class=\"form-item\" id=\"edit".$tab_champs[$un]["nom_du_champ"]."\">\n";
        $l_enonce.="<label id=\"l-".$tab_champs[$un]["nom_du_champ"]."\" for=\"".$tab_champs[$un]["nom_du_champ"]."\">".$tab_champs[$un]["nom_du_champ"].$flag_requis_ou_non."</label>\n";
        

        $var = (preg_match("/char/",$tab_champs[$un]["type"]))?"var":"rien";
        $int =  (preg_match("/int/",$tab_champs[$un]["type"]))?substr($tab_champs[$un]["type"],0,3):"rien";
        $enum = (preg_match("/enum\(/",$tab_champs[$un]["type"]))?substr($tab_champs[$un]["type"],0,3):"rien";
        $decimal = (preg_match("/dec\(/",$tab_champs[$un]["type"]))?substr($tab_champs[$un]["type"],0,3):"rien";
        //echo "type du champ ".$tab_champs[$un]["nom_du_champ"]." = ".$tab_champs[$un]["type"]."<br/>";
        //$description= (isset($GLOBALS["description"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]))?"<td class='".$classe."'>".$GLOBALS["description"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]."</td>":"";
        if (isset($GLOBALS["description"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
            $description = "<div class=\"description\">".$GLOBALS["description"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]."</div>" ;
        }
        else {
              $description.= "";
        }
        //echo montrerUnTableau($GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]], " 305 ");
        $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";

        switch ( substr($tab_champs[$un]["type"],0,3)){
            case $enum :
                  $typeDInput ="select";
                  $liste_tire = preg_split("/[,'()]+/",$tab_champs[$un]["type"]);
                  //echo montrerUnTableau($liste_tire,"391");
                  $l_enonce.="<select  class=\"form-select\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" $classe_validation $son_action >\n";
                  foreach ($liste_tire as $valeur) {
                      //echo "cle = ".$cle." valeur = ".$valeur."</br>";
                      if ( $valeur != "enum"){
                          if ( $valeur == $sa_valeur ){
                             $l_enonce.="\t\t<option selected value = '$valeur'>$valeur</option>\n";}
                          else {
                             $l_enonce.="\t\t<option value = \"$valeur\">$valeur</option>\n"; }
                      }
                  }
                  $l_enonce.="</select>";
                  //$l_enonce .=" <input type = 'text' name = '".$tab_champs[$un]["nom_du_champ"]."'  size = '".$tab_champs[$un]["longueur"]."'  onblur='verif_null(this,$un_objet)' value ='".$sa_valeur."' />";
            break;
            case $int :
                if ( $tab_champs[$un]["nom_du_champ"] == "id"){
                    /*$l_enonce =(strlen($sa_valeur) > 0)?$l_enonce." <td class = \"pair\"><input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n"
                                    .$sa_valeur."</td>".$description."</tr>":"" ;
                                    */
                    $typeDInput ="id";
                    }
                    elseif($tab_champs[$un]["nom_du_champ"] == "nul"){
						if (!empty($desGet["op"]) && $desGet["op"]=="Appliquer"){
							//echo "allo";
							$sa_valeur =(strlen($sa_valeur) >= 0)?$sa_valeur:"";
						}
						else{
							$sa_valeur =(strlen($sa_valeur) > 0)?$sa_valeur:0;
						}
						$l_enonce.= "<input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n".$sa_valeur;       
										
						$typeDInput = "hidden";
                    }
                else {
					
                    if(isset($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
						if ($typeDInput == "hidden"){
							
						}
						else{
						   $typeDInput ="select";
						   $classe_validation = (substr($classe_validation,-1) == "\"")? substr($classe_validation,0,-1)." digits\"":"\"digits\"";
						   $classe_validation= str_replace("form-text","form-select",$classe_validation);
						   $l_enonce.="<select   id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" $classe_validation $son_action >\n";
						   if(is_array($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
							   if (array_key_exists("faire_la_liste", $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])) {
								  $temp = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]];
								  
								  //echo montrerUnTableau($temp, " 393 ");
								  if (array_key_exists("groupe",$GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
									  $une_liste = faire_la_liste($temp["faire_la_liste"],$temp["cle"],$temp["val"],$GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["groupe"]);
									  //echo montrerUnTableau($une_liste, "416 ");
									  //le résultat est un tableau contenant un tableau pour chaque groupe
									  $l_enonce.="\t\t<option value = \"\" ></option>\n";
									  foreach ($une_liste as $groupe =>$items){
										  $l_enonce.="\t\t<optgroup label = \"".$groupe."s\" >\n";
										  
										foreach($items as $ind=>$item){
											
											if ( $ind == $sa_valeur ){
												//echo " items[$ind]= $item = $sa_valeur<br>";
												  $l_enonce.="\t\t<option selected label =\"$item\" value = \"$ind\" >$item</option>\n";
												}
											else {
											  $l_enonce.="\t\t<option label =\"$item\" value = \"$ind\" >$item</option>\n";
											}
										}
										$l_enonce.="\t\t</optgroup>\n";
									  }
								  }
								  else{//pas de GROUP BY
									  $une_liste = faire_la_liste($temp["faire_la_liste"],$temp["cle"],$temp["val"],NULL);
									  $l_enonce.="\t\t<option value = \"\"></option>\n";
									  foreach ($une_liste as $ind => $valeur){
										if ( $ind == $sa_valeur ){
										  $l_enonce.="\t\t<option selected value = \"$ind\" >$valeur</option>\n";
										}
										else {
										  $l_enonce.="\t\t<option value = \"$ind\" >$valeur</option>\n";
										}
									  }
								   }
							   }
							   else{
								  $une_liste = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]];
								  foreach ($une_liste as $valeur){
									 if ( $valeur == $sa_valeur ){
									  $l_enonce.="\t\t<option selected value = '$valeur'>$valeur</option>\n";}
									else {
									  $l_enonce.="\t\t<option value = \"$valeur\">$valeur</option>\n"; }
									}
							   }
						   }
						   $l_enonce.="</select>";
				   }
                    }
                    elseif(isset($GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
						//rg120326$valeur_associee = trouver_un_enregistrement($GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["table"],$GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["champ_val"],$desGet["iu_".$tab_champs[$un]["nom_du_champ"]],$GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["champ_association"]);
						$valeur_associee = trouver_un_enregistrement($GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["table"],$GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["champ_val"],$sa_valeur,$GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["champ_association"]);
						$test= array();
						
						foreach($desGet as $cle => $val){
							switch($cle){
								case "qui":
									$test[$cle]=$val;
								break;
								case "passe":
									$test[$cle]=$val;
								break;
								case "table":
									$test[$cle]=$GLOBALS["champs_associes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["table"];
								break;
								default:
								break;
							}
						}
						//rg120326$test["crit"]= $desGet["iu_".$tab_champs[$un]["nom_du_champ"]];
						$test["crit"]= $sa_valeur;
						$quoi = base64_encode(transmettreGet($test,false)."&action=Modifier");																																																																										
						//rg120326$l_enonce .= "<input type = \"hidden\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"    value =\"".$desGet["iu_".$tab_champs[$un]["nom_du_champ"]]."\"".$classe_validation."  valeurAssociee= \"".$valeur_associee["association"]."\"/>".$desGet["iu_".$tab_champs[$un]["nom_du_champ"]]."<br/><a href=\"admin.php?&quoi=$quoi\" >".$valeur_associee["association"]."</a>\n"  ;
						$l_enonce .= "<input type = \"hidden\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"    value =\"".$sa_valeur."\"".$classe_validation."  valeurAssociee= \"".$valeur_associee["association"]."\"/>".$sa_valeur."<br/><a href=\"admin.php?&quoi=$quoi\" >".$valeur_associee["association"]."</a>\n"  ;
						$typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
					}
                    else{
						$classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." digits\"":" class=\"digits\"";
                        $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                        //echo"passe lugne 390 ".$tab_champs[$un]["nom_du_champ"]." = ".$sa_valeur."  $typeDInput<br/>";
                        //$l_enonce .= "<input type = \"hidden\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"    value =\"".$sa_valeur."\"".$classe_validation." />".$sa_valeur.$description."\n"  ;
                    }
                     
                 }
            break;
            
            case $var :
            	 $vals = array();
                 $tel = (preg_match("/tel[^_]/",$tab_champs[$un]["nom_du_champ"]))?$tab_champs[$un]["nom_du_champ"]:"rien";
                 
                if(isset($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
                       $typeDInput ="select";
                       $classe_validation = str_replace("form-text","form-select",$classe_validation);
                       //$classe_validation = (substr($classe_validation,0,-1) == "\"")? substr($classe_validation,0,-1)." digits\"":"\"digits\"";
                       //$l_enonce.="<select  class=\"form-select\"  name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" >\n";
                       $l_enonce.="<select  id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\"  name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" >\n";
                       $l_enonce= substr($l_enonce,0,strlen($l_enonce)- strlen(" >\n"))."$classe_validation $son_action>\n"; 
                       if(is_array($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
						   if(array_key_exists("defaut",$GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]) && $sa_valeur == ""){ $sa_valeur = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]["defaut"];}
                           if (array_key_exists("faire_la_liste", $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])) {
                              $temp = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]];
                              $une_liste = faire_la_liste($temp["faire_la_liste"],$temp["cle"],$temp["val"],NULL);                             
                              $l_enonce.="\t\t<option value = \"\"></option>\n";
                              foreach ($une_liste as $ind => $valeur){
								  
                                if ( $ind == $sa_valeur ){
                                  $l_enonce.="\t\t<option selected value = \"$ind\" >$valeur</option>\n";
                                }
                                else {
                                  $l_enonce.="\t\t<option value = \"$ind\" >$valeur</option>\n";
                                }
                              }
                           }
                           else{ //liste créée littéralement dans vars
                              $une_liste = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]];
                              
                              foreach ($une_liste as $valeur){
                                 if ( $valeur == $sa_valeur ){
                                  $l_enonce.="\t\t<option selected value = '$valeur'>$valeur</option>\n";}
                                else {
                                  $l_enonce.="\t\t<option value = \"$valeur\">$valeur</option>\n"; }
                                }
                           }
                       }
                       $l_enonce.="</select>";
                       if(array_key_exists("nouveau",$GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
						   $classe_validation = str_replace("form-select","form-text",$classe_validation);
						   $classe_validation = str_replace("reaction","nouveau",$classe_validation);
						   $classe_validation = str_replace("required","",$classe_validation);
						   $l_enonce .= " ou nouvelle : <input type = \"text\" id=\"iu-nouveau-".$tab_champs[$un]["nom_du_champ"]."\" name = \"".$tab_champs[$un]["nom_du_champ"]."\"  maxlength=\"".$tab_champs[$un]["longueur"]."\" $classe_validation $son_action/>\n"  ;
					   }
                       
                    }
                 else {
					 
                   switch ($tab_champs[$un]["nom_du_champ"]) {
                          
						  case "auteur":
						    $sa_valeur =(strlen($sa_valeur) > 0)?$sa_valeur:$desGet["qui"] ;
                            $l_enonce.= "<input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n".$sa_valeur;       
                                    
							$typeDInput = "hidden";
						  break;
                          case "fichier" :
                             $typeDInput ="fichier";
                          break;
                          case "ville":
                             $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." reaction\" champ=\"iu_cp\"":" class=\"reaction\" champ=\"iu_cp\"";
							 $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          case "cp" :
                          $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." cp\"":" class=\"cp\"";
                            $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          case "cell" :
                             $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." tel\"":" class=\"tel\"";
                             $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          case "fax" :
                             $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." tel\"":" class=\"tel\"";
                             $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          case "tel" :
                             $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." tel\"":" class=\"tel\"";
                             $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          case "courriel" :
                             $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." email\"":" class=\"email\"";
                             $typeDInput = in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                          break;
                          default:
                             $typeDInput = ($tab_champs[$un]["longueur"] < 101)?"text":"textarea";
                          break;
                   }
                 }
                 
            break;
            case "cha" :
                 if(isset($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])){
                     $typeDInput ="select";
                     $l_enonce.="<select  class=\"form-select\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" $classe_validation>\n";
                     foreach ($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]] as $valeur){
                          if ( $valeur == $sa_valeur ){
                             $l_enonce.="\t\t<option selected value = '$valeur'>$valeur</option>\n";}
                          else {
                             $l_enonce.="\t\t<option value = \"$valeur\">$valeur</option>\n"; }
                     }
                   $l_enonce.="</select>";
                 }
                 else {
                   $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                 }
            break;
            case "tex" :
                  $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"grand_textarea";
            break;
            case "dat" :
                 $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                 if( $tab_champs[$un]["nom_du_champ"] == "la_date_exp"){
					 $sa_valeur= isset($sa_valeur)?$sa_valeur:"";
				 }
                 elseif($tab_champs[$un]["nom_du_champ"] <> "naissance" ){
					 if ($tab_champs[$un]["sa_table"] <> "visites" ){
						$sa_valeur= isset($sa_valeur)?$sa_valeur:date("Y-m-d");
					}
				}
                 $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." dateISO\"":" class =\"dateISO\"";
            break;
            case "dec" :
            
                 $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                 $classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." number\"":" class =\"number\"";
                 
                 if($tab_champs[$un]["sa_table"]=="factures"){
					 /*if($tab_champs[$un]["nom_du_champ"]=="frais_deplacement"){
												 
						 $temp = trouver_un_enregistrement("adresses","id",$desGet["iu_client_id"],null);
						 $distance =$temp["distance"] ;
						 $temp =  trouver_un_enregistrement("admin","id","1",null);
						 $taux = $temp["taux_frais_deplacement"];
						 $sa_valeur = number_format($desGet["nb_visites"]*($distance*$taux),0);
						 		 
					 }
					 
					  if($tab_champs[$un]["nom_du_champ"]=="sous-total"){
						  $sa_valeur = $desGet["iu_sous-total"]+=$desGet["iu_frais_deplacement"];
					  }
					 */
				 }
				 
            break;
            case "tim": //heure
					$typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
					$classe_validation = substr($classe_validation,-1) == "\"" ? substr($classe_validation,0,-1)." heure\"":" class =\"heure\"";
            default:
            
                  $typeDInput =in_array($tab_champs[$un]["nom_du_champ"],$GLOBALS["champs_ineditables"][$tab_champs[$un]["sa_table"]])?"hidden":"text";
                  break;
        }
        
        switch ($typeDInput){
         case "text":
			 if(substr($tab_champs[$un]["type"],0,3)== $int OR  substr($tab_champs[$un]["type"],0,3) == "dec"){
				 $l_enonce .= "<input type = \"text\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"  maxlength=\"".$tab_champs[$un]["longueur"]."\" size = \"".$tab_champs[$un]["longueur"]."\"  value =\"".$sa_valeur."\"".$classe_validation.$son_action." style= \"text-align: right;\" onkeypress=\"return handleEnter(this, event)\"/>".$description."\n"  ;
				 switch ($tab_champs[$un]["nom_du_champ"]) {
						  case "distance" :
							 //$l_enonce.=" <a id=\"map-distance\" name=\"map_distance\" href=\"http://maps.google.ca/maps?saddr=".htmlentities("14, rue St-André, Saint-André-Avellin, QC J0V 1W0")."&daddr=".htmlentities($GLOBALS["g_adresse"])."\" target=\"_blank\" >Google map</a>";
						  	$l_enonce.=" <a id=\"map-distance\" name=\"map_distance\" href=\"https://www.google.ca/maps/dir/14+Rue+St-André,+Saint-André-Avellin,+QC+J0V+1W0/".$GLOBALS["g_adresse"]."\" target=\"_blank\" >Google map </a>";//rg 2015-08-06 ajuster é google
						  	
						  	$GLOBALS["g_adresse"]="";
						  break;																																										 
			   }
		   }
			else{
				$l_enonce .= "<input type = \"text\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"  maxlength=\"".$tab_champs[$un]["longueur"]."\" size = \"".$tab_champs[$un]["longueur"]."\"  value =\"".$sa_valeur."\"".$classe_validation.$son_action." onkeypress=\"return handleEnter(this, event)\" />".$description."\n"  ;
		   }
				
            break;
         case  "textarea" :
            $l_enonce .= "<textarea id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" rows=\"2\" cols=\"80\"  value =\"".$sa_valeur."\" class=\"form-textarea\" >".$sa_valeur."</textarea>".$description."  \n"  ;
            break;
         case "fichier":
             $l_enonce .= " $sa_valeur<br/><input type = \"file\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"  size = \"62\"  ".$classe_validation.$son_action."  />".$description."  \n"  ;  //accept=\"pdf/text\"
         break;
         case  "grand_textarea" :
         if ( ($tab_champs[$un]["nom_du_champ"]== "note" && $tab_champs[$un]["sa_table"]=="visites") && !empty($tab_champs[$un]["valeur"])){
			 $l_enonce .= "<input type = \"hidden\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."O\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."O\"    value =\"".$sa_valeur."\"".$classe_validation.$son_action." />note originale : ".$sa_valeur."<br/>\n"  ;	
			$sa_valeur= "";
		 }
            $l_enonce .= " <textarea name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" rows=\"3\" cols=\"80\"  value =\"".$sa_valeur."\" class=\"form-textarea\">".$sa_valeur."</textarea>".$description."  \n"  ;
            break;
         case "select" :
            $l_enonce .= $description."\n"  ;
         break;
         case "id" :
         break;
         case "hidden":
         //echo "<i>".$tab_champs[$un]["nom_du_champ"]."</i>";
         $pos= strpos($l_enonce,"name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"");
         if ($pos === false){
			 if (!empty($GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]]) && array_key_exists("faire_la_liste", $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]])) {
								  $temp = $GLOBALS["listes"][$tab_champs[$un]["sa_table"]][$tab_champs[$un]["nom_du_champ"]];
								  $une_liste = faire_la_liste($temp["faire_la_liste"],$temp["cle"],$temp["val"],NULL);
								$l_enonce="<div class=\"form-item\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\">\n<label id=\"l-".$tab_champs[$un]["nom_du_champ"]."\" for=\"".$tab_champs[$un]["nom_du_champ"]."\">".$tab_champs[$un]["nom_du_champ"]."</label><input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n"
										.$une_liste[$sa_valeur]."";
								  
			  }
			  else{
					$l_enonce .= "<input type = \"hidden\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\"    value =\"".$sa_valeur."\"".$classe_validation.$son_action." />".$sa_valeur.$description."\n"  ;	
				
				}
         }
         else {
			
	
				 $l_enonce."<div class=\"form-item\" id=\"iu-".$tab_champs[$un]["nom_du_champ"]."\">\n<label id=\"l-".$tab_champs[$un]["nom_du_champ"]."\" for=\"".$tab_champs[$un]["nom_du_champ"]."\">".$tab_champs[$un]["nom_du_champ"]."<input type = \"hidden\" name = \"iu_".$tab_champs[$un]["nom_du_champ"]."\" value =\"".$sa_valeur."\"/>\n"
										.$sa_valeur."</label>";
				
			 
		 }
         break;
         default:
         break;
        }
      }
     
    return $l_enonce."</div> \n";
}



/*pour formuler une requete é partir des choix dans un formulaire

retourne une requete sous forme de chaine

*/

function formuler_requete($desGet){
//echo montrerUnTableau($desGet,"730");
/*formulation d'une requete é partir des choix dans un formulaire
        */
        $prefix="edit-";
        $requete = "";
        $leTri="";
        $conditions = 0; //aucune condition
        $where = " WHERE ";
        $table = "";
        $dateDebut = null;
        $dateFin = null;
        $recherche_texte = null;
        $champDate = "";
        $limite = " LIMIT 0, 20";
        $nbEnrTotal=0;
        if(isset($desGet["table"])){
			//RG 2015-03-26 correction temporaire du bug table.nom_champ
			foreach($desGet as $cle => $valeur){
				if(strstr(substr($cle,10),"_")){
					$pos_=strpos(substr($cle,10),"_");
					//echo "<b>".substr($cle,10,strlen($desGet["table"])+1)."</b> = ".$desGet["table"]."_  <br>";
					if(substr($cle,10,strlen($desGet["table"])+1)== $desGet["table"]."_"){
						unset($desGet[$cle]);
						$desGet[ substr($cle,0,10).str_replace("_",".",substr($cle,10))]=$valeur;
						//echo "desGet[ ".substr($cle,0,10).str_replace("_",".",substr($cle,10))."] = ".$desGet[ substr($cle,0,10).str_replace("_",".",substr($cle,10))]."<br>";
					}
				}
			}
			
			
	        // déterminer les champs et la requete SELECT
	       
			$champs = array();
			$champs = (isset($GLOBALS["champs_liste"][$desGet["table"]]["champs"]))?$GLOBALS["champs_liste"][$desGet["table"]]["champs"]:$champs;
			foreach($champs as $cle=>$val){
				$val=str_replace($desGet["table"].".","",$val);
			}
			//$where .= isset($GLOBALS["wherePourVoir"][$desGet["table"]])?$GLOBALS["wherePourVoir"][$desGet["table"]]." AND ":"";
	        // if(empty($GLOBALS["champs_liste"][$desGet["table"]]["req"])){
				if (count($champs)){
					
					
					$requete = "SELECT ";
					foreach($champs as $unChamp){
						$requete.= $unChamp.", ";
					}
					$requete = substr($requete, 0, strlen($requete)- 2);
					if(!empty($GLOBALS["champs_liste"][$desGet["table"]]["req"])){
						$requete .= $GLOBALS["champs_liste"][$desGet["table"]]["req"];
						$pos = strpos($requete,"ORDER ");
						
						if($pos === false){
						}
						else{
						//$pos = strpos($requete,"ORDER ");
						//$leTri = substr($requete,$pos );
						
						//if(!empty($desGet["op"]) ){
							$requete= substr($requete,0,$pos);
						//}
						//echo"<b> $requete. <br/><i>$leTri</i></b>";
					}
				}
					else{
						$requete.=" FROM ".$desGet["table"]."";
					}
				}
				
				else{
					$requete = "SELECT * FROM ".$desGet["table"]."";
				}
	        
	        if(!(isset($desGet["op"])) || $desGet["op"] <> "Initialiser"){
	        // Loop les elements
	            if (in_array("nul",$champs)||in_array($desGet["table"].".nul",$champs)){
	            	if (isset($desGet["edit-crit_nul"])) {
	            		$limite="";
	            	}
	            	else { $desGet["edit-crit_nul"]="0";}
				}
				
	            foreach($desGet as $cle => $valeur){
					
  	                if (substr($cle,0,10) == "edit-crit_") {
						 //echo "[$cle ] = $valeur<br> limite = $limite</br>";
	                     if (is_array($valeur)){ //plusieurs valeurs
	                        $nb_valeurs = 0;
	                        //ajuster la requéte pour options multiples
	                        
						   foreach($valeur as $index => $val){
							   
								  if ( $val == 'toutes' || $val == "tous"){
									 break;
								  }
								  elseif ( $val == 'libres' ){
									 $where .= " (".substr($cle,10,strlen($cle))." IS NULL OR ".substr($cle,10,strlen($cle))." = \"\"  ";
								  }
								  elseif ( $val == 'non-libres' ){
									 $where .= substr($cle,10,strlen($cle))." > \"a\" ";
								  }
								  else {
									 if($nb_valeurs){
										 $where .= " OR ".substr($cle,10,strlen($cle))." = \"".$val."\" ";
									 }
									 else{
										 $where .= " (".substr($cle,10,strlen($cle))." = \"".$val."\" ";
									 }
								  }
								  $nb_valeurs ++;
						   }
	                        
	                        if($nb_valeurs ){
	                          $where .= ") AND ";
	                        }
	                        /*else{
	                          $where .= " AND ";
	                        }*/
	                       } // fin plusieurs valeurs
	                     else{ //une seule valeur
	                        
	                        if (substr($valeur,0,3) <> "tou"){ //pas tous ou toutes
	                          if(substr($cle,10,strlen($cle))=="jour"){
	                              $where .= " DATE_FORMAT(la_date, \"%W\") = \"$valeur\" AND ";
	                          }
	                          else{
								 
	                            if( strlen($valeur)){//le champ n'est pas vide
	                                $conditions ++;
	                                
	                                if ((substr($valeur,0,5)==" BETW") || strpos($valeur,"<") || strpos($valeur,">")){
	                                      $where .= substr($cle,10,strlen($cle))." ".$valeur." AND ";
	                                }
	                                elseif($valeur =="NULL"){
	                                      $where .= substr($cle,10,strlen($cle))." IS NULL AND ";
	                                }
	                                elseif($valeur =="NOT NULL"){
	                                      $where .= substr($cle,10,strlen($cle))." IS NOT NULL AND ";
	                                }
	                                elseif ($cle == "edit-crit_debut"  ){ //les dates	                                     
	                                  $dateDebut = $valeur;
	                                  $champDate = $GLOBALS["recherche_date"][$desGet["table"]];
	                                }
	                                elseif ($cle == "edit-crit_fin"){ //les dates
	                                  $dateFin = $valeur;
	                                }
	                               
	                                elseif ($cle == "edit-crit_cherche"){ //pour les recherches de texte
										 
	                                  if( isset($GLOBALS["index"][$desGet["table"]])){
	                                      $recherche_texte = true;
	                                      $chaine_a_trouver = $valeur;
	                                  }
	                                   else{
	                                
	                                }
	                                }
	                                else{
										
										if(strpos($where,"WHERE") && !empty($GLOBALS["champs_liste"][$desGet["table"]]["req"])){
											//echo "where avant<b> $where</b>";
											$pos = strpos($GLOBALS["champs_liste"][$desGet["table"]]["req"],"JOIN");
											$where = $pos?$where:str_replace(" WHERE "," AND ",$where);
											//echo "where aprés<b> $where</b> cle= <b>".substr($cle,10,strlen($cle))."</b><br><br></b>";
										}
										//echo "cle = $cle et where avant  = $where<br>";
										$where .= ($cle == "edit-crit_nul")?$desGet["table"].".nul = \"".$valeur."\" AND ":substr($cle,10,strlen($cle))." = \"".$valeur."\" AND ";
									     //echo "wherrrrrrrrre = $where<br>";                         
	                                  
	                                }
	                             }
	                          }
	                        }
	                     }
	
	                   //else{ $where .=" AND ";}
	                }
	            }//foreachh
	            
	            $leTri = (isset($desGet["edit-tri"]))?" ORDER BY ".str_replace(","," ".$desGet["edit-asc_desc"].",",$desGet["edit-tri"])." ".$desGet["edit-asc_desc"]:"";
			}//!(isset($desGet["op"])) || $desGet["op"] <> "Initialiser")
			elseif ($desGet["op"] == "Initialiser"){
				$limite="";
			}
	
	        if (isset($dateDebut) || isset($dateFin)){ //on a une des dates
				$uneDate="";
				$signe =array(">"=>" (".$desGet["table"].".$champDate > \"".$uneDate."\" ) AND ","<"=>" (".$desGet["table"].".$champDate < \"".$uneDate."\" ) AND ");

				if(array_key_exists(substr($dateDebut, 0, 1),$signe)){
					$uneDate= substr($dateDebut, 1);
					$signe =array(">"=>" (".$desGet["table"].".$champDate > \"".$uneDate."\" ) AND ","<"=>" (".$desGet["table"].".$champDate < \"".$uneDate."\" ) AND ");
					$where .= $signe[substr($dateDebut, 0, 1)];
				
					if(array_key_exists(substr($dateFin, 0, 1),$signe)) {
						$uneDate= substr($dateFin, 1);
						$signe =array(">"=>" (".$desGet["table"].".$champDate > \"".$uneDate."\" ) AND ","<"=>" (".$desGet["table"].".$champDate < \"".$uneDate."\" ) AND ");
						$where .= $signe[substr($dateFin, 0, 1)];
					} 
				}
				else{ 
				   $dateFin = ($dateFin < $dateDebut )? $dateDebut:$dateFin;
				   $where.= " (".$desGet["table"].".$champDate BETWEEN \"".$dateDebut."\" AND \"".$dateFin."\") AND ";
			   }
	           
	        }
	        if (isset($recherche_texte)){
	           $indexes = $GLOBALS["index"][$desGet["table"]];
	           $lesChamps = array();
	           $trouve=true;
	           $temp =substr($cle,12);
	           while ($trouve):
	               $trouve = strrpos($temp, ".");//cherche le .
	               if ($trouve) {
	                 $lesChamps[]= substr($temp,$trouve + 1);
	                 $temp = substr($temp,0,$trouve);
	               }
	           endwhile;
	           //WHERE MATCH(titre,texte) AGAINST('texte é trouver')
	           $where .= " MATCH(";
	           foreach( $indexes as $unChamp){
	              $where.= $unChamp.",";
	           }
	           $where = substr($where,0,strlen($where)-1).") AGAINST (\"$chaine_a_trouver\") AND ";
	        }
	         //echo "le tri = $leTri<br/>";                                   
	        if (!strlen($leTri)){
	            $leTri= isset($GLOBALS["le_tri"][$desGet["table"]])?" ORDER BY ".$GLOBALS["le_tri"][$desGet["table"]]:"";
	        }
	        //le POST["tri"] contient le nom du champ é trier
	        //le POST["asc_desc"] contient l'ordre de tri
	        //
	        
	        //$leTri = (isset($desGet["edit-tri"]))?" ORDER BY ".str_replace(","," ".$desGet["edit-asc_desc"].",",$desGet["edit-tri"])." ".$desGet["edit-asc_desc"]:"";
	        
	        
	        // si une condition 
	        $te = $where;
	        //echo "where avant $te";
	        $where = ($te == " WHERE  " || $te == " WHERE  AND ")?"":$where; 
	        //echo " - where aprés $te<br><b>$requete</b>";
	        $ret =($conditions > 0 )? substr($requete.$where,0,strlen($requete) + strlen($where) - 5).$leTri : $requete.$leTri;
	        /*if ($conditions == 0 && array_key_exists("nul",$champs)){
				$ret= $requete."  AND null =0 ".$leTri;
				
			}
			* */
	        //echo "where = $where<br/>";
	/*	} 
		else{
			$requete = "SELECT ";
				foreach($champs as $unChamp){
					$requete.=  $unChamp.", ";
					}
				$requete = substr($requete, 0, strlen($requete)- 2);
			$ret = $requete.$GLOBALS["champs_liste"][$desGet["table"]]["req"];
			echo $ret;
		}
	        return  $ret;
	        
	        
        }*/
       // echo "<b>$ret.$limite</b>  986<br><br>";
        
        return  $ret.$limite;}
        else{//pas de table
        	
        	return false;
        }

}

function reutiliser_POST($les_posts){
/* cette fonction ecrit le code html pour retransmettre les éléments et
les valeurs des POST
param $_POST
retourne string*/

  $temp = "";
  foreach($les_posts as $cle => $valeur){
     if((substr($cle,0,3) == "iu_") || $cle == "soumettre" ){
     }
     else{
        //echo "cle = ".$cle."<br/>";
        if(is_array($valeur)){
             foreach($valeur as $index => $val){
                 $temp .= "<input type='hidden' name='".$cle."[".$index."]' value= '".$val."'}/>";}
         }
         else {
          $temp.= "<input type = 'hidden' name = '$cle' value = '".htmlentities($valeur)."' />\n";
         }
     }
  }
 return $temp;

}

function enteteDePage($titre,$logo){
  date_default_timezone_set('America/New_York');
  $nouv_titre = str_replace("_"," ",$titre);
  /*return "<br/><table class='accueil' width = '800px' align='center'>\n\t<tr><td width = '200px' align = 'center'>\n".
        "<img src=\"images/$logo\" alt=\"AHMA\" title=\"logo ahma\"   border=\"0\" /></td>\n".
        "<td ><h3>".$nouv_titre."</h3><br/> ".$GLOBALS["jours"][date("l")]." ".date("j")." ".$GLOBALS["mois"][date("n")-1]." ".date("Y")."</td>\n".
        "</tr></table><br/>\n";
   */
   return "<div id=\"header-wrapper\">
        <div id=\"header\" class=\"clearfix\">

			
             
				<div id=\"logo\" >
				  <a href=\".\" title=\"Accueil\"><img align=\"left\" src=\"images/horizontal_couleur.jpg\" height= \"180\" width=\"229\" alt=\"Accueil\" /></a>
				</div>
             
			 <!-- <div id=\"header-middle\"></div>	-->					
			<!-- /header-middle -->
			  <!-- <div id=\"header-last\"></div> -->
			  <!-- /header-last -->
		</div><!-- /header -->
      </div><!-- /header-wrapper -->
      <div id=\"preface\">
                <div id=\"preface-wrapper\" class=\"prefaces-0 clearfix\">
                    <div id=\"administration\"> $nouv_titre </div>
                 </div><!-- /preface-wrapper -->
         </div><!-- /preface -->";
}


/*
pour formuler le pied de page
	param.	tableau des menus
			   item actif
retourne une string
*/

function piedDePage($pages, $une_page){
	
  $bas = "<div id=\"footer\" class=\"clearfix\">\n
<div class=\"block-wrapper odd\">\n\t
<div id=\"block-menu-secondary-links\" class=\"block block-menu\">\n\t\t
<div class=\"content\">\n\t
<ul class=\"menu\">\n\t\t";
$taille = sizeof($pages);
$actif="leaf";
$a_actif="";
	for ($i = 0;  $i < $taille;  ++$i) {	
		$a_actif = $pages[$i]==$une_page ?"active" :"";
		switch ($i) {
			case 0:
				$actif= $pages[$i]==$une_page?"leaf first active-trail" :"leaf first";
				$bas.="<li class=\"$actif\">";
				break;
			case $taille:
			   $actif= $pages[$i]==$une_page?"leaf last active-trail" :"leaf last";
				$bas.="<li class=\"$actif\">";
				break;
			default:
			   $actif= $pages[$i]==$une_page?"leaf active-trail" :"leaf";
			   $bas.="<li class=\"$actif\">";
		}
		$bas.="<a href=\"index.php?titre=$pages[$i]\" title=\"$pages[$i]\"  target=\"_top\" class=\"$a_actif\" >$pages[$i]</a></li>";
		
}
$bas.="</ul>\n          
</div>\n
</div>\n
</div>\n
<div class=\"block-wrapper even\">\n
<div id=\"block-system-0\" class=\"block block-system\">\n
<div class=\"content\">\n
<!--<a href=\"http://drupal.org\"><img src=\"/allbert0/misc/powered-blue-80x15.png\" alt=\"Créé avec l&#039;aide de Drupal, un systéme de gestion de contenu &quot;opensource&quot;\" title=\"Créé avec l&#039;aide de Drupal, un systéme de gestion de contenu &quot;opensource&quot;\" width=\"80\" height=\"15\" /></a>  -->\n
</div>\n
</div>\n
</div>\n
Profitez de nos services<br/>cellulaire 819.983.8642\n
	</div><!-- /footer -->\n";
  
   return $bas;
}


function formatter_output($valeur,$nom_du_champ,$substitution){
   $retour ="";
   //$valeur =strlen($valeur)?$valeur:" ";
   $substitue= (isset($substitution))?$substitution:$valeur;
   switch ($nom_du_champ) {
      case "courriel" :
           $retour =(isset($valeur)&& strlen($valeur))?"<a class = 'select' href='mailto:$valeur'>$valeur</a>":"&nbsp;";
      break;
      case "tel":
           $retour =(isset($valeur)&& strlen($valeur))?substr($valeur,0,3).".".substr($valeur,3,3).".".substr($valeur,6):"&nbsp;";
      break;
      case "fax":
           $retour =(isset($valeur)&& strlen($valeur))?substr($valeur,0,3).".".substr($valeur,3,3).".".substr($valeur,6):"&nbsp;";
      break;
      case "cell":
           $retour =(isset($valeur) && strlen($valeur))?substr($valeur,0,3).".".substr($valeur,3,3).".".substr($valeur,6):"&nbsp;";
      break;
      case "cp":
           $retour =(isset($valeur)&& strlen($valeur))?substr($valeur,0,3)."-".substr($valeur,3,3):"&nbsp;";
      break;
      case "debut":
           //debut peut étre une heure 17:50:00 ou une date 2008-09-26
           if(strlen($valeur) == 8){//on ne formatte que l'heure
             $retour =(isset($valeur))?substr($valeur,0,strlen($valeur)-3):"";
           }
           else{
             $retour = $valeur;
           }
      break;
      case "fin":
           //fin peut étre une heure 17:50:00 ou une date 2008-09-26
           if(strlen($valeur) == 8){//on ne formatte que l'heure
             $retour =(isset($valeur))?substr($valeur,0,strlen($valeur)-3):"";
           }
           else{
             $retour =(isset($valeur)&& strlen($valeur))? $valeur:"&nbsp;";
           }
      break;
      case "h_fin":
           $retour =(isset($valeur))?substr($valeur,0,strlen($valeur)-3):"";
      break;
      case "la_date":
           $ce_mois = date("n",mktime(0,0,0,substr($valeur,5,2),substr($valeur,8),substr($valeur,0,4)))-1;
           $retour =(isset($valeur))?substr($valeur,8)." ".substr($GLOBALS["mois"][$ce_mois],0,3):"";
      break;
      case "naissance":
           $retour =(isset($valeur))?str_replace("-",".",$valeur):"";
      break;
      case "url":
      		$verif_image = verif_img($substitue,"liens");
           $retour =(isset($valeur))?"<a class = 'select' href=\"".verif_url($valeur)."\" target = \"_blank\" >".$verif_image."</a>":"";
      break;
      case "description":
      	    if($substitue == "image"){
           		$retour = verif_img($valeur,"liens");
      	    }
      	    else{
      	    	$retour =(isset($valeur)&& strlen($valeur))? $valeur:"&nbsp;";
      	    }
      		 break;
      case "modifie":
           $retour =($valeur)?"<font color = 'red'><b>* </b></font>":"";
      break;
      case "fichier":
           $path = strrchr($valeur,"\\");
           $fichier = ($path)?substr($path,1):$valeur;
           $retour =(isset($valeur))?"<a class = 'select' href=\"./docs/".$fichier."\" target = \"_blank\" >".$substitue."</a>":"";
			//echo "<p class = 'echec'>retour = ".substr($retour,1)."</p><br/>";
           break;
	  case "prix":
           $retour =(isset($valeur) && strlen($valeur))?$valeur."$":"&nbsp;";
      break;
      default:
           $retour =(isset($valeur)&& strlen($valeur))? $valeur:"&nbsp;";
      break;
   }

   return $retour;
}



function omettre_champs($une_Table,$un_regroupement,$lesChamps){
//les champs qui sont affichés é la place d'un autre sont définis $affiche[nom_de_table][nom_du_champ]
//le regroupement est passé en GET
//cette fonction enleve ces valeurs du tableau qui détermine les champs é afficher
//retourne le tableau $lesChamps  modifié ou non
  $retour = $lesChamps;
  $omettre = array();
  if( isset($GLOBALS["affiche"][$une_Table])){
    //$retour["substituer"]= $GLOBALS["affiche"][$une_Table];
    foreach($GLOBALS["affiche"][$une_Table] as $cle => $val){
     $uneDescription = $val;
     $index = array_search($val, $retour);
     unset($retour[$index]);
    }
  }
  if( isset($GLOBALS["regrouperPourVoir"][$une_Table])){
     $index = array_search($GLOBALS["regrouperPourVoir"][$une_Table], $retour);
     unset($retour[$index]);
  }
  foreach ($retour as $cle => $val){
          if ( strstr($val," as ")){
              $retour[$cle] = substr( strstr($val," as "),4);
          }
  }
  return $retour;
}

//pour traduire en franéais l'énoncé where et le tri
//d'une requéte
function traduire_criteres($une_req){

   $a_enlever = array("(",")","WHERE ","'","DATE_FORMAT","%W",";", "definitif = 0 AND ");
   $debut = strpos($une_req,"WHERE ")?strpos($une_req,"WHERE "):strpos($une_req,"ORDER ") ;
   if ($debut){
      $traduit = substr($une_req,$debut);
      $traduit = str_replace($a_enlever, "  ",$traduit);
      $traduit = str_replace(" BETWEEN ", " entre ",$traduit);
      $traduit = str_replace(" AND ", " et ",$traduit);
      $traduit = str_replace(" OR ", " ou ",$traduit);
      $traduit = str_replace(" || ", " ou ",$traduit);
      $traduit = str_replace(" IS NOT NULL", " n'est pas vide",$traduit);
      $traduit = str_replace("IS NULL", " est vide ",$traduit);
      $traduit = str_replace("ORDER BY", "<br/>trié sur ( ",$traduit);
      $traduit = str_replace("ASC", ") croissant ",$traduit);
      $traduit = str_replace("DESC", ") Décroissant ",$traduit);
      foreach($GLOBALS["jours"] as $cle => $valeur){
         $traduit = str_replace($cle, $valeur,$traduit);
      }
   }
   else{
       $traduit = "";
   }
  return $traduit;
}

function stripper_les_POST($des_POST_ou_GET){
   $strippe= array();
   foreach($des_POST_ou_GET as $cle => $valeur){
       if(is_array($valeur)){
         $strippe[$cle]= array();
         foreach($valeur as $index => $val){
             $strippe[$cle][$index] = StripSlashes($val);}
             //echo "avant[$cle][$index] = $val <b>strippe[$cle][$index] = ".StripSlashes($val)."</b><br/>";
      }
      else {
       if ( $cle == "iu_fichier"){
          $strippe[$cle] = str_replace("\\\\\\","\\",$valeur);
          $strippe[$cle] = str_replace("\\\\","\\",$valeur);
          //echo "avant[$cle] = $valeur <b>strippe[$cle] = ".$strippe[$cle]."</b><br/>";
       }
       else {
          $strippe[$cle] = StripSlashes($valeur);
       }

       }
   }
   return $strippe;
}

function qui_insere(){
    $insere = array();
    $tables = array();
    $con = new Lien($GLOBALS["origine"][$_POST['origine']], $_POST['passe']);
    $un_lien= $con->getLien();
    /* $un_lien = mysqli_connect("127.0.0.1",$GLOBALS["origine"][$_POST['origine']],$_POST['passe'],"allbert")
    or die("<br/><br/><center><b>pas d'accés aux données consulter le responsable</b></center>"  );
 */
    mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données". mysqli_error($un_lien));


    $req = "SHOW TABLES;";
    $resultat = mysqli_query($un_lien,$req) or die(mysqli_error($un_lien)." ::<br/>".$req);
   /* Entreposer les résultats dans le tableau */
   while ($ligne = mysqli_fetch_assoc($resultat)) {
         $tables[$ligne["Tables_in_".$GLOBALS["accueil"]["base"]]] = array();
   }

   /* Libération des résultats */
   mysqli_free_result($resultat);
   mysqli_close($un_lien);
   unset($un_lien);
   $les_champs= array();

   foreach($tables as $cle => $val){
     $les_champs = show_colonnes($cle, null);

     $con = new Lien($GLOBALS["origine"][$_POST['origine']], $_POST['passe']);
     $un_lien= $con->getLien();

     mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données". mysqli_error($un_lien));
     //tester insert
     $insere[$cle]["insere"] = 1;
     if(isset($les_champs["min"])){
        $req = "INSERT INTO $cle (id) VALUES(1);";
        $code = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_errno($un_lien);
        $resultat = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_errno($un_lien)." ".mysqli_error($un_lien);
        //echo "resultat de $req = $resultat<br/>";
        if ($code == 1142){
           $insere[$cle]["insere"] = 0;
        }
        //mysqli_free_result($resultat);
      }

    }
    mysqli_close($un_lien);
    unset($un_lien);


   return $insere;
}

function update_quoi(){

     $update = array();
     $les_champs= array();
     $les_champs = show_colonnes($_REQUEST['table'], null);
     $con = new Lien($_REQUEST['qui'],$_REQUEST['passe']);
     $un_lien= $con->getLien();

     /* $un_lien = mysqli_connect("127.0.0.1",$_REQUEST['qui'],$_REQUEST['passe'],"allbert")
     or die("<br/><br/><center><b>pas d'accés aux données consulter le responsable</b></center>"  );
 */
     mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données". mysqli_error($un_lien));
     //tester insert

     //$update[$cle]["update"]=array();
     //$req = "SHOW GRANTS FOR ".$GLOBALS["origine"][$_POST["origine"]]."@127.0.0.1;";
     $req = "SHOW GRANTS";
     $code = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_errno($un_lien);
     $resultat = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_errno($un_lien)." ".mysqli_error($un_lien);
     $ap = array();
     $base = "";
     $la_table = "";
	
    while ($ligne = mysqli_fetch_assoc($resultat)) {
    	
       $chaine  = $ligne["Grants for ".$_REQUEST['qui']."@127.0.0.1"]?$ligne["Grants for ".$_REQUEST['qui']."@127.0.0.1"]:$ligne["Grants for ".$_REQUEST['qui']."@%"];
      // echo "chaine = $chaine<br/>";
       if(strstr($chaine,"`")){
         for ($i=0;$i < 4;$i++){
            if($i == 0){
              $ap[$i] = strpos($chaine,"`")+1;
            }
            else{
              $ii = $i -1;
              $ap[$i] = strpos($chaine,"`",$ap[$ii] )+1;
            }
         }

         $base = substr($chaine,$ap[0],$ap[1]-$ap[0]-1);
         $la_table = substr($chaine,$ap[2],$ap[3]-$ap[2]-1);
         if( $la_table == $_REQUEST["table"]){
             if (strpos($chaine,"UPDATE,") || strpos($chaine,"UPDATE ON")){
                foreach($les_champs as $cle => $val){
                   if (is_array($val)){
                      foreach($val as $ind => $un_champ){
                         if ($ind =="nom_du_champ"){
                            $update[$un_champ]=1;
                         }
                      }
                   }
                }
             }
             elseif( strpos($chaine,"UPDATE (")){
                $liste = substr($chaine,strpos($chaine,"(") +1,strpos($chaine,")")- strpos($chaine,"(")-1);
                $liste = str_replace(", "," ",$liste);
                $tableau_champ = explode(" ", $liste);
                foreach($les_champs as $cle => $val){
                    if (is_array($val)){
                      foreach($val as $ind => $un_champ){
                         if ($ind =="nom_du_champ"){
                            if (in_array($un_champ,$tableau_champ)){
                               $update[$un_champ]=1;
                            }
                            else{
                                $update[$un_champ]=0;
                            }
                         }
                       }
                   }
                }
             }
         }
       }
    }

    mysqli_free_result($resultat);

   mysqli_close($un_lien);
   unset($un_lien);


   return $update;
}



function verif_acces(){
     $con = new Lien($_REQUEST['qui'],$_REQUEST['passe']);
     $un_lien= $con->getLien();
     mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("<br/><br/><center><b>Ne trouve pas la base de données</b> (".mysqli_error($un_lien).")</center>");
     
     $req = "SHOW GRANTS FOR `".$_REQUEST["qui"]."`@127.0.0.1";
     $code = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_error($un_lien);
     $resultat = mysqli_query($un_lien,$req)?mysqli_query($un_lien,$req):mysqli_error($un_lien)." ".mysqli_error($un_lien);
     //echo $req;
     $ap = array();
     $base = "";
     $la_table = "";
     $grants = array();
    while ($ligne = mysqli_fetch_assoc($resultat)) {
       $chaine  = $ligne["Grants for ".$_REQUEST["qui"]."@127.0.0.1"];
       if(strstr($chaine,"`")){
         for ($i=0;$i < 4;$i++){
            if($i == 0){
              $ap[$i] = strpos($chaine,"`")+1;
            }
            else{
              $ii = $i -1;
              $ap[$i] = strpos($chaine,"`",$ap[$ii] )+1;
            }
         }

         $base = substr($chaine,$ap[0],$ap[1]-$ap[0]-1);
         $la_table = substr($chaine,$ap[2],$ap[3]-$ap[2]-1);
         $grants[$la_table] = $chaine;

       }
     }

     mysqli_free_result($resultat);

    mysqli_close($un_lien);
    unset($un_lien);
     /*
      * if (isset($GLOBALS["acces"][$GLOBALS["origine"][$_POST["origine"]]])){
     	$grants["fichiers"]= $GLOBALS["acces"][$GLOBALS["origine"][$_POST["origine"]]];
     }
     */

     return $grants;
}

function courriel($from,$dest, $sujet, $message){

         // Pour envoyer un mail HTML, l'en-téte Content-type doit étre défini
         $headers  = "MIME-Version: 1.0\n";
         $headers .= "Content-type: text/html; charset=iso-8859-1\n";
         $headers .="From: ".$from."\n";
         $headers .='Reply-To: '.$from."\n";

         return mail($dest, $sujet, $message,$headers);

}

function transfert_fichier(){
    $ftp_server = "hockeyaylmer.com";
    $ftp_user_pass = "d1ran1@862";
    $ftp_user_name = "ahma";
    $fichier = $_FILES['iu_fichier'];
    $destination = "";
    $message = "";
    $repertoire = $_POST["repertoire"]?$_POST["repertoire"]:"docs";
    $message = "";
    //$ext_images = array("jpg","jpeg","gif");
    $ext_rejetees = array("htm","exe","cgi");
    //$type = substr(strrchr($fichier["type"], "/"), 1 );
    $type = substr(strrchr($fichier["name"], "."), 1 );
    //echo $type." tyyyy<br/>";
    if($repertoire == $GLOBALS["repertoires"]["images"]){
       if (!in_array($type,$GLOBALS["ext_images"])){
          $message = "Le fichier doit étre de type: <b><i>";
          foreach($GLOBALS["ext_images"] as $ext){
              $message.= "&nbsp;'".$ext."'";
          }
          $message.="</i></b>";
          return $message;
       }
       else{
          if($_POST["origine"] =="gerant"){ 
             $fichier["name"] = renommer($_POST["categorie"]." ".$_POST["equipe"]." ".$_POST["saison"].".".$type);
          }
       }
    }
    foreach ( $ext_rejetees as $extension){

         if( strstr(substr($fichier["name"],strlen($fichier["name"])-4),$extension)){
            $message = "Transfert refusé<br/>le ".date("Y.m.d H:i:s")."<br/>";
            foreach( $fichier as $cle => $val){
               $message .= "<i>_FILES[$cle]</i> = <b>$val</b><br/>";
            }
            foreach( $_POST as $cle => $val){
               $message .= "<i>POST[$cle]</i> = <b>$val</b><br/>";
            }
            $fait = courriel($GLOBALS["accueil"]["base"],"renegelinas1@gmail.com","Transfert refusé",$message);
            $message = "Impossible de télécharger ".$fichier["name"];
            return $message;
         }
    }
    //echo "fichier = ".$fichier["name"]." et fichier[tmp_name] = ".$fichier['tmp_name']." <br/>";
    /* Connexion */
    $conn_id = ftp_connect($ftp_server);

    /* Identification */
    $login_result = ftp_login($conn_id, $ftp_user_name,$ftp_user_pass);

    /* Vérification de la connexion */
    if ((!$conn_id) || (!$login_result)) {
            //echo "La connexion FTP a échoué!\n";
            //echo "Tentative de connexion é $ftp_server avec $ftp_user_name.\n";
            //die;
            $message = "Impossible de transférer le fichier <i>".$original."</i> maintenant.";
            return $message;
        }
        else {
           // echo "Connecté sur $ftp_server, avec l'utilisateur $ftp_user_name<br/>";
        }
   //echo "Dossier courant : ". ftp_pwd($conn_id). "<br/>\n";

   /* Essai de changement de répertoire pour le dossier somedir */
       
   if (ftp_chdir($conn_id, "www/".$repertoire)) {
      $dossier_courant = ftp_pwd($conn_id);
     //echo "Le dossier courant est maintenant : ".$dossier_courant. "\n";
   }
   else {
    $message = "Impossible de changer le dossier courant.\n";
    return $message;
    }

    /* Liste les fichiers du dossier / */
    $fichiers = ftp_nlist($conn_id, ".");
    /*$f = 0;
    foreach($fichiers as $val){
        echo "$f fichier = $val<br/>";
        $f ++;
    }
    echo "fichier[name] = ".$fichier['name']."<br/>"; */
    if ( in_array($fichier['name'],$fichiers) && $repertoire == "docs"){
        $message =  "<b>".$fichier['name']."</b>&nbsp;existe déjé. Renommez votre fichier.";
        return $message;
    }
    
    else{
         //echo "délai d'expiration avant = ".ftp_get_option($conn_id, FTP_TIMEOUT_SEC);
         ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 300);
         //echo "<br/>d.lai d'expiration apres = ".ftp_get_option($conn_id, FTP_TIMEOUT_SEC);
         //$ret = ftp_nb_put($conn_id, $fichier['name'], $fichier['tmp_name'],  FTP_ASCII , FTP_AUTORESUME);

        /* Téléchargement d'un fichier  */
       //echo "<br/>destination_file = ".renommer($fichier['name'])." et source_file = ".$fichier['tmp_name']." <br/>";
        $upload = ftp_put($conn_id, renommer($fichier['name']), $fichier['tmp_name'],   FTP_BINARY);

        // Vérification de téléchargement
        if (!$upload) {
            $message = "Le téléchargement de ".$fichier['name']." a échoué!";
            return $message;
        }
        else {
	        if ($repertoire == "images" || $repertoire == "equipes"){
	    		foreach( $fichier as $cle => $val){
	               $message .= "<i>_FILES[$cle]</i> = <b>$val</b><br/>";
	            }
	            foreach( $_POST as $cle => $val){
	               $message .= "<i>POST[$cle]</i> = <b>$val</b><br/>";
	            }
	            $message.="<br/>repertoire = $dossier_courant<br/>merci";
	            $fait = courriel($GLOBALS["accueil"]["base"],"renegelinas1@gmail.com","Transfert de ".renommer($fichier['name'])." effectué",$message);
	            $message ="";
	    	}
        }
     }
    /* Fermeture de la connexion FTP */
    ftp_quit($conn_id);

     return $message ;
}

function ecrire_requete($une_req,$lesGet){
   $file = "requetes.sql";
   date_default_timezone_set('America/New_York');
   // Assurons nous que le fichier est accessible en écriture
   if (is_writable($file)) {

       // Dans notre exemple, nous ouvrons le fichier $file en mode d'ajout
       // Le pointeur de fichier est placé é la fin du fichier
       // c'est lé que $une_req sera placé
       if (!$handle = fopen($file, 'a')) {
           // echo "Impossible d'ouvrir le fichier ($file)";
            exit;
       }

       // Ecrivons quelque chose dans notre fichier.
       $une_req = strpos($une_req, ";")?$une_req:$une_req.";";
       if (fwrite($handle, $une_req."  -- ".$lesGet["qui"]." ".date("Y.m.d H:i:s")."\n") === FALSE) {
         // echo "Impossible d'écrire dans le fichier ($file)";
          exit;
       }
       
       //echo "L'écriture de ($une_req) dans le fichier ($file) a réussi";
       
       fclose($handle);
                       
   }
   else {
      // echo "Le fichier $file n'est pas accessible en écriture.";
   }
}

function renommer($unNom){
	$renomme ="";

  for($i=0; $i < strlen($test);$i ++){
    $t[$i] = substr($test,$i,1);
  }
  foreach( $t as  $lettre){
    $i = Ord($lettre);
    if(Chr($i) == "."){ $renomme.= Chr($i);}
    elseif($i > 47 && $i < 58 ){
       $renomme.=Chr($i);}
    elseif($i > 64 && $i < 91 ){
       $renomme.=Chr($i);}
    elseif($i > 96 && $i < 123 ){
       $renomme.=Chr($i);}
    elseif($i > 191 && $i < 198 ){
       $renomme.="A";}
    elseif($i == 199 ){
       $renomme.="C"; }
    elseif($i > 199 && $i < 204 ){
       $renomme.="E";}
    elseif($i > 203 && $i < 208 ){
       $renomme.="I";}
    elseif($i > 209 && $i < 215 ){
       $renomme.="O";}
    elseif($i > 216 && $i < 221 ){
       $renomme.="U";}
    elseif($i > 223 && $i < 230 ){
       $renomme.="a";}
    elseif($i == 231 ){
       $renomme.="c"; }
    elseif($i > 231 && $i < 236 ){
       $renomme.="e";}
    elseif($i > 235 && $i < 240 ){
       $renomme.="i";}
    elseif($i > 241 && $i < 247 ){ 
       $renomme.="o";}
    elseif($i > 248 && $i < 253 ){ 
       $renomme.="u";}
    else{ $renomme.="_";}
  }
  $temp = strtolower($renomme);
  return $temp;
}

function accentsMinuscules($unNom){
  $temp = strtolower($unNom);
  $remplacerCa = array("é","é","é","é","é") ;
  $par = array("é","é","é","é","é");
  for( $i=0;$i < count($remplacerCa); $i++){
      $temp = str_replace($remplacerCa[$i], $par[$i],$temp);
  }
  return $temp;
}

//pour afficher les enregistrements d'une autre table
//liés é un enregistrement choisi
function afficherInfosSupp($desGet){
	$retour="";
	//$desGet["iu_adresses_id"]= "";
	$dejaUneNote="";
	$dejaUneNoteVaccin="";
	$idVisite="";
	$nomSoin="";
	
	
	
	for($no =0; $no <= count($GLOBALS["infosSupp"][$desGet["table"]]); $no++){
		
		if(isset($GLOBALS["infosSupp"][$desGet["table"]][$no]["requete"])){
			
			$req = $GLOBALS["infosSupp"][$desGet["table"]][$no]["requete"];
			switch ($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]){
				case "visites";
					$req.= " WHERE adresses_id = ".$desGet["crit"]." AND (soins.id = visites.soins_id) AND nul = 0 ORDER BY la_date DESC";
					break;
				case "notes":
					//$req.= " WHERE adresses_id = ".$desGet["crit"]." AND note !=  \"\" AND (soins.id = visites.soins_id) AND nul = 0 ORDER BY la_date DESC";
					$req.= " WHERE adresses_id = ".$desGet["crit"]." AND note !=  \"\" AND (soins.id = visites.soins_id)  ORDER BY la_date DESC";
					//$desGet["iu_adresses_id"]= $desGet["crit"];
					//echo montrerUnTableau($GLOBALS["champs_ineditables"]["visites"], " 1515  ");
				break;
				case "pressions";
					$req.= " WHERE adresses_id = ".$desGet["crit"]."  AND nul = 0 ORDER BY la_date DESC";
				break;
				case "prescriptions";
					$req.= " WHERE adresses_id = ".$desGet["crit"]."  ORDER BY la_date DESC";
				break;
				case "notes_vaccins";
					$req.= " WHERE adresses_id = ".$desGet["crit"]."  AND nul = 0 ORDER BY la_date DESC";
				break;
			}
			
			
			//echo "<b>".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."</b> -- ".$req."<br/>";
			$con = new Lien($GLOBALS["pourVoir"], $GLOBALS["ppourVoir"]);
			$un_lien= $con->getLien();
			
			   mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
			$une_liste = array();
			$res_une_req = mysqli_query($un_lien,$req) or die(mysqli_error($un_lien)." ::<br/>$req");
			/* Entreposer les résultats dans le tableau */
			$i=0;
			while ($ligne = mysqli_fetch_assoc($res_une_req)) {
				 foreach($ligne as $cle => $val){
						if($i == 0){
							$noms_champ[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]][]= $cle;
						}
						 $une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]][$i][$cle] = $val;
						 //echo "$cle = $val<br/>";  
				}
						 $i++;
			}
			

			/* Libération des résultats */
			mysqli_free_result($res_une_req);

			//fermeture de la connexion
			mysqli_close($un_lien);
			if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"] == "visites"  ){
						$retour.="<form action=\"admin.php\"   method=\"post\" id=\"listes_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\"  >\n";
						$retour.="<input type=\"hidden\" name=\"form_id\" id=\"plusieurs_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" value=\"".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-infossup\"  />";
					}
			if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"] == "pressions"){
						$retour.="<form action=\"pression.php\"   method=\"post\" id=\"listes_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" target=\"_blank\" >\n";
						$retour.="<input type=\"hidden\" name=\"form_id\" id=\"plusieurs_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" value=\"".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-infossup\"  />";
					}
		    if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"] == "prescriptions"){
						$retour.="<form action=\"prescription.php\"   method=\"post\" id=\"listes_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" target=\"_blank\" >\n";
						$retour.="<input type=\"hidden\" name=\"form_id\" id=\"plusieurs_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" value=\"".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-infossup\"  />";
					}
			if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"] == "notes_vaccins"){
						$retour.="<form action=\"note_vaccin.php\"   method=\"post\" id=\"listes_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" target=\"_blank\" >\n";
						$retour.="<input type=\"hidden\" name=\"form_id\" id=\"plusieurs_".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-form\" value=\"".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."-infossup\"  />";
					}
			//echo montrerUnTableau($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]], $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]);
			$retour.="<fieldset  class=\"collapsible ".$GLOBALS["collapsed"][$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]]."\" ><legend ><a href=\"#\">".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]." ($i)</a> </legend>";
			
			/*foreach($une_liste as $ligne=>$valeurs){
				$retour.="<div>";
				   foreach($valeurs as $cle=> $val){
						$retour.="$val     ";
					}
					$retour.="</div>\n";
				}
				*/
					if(!empty($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]])){

					$retour.="<table class=\"sticky-header\" style=\"position: fixed; top: 0px;  visibility: hidden;\">\n
									<thead>\t\n
									  <tr>\t\t\n";
					//$retour.="<div id=\"page-manager-links\" class=\"links\"><ul class=\"links\">\n";
					foreach($noms_champ[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]] as $nom){
						if(in_array($nom,$GLOBALS["infosSupp"][$desGet["table"]][$no]["champs"])){
							$retour.="<th class=\"page-manager-page-type\" >$nom</th>\t\t\t\n";
						}
					}
					if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="pressions" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="notes_vaccins" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="prescriptions"){
						$retour.="<th class=\"page-manager-page-type\" style=\"text-align:center;\">imprimer</th>\t\t\t\n";
					}
					
					$retour.="<th>actions</th></tr></thead>\n</table>\n";
					
					
					 $retour.="<table id=\"page-manager-list-pages".$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]."\" class=\"sticky-enabled\" >\n
									<thead><tr>\n";
					foreach($noms_champ[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]] as $nom){
						if(in_array($nom,$GLOBALS["infosSupp"][$desGet["table"]][$no]["champs"])){
							$retour.="<th class=\"page-manager-page-type\" style=\"text-align:center;\">$nom</th>\t\t\t\n";	
						}
					}
					if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="pressions" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="notes_vaccins" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="prescriptions"){
						$retour.="<th class=\"page-manager-page-type\" style=\"text-align:center;\">imprimer</th>\t\t\t\n";
					}
					$retour.="<th>actions</th></tr></thead>\n<tbody>\n";
					
					
					$pair_impair ="odd";
					$items = 0;
					$i=0;
					foreach($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]] as $ligne=>$valeurs){
						$dejaUneNote="";
						
						if($valeurs["nul"]){
						    $retour.="<tr class=\"page-task-blog page-manager-nul\" style=\"color:#cc0000;\" >\n";        
						}
						else{
						    $retour.="<tr class=\"page-task-blog page-manager-disabled ".$pair_impair."\" >\n";}
						  foreach($valeurs as $cle=> $val){
							  switch ($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]){
								  case  "visites":
    								  $nul = $valeurs["nul"];
    								  $idVisite= $valeurs["id"];//pour trouver si on a déjé une notes_vaccins
    								  $nomSoin=$valeurs["nom"];//pour trouver si le nom du soin = Administration de vaccin
    								  if ($cle <>"note"){
    									if($cle =="facture_id"){
    								  
    										 if(empty($val)){//aucun no de facture
    											$retour.= "<td class=\"page-manager-page-type\" style=\"text-align:center;\"><input type = \"checkbox\" name =\"visite_no_".$valeurs["id"]."\" value=\"".$valeurs["id"]."\"  checked /></td>\n";
    											$items ++;
    										}
    										else{
    											$retour.= "<td class=\"page-manager-page-type\" style=\"text-align:center;\">$valeurs[$cle]</td>\n";
    										}
    									 }
    									 
    									 else{
    										 $retour.= "<td class=\"page-manager-page-type\" style=\"text-align:center;\"><input type = \"hidden\" name =\"iu_".$cle."[".$valeurs["id"]."]\" value=\"$val\" />$valeurs[$cle]</td>\n";
    									 
    									 }
    								 }
    								 else{
    									 $dejaUneNote=$val;
    								 }
    								  
    								  break;
							  case "notes":
    								//$desGet["crit"]=$valeurs["id"];
							         $dejaUneNote= "";  //met la valeur é empty pour permettre l'affichage de l'action 'modifier la note'
                                        $retour.= "<td class=\"page-manager-page-type\" style=\"vertical-align:top;\">$valeurs[$cle]</td>\n";

							   break;
							  /*case "pressions":
								  $retour.= "<td class=\"page-manager-page-type\" style=\"vertical-align:top; text-align:center;\">$valeurs[$cle]</td>\n";
								  
								 break;
							  case "prescriptions":
								  $retour.= "<td class=\"page-manager-page-type\" style=\"vertical-align:top; text-align:center;\">$valeurs[$cle]</td>\n";
								  
								 break;
							  case "notes_vaccins":
								  $retour.= "<td class=\"page-manager-page-type\" style=\"vertical-align:top; text-align:center;\">$valeurs[$cle]</td>\n";
								  
								 break;
								 */
								 default:
									$retour.= "<td class=\"page-manager-page-type\" style=\"vertical-align:top; text-align:center;\">$valeurs[$cle]</td>\n";
								  
								 break;
							  }
							  
							  
						  }
						  $i++;
					   if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="pressions" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="notes_vaccins" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="prescriptions" ){
						$retour.="<td class=\"page-manager-page-type\"style=\"text-align:center;\"><input type = \"checkbox\" name =\"a_imprimer".$valeurs["id"]."\" value=\"".$valeurs["id"]."\"  /></td>\n";
						
					}
					  //$retour.="<td class=\"page-manager-page-operations\"><ul class=\"links\">\n";
					  $retour.="<td class=\"page-manager-page-operations\" >\n";
					  $i=0;
					  $dernier= count($GLOBALS["infosSupp"][$desGet["table"]][$no]["actions"]);
					  $actions=$GLOBALS["infosSupp"][$desGet["table"]][$no]["actions"];
					  //$desGet["table"]= $GLOBALS["infosSupp"][$desGet["table"]][$no]["table"];
					  $l_transmettreGet= transmettreGet($desGet,false);
					  $testNote= trouver_un_enregistrement("notes_vaccins","visites_id",$idVisite,NULL);
					  $dejaUneNoteVaccin=$testNote["id"];
					  if(!isset($valeurs["nul"])){
    					  foreach($actions as $val){
    							//echo " ca <b>".str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$desGet["crit"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&visite_id=".$valeurs["id"]."</b><br/>";
    							if($desGet["qui"] == $valeurs["auteur"]){ //seul l'auteur peut agir sur ces enregistrements'
    		
    								if ($val=="Annuler"){
    									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$valeurs["id"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&visite_id=".$valeurs["id"]);
    								}
    								elseif($val == "+ pression" || $val=="notes_vaccins"){
    									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$valeurs["id"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&iu_visites_id=".$valeurs["id"]."&iu_la_date=".$valeurs["la_date"]);
    								}
    								elseif($val == "Modifier" || $val == "Imprimer pression" || $val == "Imprimer prescription"){
    									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$valeurs["id"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&iu_la_date=".$valeurs["la_date"]);
    								}
    								else{
    									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$desGet["crit"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&iu_visites_id=".$valeurs["id"]."&iu_la_date=".$valeurs["la_date"]);
    								 }
    								 
    								   
    										//$retour.="<li class=\"0 first\"><a href=\"admin.php?quoi=$quoi\">$val</a></li>\n";
    									if(!empty($dejaUneNote) && $val == "+ note"){
    										//rien
    									}
    									elseif((!empty($dejaUneNoteVaccin) && $val == "+ note vaccin")  ){
    										//rien
    									}
    									else{
    										if((empty($dejaUneNoteVaccin) && $nomSoin <> "Administration de vaccin" && $val == "+ note vaccin")  ){
    										//rien
    										}
    										else{
    											$ahref= ecrireAHref($desGet["table"],$val,$quoi,null);	
    											$retour.="<a ".$ahref."</a>\n";
    										}
    									}
    									$i++;
    																		
    								}
    							}
    										
    							 
    //retour.= "</ul>\n</td>\n";
    						$retour.= "</td>\n";
					  }
						  
				$retour.="\n</tr>\n";
				$pair_impair=$pair_impair=="odd"?"even":"odd";
			}
				
				$retour.="</tbody>\n
									</table>\n";
				
				if($items){
					$retour.="<p style=\"text-align:center;\"><button id=\"edit-pages-apply\"   class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\" style=\"float:none;\" type=\"submit\"   value=\"Facturer\"  name=\"op\" >Facturer</button>\n
									<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n
									<input type=\"hidden\" name=\"iu_auteur\" value=\"".$desGet["qui"]."\" />\n
									<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n
									<input type = \"hidden\" name = \"omettre\" value = \"\" />\n
									<input type = \"hidden\" name = \"action\" value = \"\" />\n
									<input type=\"hidden\" name=\"iu_adresses_id\" value=\"".$desGet["crit"]."\" />\n
									<input type = \"hidden\" name = \"table\" value = \"factures\" />\n</p>";
				}
				if(($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="pressions" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="prescriptions") && !empty($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]])){
					$retour.="<p style=\"text-align:center;\"><button id=\"edit-pages-apply\"   class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\" style=\"float:none;\" type=\"submit\"   value=\"Imprimer\"  name=\"op\" >Imprimer</button>\n
									<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n
									<input type=\"hidden\" name=\"iu_adresses_id\" value=\"".$desGet["crit"]."\" />\n
									<input type = \"hidden\" name = \"table\" value = \"pressions\" />\n</p>\n";
						//$retour.="</form>\n";
					}
					if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="notes_vaccins"  && !empty($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]])){
					$retour.="<p style=\"text-align:center;\"><button id=\"edit-pages-apply\"   class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\" style=\"float:none;\" type=\"submit\"   value=\"Imprimer\"  name=\"op\" >Imprimer</button>\n
									<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n
									<input type=\"hidden\" name=\"iu_adresses_id\" value=\"".$desGet["crit"]."\" />\n
									<input type = \"hidden\" name = \"table\" value = \"notes_vaccins\" />\n</p>\n";
						//$retour.="</form>\n";
					}
				
				
			}//fin de 	if(count($une_liste))	
			if(!empty($GLOBALS["infosSupp"][$desGet["table"]][$no]["boutons"])){
				//$retour.="<br/><br/><div><ul class=\"links\">\n";
				
				  $i=0;
				 
			  $dernier= count($GLOBALS["infosSupp"][$desGet["table"]][$no]["boutons"]);
			  $boutons=$GLOBALS["infosSupp"][$desGet["table"]][$no]["boutons"];
			  //$desGet["table"]= $GLOBALS["infosSupp"][$desGet["table"]][$no]["table"];
			  $l_transmettreGet= transmettreGet($desGet,false);
			  //echo montrerUnTableau($boutons," 1808");
			  if($GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="visites" || $GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]=="prescriptions" || !empty($une_liste[$GLOBALS["infosSupp"][$desGet["table"]][$no]["titre"]])){
				  $retour.="<p style=\"text-align:center;\">\n";
				  foreach($boutons as $val){
					/*  $quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$desGet["crit"]."&action=$val");
							//$retour.="<li class=\"0 first\"><a href=\"admin.php?quoi=$quoi\">$val</a></li>\n";
							switch ($i) {
								case 0:
									$retour.="<li class=\"0 first\"><a href=\"admin.php?quoi=$quoi\">&raquo; $val</a></li>\n";
									break;
								case $dernier:
									$retour.="<li class=\"$dernier last\"><a href=\"admin.php?quoi=$quoi\">&raquo; $val</a></li>\n";
									break;
								default:
									$retour.="<li class=\"$i middle\"><a href=\"admin.php?quoi=$quoi\">&raquo; $val</a></li>\n";
									break;
							}
							$i++;
									
						 }
					$retour.="</ul>\n</div>\n";
					*/
					
								if($val == "+ visite"){
									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$desGet["crit"]."&action=$val");
								}
								elseif($val == "Modifier la pression" || $val == "Imprimer pression"){
									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$valeurs["id"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&iu_la_date=".$valeurs["la_date"]);
								}
								else{
									$quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$GLOBALS["infosSupp"][$desGet["table"]][$no]["table"],$l_transmettreGet)."&crit=".$desGet["crit"]."&action=$val&iu_adresses_id=".$desGet["crit"]."&visite_id=".$valeurs["id"]);
								 }
								   $ahref= ecrireAHref($desGet["table"],$val,$quoi,"client");	
										
									$retour.="<a ".$ahref."</a>\n";
									
									$i++;
									
				}
				$retour.="</p>\n";
			}
		}
		$retour.="</fieldset>\n";
		$retour.="</form>\n";											
		}
	}//fin de for
	return $retour;
	
}


//pour afficher les champs d'un enregistrement é modifier
//param GET

function modifier($desGet){
    $retour = "";
    $nom="";
    $categorie="";
    
    if(isset($desGet["crit"])){$desGet["iu_id"]=$desGet["crit"];}
    else{
		
			$id =trouver_un_enregistrement($desGet["table"],"id","MAX",null);
		
		$desGet["crit"]=$id["id"];
	}
	//echo montrerUnTableau($desGet, "modifier 2008 ");
	$tab_champs = show_colonnes($desGet);
	//echo montrerUnTableau($tab_champs, "modifier 1983 ");
	if ( !isset($tab_champs['erreur'])){
       
       $ce_titre ="";
       $ce_sous_titre="";
       for ($i=0; $i < $tab_champs['nb_champs']; $i++) {
		   foreach($tab_champs[$i] as $cle=>$val){
			   if($cle == "nom_du_champ" && $val == "categorie"){
				  $categorie =  $tab_champs[$i]["valeur"];
			  }
			  if($cle == "nom_du_champ" && $val == $GLOBALS["champs_titres"][$desGet["table"]][0]){
				  $ce_titre =  $tab_champs[$i]["valeur"];
			  }
			  else{
				  $ce_titre= $desGet["table"];
			  }
			  for ($ii=0; $ii < count($GLOBALS["champs_titres"][$desGet["table"]][1]); $ii++) {
				 if($cle == "nom_du_champ" && $val == $GLOBALS["champs_titres"][$desGet["table"]][1][$ii]){
				  $ce_sous_titre .=  $tab_champs[$i]["valeur"]." ";
			  } 
			}
	    }
	}
       $retour.="<h1 class=\"title\">$ce_titre</h1>\n";
       $retour.="<div id=\"content-content\" style=\"width: 800px;\">\n";
       if($desGet["table"] <> "factures"){
			$retour.="<form action=\"admin.php\"   method=\"get\" id=\"a_valider\" name=\"a_valider\" >\n";
		}

       $numero = 0;
       $fonds= array("pair","impair");
       
       
       $retour.="<fieldset class=\"collapsible ".$GLOBALS["collapsed"][$desGet["table"]]."\">\n<legend ><a href=\"#\">Informations pour $ce_sous_titre</a></legend>\n";

       for ($i=0; $i < $tab_champs['nb_champs']; $i++) {
          $tab_champs[$i]["sa_table"]= $desGet["table"];
          $retour.= formatter_input_pour($tab_champs,$i,$fonds[fmod($numero,2)],$desGet);
            $numero ++;
       }
	   
       if($desGet["table"]=="visites" && ($desGet["action"]=="+ note" || $desGet["action"]=="Modifier la note")){
			$actions =array("Modifier"=>"visites");
		}
		else{
			$actions=$GLOBALS["actionsSurEnr"][$desGet["table"]] ;
		}
		$table_origine= $desGet["table"];
        foreach($actions as $action=>$tab){
			switch($action){
				case "Imprimer facture":
					$retour.= "<div><input type = \"hidden\" name = \"table\" value = \"adresses\" />";
					  $quoi = base64_encode(transmettreGet($desGet,false));
					  $retour.="\n<button id=\"$action\" class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\"   value=\"Rechercher\"  name=\"action\" onclick =\"javascript:ouvre_popup('facture.php?quoi=$quoi',800,850)\" >$action</button>\n";
				break;
				case "Imprimer recu":
					$retour.= "<input type = \"hidden\" name = \"table\" value = \"adresses\" />";
					  $quoi = base64_encode(transmettreGet($desGet,false));
					  $retour.="<button id=\"$action\" class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\"   value=\"Rechercher\"  name=\"action\" onclick =\"javascript:ouvre_popup('recu.php?quoi=$quoi',800,850)\" >$action</button>\n</div></div>\n";
				break;
				case "Modifier":
					$retour.="<form action=\"admin.php\"   method=\"get\" id=\"a_valider\" name=\"a_valider\" >\n";
					$retour.= "<input type = \"hidden\" name = \"table\" value = \"".$tab."\" />";
					$retour.= "<input type=\"hidden\" name=\"action\" value= \"Modifier\"/>\n";
						//$retour.= "<input type=\"submit\" name=\"op\" id=\"supprimer\" value=\"Supprimer\"  class=\"form-submit\" />\n";
				   $retour.= "<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n";
				   $retour.= "<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n";
				   $retour.= "<input type=\"hidden\" name=\"crit\" value= \"".$desGet["crit"]."\"/>\n";
				   //$retour.= "<input type = \"hidden\" name = \"requete_originale\" value =\"".$lesPostsStrippes[\"requete_originale\"]."\" />";
					$retour.= "<input type=\"submit\" name=\"op\" id=\"$action\" value=\"$action\"   class=\"form-submit\" />\n";
					$retour.="</form>\n";
				break;
				
			}
			
		}
       $retour.= "</fieldset>\n";
       
       if($table_origine=="adresses"){
		   $quoi = base64_encode(transmettreGet($desGet,false));
		   $retour.="<div id='fiche_vaccination'><a href='fiche_vaccination.php?quoi=$quoi'"." title=\"\" target=\"blank\">Fiche de vaccination à son nom</a></div>";
		}
       
       //$retour.= isset($desGet['asc_desc'])?"<input type=\"hidden\" name=\"asc_desc\" value=\"".$desGet["asc_desc"]."\" />\n":"";
       //$retour.= "<input type = \"hidden\" name = \"tri\" value = \"".$desGet["tri"]."\"/>\n";
       

   }
   else{   $retour.= $tab_champs['erreur']."<br/>";}
   
   
 
    if($table_origine == $desGet["table"]){
		if($categorie <> "administrateur"){
			$retour.= afficherInfosSupp($desGet);
		}
		$retour.="</div>\n <!--content-content-->";  }
	
   return $retour ;
}


function montrerUnTableau($tableau, $no_ligne){
	$texte = $no_ligne?"<i>ligne $no_ligne</i> ":"";
		foreach($tableau as $cle => $val){
			if(is_array($val)){
				foreach($val as $sousCle => $sousVal){
					echo $texte." tableau[$cle][$sousCle]  = $sousVal<br/>";
				}
			}
			else{
				echo $texte."  tableau  [$cle] = $val <br/>";
			}
		}
	
	}


function verifier_doublons($desGet){
	$retour="";
	if(count($GLOBALS["champs_doublons"][$desGet["table"]]) && empty($desGet["pasDeDoublons"])){
		$req="SELECT id, ";
		$where=" WHERE (";
		$tri=" ORDER BY";
		$legende = "Enregistrements existants dans la table ".$desGet["table"]." pour <br/>"; 
		foreach($GLOBALS["champs_doublons"][$desGet["table"]]["champs"] as $ceChamp){
			if ($ceChamp <> "id"){
				$req.=" $ceChamp,";
				if(empty($desGet["iu_".$ceChamp])){
					$where .= "( $ceChamp =\"\" OR $ceChamp IS NULL ) AND";
					$legende.= " $ceChamp =\"\" et";
				}
				else{
					$where .= " $ceChamp = \"".$desGet["iu_".$ceChamp]."\" AND";
					$legende.= " $ceChamp = \"".$desGet["iu_".$ceChamp]."\"  et";
				}
				$tri .= " $ceChamp,";
			}
		}
		$where =substr($where,0,-3).")";
		$tri = substr($tri,0,-1)." ASC ";
		$req = substr($req,0,-1)." FROM ".$desGet["table"].$where.$tri;		
		$legende = substr($legende,0,-2);
		//echo $req."<br/>";
		$doublons= requeteSelect($req);
		
		if(count($doublons)){
			$retour.="<div ><fieldset>\n<legend class=\"messages  error\">".$legende."</legend>\n";
			
			$retour.="<table class=\"messages  error\"><tr>\n";
			foreach($GLOBALS["champs_doublons"][$desGet["table"]]["champs"] as $ceChamp){
				$retour.="<th>$ceChamp</th>\n";
			}	
			$retour .="<th>choisir</th>\n";		
			$retour .="</tr>\n";
			foreach($doublons as  $une_ligne){
				
				$retour.="<tr>";
					foreach($une_ligne as $uneCle => $uneVal){
						$retour.= "<td>$uneVal</td>\n";
						
					}
					$desGet["crit"]=$une_ligne["id"];
					$desGet["pasDeDoublons"] = "non";
					$quoi = base64_encode(transmettreGet($desGet,false)."&action=Modifier");	
					$retour.= "<td><ul><li class=\"0 first\"><a href=\"admin.php?quoi=$quoi\">atteindre</a></li></ul></td>\n";
				$retour.="</tr>\n";
			}
			
			$retour.="</table>\n";
			$retour.="</fieldset></div>\n";
			$retour.="<div><form action=\"admin.php\"   method=\"get\" id=\"a_valider\" name=\"a_valider\" >\n";
			unset($desGet["crit"]);
			foreach($desGet as $cle => $val){
				if(substr($cle,0,3)=="iu_"){
					$retour.="<input type = \"hidden\" name = \"$cle\" value = \"$val\" />".substr($cle,3)." : $val<br/>\n";
				}
				else{
					$retour.="<input type = \"hidden\" name = \"$cle\" value = \"$val\" />\n";
				}
			}
			$retour.="<br/><br/><input type=\"submit\" name=\"bop\" id=\"ajouter\" value=\"Ajouter celui-ci\"  class=\"form-submit\" />\n";
			
			$retour.="</form>\n</div>\n";
		}
		
		
	}
	return $retour;
}

//pour afficher un enregistrement 
//param nom de la table, id

function voirCetEnr($desGet){
	$retour ="";
		
	$un_crit = isset($desGet["crit"])? $desGet["crit"] :$GLOBALS["g_tables"][$desGet["table"]][1];
	$qui = trouver_un_enregistrement($desGet["table"],$GLOBALS["g_tables"][$desGet["table"]][0],$un_crit,null);
	$tab_champs = show_colonnes($desGet["table"],$un_id);
	
	if(isset($un_id)){
			$retour.="<h1 class=\"title\">".$qui[$GLOBALS["champs_titres"][$desGet["table"]][0]]."</h1>
				 <div id=\"content-content\">
					<div class=\"profile\">
						<div class=\"picture\"></div>
							<h3>";
							foreach($GLOBALS["champs_titres"][$desGet["table"]][1] as $uneVal){
								$retour.= $qui[$uneVal]." ";
							}
							
							$retour.="</h3>
							<dl class=\"user-member\">";
							if ( !isset($tab_champs['erreur'])){
								foreach($qui as $cle => $val){
									if(in_array($cle,$GLOBALS["champs_visibles_admin"][$desGet["table"]])){
										$retour.="<dt>$cle</dt>
										<dd>".formatter_output($val,$cle,NULL)."</dd>";
									}
								   }
							}
								
							$retour.="</dl>
					</div><!-- profile -->
			</div><!-- content-content -->";
		}
		else{
			$retour.="<h3>Aucun enregistrement correspondant trouvé.<h3>";
				 
		}
		
		return $retour;
}

//fonction pour limiter les dimensions d'une image
// selon la page ou la table
function redimensionne_image($uneImage,$unePage){
		  
         if (exif_imagetype($GLOBALS["repertoires"]["images"]."/".$uneImage)){
               list($width, $height, $type, $attr) = getimagesize($GLOBALS["repertoires"]["images"]."/".$uneImage);
               $datem = date ("Y.n.d  H:i:s", filemtime($GLOBALS["repertoires"]["images"]."/".$uneImage));
               //echo "<tr><td><b>".$val."</b><i> ($width px X $height px ) $type $attr</i></td><td>";
               if( $width < $GLOBALS["img_max_width"][$unePage] ){
                  $la_largeur  = "width='$width'";
                  $la_hauteur = "height ='$height'";
               }
               else {
                   $la_largeur  = "width='".$GLOBALS["img_max_width"][$unePage]."' ";
                   $hauteur = round($height * $GLOBALS["img_max_width"][$unePage] /  $width);
                   $la_hauteur = "height='$hauteur' ";

               }
               return $la_largeur." ".$la_hauteur;
           }
           else{
               return "";
           }

}


function contenuDuRep($unRepertoire){

     /*$lien = mysql_connect("127.0.0.1",$GLOBALS["pourVoir"],$GLOBALS["ppourVoir"])
     or die("<br/><br/><center><b>Mot de passe invalide</b> (".mysql_errno().")</center>");
     mysql_select_db($GLOBALS["accueil"]["base"]) or die("<br/><br/><center><b>Ne trouve pas la base de données</b> (".mysql_errno().")</center>");
     $req = "SELECT * FROM passe WHERE nom = \"".$GLOBALS["accueil"]["base"]."\"";
     $resultat = mysql_query($req);
     $ligne = mysqli_fetch_assoc($resultat); */
     $ftp_user_pass = "d1ran1@862";
     //mysqli_free_result($resultat);
     //mysqli_close($lien);
     $ftp_server = "hockeyaylmer.com";
     $ftp_user_name = $GLOBALS["accueil"]["base"];
     $repertoire = $unRepertoire;
     //$erreurLecture = "";
     $retour = array();
     /* Connexion */
     if($conn_id = ftp_connect($ftp_server)){
       //$conn_id = ftp_connect($ftp_server) or die("Impossible de se connecter au serveur");
           /* Identification */
       if($login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)){
           if(ftp_chdir($conn_id, "www/".$repertoire)){
              /* Lire le contenu du dossier courant */
              $fichiers = ftp_nlist($conn_id, ".");
           }
           else{
               $retour["erreurLecture"] = "Impossible d'atteindre $repertoire";
               return $retour;
           }
       }
       else{
           $retour["erreurLecture"] = "Erreur d'authentification";
           return $retour;
       }
     }
     else{
        $retour["erreurLecture"] =  "Pas de connection au serveur";
        return $retour;
     }
     $i = 0;
     foreach($fichiers as $val){
        $datem = date ("Y.m.d  H:i", filemtime($repertoire."/".$val));
        $retour[$i]["nom"] = $val;
        $retour[$i]["date"] = $datem;
        $i++;
     }

     ftp_quit($conn_id);
     return $retour;
}

function lireFichierIns($uneSaison){
		$annee = substr($uneSaison,2);
        $fichier = $GLOBALS["repertoires"]["inscriptions"]."/".$GLOBALS["fichier_inscription"].$annee.".csv";
        //$premiere = explode(",","éétextbox30,textbox27,textbox2,textbox26,textbox14,textbox11,textbox23,Member_LastName,Member_FirstName,Gender_Type,Member_DateOfBirth,Member_HockeyID,MemberType_Name_E,textbox19,Class_Name_F,Category_Name_F,textbox21,textbox12,textbox5,textbox39,textbox51,textbox48,textbox60,textbox63,textbox45,textbox42,textbox54,textbox57,textbox66,textbox74,textbox140,textbox75,textbox77,textbox78,textbox91,textbox166,textbox178,textbox175,textbox172,textbox169,textbox181,textbox163");
        $premiere ='RAPPORT DE PRÉENREGISTREMENTS;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;';
        $premiere = str_replace('"','',$premiere);
        $colonnes = explode(",","NOM,PRÉNOM,SEXE,DATE DE NAISSANCE (aaaa-mm-jj),HOCKEY ID,POSITION,DIVISION,CATéGORIE,CLASSE,GROUPE,TYPE D'INSCRIPTION,DATE D'INSCRIPTION,TéL. DOMICILE,TéLéCOPIEUR,TéL. AU TRAVAIL,COURRIEL,ADRESSE,ADRESSE2,VILLE,PROV,CODE POSTAL,NOTES,ANCIENNE éQUIPE,ANCIENNE POSITION,éQUIPE COURANTE,ANCIENNE DIVISION,ANCIENNE CLASSE,FORFAIT,FRAIS TOTAL,TVP TOTAL,TPS TOTAL,TVQ TOTAL,TAXES TOTAL,PAIEMENT TOTAL,SOLDE TOTAL");
        if(file_exists($fichier)){
        	 $inscriptions = array("Initiation"=>array(),"Novice"=>array(),"Atome"=>array(),"PeeWee"=>array(),"Bantam"=>array(),"Midget"=>array(),"Junior"=>array());
             $datem  = date ("Y.m.d", filemtime($fichier));
             $ligneCourante = 0;
             //$index_RG = array_flip($this->champs_RG);
             $fd = fopen ($fichier, "r");
             while (!feof ($fd)) {
                   $buffer = fgets($fd, 4096);
                   $buffer = rtrim(str_replace(chr(0),'',$buffer));
                   $buffer = str_replace('"','',$buffer);
                   $ligneCourante ++;
                   $temp = explode(";",$buffer);
                   //echo "<b>$ligneCourante</b> ".$temp[$this->index_RG["prenom"]]." ".$temp[$this->index_RG["nom"]].$temp[$this->index_RG["titre"]]." ".$temp[$this->index_RG["position"]]."<br/>";
                   if($ligneCourante == 1){  //compare la premiere ligne pour s'assurer que le fichier est conforme
                      if($buffer != $premiere){
                      	echo $buffer."<br/>Le fichier <b>".$fichier." </b> n'est pas conforme<br/>";
                        //$this->membres[]="Le fichier <b>".$this->getFichierCSV()." </b> n'est pas conforme";
                        break;
                      }
                   }
                   elseif($ligneCourante > 7){ //les lignes suivantes
                   	 $inscrit = new Membre("ins",$temp);
                     if(is_array($inscriptions[$inscrit->getMembreInfo("categorie")])){
                     	array_push($inscriptions[$inscrit->getMembreInfo("categorie")],$inscrit);
                     }
                   }
                }
                fclose ($fd);
        }
        else{
            $inscriptions["Aucune inscription à date"]= array("Aucune inscription é date");
        }
        return $inscriptions;
}

//fonction pour mettre en majuscule la premiére lettre du deuxieme nom d'un nom composé
function majNomPrenom($nom) {
	$trans = ucwords(accentsMinuscules($nom));
	$trouve = "-";
	
	if(strpos($trans,$trouve)){
		$trans = preg_replace("/(-)(\p{Ll})/e","'\\1'.strtoupper('\\2')",$trans );
	}
	return $trans;
}

//fonction pour détecter si le cahamp description contient une balise img
//et dimensionner l'image en conséquence du besoin
//ex $valeur = <br/><img src='images/tonimage.jpg' border = '0' /> $cas = 'liens'
// $retour = <br/><img src='images/tonimage.jpg' width='110'  height='34' border = '0' />
function verif_img($valeur,$cas){
	$retour = $valeur;
	$pos = strpos($valeur,"img src");	
    if ($pos!==false){
      	  $baliseImage = strstr($valeur,"img src");
      	  $baliseImage= substr($baliseImage,8);
      	  $nomImage = strstr($baliseImage,"images/");
      	  $trouve = strpos($nomImage,"'");
      	  $nomImage = substr($nomImage,0,$trouve);
      	  $dimensions = redimensionne_image(substr($nomImage,7),$cas);
      	  $nouv_nomImage= $nomImage."' ".$dimensions;
      	  $retour = str_replace($nomImage,$nouv_nomImage,$valeur);
    }
    return $retour;
}

/*fonction pour détecter si le champ url contient www
	param string $url
	$retour = <br/><img src='images/tonimage.jpg' width='110'  height='34' border = '0' />
*/
function verif_url($url){
	$retour = $url;
	$http = strpos($url,"//");
	$pos = strpos($url,"www.");	
    if ($pos!==false){
    	  if($http!==false){
    	  	$retour = "http://".substr($url,$http + 2);
    	  }
    	  else{
      	  	$retour = "http://".$url;
    	  }
    }
    return $retour;
}



function int2str($a)
{ 
$convert = explode('.',$a); 
if (isset($convert[1]) && $convert[1]!=''){ 
	return int2str($convert[0]).' Dollars'.' et '.int2str($convert[1]).' Cents' ; 
} 
if ($a<0) return 'moins '.int2str(-$a); 
if ($a<17){ 
	switch ($a){ 
		case 0: return 'zero'; 
		case 1: return 'un'; 
		case 2: return 'deux'; 
		case 3: return 'trois'; 
		case 4: return 'quatre'; 
		case 5: return 'cinq'; 
		case 6: return 'six'; 
		case 7: return 'sept'; 
		case 8: return 'huit'; 
		case 9: return 'neuf'; 
		case 10: return 'dix'; 
		case 11: return 'onze'; 
		case 12: return 'douze'; 
		case 13: return 'treize'; 
		case 14: return 'quatorze'; 
		case 15: return 'quinze'; 
		case 16: return 'seize'; 
	} 
} 
else if ($a<20){ 
	return 'dix-'.int2str($a-10); 
} 
else if ($a<100){ 
	if ($a%10==0){ 
		switch ($a){ 
			case 20: return 'vingt'; 
			case 30: return 'trente'; 
			case 40: return 'quarante'; 
			case 50: return 'cinquante'; 
			case 60: return 'soixante'; 
			case 70: return 'soixante-dix'; 
			case 80: return 'quatre-vingt'; 
			case 90: return 'quatre-vingt-dix'; 
		} 
	} 
	elseif (substr($a, -1)==1){ 
		if( ((int)($a/10)*10)<70 ){ 
			return int2str((int)($a/10)*10).'-et-un'; 
		} 
		elseif ($a==71) { 
			return 'soixante-et-onze'; 
		} 
		elseif ($a==81) { 
			return 'quatre-vingt-un'; 
		} 
		elseif ($a==91) { 
			return 'quatre-vingt-onze'; 
		} 
	} 
	elseif ($a<70){ 
		return int2str($a-$a%10).'-'.int2str($a%10); 
	} 
	elseif ($a<80){ 
		return int2str(60).'-'.int2str($a%20); 
	} 
	else{ 
		return int2str(80).'-'.int2str($a%20); 
	} 
} 
else if ($a==100){ 
	return 'cent'; 
} 
else if ($a<200){ 
	return int2str(100).' '.int2str($a%100); 
} else if ($a<1000){ 
return int2str((int)($a/100)).' '.int2str(100).' '.int2str($a%100); 
} else if ($a==1000){ 
return 'mille'; 
} else if ($a<2000){ 
return int2str(1000).' '.int2str($a%1000).' '; 
} else if ($a<1000000){ 
return int2str((int)($a/1000)).' '.int2str(1000).' '.int2str($a%1000); 
}
else if ($a==1000000){ 
return 'millions'; 
} 
else if ($a<2000000){ 
return int2str(1000000).' '.int2str($a%1000000).' '; 
}
else if ($a<1000000000){ 
return int2str((int)($a/1000000)).' '.int2str(1000000).' '.int2str($a%1000000); 
}
}


/*
 * Fonction pour lire un fichier texte
 */
function lireAcces($fichier){ 
	$handle = fopen($fichier, "r");
	$ligne = fread($handle, filesize($fichier));
	fclose($handle);
	return $ligne;
}
/*
 * fonction pour remplacer une ligne dans un fichier
 * 
 */
function ecrireLigne($fichier, $ligne) {
	$retour = file_put_contents($fichier, $ligne);
	return $retour;
}

/*fonction pour exucuter une requete UPDATE
 * les params sont dans le $GET
 * 
 */
function modifierCetEnr($desGet){
	$class="";
	$messages= array();
	$retour=array("",false);
	$con = new Lien($desGet["qui"],$desGet["passe"]);
	$un_lien= $con->getLien();
		 
	//if($un_lien = mysqli_connect("127.0.0.1",$desGet["qui"],$desGet["passe"],"allbert")){
	if($un_lien){
		if(mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"])){
			
			$query = "UPDATE ".$desGet["table"]." SET ";
					
					foreach($desGet as $nom_champ => $val){
						if (substr($nom_champ,0,3) == "iu_" && $nom_champ<>"iu_id"){
							if ($nom_champ=="iu_noteO" && !empty($val)){//note originale
								$la_note_o= $val;
							}
							elseif($nom_champ=="iu_note"){
									$val = empty($la_note_o)?$val:$la_note_o." ".$val;
									if(  !empty($val)){
										$query .= " ".substr($nom_champ,3)."= \"$val\",";
									}
									else{
										$query .= " ".substr($nom_champ,3)."= NULL,";
									}
								}
								else{
								   if ( strlen($val)>0){
									  $query .= " ".substr($nom_champ,3)."= \"$val\",";
								   }
								   else {$query .= " ".substr($nom_champ,3)."= NULL,";}
								}
							}
						}
					
				  
			$query = substr($query,0,strlen($query)-1)." ";
			
			$query.="WHERE id = ".$desGet["iu_id"];
			//echo "<br/><br/>".$query;		
			if ( mysqli_query($un_lien,$query)){
			 $class =" class=\"messages status\"";
			 $messages[]="Les modifications ont été enregistrées .";
			 ecrire_requete($query,$desGet);
		   }
		   else{
			   $class= " class=\"messages  error\"";
			   $messages[]="Les modifications n'ont pas été enregistrées";
			   $messages[]= mysqli_error($un_lien);
		   }
		   
	   
	}
	else{//ne trouve pas la base
	    $class= " class=\"messages  error\"";
		$messages[]="Ne trouve pas la base de données";
		$messages[]=mysqli_error($un_lien);
	}
	

    //fermeture de la connexion
	mysqli_close($un_lien);
  }//fin de connection
	else{ //impossible de connecter
		$class= " class=\"messages  error\"";
		$messages[]="Impossible de se connecter. Vérifier votre mot de passe.";
		$messages[]= mysqli_error($un_lien);
	}
	$retour = "<div $class>\n<ul>";
    foreach($messages as $un_message) {
		$retour.="<li>$un_message</li>\n";
	}       
	$retour.="</ul>\n</div>";
	return $retour;
}

function supprimerCetEnr(){
	$retour="<script language=\"javascript\">
		alert(\"Il est impossible de supprimer.\");
		</script>";
	return $retour;	
}

function ajouterCetEnr($desGet){
	
	$retour="";
	/*
	if(count($GLOBALS["champs_doublons"][$desGet["table"]]) && empty($desGet["pasDeDoublons"])){		
		echo verifier_doublons($desGet);
	}*/
	//else{//pas de doublons
			$test = true;
			$class="";
			$messages= array();
			$visites = "";
			$valeurs = " VALUES(";
			$dernierNoDeVisite=0;
			$dernier = 0;
			$query = "INSERT INTO ".$desGet["table"]."  (";
			$unFrais=array();
			$ajoutFraisADepenses=NULL;
			$soins=array();
			$ajoutProduitADepenses=Null;

			if($desGet["table"]=="factures"){
				//echo "<br>".montrerUnTableau($desGet,2640);
				$dernier = noDeFacture($desGet);
				$query = "INSERT INTO ".$desGet["table"]."  (id,";
				$valeurs = " VALUES(\"$dernier\",";
				$desGet["crit"]=$dernier;
			}
			$con = new Lien($desGet["qui"],$desGet["passe"]);
			$un_lien= $con->getLien();
			
			if($un_lien){
				if(mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"])){
					
						 foreach($desGet as $nom_champ => $val){
							 if (substr($nom_champ,0,3) == "iu_"){
								 if ( strlen($val)>0 && $nom_champ <> "iu_id"){
									  $query .= " ".substr($nom_champ,3).",";
									  $valeurs .= "  \"$val\",";
								   }							   
							 }
							}
						 $valeurs = substr($valeurs,0,strlen($valeurs)-1).") ";
						 $query = substr($query,0,strlen($query)-1).")".$valeurs;
						 ecrire_requete($query,$desGet);
						 
					//echo$query."<br/><br/>test = $test<br/><br/>";		
					if( $test=mysqli_query($un_lien,$query)){
						$messages[]=$GLOBALS["g_messages"]["Ajouter"]["succes"];
						$desGet["op"] = "Rechercher";
						if($desGet["table"] == "pressions" || $desGet["table"] == "notes_vaccins" ){
							$desGet["action"] = "Modifier";
							$desGet["table"] = "adresses";
							$desGet["crit"] = $desGet["iu_adresses_id"];
							unset($desGet["op"]);
						}
						//echo montrerUnTableau($desGet," 2484");
						if($desGet["table"]=="factures"){
							foreach($desGet as $cle=> $val){
								if(substr($cle,0,10) == "visite_no_"){
								//echo "$cle = $val<br/>";
									$visites.= "id = $val OR ";
									$tempSoin =requeteSelect("SELECT soins_id  FROM visites WHERE id = $val");
									$tempType =requeteSelect("SELECT type,id,nom,prix  FROM soins WHERE id =".$tempSoin[0]["soins_id"]);
									if ($tempType[0]["type"]=="produit"){
										$soins[$val]=$tempType;
									}
								}
							}
							$visites= substr($visites,0,-3);
							$req_visite="UPDATE visites SET facture_id = $dernier WHERE $visites";
							//echo"req_visite = $req_visite<br/>";
							$test = mysqli_query($un_lien,$req_visite);
							ecrire_requete($req_visite,$desGet);
							$tempDist =requeteSelect("SELECT distance  FROM adresses WHERE nom = \"".$desGet["qui"]."\"");
							$distanceMinimale = $tempDist[0]["distance"];
							$tempTauxFrais =requeteSelect("SELECT taux_frais_deplacement  FROM admin ");
							$taux_frais = $tempTauxFrais[0]["taux_frais_deplacement"];
							$distanceChargee = round($desGet["iu_frais_deplacement"]/$taux_frais);
							$distanceDeplacement = $distanceMinimale + $distanceChargee;
							$tempEndroit =requeteSelect("SELECT CONCAT(nom_resp,\"  \",  prenom_resp,\"  \",ville) as endroit FROM adresses WHERE id = \"".$desGet["iu_adresses_id"]."\"");
							$endroit = $tempEndroit[0]["endroit"];
							if ($endroit){
								if ($desGet["ajouterUnDeplacement"]=="oui"){
									$req_deplacement="INSERT INTO deplacements (la_date,endroit,raison,km,auteur) VALUES (\"".$desGet["iu_la_date"]."\",\"$endroit\",\"soins\",\"$distanceDeplacement\",\"".$desGet["qui"]."\")";
									$test = mysqli_query($un_lien,$req_deplacement);
									if($test){
										$messages[]="Un déplacement de $distanceDeplacement km a aussi été ajouté avec succès";
										ecrire_requete($req_deplacement,$desGet);
									}
									else{
										
										$echecs[]=$GLOBALS["g_messages"]["Ajouter deplacement"]["echec"];
										$echecs[]= mysqli_error($un_lien);
									}
								}
							}
							else{
										
										$echecs[]=$GLOBALS["g_messages"]["Ajouter deplacement"]["endroit"];
										$echecs[]= mysqli_error($un_lien);
							}
														
						}
						//rg 2015-03-21 pour ajouter un frais dans la table dépenses
						if(isset($desGet["iu_soins_id"])){
							$tempFrais =requeteSelect("SELECT frais_id  FROM soins WHERE id = \"".$desGet["iu_soins_id"]."\"");
							$frais_id = $tempFrais[0]["frais_id"];
							$unFrais=isset($frais_id)?requeteSelect("SELECT *  FROM frais WHERE id = $frais_id"):null;//rg2015-08-06 pour corriger erreur ajout facture lorsque pas de frais ajout de isset
							//echo montrerUnTableau($unFrais,2611);
							//echo "<br>".montrerUnTableau($desGet,2721);
							
							
						}
						//rg 2018-03-29 pour ajouter une dépense dans la table dépenses pour la vente d'un produit
						if(isset($soins)){
							foreach($soins as $cle=> $val){
								$dernierNoDeVisite = noDeVisite($desGet);
								$ajoutProduitADepenses="INSERT INTO depenses(la_date,raison,montant,compte,visite_id,auteur) VALUES(\"".$desGet["iu_la_date"]."\",\"".$val[0]["nom"]."\",\"".$val[0]["prix"]."\",\"fournitures\",\"$cle\",\"".$desGet["iu_auteur"]."\");";	
								$test = mysqli_query($un_lien,$ajoutProduitADepenses);
								if($test){
									$messages[]="Un ".$val[0]["nom"]." a aussi été ajouté avec succès aux dépenses";
									ecrire_requete($ajoutProduitADepenses,$desGet);
								}
								else{
									
									$echecs[]=$GLOBALS["g_messages"]["Ajouter produit a depenses"]["echec"];
									$echecs[]= mysqli_error($un_lien);
								}
							}
							
							//echo "<br><br>".montrerUnTableau($desGet,2612);
										
										
						}

			
						if (isset($unFrais[0])){
							
							$dernierNoDeVisite = noDeVisite($desGet);
							$ajoutFraisADepenses="INSERT INTO depenses(la_date,raison,montant,compte,visite_id,auteur) VALUES(\"".$desGet["iu_la_date"]."\",\"".$unFrais[0]["nom_frais"]."\",\"".$unFrais[0]["prix_frais"]."\",\"fournitures\",\"$dernierNoDeVisite\",\"".$desGet["iu_auteur"]."\");";	
							$test = mysqli_query($un_lien,$ajoutFraisADepenses);
							if($test){
								$messages[]="Un ".$unFrais[0]["nom_frais"]." a aussi été ajouté avec succès aux dépenses";
								ecrire_requete($ajoutFraisADepenses,$desGet);
							}
							else{
								
								$echecs[]=$GLOBALS["g_messages"]["Ajouter frais"]["echec"];
								$echecs[]= mysqli_error($un_lien);
							}
								
						}
					
				   }
				   else{					   
					   $echecs[]=$GLOBALS["g_messages"]["Ajouter"]["echec"];
					   $echecs[]= mysqli_error($un_lien)." ....".$query;
					   
				   }
				   
			   
			}
			else{//ne trouve pas la base
				$class= " class=\"messages  error\"";
				$echecs[]="Ne trouve pas la base de données";
				$echecs[]=mysqli_error($un_lien);
			}
			

			//fermeture de la connexion
			mysqli_close($un_lien);
		  }//fin de connection
			else{ //impossible de connecter
				
				$messages[]="Impossible de se connecter. Vérifier votre mot de passe.";
				$messages[]= mysqli_error($un_lien);
			}
			if(!empty($messages)){
				$class =" class=\"messages status\"";
				$retour .= "<div $class>\n<ul>";
				foreach($messages as $un_message) {
					$retour.="<li>$un_message</li>\n";
				}       
				$retour.="</ul>\n</div>";
			}
			if(!empty($echecs)){
				$class= " class=\"messages  error\"";
				$retour .= "<div $class>\n<ul>";
				foreach($echecs as $un_echec) {
					$retour.="<li>$un_echec</li>\n";
				}       
				$retour.="</ul>\n</div>";
			}
			return $retour;
		//}//fin pas de doublons
}

//pour afficher les champs d'un enregistrement à modifier
//param nom de la table, id

function ajouter($desGet){
	$retour = "";
    $nom="";
	$tab_champs = show_colonnes($desGet);
	//echo montrerUnTableau($tab_champs," 2824");
	/*foreach($tab_champs[0] as $cle => $val){
		echo " $cle = $val <br/>";
	}*/
	if ( !isset($tab_champs['erreur'])){
	switch ($desGet["table"]){
		
		case "factures"://Facturer
				$temp = trouver_un_enregistrement("adresses","id",$desGet["iu_adresses_id"],null);
				$distance =$temp["distance"] ;
				$temp =  trouver_un_enregistrement("admin","id","1",null);
				$taux_frais_deplacement = $temp["taux_frais_deplacement"];
				$desGet["iu_total"] = 0;	
				//$desGet["iu_la_date"]="";
				if(is_array($desGet["iu_la_date"])){
				foreach($desGet as $cle=>$val){
					if(substr($cle,0,10)== "visite_no_" ){
									if($val <> $desGet["omettre"]){
										$desGet["iu_la_date"]= $desGet["iu_la_date"][$val];
										break;
									}
								}
				}			
			}			
			   $ce_titre ="Nouvelle facture";
			   $ce_sous_titre=$desGet["table"];
			   
			   
			   $retour.="<h1 class=\"title\">$ce_titre</h1>\n";
			   $retour.="<div id=\"content-content\">\n
						  <form action=\"admin.php\"   method=\"get\" id=\"a_valider\" name=\"a_valider\" >\n";
			   $retour.="<div><fieldset class=\"collapsible\">\n<legend ><a href=\"#\">Informations pour ".$desGet["table"]."</a> </legend>\n";
			 
				$numero = 0;
			   $fonds= array("pair","impair");
			   
				for ($i=1; $i < $tab_champs['nb_champs']; $i++) {//commence à 1 pour éviter id
						$tab_champs[$i]["sa_table"]= $desGet["table"];
						//echo" champ = ".$tab_champs[$i]["nom_du_champ"]."<br/>";
						$retour.= formatter_input_pour($tab_champs,$i,$fonds[fmod($numero,2)],$desGet);
						if($tab_champs[$i]["nom_du_champ"]== "auteur"){
							$no_visites=array();
							foreach($desGet as $cle=>$val){
								if(substr($cle,0,10)== "visite_no_" ){
									if($val <> $desGet["omettre"]){
										$no_visites[]= $val ;
									}
									else{
										unset($desGet[$cle]);
									}
								}
							}
							$inclure="";
							if (count($no_visites)){
								$inclure.=" AND (";
								foreach($no_visites as $val){
									$inclure.=" visites.id = $val OR";
								}
								$inclure=substr($inclure,0,-2).") ";
							}
							
							
							$visites = requeteSelect("SELECT visites.id,visites.la_date, soins.nom,visites.note, visites.prix FROM visites, soins WHERE visites.adresses_id= \"".$desGet["iu_adresses_id"]."\"$inclure AND ( visites.soins_id = soins.id) ORDER BY visites.la_date DESC");
							$retour.="<table>\n";
							$retour.="<tr><th></th><th>date</th><th>service</th><th>montant</th></tr>";
							$desGet["prix"] = 0;
							$desGet["iu_frais_deplacement"] =0;
							foreach($visites as  $une_ligne){
								
								$retour.="<tr>";
									foreach($une_ligne as $uneCle => $uneVal){
											switch($uneCle){
												case "id":
													if(count($visites)>1){
														if($une_ligne["id"]<> $desGet["omettre"]){
															$quoi= base64_encode(transmettreGet($desGet,true)."&omettre=".$une_ligne["id"]);
															$retour.= "<td><input type=\"hidden\"  name =\"visite_no_$uneVal\" value=\"$uneVal\" /><a href=\"admin.php?quoi=$quoi\" ><img src=\"images/b_drop.png\" align = \"center\" title=\"exclure de cette facture\" \></a>$uneVal</td>";
														}
													}
													else{
														$retour.="<td><input type=\"hidden\"  name =\"visite_no_$uneVal\" value=\"$uneVal\" />$uneVal</td>";
													}
													break;
												case "nom":
													$retour.= "<td>$uneVal<br/> ";
													break;
												case "note":
													$retour.= "<i>$uneVal</i></td> ";
													break;
												case "prix":
													$retour.= "<td>$uneVal<br/> ";
													$desGet["iu_total"]+=number_format($une_ligne["prix"],2,'.','');
													break;
												/*case "type":
													$desGet["iu_produit_id"][]=$une_ligne["soins_id"];
													break;*/
												default:
													$retour.= "<td>$uneVal</td> ";
													break;
											}
									}
								$retour.="</tr>";
							}
							$desGet["iu_frais_deplacement"] = number_format(($distance*$taux_frais_deplacement),0).".00";
							$desGet["iu_total"] = number_format($desGet["iu_frais_deplacement"]+$desGet["iu_total"]  ,2);
							$retour.="</table>";
						}
					$numero ++;
				}
			   $retour.="<div class=\"form-item\" id=\"ajouterUnDeplacement\">\n<label id=\"l-ajouterUnDeplacement\" for=\"ajouterUnDeplacement\">Ajouter automatiquement un déplacement dans la table <i>deplacements</i><span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
			   <select  class=\"form-select required\" id=\"ajouterUnDeplacement\" name = \"ajouterUnDeplacement\" />\n
			   <option selected value = \"\"></option>\n
			   <option value = \"oui\" >oui</option>\n
			   <option value = \"non\" >non</option>\n
			   </select></div>";
			   $retour.= "</fieldset><input type=\"submit\" name=\"op\" id=\"ajouter\" value=\"Ajouter\"  class=\"form-submit\" />\n";
			   //$retour.= "<input type=\"submit\" name=\"op\" id=\"supprimer\" value=\"Supprimer\"  class=\"form-submit\" />\n";
			   $retour.= "<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n";
			   $retour.= "<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n";
			   //$retour.= "<input type = \"hidden\" name = \"requete_originale\" value =\"".$lesPostsStrippes[\"requete_originale\"]."\" />";
			   $retour.= "<input type = \"hidden\" name = \"table\" value = \"".$desGet["table"]."\" />";
			   $retour.= "<input type=\"hidden\" name=\"action\" value= \"Modifier\"/>\n";
			   //$retour.= "<input type=\"hidden\" name=\"crit\" value= \"$un_id\"/>\n";
			   $retour.="</div></form></div>\n";
		break;
	    default:
				$ce_titre ="Nouvel enregistrement";
			   $ce_sous_titre=$desGet["table"];
			   
			   $retour.="<h1 class=\"title\">$ce_titre</h1>\n";
			   $retour.="<div id=\"content-content\">\n
						  <form action=\"admin.php\"   method=\"get\" id=\"a_valider\" name=\"a_valider\" >\n";
			   $retour.="<div><fieldset class=\"collapsible\">\n<legend ><a href=\"#\">Informations pour ".$desGet["table"]."</a> </legend>\n";
			 
				$numero = 0;
			   $fonds= array("pair","impair");
			   
				for ($i=1; $i < $tab_champs['nb_champs']; $i++) {//commence à 1 pour éviter id
						$tab_champs[$i]["sa_table"]= $desGet["table"];
						//echo" champ = ".$tab_champs[$i]["nom_du_champ"]."<br/>";
						$retour.= formatter_input_pour($tab_champs,$i,$fonds[fmod($numero,2)],$desGet);
					$numero ++;
				}
			   
			   $retour.= "</fieldset><input type=\"submit\" name=\"op\" id=\"ajouter\" value=\"Ajouter\"  class=\"form-submit\" />\n";
			   //$retour.= "<input type=\"submit\" name=\"op\" id=\"supprimer\" value=\"Supprimer\"  class=\"form-submit\" />\n";
			   $retour.= "<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n";
			   $retour.= "<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n";
			   //$retour.= "<input type = \"hidden\" name = \"requete_originale\" value =\"".$lesPostsStrippes[\"requete_originale\"]."\" />";
			   $retour.= "<input type = \"hidden\" name = \"table\" value = \"".$desGet["table"]."\" />";
			   $retour.= "<input type=\"hidden\" name=\"action\" value= \"Modifier\"/>\n";
			   //$retour.= "<input type=\"hidden\" name=\"crit\" value= \"$un_id\"/>\n";
			   $retour.="</div></form></div>\n";
	   break;
	}//switch
   }//!isset($tab_champs['erreur'])
    else{   $retour.= $tab_champs['erreur']."<br/>";}
    return $retour ;
}

//pour annuler un enregistrement

function annuler($desGet){
	//echo montrerUnTableau($desGet," fonctions 2988 ");
	$nb_req=0;
	$cetteVisite=array();
	$cetteFacture=array();
	$ceClient=array();
	$ceDeplacement= array();
	$cetteDepense= array();
    $enregistrement=array("visites"=>" cette visite:<br/>", "deplacements"=>" ce déplacement:<br/>", "factures"=>"cette facture:<br/>", "notes_vaccins"=>"cette note de vaccin:<br/>", "pressions"=>"cette pression:<br/>", "commandes"=>"cette commande:<br/>","depenses"=>" cette dépense:<br/>");
    $retour="<h1 class=\"title\">ANNULATION</h1>\n";
    $retour.="<div id=\"content-content\">\n";
    $retour .= "Tu as choisi d'annuler ".$enregistrement[$desGet["table"]];
    $req=array();
    $nb_req++;
    $req[$nb_req]["table"]=$desGet["table"];
	$req[$nb_req]["id"]=$desGet["crit"];
   
    switch ($desGet["table"]){
		case "visites":
			$cetteVisite=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			$ceClient=trouver_un_enregistrement("adresses","id",$cetteVisite["adresses_id"],null);
			$cettePression=trouver_un_enregistrement("pressions","visites_id",$desGet["crit"],null);
			$cetteNotes_vaccins=trouver_un_enregistrement("notes_vaccins","visites_id",$desGet["crit"],null);
			$cetteDepense=trouver_un_enregistrement("depenses","visite_id",$desGet["crit"],null);
			$sonNom= $ceClient["nom_resp"]."  ".$ceClient["prenom_resp"]."  ".$ceClient["ville"];
			//echo montrerUnTableau($cetteDepense, "3011");
			$retour.="<b>".$cetteVisite["id"]."</b> à <b>".$sonNom."</b> le <b>".$cetteVisite["la_date"]." </b><br/>";
			if(!empty($cettePression["id"])){
					$retour.= "<br/>Cette pression sera aussi annulée.<br/>";
					$retour.="<b>".$cettePression["id"]."</b> à <b>".$sonNom."</b> en date du <b>".$cettePression["la_date"]." </b><br/>";
					$nb_req++;
					$req[$nb_req]["table"]="pressions";
					$req[$nb_req]["id"]=$cettePression["id"];
					
			   }
			   else{
				   $retour.= "<br/>Aucune pression pour la visite no ".$desGet["crit"].".<br/>";
			   }
			if(!empty($cetteNotes_vaccins["id"])){
					$retour.= "<br/>Cette note de vaccin sera aussi annulée.<br/>";
					$retour.="<b>".$cetteNotes_vaccins["id"]."</b> à <b>".$sonNom."</b> en date du <b>".$cetteNotes_vaccins["la_date"]." </b><br/>";
					$nb_req++;
					$req[$nb_req]["table"]="notes_vaccins";
					$req[$nb_req]["id"]=$cetteNotes_vaccins["id"];
			   }
			   else{
				   $retour.= "<br/>Aucune note de vaccin pour la visite no ".$desGet["crit"].".<br/>";
			   }
			   if(!empty($cetteDepense["id"])){
					$retour.= "<br/>Cette dépense sera aussi annulée.<br/>";
					$retour.="<b>".$cetteDepense["id"]."</b> pour <b>".$cetteDepense["raison"]."</b> en date du <b>".$cetteDepense["la_date"]." </b><br/>";
					$nb_req++;
					$req[$nb_req]["table"]="depenses";
					$req[$nb_req]["id"]=$cetteDepense["id"];
			   }
			   else{
				   $retour.= "<br/>Aucune dépense pour la visite no ".$desGet["crit"].".<br/>";
			   }
			
			if($cetteVisite["facture_id"]>0){
				$cetteFacture = trouver_un_enregistrement("factures","id",$cetteVisite["facture_id"],null);
				$retour.= "<br/>Cette facture sera aussi annulée.<br/>";
				$retour.="<b>".$cetteFacture["id"]."</b> à <b>".$sonNom."</b> le <b>".$cetteFacture["la_date"]." </b><br/>";
				$nb_req++;
				$req[$nb_req]["table"]="factures";
				$req[$nb_req]["id"]=$cetteFacture["id"];
				$ceDeplacement=requeteSelect("SELECT * FROM deplacements WHERE endroit = \"".$sonNom."\" AND la_date =\"".$cetteFacture["la_date"]."\" AND auteur =\"".$desGet["qui"]."\"");
				if(count($ceDeplacement)){
					$retour.= "<br/>Ce déplacement sera aussi annulé.<br/>";
					$retour.="<b>".$ceDeplacement[0]["id"]."</b> à <b>".$sonNom."</b> le <b>".$ceDeplacement[0]["la_date"]." </b><br/>";
					$nb_req++;
					$req[$nb_req]["table"]="deplacements";
					$req[$nb_req]["id"]=$ceDeplacement[0]["id"];
			   }
			   else{
				   $retour.= "<br/>Aucun déplacement pour $sonNom le ".$cetteFacture["la_date"]." .<br/>";
			   }
			}
			else{
				$retour.= "<br/>Aucune facture pour la visite no ".$desGet["crit"]." .<br/>";
			}
			
		
		break;
		case "factures":
			$visites = requeteSelect("SELECT visites.id,visites.la_date, soins.nom,soins.type, visites.note, visites.prix FROM visites, soins WHERE visites.facture_id= \"".$desGet["crit"]."\" AND ( visites.soins_id = soins.id) ORDER BY visites.la_date DESC");
			$cetteFacture=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			//$cetteDepense=trouver_un_enregistrement("depenses","visite_id",$desGet["crit"],null);
			$ceClient=trouver_un_enregistrement("adresses","id",$cetteFacture["adresses_id"],null);
			$sonNom= $ceClient["nom_resp"]."  ".$ceClient["prenom_resp"]."  ".$ceClient["ville"];
			//echo montrerUnTableau($cetteDepense, "3077");
			$retour.="<b>".$cetteFacture["id"]."</b>  <b>".$sonNom."</b> le <b>".$cetteFacture["la_date"]." </b><br/>";
			
				
			if(count($visites)==1){
				$retour.= "La visite suivante sera aussi annulée<br/>";
			}
			elseif(count($visites)>1){
				$retour.= "Les visites suivantes seront aussi annulées<br/>";
			}
			else{
			}
			for($i=0;$i<count($visites); $i++){
				$retour.="<b>".$visites[$i]["id"]."</b> <b>".$visites[$i]["nom"]."</b> le <b>".$visites[$i]["la_date"]." </b><br/>";
				$nb_req++;
				$req[$nb_req]["table"]="visites";
				$req[$nb_req]["id"]=$visites[$i]["id"];
				//echo "SELECT * FROM depenses WHERE depenses.visite_id = ".$visites[$i]["id"] ;
				$depenses = requeteSelect("SELECT * FROM depenses WHERE depenses.visite_id = ".$visites[$i]["id"]);//2018.07.25 correction pour les dépenses pas annulées
				//if ($visites[$i]["type"]=="produit") {//pas de dépense si c'est un soin 2018.07.25 enlevé car frsi de labo sur soin
				//	$cetteDepense[$i]=trouver_un_enregistrement("depenses","visite_id",$visites[$i]["id"],null);
				//	echo montrerUnTableau($cetteDepense, "3097");
				//}
				
		   }
		   $ceDeplacement=requeteSelect("SELECT * FROM deplacements WHERE endroit = \"".$sonNom."\" AND la_date =\"".$cetteFacture["la_date"]."\" AND auteur =\"".$desGet["qui"]."\"");
			if(count($ceDeplacement)==1){
				$retour.= "Le déplacement suivant sera aussi annulé<br/>";
			}
			elseif(count($ceDeplacement)>1){
				$retour.= "Les déplacements suivant seront aussi annulés<br/>";
			}
			else{
				 $retour.= "<br/>Aucun déplacement pour $sonNom le ".$cetteFacture["la_date"]." .<br/>";
			}
			for($i=0;$i<count($ceDeplacement); $i++){
				$retour.="<b>".$ceDeplacement[$i]["id"]."</b> à <b>".$ceDeplacement[$i]["endroit"]."</b> le <b>".$ceDeplacement[$i]["la_date"]." </b><br/>";
				$nb_req++;
				$req[$nb_req]["table"]="deplacements";
				$req[$nb_req]["id"]=$ceDeplacement[$i]["id"];
			}
			if(count($depenses)==1){
				$retour.= "Cette dépense sera aussi annulée<br/>";
			}
			elseif(count($depenses)>1){
				$retour.= "Les dépenses suivantes seront aussi annulées<br/>";
			}
			else{
				$retour.= "<br/>Aucune dépense pour $sonNom le ".$cetteFacture["la_date"]." .<br/>";
			}
			
			for($i=0;$i<count($depenses);$i++){
				$retour.="<b>".$depenses[$i]["id"]."</b> pour <b>".$depenses[$i]["raison"]."</b> en date du <b>".$depenses[$i]["la_date"]." </b><br/>";
				$nb_req++;
				$req[$nb_req]["table"]="depenses";
				$req[$nb_req]["id"]=$depenses[$i]["id"];
			}

			//echo montrerUnTableau($req," fonctions 3135 ");
		   
			  
		break;
		case "deplacements":
			$ceDeplacement=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			
			
			$retour.="<b>".$ceDeplacement["id"]."</b> à <b>".$ceDeplacement["endroit"]."</b> le <b>".$ceDeplacement["la_date"]." </b><br/>";
		
		break;
		case "notes_vaccins":
			$cetteNote_vaccin=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			$ceClient=trouver_un_enregistrement("adresses","id",$cetteNote_vaccin["adresses_id"],null);
			
			$retour.="<b>".$cetteNote_vaccin["id"]."</b> à <b>".$ceClient["prenom_resp"]." ".$ceClient["nom_resp"]."</b> le <b>".$cetteNote_vaccin["la_date"]." </b> vaccin: <b>".$cetteNote_vaccin["nom_vaccin"]." </b><br/>";
		
		break;
		case "pressions":
			$cettePression=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			$ceClient=trouver_un_enregistrement("adresses","id",$cettePression["adresses_id"],null);
			
			$retour.="<b>".$cettePression["id"]."</b> à <b>".$ceClient["prenom_resp"]." ".$ceClient["nom_resp"]."</b> le <b>".$cettePression["la_date"]." </b><br/>";
		
		break;
		case "commandes":
			$cetteCommande=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			$ceClient=trouver_un_enregistrement("adresses","id",$cetteCommande["fournisseur_id"],null);
			
			$retour.="id: <b>".$cetteCommande["id"]."</b> produit: <b>".$cetteCommande["produit"]."</b> à <b>".$ceClient["nom"]."</b> le <b>".$cetteCommande["la_date"]." </b><br/>";
		
		break;
		case "depenses":
			$cetteDepense=trouver_un_enregistrement($desGet["table"],"id",$desGet["crit"],null);
			$cetteVisite=trouver_un_enregistrement("visites","id",$cetteDepense["visite_id"],null);
			
			$retour.="id: <b>".$cetteDepense["id"]."</b> raison: <b>".$cetteDepense["raison"]."</b> de : <b>".$cetteDepense["montant"]."$</b> le <b>".$cetteDepense["la_date"]." </b><br/>";
		
		break;
	}
	$retour.="<form action=\"admin.php\"   method=\"get\" id=\"a_annuler\" name=\"a_annuler\" >\n";
	$retour.="<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n
			<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n";
	for($i=1;$i<=count($req); $i++){
		$retour.="<input type=\"hidden\" name=\"requete".$i."\" value=\"UPDATE ".$req[$i]["table"]." SET nul = 1 WHERE id = ".$req[$i]["id"]."\" />\n";
	}
	$retour.= "<br/><input type=\"submit\" name=\"op\" id=\"annuler\" value=\"Poursuivre annulation\"  class=\"form-submit\" />\n";
	$retour.="</form>\n";
    $retour.="</div>\n";

	return $retour;
    
	}

/*
 * 
 * 
 * name:annulerCetEnr
 * pour inscrire 1 dans le champ nul des enregistrements désirés
 * @param $desGet
 * $desGet contient autant de valeurs "requete#" que d'enregistrement à annuler'
 * @return 
 * retourne un message de succès ou échec
 */

function annulerCetEnr($desGet){
	//echo montrerUnTableau($desGet," annulerCetEnr ");
    $con = new Lien($desGet["qui"],$desGet["passe"]);
    $un_lien= $con->getLien();
    if($un_lien){
		foreach($desGet as $cle=>$val){
			mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]);
			if(substr($cle,0,7)=="requete"){
				$test = explode(" ",$val);
				$resultat = mysqli_query($un_lien,$val);
				if($resultat){
				   $class= " class=\"messages  succes\"";
				   $messages[]="Annulation de ".substr($test[1],0,-1)." no: ".$test[9]." - exécutée avec succès";
				   ecrire_requete($val,$desGet);
			   }
			   else{ //mysql_query = false
				    $class= " class=\"messages  error\"";
				   $messages[]="La requète na pu étre exécutée";
				   $messages[]= mysqli_error($un_lien);
				   $messages[]= $val;
			   }
			}
		
		}
		
		mysqli_close($un_lien);	 
	}
	else{//impossible de connecter
		$class= " class=\"messages  error\"";
		$messages[]="Impossible de se connecter. Vérifier votre mot de passe.";
		$messages[]= mysqli_error($un_lien);
	}
	$retour="<div $class><ul>";
	 foreach($messages as $un_message) {
		$retour.="<li>$un_message</li>\n";
	}  
	$retour.="</ul></div>" ;    
	return $retour;
}    
   
       
 function rechercher ($desGet){
	
	$retour = "";
	$messages= array();
	$sa_valeur ="";
	$categorie = "";
	
	$l_transmettreGet= transmettreGet($desGet,false);
	$tab_champs = show_colonnes($desGet);
	$len_des_champs=array();
	$asc_desc = array("ASC"=>"","DESC"=>"");
	$pos=false;
	$cetOrdre ="";
	$ce_tri= $GLOBALS["le_tri"][$desGet["table"]];
	$nbEnrTotal=0;
	
	 foreach($asc_desc as $cle => $val){
		 $pos = strpos($ce_tri,$cle);
		 if($pos === false){
		 
		 }
		 else{
			 
			 $ce_tri= substr($ce_tri,0,$pos-1);
			 $cetOrdre = $cle;
			 break;
		 }
	 }
	 //echo montrerUnTableau($desGet,3256)."</br>";
	 $con = new Lien($desGet["qui"],$desGet["passe"]);
	 $un_lien= $con->getLien();
	 if($un_lien){
		if(mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"])){
		$req = formuler_requete($desGet);
		
		$selectdb = mysqli_select_db($un_lien,$desGet["qui"]);  
		$result = mysqli_query($un_lien,"SELECT * from ".$desGet["table"]);  
		$nbEnrTotal = mysqli_num_rows($result);  
		
		//echo "<br>3266  $req<br/>";
	
		if($resultat = mysqli_query($un_lien,$req)){
		  
		  foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $cle =>$val){
		        $finfo = mysqli_fetch_field_direct($resultat, $cle);
		        $long = $finfo->length * 2;
				$len_des_champs[$val]= $long >170?"170px;":$long."px;";
			}
			
			
			//formulaire de recherche
			 $retour.="<div id=\"content-content\" style=\"width: 800px;\">\n
					  <form action=\"admin.php\"   method=\"post\" id=\"page-manager-list-pages-form\"  name = \"a_rechercher\" >\n
					  <div  >\n
					  <div class=\"clear-block\" width=\"500px\">\n";
			
			
	
	   

			 foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ){
				 $nomSimple= nomDuChampSimplifie($un_champ) ;
				 //echo "ca :".$GLOBALS["enumerations"][$desGet["table"]][$un_champ]."<br/>";
				 if(isset($GLOBALS["enumerations"][$desGet["table"]][nomDuChampSimplifie($un_champ)])){
					 if ($un_champ=="depenses.auteur") {
					 	$retour.="<div class=\"form-item\" id=\"edit-".$un_champ."-wrapper\">\n
									 <label for=\"edit-".$un_champ."\">$un_champ : </label>\n
									 <select class=\"form-select pm-processed\" id=\"edit-crit_".$un_champ."\" name = \"edit-crit_".$un_champ."\" >\n";
					
					 }
					 else {
					 	$retour.="<div class=\"form-item\" id=\"edit-".$nomSimple."-wrapper\">\n
									 <label for=\"edit-".$un_champ."\">$nomSimple : </label>\n
					 										 <select class=\"form-select pm-processed\" id=\"edit-crit_".$nomSimple."\" name = \"edit-crit_".$nomSimple."\" >\n";
					 		
					 	
					 }
				 	   
					   if(is_array($GLOBALS["enumerations"][$desGet["table"]][nomDuChampSimplifie($un_champ)])){
						   if (array_key_exists("faire_la_liste", $GLOBALS["enumerations"][$desGet["table"]][nomDuChampSimplifie($un_champ)])) {
							  $temp = $GLOBALS["enumerations"][$desGet["table"]][nomDuChampSimplifie($un_champ)];
							  $une_liste = faire_la_liste($temp["faire_la_liste"],$temp["cle"],$temp["val"],NULL);
							  $sa_valeur = isset($desGet["edit-crit_".$nomSimple])?$desGet["edit-crit_".$nomSimple]:"";
							  $retour.="\t\t<option value = \"\"></option>\n";
							  foreach ($une_liste as $ind => $valeur){
								if ( $ind == $sa_valeur && (empty($desGet["op"]) || $desGet["op"] <>"Initialiser")){
								  $retour.="\t\t<option selected value = '$ind'>$valeur</option>\n";
								}
								else {
								  $retour.="\t\t<option value = \"$ind\">$valeur</option>\n";
								}
							  }
						   }
						   else{//la liste est dèjà déterminée dans vars
							  $une_liste = $GLOBALS["enumerations"][$desGet["table"]][nomDuChampSimplifie($un_champ)];
							  $sa_valeur = isset($desGet["edit-crit_".$nomSimple])?$desGet["edit-crit_".$nomSimple]:"";
							  if ($sa_valeur =="" && (empty($desGet["edit-crit_nul".$nomSimple]) && ($desGet["op"]<>"Appliquer"))){
								  $sa_valeur = "0";
								  //echo " nom :".$nomSimple." = $sa_valeur<br/>";
							  }
							  
							  $la_val="";
							  $la_cle="";
							  foreach ($une_liste as $cle=>$valeur){
								  //si une valeur textuelle a été donné à la clé dans enumerations elle apparaîtra comme choix dans la liste
								  if(is_string($cle)){
									  $la_val=$valeur;		
									  $la_cle= $valeur;							  
								  }
								  else{
									  $la_val=$valeur;
									  $la_cle= $cle;									  
								  }
								 if ( $valeur == $sa_valeur  && (empty($desGet["op"]) || $desGet["op"] <>"Initialiser")){
								  $retour.="\t\t<option selected value = '$valeur'>$cle</option>\n";}
								else {
								  $retour.="\t\t<option value = \"$valeur\">$cle</option>\n";
								  //echo "valeur= $valeur et sa valeur = $sa_valeur<br/>"; 
								  }
								}
						   }
					   }
					   $retour.="</select></div>\n";
				   }//isset($GLOBALS["enumerations"][$desGet["table"]][$un_champ]
				   else{

					if($GLOBALS["recherche_date"][$desGet["table"]]== nomDuChampSimplifie($un_champ)){
						
						$val_crit_debut = (isset($desGet["edit-crit_debut"]) && ( isset($desGet["op"]) && $desGet["op"] <> "Initialiser"))?$desGet["edit-crit_debut"]:"";
						$val_crit_fin = (isset($desGet["edit-crit_fin"]) && ( isset($desGet["op"]) && $desGet["op"] <> "Initialiser"))?$desGet["edit-crit_fin"]:"";
						$retour.="<div class=\"clear-block\" width=\"500px\">
								 <div class=\"form-item\" id=\"edit-debut-wrapper\">\n
								 <label for=\"edit-crit_debut\">Début : </label>\n
								 <input class=\"form-text pm-processed\" type=\"text\" maxlength=\"11\" name=\"edit-crit_debut\" id=\"edit-crit_debut\" size=\"11\" value=\"$val_crit_debut\"  /> 
								 </div>\n
								 <div class=\"form-item\" id=\"edit-fin-wrapper\">\n
								 <label for=\"edit-crit_fin\">Fin : </label>\n
								 <input type=\"text\" maxlength=\"11\" name=\"edit-crit_fin\" id=\"edit-crit_fin\" size=\"11\" value=\"$val_crit_fin\" class=\"form-text pm-processed\" />
								</div>\n
								</div>\n";
					//class="form-text dateISO" type="text" onkeypress="return handleEnter(this, event)" value="" size="10" maxlength="10" name="iu_naissance"
						
					}
				} 
				   
			 }//foreach$GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ
			 
			 
			 
			 
			 //champ de recherche
			 
			 
			 $val_crit_cherche = (isset($desGet["edit-crit_cherche"]) && ( isset($desGet["op"]) && $desGet["op"] <> "Initialiser"))?$desGet["edit-crit_cherche"]:"";
			 if(!empty($GLOBALS["index"][$desGet["table"]])){
				$retour.="<div class=\"form-item\" id=\"edit-search-wrapper\">\n
						 <label for=\"edit-crit_cherche\">Recherche : </label>\n
						 <input type=\"text\" maxlength=\"128\" name=\"edit-crit_cherche\" id=\"edit-crit_cherche\" size=\"50\" value=\"$val_crit_cherche\" class=\"form-text pm-processed\" />\n
						</div><br/>\n";
					}
			 
			
			 $retour.="<div class=\"form-item\" id=\"edit-order-wrapper\">
						 <label for=\"edit-tri\">Trier par : </label>\n
						 <select name=\"edit-tri\" class=\"form-select pm-processed\" id=\"edit-tri\" >\n";
			 if (isset($desGet["edit-tri"]) && ( isset($desGet["op"]) && $desGet["op"] <> "Initialiser")){
				//$retour.="<option value=\"".$ce_tri."\" >".$ce_tri."</option>\n";	
					 
				foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ){
					$nomSimple= nomDuChampSimplifie($un_champ) ;
					$selectionne = $desGet["edit-tri"] == $nomSimple?" selected=\"selected\"":"";
					$retour.="<option value = \"$nomSimple\" $selectionne >$nomSimple</option>\n";
					
				}
			}
			else{//pas de tri
				
				$retour.="<option value=\"".$ce_tri."\"  selected=\"selected\" >".$ce_tri."</option>\n";	
					 
				foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ){
					$nomSimple= nomDuChampSimplifie($un_champ) ;
					$selectionne = $ce_tri == $nomSimple?" selected=\"selected\"":"";					
					$retour.="<option value = \"$nomSimple\" $selectionne >$nomSimple</option>\n";
					
				}
			}
			$retour.="</select></div>\n
				<div class=\"form-item\" id=\"edit-sort-wrapper\">\n
				 <label for=\"edit-asc_desc\">Ordre : </label>\n
				 <select name=\"edit-asc_desc\" class=\"form-select pm-processed\" id=\"edit-asc_desc\" >\n";
			 if((isset($desGet["op"]) && $desGet["op"]=="Appliquer") && isset($desGet["edit-asc_desc"])){
				 $tab_ordre=array("ASC"=>"<option value=\"ASC\" selected=\"selected\">Croissant</option>\n<option value=\"DESC\">Décroissant</option></select>\n",
												"DESC"=>"<option value=\"ASC\" >Croissant</option>\n<option value=\"DESC\" selected=\"selected\">Décroissant</option></select>\n");
				$retour.=$tab_ordre[$desGet["edit-asc_desc"]];
			 }
			 else{
				 $affiche = array("ASC"=>"Croissant","DESC"=>"Décroissant");
				 foreach($asc_desc as $cle =>$val){
					 $selectionne = $cle == $cetOrdre?" selected=\"selected\"":"";
						
					$retour.="<option value=\"$cle\" $selectionne >$affiche[$cle]</option>\n";
				}
				$retour.="</select>\n";
			 }
			 
			$retour.="</div>\n
			<input id=\"edit-pages-apply\" class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\" type=\"submit\" value=\"Appliquer\"  name=\"op\"/>\n
			<input id=\"edit-pages-reset\" class=\"form-submit ctools-use-ajax ctools-use-ajax-processed\" type=\"submit\" value=\"Initialiser\" name=\"op\"  />\n
			<input type=\"hidden\" name=\"form_id\" id=\"edit-page-manager-list-pages-form\" value=\"page_manager_list_pages_form\"  />
			<input type=\"hidden\" name=\"qui\" value=\"".$desGet["qui"]."\" />\n
			<input type = \"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\"/>\n
			<input type = \"hidden\" name = \"table\" value = \"".$desGet["table"]."\" />\n
			<input type = \"hidden\" name = \"action\" value = \"Rechercher\" />\n
			</div></div>\n</form>\n";
			
			$retour.="<table class=\"sticky-header\" style=\"position: fixed; top: 0px; left: 405px; visibility: hidden;\">\n
							<thead>\t\n
							  <tr>\t\t\n";
			foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ){
				$nomSimple= nomDuChampSimplifie($un_champ) ;
				$retour.="<th class=\"page-manager-page-type\" style=\"width: ".$len_des_champs[$un_champ]."\">$nomSimple</th>\t\t\t\n";
			}
			$retour.="</tr>\t\t\n
							</thead>\t\n
						  </table>\n";
		  
		  
		  
		  
		  
		  
		  
		  	
		  if(mysqli_num_rows($resultat)){
			  
			$retour.="<h1 class=\"title\">".$desGet["table"]."</h1>";
			if(mysqli_num_rows($resultat)==1){
				$retour.="<h3>".mysqli_num_rows($resultat)." enregistrement trouvé sur $nbEnrTotal</h3>";
			}
			else{
				$retour.="<h3>".mysqli_num_rows($resultat)." enregistrements trouvés sur $nbEnrTotal</h3>";
			}
			  
			
			
			 $retour.="<table id=\"page-manager-list-pages\" class=\"sticky-enabled\" >\n
							<thead><tr>\n";
			foreach($GLOBALS["champs_liste"][$desGet["table"]]["champs"] as $un_champ){
				$nomSimple= nomDuChampSimplifie($un_champ) ;
				$retour.="<th class=\"page-manager-page-type\">$nomSimple</th>\n";
			}
			$retour.="<th class=\"page-manager-page-type\">action</th>\n</tr></thead>\n
							<tbody>\n";
			$pair_impair ="odd";
			//echo "transmettre = $l_transmettreGet<br/>";
			
			while ($ligne = mysqli_fetch_assoc($resultat)) {
				if( !empty($ligne["nul"])){
					$retour.="<tr class=\"page-task-blog page-manager-nul\" >\n";
				}
				else{
				  $retour.="<tr class=\"page-task-blog page-manager-disabled ".$pair_impair."\" >\n";
			    }
				  foreach($ligne as $une_cle =>$une_val){
					  if($une_cle == "client"){
						  $test= array();
						
						foreach($desGet as $cle => $val){
							switch($cle){
								case "qui":
									$test[$cle]=$val;
								break;
								case "passe":
									$test[$cle]=$val;
								break;
								case "table":
									$test[$cle]="adresses";
								break;
								default:
								break;
							}
						}
						//rg120326$test["crit"]= $desGet["iu_".$tab_champs[$un]["nom_du_champ"]];
						$test["crit"]= substr($une_val,0,strpos($une_val,"@"));
						$quoi = base64_encode(transmettreGet($test,false)."&action=Modifier");
						$retour.="<td class=\"page-manager-page-type\"><a href=\"admin.php?&quoi=$quoi\" >".substr($une_val,strpos($une_val,"@")+1)."</a></td>\n";	
					  }
					  else{
						  if($une_cle == "categorie" ){
							  $categorie = $une_val;
						  }
						$retour.= "<td class=\"page-manager-page-type\">$une_val</td>\n";
					}
				  }
				  $retour.="<td class=\"page-manager-page-operations\" nowrap >";//rg20121128<ul class=\"links\">\n";
				  $i=0;
				  $dernier= count($GLOBALS["actionsSurEnr"][$desGet["table"]])-1;
				  
				  foreach($GLOBALS["actionsSurEnr"][$desGet["table"]] as $action=>$tab){
					  //echo str_replace("table=".$desGet["table"],"table=".$tab,$l_transmettreGet)."&crit=".$ligne["id"]."&action=$action<br/>";
					  if($action == "Annuler" && $ligne["nul"]==1 && $ligne["auteur"]<>$desGet["qui"]){
						  $action ="";
					  }
					  $tableO= $desGet["table"];
					  $adresse_id= isset($ligne["adresses_id"])?"&iu_adresses_id=".$ligne["adresses_id"]:"";
					  $quoi = base64_encode(str_replace("table=".$desGet["table"],"table=".$tab,$l_transmettreGet)."&crit=".$ligne["id"]."&action=$action".$adresse_id);
					  $ahref= ecrireAHref($tableO,$action,$quoi,$categorie);
							//$retour.="<li class=\"0 first\"><a href=\"admin.php?quoi=$quoi\">$val</a></li>\n";
					if(($action == "Annuler" || $action == "Modifier")  && (!empty($ligne["nul"]) || (!empty($ligne["auteur"]) && $ligne["auteur"]<>$desGet["qui"]))){}//si nul ou créé par auteur différent , ne peut être annulé
					else{		
					 if(strlen($ahref) && empty($ligne["nul"])){
					 	/*
					 	 switch ($i) {
					 	 case 0:
					 	 $retour.="<li class=\"0 first\"><a ".$ahref."</a></li>\n";
					 	 break;
					 	 case $dernier:
					 	 $retour.="<li class=\"$dernier last\"><a ".$ahref."</a></li>\n";
					 	 break;
					 	 default:
					 	 $retour.="<li class=\"$i middle\"><a ".$ahref."</a></li>\n";
					 	 break;
					 	 }
					 	 */
						 
							$retour.="<a ".$ahref."</a>\n";
							$i++;
						}
					}		
				 }
				 
				 $retour.="</td></tr>\n";//rg20121128$retour.="</ul>\n</td></tr>\n";
				 $pair_impair=$pair_impair=="odd"?"even":"odd";
			}
			
			$quoi = base64_encode($l_transmettreGet."&action=Ajouter");
			$retour.="</tbody>\n
							</table>\n";
							if(in_array("Ajouter",$GLOBALS["actionsGenerales"])){
								$retour.="<div id=\"page-manager-links\" class=\"links\"><ul class=\"links\"><li class=\"0 first last\"><a href=\"admin.php?&quoi=$quoi\" >&raquo; Ajouter un nouvel enregistrement</a></li>
								</ul></div>\n";
							}
 
			$retour.="</div>\n";

			
		}
		else{//mysqli_num_rows = 0
		    $quoi = base64_encode($l_transmettreGet."&action=Ajouter");
		    $retour.="<div><h3>Aucun enregistrement correspondant trouvé.<h3></div>\n";
		    if(in_array("Ajouter",$GLOBALS["actionsGenerales"])){
				$retour.="<div id=\"page-manager-links\" class=\"links\"><ul class=\"links\"><li class=\"0 first last\"><a href=\"admin.php?&quoi=$quoi\" >&raquo; Ajouter un nouvel enregistrement</a></li>
				</ul></div>\n";
			}
			
		}
		

	   /* Libération des résultats */
	   mysqli_free_result($resultat);
	   mysqli_close($un_lien);
       unset($un_lien);	 
	   
	   }
	   else{ //mysql_query = false
		   $class= " class=\"messages  error\"";
		   $messages[]="La requète na pu être exécutée";
		   $messages[]= mysqli_error($un_lien);
		   $messages[]= $req;
	   }
    
   }
	else{//ne trouve pas la base
	     $class= " class=\"messages  error\"";
		$messages[]="Ne trouve pas la base de données";
		$messages[]=mysqli_error($un_lien);
	}
    
  }//fin de connection
	else{ //impossible de connecter
		$class= " class=\"messages  error\"";
		$messages[]="Impossible de se connecter. Vérifier votre mot de passe.";
		$messages[]= mysqli_error($un_lien);
	}
	//$retour .= "</div >\n";
    foreach($messages as $un_message) {
		$retour.="<li>$un_message</li>\n";
	}       
	//$retour.="</ul>\n</div>";
	return $retour;
}

function ecrireAHref($la_table,$l_action,$le_quoi,$la_categorie){
	$ref="";
	
	  switch($l_action){
		  case "Imprimer commande":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\" align=\"middle\" />";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		  case "Imprimer facture":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\" align=\"middle\" />";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		case "Imprimer note":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\" align=\"middle\"/>";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		case "Imprimer recu":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\"  align=\"middle\" />";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		case "Imprimer pression":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\"  align=\"middle\" />";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		case "Imprimer prescription":
			$pos=strrpos($l_action," ");
			$url = substr($l_action,$pos +1).".php";
			$ref = "href=\"javascript:ouvre_popup('$url?quoi=$le_quoi',800,850)\" title = \"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/printer.png\" alt=\"$l_action\"  align=\"middle\" />";//$url.php?quoi=$le_quoi		echo "ref = $ref<br/>";
			break;
		case "+ visite":
			if($la_categorie == "client"){
				$ref =" href=\"admin.php?quoi=$le_quoi\" style=\"font-size: 7pt;\" title = \"$l_action\" ><img src=\"images/add.png\" align=\"middle\" title=\"$l_action\" /> ";
			}
			break;
		case "+ prescription":
			if($la_categorie == "client"){
				$ref =" href=\"admin.php?quoi=$le_quoi\" style=\"font-size: 7pt;\" title = \"$l_action\" ><img src=\"images/add.png\" align=\"middle\" title=\"$l_action\" /> ";
			}
			break;
		
		case "Annuler":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/b_drop.png\" align=\"middle\" title=\"$l_action\" /> ";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "Modifier la note":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/pencil.png\"  align=\"middle\"  title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "Modifier la pression":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/pencil.png\"  align=\"middle\"  title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "+ note":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/note_add.png\"  align=\"middle\"  title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "+ pression":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/heart_add.png\"  align=\"middle\"  title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "+ note vaccin":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/seringue.png\"  align=\"middle\"  title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
		case "Modifier":
			
				$ref =" href=\"admin.php?quoi=$le_quoi\"  title=\"$l_action\" style=\"font-size: 7pt;\"><img src=\"images/pencil.png\" align=\"middle\" title=\"$l_action\" />";//<font color =\"#cc0000\">".$l_action."</font>";
			
			break;
	   default:
			$ref =" href=\"admin.php?quoi=$le_quoi\" >".$l_action;
			break;
	  }
	  return $ref;
	
}

	
function transmettreGet($desGet,$tout){
	$retour= "";
	foreach($desGet as $cle => $variable){
		if($tout){
			if (isset($variable)){
			$retour.="$cle=$variable&";
			//echo "clé : $cle => $variable<br/>";
		} 
		else{
			
		} 
	}//tout
	else{
		if($cle == "action" || substr($cle,0,2)=="iu" || $cle == "id" || $cle == "op" || substr($cle,0,4)=="edit"){
			//echo "clé : $cle => $variable<br/>";
		}
		else{
			if (isset($variable)){
				$retour.="$cle=$variable&";
				//echo "clé : $cle => $variable<br/>";
			} 
			else{
				
			} 
		}
	}
}
	$retour = substr($retour,0,-1);
	return $retour;
	
	}
	
function PostGetStr($str,$type){
	  $VARS = explode(' ',$str);
	  $RESULT = '';
	  if($type == 'POST'){
		 foreach($VARS as $PV) if(isset($_POST[$PV])) {
		   $RESULT .= "$PV=".trim(strip_tags($_POST[$PV])).'&';
		   } else {$RESULT .= "$PV=&";} 
	  } elseif($type == 'GET'){
		 foreach($VARS as $PV) if(isset($_REQUEST[$PV])) {
		   $RESULT .= "$PV=".trim(strip_tags($_REQUEST[$PV])).'&';
		   } else {$RESULT .= "$PV=&";} 
	  } elseif($type == 'BOTH'){
		 foreach($VARS as $PV) if(isset($_REQUEST[$PV])) {
		   $RESULT .= "$PV=".trim(strip_tags($_REQUEST[$PV])).'&';
		 } else {$RESULT .= "$PV=&";} 
	  }
	  $RESULT = substr($RESULT,0,-1);
	  return $RESULT;
}

function decodeRequest(){
	$retour=array();
	foreach($_REQUEST as $cle => $val){
		if ($cle == "quoi"){
			$retourTemp = preg_split("/\&/",base64_decode($val)) ;
		   foreach($retourTemp as $unGet){   
			   $pos=strpos($unGet,"="); 
			   //echo "[".substr($unGet,0,$pos)."]=".substr($unGet,$pos + 1)."<br/>";    
			   $retour[substr($unGet,0,$pos)]=substr($unGet,$pos + 1);
		   } 
			
			
			/*
			$retourTemp  = preg_split("/\&/",base64_decode($val)) ;
			//echo "base 64 =".base64_decode($val)."<br/>";
			foreach($retourTemp as $vval){
				//echo"retour[".strstr($vval,"=",true)."]=".substr(strstr($vval,"="),1)."<br/>";
				
				$retour[strstr($vval,"=",true)]=substr(strstr($vval,"="),1);
			}
			*/
		}
		else{
		 $retour[$cle] = $val;
	 }
	}
	
	return $retour;
	
	
	}


function decodeGet($string){ 
   $string = preg_split("/\&/",base64_decode($string)) ;
   foreach($string as $unGet){   
	   $pos=strpos($unGet,"="); 
       //echo "[".substr($unGet,0,$pos)."]=".substr($unGet,$pos + 1)."<br/>";    
       $retour[substr($unGet,0,$pos)]=substr($unGet,$pos + 1);
   } 
   return $retour; 
 } 

function premier_acces($desGet){
	$avecSucces= false;
			if(!empty($desGet["nouv_passe"])){
				$demanderNouvPasse = true;
				$retour="";
			   /* Connexion et sélection de la base */
				$con = new Lien($desGet["qui"],$desGet["passe"]);
				$un_lien= $con->getLien();
			   /* $un_lien = mysqli_connect("127.0.0.1", $desGet['qui'], $desGet['passe'],"allbert")
				   or die("<br/><p class='echec'>Impossible de se connecter</p><br/>");
			    */
				mysqli_select_db($un_lien,$GLOBALS["accueil"]["base"]) or die("Ne trouve pas la base de données");
			
			   /* Exécuter des requètes SQL */
			   
			   $query = "SET PASSWORD = PASSWORD('".$desGet["nouv_passe"]."')";
			   if( mysqli_query($un_lien,$query) ){
				    $avecSucces = "oui";
				   }
				   else{
					    $avecSucces = "non";
					   }  
			   
			  mysqli_close($un_lien);
                unset($un_lien);
		}	   
			
	$retour="<div id=\"main-wrapper\">
        <div id=\"main\" class=\"clearfix\">
			<div id=\"content-wrapper\">
			
				<div id=\"content\">
				<div id=\"content-inner\">";
				if($avecSucces == "oui"){
					ecrire_requete("-- ".$desGet["qui"]." passe : ".$desGet["passe"]." change de mot de passe \n ".$query);
						  $class =" class=\"messages status\"";
							$retour .= "<div $class>\n<ul>";
							$retour.="<li>Votre mot de passe a été modifié avec succès.<br/>Veuillez vous reconnecter</li>\n";   
							$retour.="</ul>\n</div>";
							$demanderNouvPasse=false;
					   }
					   elseif($avecSucces== "non"){
						   $class= " class=\"messages  error\"";
							$retour .= "<div $class>\n<ul>";
							$retour.="<li>Le mot de passe n'a pas été changé.</li>\n";
							$retour.="</ul>\n</div>";
					   }
						
					
                                $retour.="<h1 class=\"title\">Compte administrateur</h1>
                                <div id=\"content-content\">
										<form action=\"admin.php\"   method=\"post\" id=\"a_valider\" name=\"a_valider\" >
											<div>
												<div class=\"form-item\" id=\"edit-name-wrapper\">
													<label for=\"edit-name\">Nom d'utilisateur : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													<input type=\"text\" maxlength=\"20\" name=\"qui\" id=\"edit-name\" size=\"20\" value=\"\" class=\"form-text required\" onkeypress=\"return handleEnter(this, event)\" />
													<div class=\"description\">Saisissez votre nom d'utilisateur pour Aux petits soins d'AllBert.</div>
												</div>
												<div class=\"form-item\" id=\"edit-pass-wrapper\">
													 <label for=\"edit-pass\">Mot de passe : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													 <input type=\"password\" name=\"passe\" id=\"edit-pass\"  maxlength=\"20\"  size=\"20\"  class=\"form-text required\"  onkeypress=\"return handleEnter(this, event)\"  />
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
      return $retour;
	
	}
	
	function changer_passe($desGet){
		
		$retour="<div id=\"main-wrapper\">
        <div id=\"main\" class=\"clearfix\">
			<div id=\"content-wrapper\">
			
				<div id=\"content\">
				<div id=\"content-inner\">
                                <h1 class=\"title\">Mot de passe</h1>
                                <div id=\"content-content\">
										<form action=\"admin.php\"   method=\"post\" id=\"a_valider\" name=\"a_valider\" >
											<div>
												<div class=\"form-item\" id=\"edit-passe-wrapper\">
													<label for=\"edit-passe\">Nouveau mot de passe : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													<input type=\"password\" maxlength=\"20\" name=\"nouv_passe\" id=\"nouv_passe\" size=\"20\"  class=\"form-text\"  />
													<div class=\"description\">Saisissez votre nouveau mot de passe</div>
												</div>
												<div class=\"form-item\" id=\"edit-passe_encore-wrapper\">
													 <label for=\"edit-passe_encore\">Entrez encore : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													 <input type=\"password\" name=\"passe_encore\" id=\"passe_encore\"  maxlength=\"20\"  size=\"20\"  class=\"form-text\"  />
													 <div class=\"description\">Saisissez encore votre nouveau mot de passe</div>
												</div>
												<input type=\"submit\" name=\"soumettre\" id=\"edit-submit\" value=\"Changer\"  class=\"form-submit\" />
												<input type=\"hidden\" name = \"qui\" value = \"".$desGet["qui"]."\" />
												<input type=\"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\" />
												<input type=\"hidden\" name = \"action\" value = \"Changer\" />
											</div>
										</form>
								</div><!-- /content-content -->
              </div><!-- /content-inner -->
            </div><!-- /content -->

		</div><!-- /main -->
</div><!-- /main-wrapper -->";
return $retour;
		
		}
		
		
	function rapport_annuel($desGet){
		
		$annees=requeteSelect("SELECT DISTINCT(YEAR(la_date))as annee FROM `factures` WHERE nul= 0 ORDER BY annee DESC ");
		
		$retour="<div id=\"main-wrapper\">
        <div id=\"main\" class=\"clearfix\">
			<div id=\"content-wrapper\">
			
				<div id=\"content\">
				<div id=\"content-inner\">
                                <h1 class=\"title\">Rapport annuel</h1>
                                <div id=\"content-content\">
										<form action=\"rapport_annuel.php\"   target=\"_blank\" method=\"post\" id=\"a_valider\" name=\"a_valider\" >
											<div>
												<div class=\"form-item\" id=\"edit-annee-wrapper\">
													<label id=\"l-edit_annee\" for=\"edit_annee\">annee : <span class=\"form-required\" title=\"Ce champ est obligatoire.\">*</span></label>
													<select   id=\"edit_annee\" name = \"annee\" class=\"form-select required digits\"  style=\"width: 80px\">
													\t\t<option ></option>\n";
                  foreach ($annees as $annee) {
                             $retour.="\t\t<option value = ".$annee["annee"].">".$annee["annee"]."</option>\n"; 
                  }
                  $retour.="</select>";
													
													
													
													
													$retour.="<div class=\"description\">pour quelle annee</div>
												</div>
												<input type=\"submit\" name=\"soumettre\" id=\"edit-submit\" value=\"Rapport annuel\"  class=\"form-submit\" />
												<input type=\"hidden\" name = \"qui\" value = \"".$desGet["qui"]."\" />
												<input type=\"hidden\" name = \"passe\" value = \"".$desGet["passe"]."\" />
												<input type=\"hidden\" name = \"action\" value = \"rapport_annuel\" />
											</div>
										</form>
								</div><!-- /content-content -->
              </div><!-- /content-inner -->
            </div><!-- /content -->

		</div><!-- /main -->
</div><!-- /main-wrapper -->";
return $retour;
		
		}
	
	
	function nomDuChampSimplifie($champ){
		
		$simple =$champ;
		$test = array("as ");
		foreach($test as $unCas){
			$pos = strrpos($champ,$unCas);
			if ($pos === false){}
			else{ 
				$simple = substr($champ,$pos+strlen($unCas));
				break;
			}
		}
		//echo"simple avant $simple";
		$simple= strstr($simple,".")?substr(strstr($simple,"."),1):$simple;
		//echo" - simple après $simple<br>";
		return $simple;
		
		}
		
function noDeFacture($desGet){
			$la_date = $desGet["iu_la_date"];
			$auteur =$desGet["iu_auteur"];
			$noDeFacture= 1;
			
			if($la_date < "2012-01-01"){
				
				switch($auteur){
					case "all";												
					$dernier = requeteSelect("SELECT IFNULL(MAX(id),0) as no FROM ".$desGet["table"]." WHERE id < 100");
					break;
					case "bert";												
					$dernier = requeteSelect("SELECT IFNULL(MAX(id),100) as no  FROM ".$desGet["table"]." WHERE id BETWEEN 100 AND 200");
					break;
				}
			}
			else{
				
				$dernier = requeteSelect("SELECT IFNULL(MAX(id),200) as no  FROM ".$desGet["table"]." WHERE id > 200");
				
			}
			$noDeFacture += $dernier[0]["no"];
			return $noDeFacture;
			
}

function noDeVisite($desGet){
			$la_date = $desGet["iu_la_date"];
			$auteur =$desGet["iu_auteur"];
			$noDeVisite= 0;
			
			$dernier = requeteSelect("SELECT MAX(id) as no  FROM ".$desGet["table"]." WHERE id > 200");

			$noDeVisite += $dernier[0]["no"];
			return $noDeVisite;
			
}
?>



