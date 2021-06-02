function get_address_domicile(){
	return (user_data['ADRESS'] + ", " + user_data['POSTAL_CODE'] + ", " + user_data['CITY']);
}

function get_address_travail(){
	return (user_data['WORK_ADRESS'] + ", " + user_data['WORK_POSTAL_CODE'] + ", " + user_data['WORK_CITY']);
}
