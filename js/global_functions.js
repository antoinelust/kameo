String.prototype.shortDate=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2);
};

String.prototype.shortDateHours=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2)+" "+this.substr(11,2)+":"+this.substr(14,2);
};
String.prototype.shortHours=function(){
    return this.substr(11,2)+":"+this.substr(14,2);
};