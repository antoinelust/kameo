String.prototype.shortDate=function(){
    return this.substr(8,2)+"/"+this.substr(5,2)+"/"+this.substr(2,2);
}