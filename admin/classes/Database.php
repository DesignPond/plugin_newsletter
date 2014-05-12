<?php

class Database{

	function __construct() {
			
		// Set tables		
		$this->nouveautes_table    = 'wp_nouveautes';

		$this->categories_table    = 'wp_custom_categories';

		$this->subcategories_table = 'wp_subcategories';
	}
	
	/*
	 * Get all arrets for date
	 * @return array
	*/
	public function getArretsAndCategoriesForDates($date){
	
		global $wpdb;
		
		$categories = array();
		$arrets     = array();
		$new        = array();
		
		// Find if we passed a single date or a range	
		$when = ( is_array($date) ? ' BETWEEN "'.$date[0].'" AND "'.$date[1].'"' : ' = "'.$date.'"' );
		
		$listArrets = $wpdb->get_results('SELECT '.$this->nouveautes_table.'.id_nouveaute , 
												 '.$this->nouveautes_table.'.datep_nouveaute , 
												 '.$this->nouveautes_table.'.dated_nouveaute , 
												 '.$this->nouveautes_table.'.categorie_nouveaute , 
												 '.$this->nouveautes_table.'.link_nouveaute , 
												 '.$this->nouveautes_table.'.numero_nouveaute , 
												 '.$this->nouveautes_table.'.publication_nouveaute , 
												 '.$this->categories_table.'.name as nameCat , 
												 '.$this->subcategories_table.'.name as nameSub 
										  FROM '.$this->nouveautes_table.' 
										  JOIN '.$this->categories_table.'  on '.$this->categories_table.'.term_id  = '.$this->nouveautes_table.'.categorie_nouveaute 
										  LEFT JOIN '.$this->subcategories_table.' on '.$this->subcategories_table.'.refNouveaute = '.$this->nouveautes_table.'.id_nouveaute 
										  WHERE '.$this->nouveautes_table.'.publication_nouveaute = 1 
										  AND   '.$this->nouveautes_table.'.datep_nouveaute '.$when.'');	

		if(!empty($listArrets))
		{
			foreach ($listArrets as $arret) 
			{
				$new['id_nouveaute']          = $arret->id_nouveaute;
				$new['datep_nouveaute']       = $arret->datep_nouveaute;
				$new['dated_nouveaute']       = $arret->dated_nouveaute;
				$new['categorie_nouveaute']   = $arret->categorie_nouveaute;
				$new['nameCat']               = $arret->nameCat;
				$new['nameSub']               = $arret->nameSub;
				$new['link_nouveaute']        = $arret->link_nouveaute;
				$new['numero_nouveaute']      = $arret->numero_nouveaute;
				$new['publication_nouveaute'] = $arret->publication_nouveaute;
				
				$arrets[$arret->id_nouveaute] = $new;	
			}
		}
		
		return $arrets;										  
	}
	
	// Get 5 last week days
	public function getWeekDays(){
	
		global $wpdb;
		
		$week  = array();
		
		$dates = $wpdb->get_results(' SELECT datep_nouveaute FROM '.$this->nouveautes_table.' GROUP BY datep_nouveaute ORDER BY datep_nouveaute DESC LIMIT 0,5 ');
		
		if( !empty($dates) )
		{
		   foreach($dates as $date)
		   {
		   	   $week[] = $date->datep_nouveaute;
		   }
		}
		
		$range[] = array_pop($week);
		$range[] = array_shift($week);					
		
		return $range;
	}
	
	public function arrangeDate($dates){
	
		if (setlocale(LC_TIME, 'fr_FR') == '') 
		{
	    	setlocale(LC_TIME, 'FRA');  //correction problème pour windows
	    	$format_jour = '%#d';
		} 
		else 
		{
	   		$format_jour = '%e';
		}
		
		$datedebut = $dates[0];
		$datefin   = $dates[1];
		
		$date = '';
	
        //explode pour mettre la date du fin en format numerique: 12/05/2006  -> 12052006
		$dfin  = explode("-", $datefin); 
        //explode pour mettre la date du jour en format numerique: 31/05/2009  -> 31052009
        $djour = explode("-", $datedebut); 
        // concaténation pour inverser l'ordre: 12052006 -> 20060512
		$finab = $dfin[2].$dfin[1].$dfin[0];
	    // concaténation pour inverser l'ordre: 31052009 -> 20090531
		$auj   = $djour[2].$djour[1].$djour[0]; 
		$aujd  = $djour[0].$djour[1]; 

		// Ensuite il suffit de comparer les deux valeurs
	
		if ($auj==$finab)
		{	
			$date .= strftime("$format_jour %B %Y", strtotime($datedebut));
			// affiche : vendredi 18 avril 2008
		}
		else
		{
			$date .= strftime("$format_jour %B", strtotime($datedebut));
			$date .= ' au ';
			$date .= strftime("$format_jour %B %Y", strtotime($datefin));
		}
		
		return $date;	
	
	}
	
}