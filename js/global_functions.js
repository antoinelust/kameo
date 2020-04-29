String.prototype.shortDate=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2);
};

String.prototype.shortDateHours=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2)+" "+this.substr(11,2)+":"+this.substr(14,2);
};
String.prototype.shortHours=function(){
    return this.substr(11,2)+":"+this.substr(14,2);
};


function get_dateNow_string(){
    var dateNow=new Date(Date.now());
    var year=dateNow.getFullYear();
    var month=("0" + (dateNow.getMonth()+1)).slice(-2)
    var day=("0" + dateNow.getDate()).slice(-2)
    var dateNowString=year+"-"+month+"-"+day;
    return dateNowString;

}