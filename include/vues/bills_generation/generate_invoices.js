function get_list_of_companies_bills(){
    $.ajax({
        url: 'apis/Kameoget_list_of_companies_bills.php',
        method: 'get',
        data: {
            'action': 'list'
        },
        success: function(response) {
        }
    })
}