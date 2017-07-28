<?php
/*
     This file is part of CyberGestionnaire.

    CyberGestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    ///**********Fichier de modification d'une transaction *************///
    // error_log('in post_transac.php');
    // error_log("---- POST ----");
    // error_log(print_r($_POST, true));
    // error_log("---- GET  ----");
    // error_log(print_r($_GET, true));
    
    require_once("include/class/Utilisateur.class.php");
    require_once("include/class/Tarif.class.php");
    require_once("include/class/Impression.class.php");

    $id_user     = isset($_GET["iduser"])       ? $_GET["iduser"] : '';
    $transac     = isset($_GET["idtransac"])    ? $_GET["idtransac"] : '';
    $b           = isset($_GET["b"])            ? $_GET["b"] : '';
    $typeTransac = isset($_GET["typetransac"])  ? $_GET["typetransac"] : '';
    $statutp     = isset($_POST["statutprint"]) ? $_GET["statutprint"] : '';
    
    //debug($statutp);

    switch ($typeTransac) {
        ///impressions  

        case "p":
            if (isset($_POST["submit"])){
                $datep    = isset($_POST["date"])          ? $_POST["date"] : "";
                $debitp   = isset($_POST["debitprint"])    ? $_POST["debitprint"] : "";
                $tarifp   = isset($_POST["tarifprint"])    ? $_POST["tarifprint"] : "";
                $creditp  = isset($_POST["creditprint"])   ? $_POST["creditprint"] : "";
            //  $statutp = $_POST["statutprint"];
                $nomuserp = isset($_POST["nomuser"])        ? $_POST["nomuser"] : "";
                $moyen_p  = isset($_POST["moyen_paiement"]) ? $_POST["moyen_paiement"] : "";

                if ($statutp < 2) {
                    if ($_POST["submit"] == "Encaisser") {
                        $statutp = "1";
                    } else {
                        $statutp = "0";
                    }
                } else {
                    $statutp = "2";
                }
                $impression = Impression::getImpressionById($transac);
                // if (FALSE == modPrint($transac,$datep,$debitp,$tarifp, $statutp, $creditp,$nomuserp,$moyen_p)) {
                if ($impression->modifier($datep, $impression->getIdUtilisateur(), $debitp, $tarifp, $statutp, $creditp, $nomuserp, $impression->getIdEspace(), $impression->getIdCaissier(), $moyen_p)) {
                    header("Location: ./index.php?a=21&mesno=0");
                } else {
                    header("Location: ./index.php?a=21&b=1&iduser=" . $id_user . "");
                }
            }
        break;

    
        ///renouvellement adhésion
        case "adh":
            
            $daterenouv    = $_POST["daterenouv"];
            $datetransac   = $_POST["date"];
            $adhesiontarif = $_POST["tarif_adh"];
            $transac       = $_POST["idtransac"];
            $type_transac  = "adh";
            
            if (isset($_POST["submit"])) {
                    
                if ($_POST["submit"] == "Encaisser" ) {
                    $statutp="1";
                } else {
                    $statutp="0";
                }
                
            //renouvellement et nouvelle inscription, entrée dans la tab_ransac de la nouvelle transaction
            
            if (FALSE == addAdhesion($datetransac,$type_transac,$id_user,$adhesiontarif, $statutp))
                             {
                                 header("Location: ./index.php?a=1&mesno=0");
                             } else {
                                //modification de la date de renouvellement dans tab_user, actualisation du statut et du tarif
                                
                            if(FALSE==modifUserStatut($id_user,1, $daterenouv, $adhesiontarif))
                                {
                                     header("Location: ./index.php?a=1&mesno=0");
                                } else {
                                    header("Location: ./index.php?a=1&b=2&iduser=".$id_user."&mesno=26");
                                }
                             }
                        
                    
                }

          break;

        
        ///forfait
        case "forfait":
            error_log("Dans forfait !");
            //recuperation
            if (isset($_POST["submit"])) {
                error_log("Submit set !");
                $date          = $_POST["date"];
                $idTarif       = $_POST["tarif_forfait"];
                $nbredeforfait = $_POST["nbrf"];
                
                $tarif = Tarif::getTarifById($idTarif);
                
                //calcul du nombre d'atelier total pour le forfait choisi
                // $totalatelier  = getNbatelierbytarif($idTarif);
                // $nbatelier     = $nbredeforfait * $totalatelier;
                
                $nbatelier     = $nbredeforfait * $tarif->getNbAtelierForfait();
                $transac       = isset($_GET["idtransac"]) ? $_GET["idtransac"] : '';
                $type_transac  = "for";
                    
                if ($_POST["submit"] == "Encaisser" ) {
                    $statutp = "1"; //en cours
                } else {
                    $statutp = "0"; // en attente de paiement
                }
                error_log("transac = " . $transac );
                if ($transac != '') {
                    //modification
                    if (FALSE == modifForfaitUser($transac, $idTarif, $date, $nbredeforfait, $statutp, $nbatelier)) {
                        header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
                    } else {
                        header("Location: ./index.php?a=6&iduser=".$id_user."");
                    }   
                } else {
                    //creation
                    //$idtransac = addForfaitUser($type_transac, $id_user, $idTarif, $nbreforfait, $date, $statutp);
                    //$transaction = Transaction::creerTransaction($type_transac, intval($id_user), $idTarif, $nbreforfait, $date, $statutp);
                    $transaction = 0;
                    if ($transaction === null ) {
                         header("Location: ./index.php?a=6&mesno=0&iduser=" . $id_user . "");
                    } else {
                        ///verifier avant la participation aux ateliers et inscrementer pour régulariser !
                        $avalid  = getnbASUserEncours($id_user, 1);// total des ateliers et session validés
                        $utilisateur = Utilisateur::getUtilisateurById($id_user);
                        error_log("NbASEnCours = " . $avalid );
                        error_log("utilsateurASI -> " . $utilisateur->getNBAteliersEtSessionsInscrit());
                        error_log("utilsateurAI -> " . count($utilisateur->getAteliersInscrit()));
                        error_log("utilsateurSI -> " . count($utilisateur->getSessionDatesInscrit()));
                        error_log("utilsateurASP -> " . $utilisateur->getNBAteliersEtSessionsPresent());
                        error_log("utilsateurAP -> " . count($utilisateur->getAteliersPresent()));
                        error_log("utilsateurSP -> " . count($utilisateur->getSessionDatesPresent()));
                        $farchiv = getFUserArchiv($id_user);//anciens forfaits archivés à décompter..
                        $reste   = $avalid - $farchiv;
                        if ($reste > 0) {
                            $depense = $reste;
                        } else {
                            $depense = 0;
                        }
                        //
                        //addRelforfaitUser($id_user, $idtransac, $nbatelier, $depense, $statutp);
                        header("Location: ./index.php?a=6&iduser=" . $id_user . "");
                    }   
                }
            }

        break;

        case "temps" :
        //recuperation
            $date=$_POST["date"];
            $tarif_forfait=$_POST["tarif_forfait"];
            $nbreforfait=1;
            $type_transac="temps";
            $transac=$_GET["idtransac"];
            $nbatelier=0;
            
            if (TRUE == isset($_POST["submit"])){
                    
                if ($_POST["submit"] =="Encaisser" ){
                        $statutp="1"; //en cours
                        } else {
                        $statutp="0"; // en attente de paiement
                        }
            
            if(isset($transac)){
            //modification
                    $rowtransacuser=getForfait($transac);
                    $statut0=$rowtransacuser['status_transac'];
                    if (FALSE == modifForfaitUser($transac,$tarif_forfait,$date,$nbreforfait,$statutp,$nbatelier))
                     {
                         header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
                     } else {
                        if($statut0==0 and $statutp==1){ 
                                addrelconsultationuser(1,$tarif_forfait,$id_user);//ajouter la relation pour activer-epnconnect
                            }else if($statut0==1 and $statutp==1){
                                addrelconsultationuser(2,$tarif_forfait,$id_user);//modifier la relation si car forfait a changé..
                            }
                         
                         header("Location: ./index.php?a=6&iduser=".$id_user."");
                     }  
                     
                     
                } else {
                    //creation
                $idtransac=addForfaitUser($type_transac,$id_user,$tarif_forfait,$nbreforfait,$date,$statutp);
                
                if (FALSE ==$idtransac )
                     {
                         header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
                     } else {
                            //n'ajouter la relation pour epnconnect que si c'est encaissé
                            if($statutp==1){
                             addrelconsultationuser(1,$tarif_forfait,$id_user);
                         }
                            header("Location: ./index.php?a=6&mesno=26&iduser=".$id_user."");
                     }
                    
                    }
            }


        break;


    }
?>