<?php 

/**
 * @author AMORIN
 *
 */
interface Klee_Module_Commun_Service_Interface_IServiceFichier
{
    /**
     * Retourne le fichier à partir de {$ficId}, sinon {NULL}.
     * 
     * @param int $ficId Identifiant du fichier
     * @return array|NULL
     */
    public function getFichier($ficId);
    
    /**
     * Retourne le fichier image à partir de {$fiiId}, sinon {NULL}.
     * 
     * @param int $fiiId Identifiant du fichier image.
     * @return array|NULL
     */
    public function getFichierImage($fiiId);
}