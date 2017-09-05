<?php
    /* Libchart - PHP chart library
     * Copyright (C) 2005-2013 Jean-Marc Tr�meaux (jm.tremeaux at gmail.com)
     * 
     * This program is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     * 
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with this program.  If not, see <http://www.gnu.org/licenses/>.
     * 
     */
    
    /**
     * The default label generator simply uses strval() to convert the value.
     *
     * @author Jean-Marc Trémeaux (jm.tremeaux at gmail.com)
     */
    class DefaultLabelGenerator implements LabelGenerator {
        
        public function generateLabel($value) {
		if($value==0){$value="";} 
            return strval($value);
        }
        
    }
	
// Ajout : convertir les labels en h:mn
class TimeLabelGenerator implements LabelGenerator {
        function generateLabel($value) {
			if($value < 60)
				{
				  $heures = 0;
				  $minutes= $value;
				} else {
				  $heures  = floor(($value)/60);
				  $minutes = $value-($heures*60) ;
				}
				
				if ($minutes == 0)
				{
					$time = $heures."h" ;
				} else {
					if ($heures == 0)
					{
						$time = $minutes."mn" ;
					} else {
						$time = $heures."h".$minutes;
					}
				}
			if($value==0){$time="";} //"%02dh%02d"
			
			  //return sprintf($time, (int) ($value / 60), (int) ($value % 60));
            return $time;
        }
    }
?>