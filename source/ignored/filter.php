<?php

if($_SESSION["role"] == 2){
	// only treasurers can switch mode
	
	// include the banner and the title first
	
	if($view == 1){
		// student's view
		if($p == 1){// payments
			
			
		}elseif($p == 2){// profile
			
			
		}elseif($p == 3){// settings
			
			
			// sub
			if($s == 1){// change username
				
			}elseif($s == 2){// change password
				
				
			}
		}
		
	}elseif($view == 2){
		// treausres' view
		if($p == 1){// summary
			
			
		}elseif($p == 2){// students
			
			
			// sub
			if($s == 1){// name list
				
			}elseif($s == 2){// add students
				
			}elseif($s == 3){// edit students' profile
				
			}elseif($s == 4){// edit students' payment information
				
			}elseif($s == 5){// delete students
				
			}elseif($s == 6){// migrate students
				
			}
			
		}elseif($p == 3){// payments
		
			// sub
			if($s == 1){// payment list
				
			}elseif($s == 2){// add payments
				
			}elseif($s == 3){// edit payments information
				
			}elseif($s == 4){// delete payments
				
			}
			
			
		}elseif($p == 4){// expences
		
			// sub
			if($s == 1){// view expences
				
			}elseif($s == 2){// add expences
				
			}elseif($s == 3){// edit expences information
				
			}elseif($s == 4){// delete expences
				
			}
			
			
		}elseif($p == 5){// profile
			
			
		}elseif($p == 6){// settings
			
			// sub
			if($s == 1){// change username
				
			}elseif($s == 2){// change password
				
			}
			
		}
		
	}
}else{// $_SESSION["role"] == 1
	
	// students page
	
	// include the banner and the title first
	
	if($p == 1){// payments
		
	}elseif($p == 2){// profile
		
	}elseif($p == 3){// settings
		
		// sub
		if($s == 1){// change username
			
		}elseif($s == 2){// change password
			
		}
		
	}
	
}


?>