$( document ).ready(function() {      
    if( $('.foothead').css('font-weight')=='700') {
        console.log("mobile");
        $(".columns > *").attr("data-aos-delay","0");
        $(".foothead").attr("data-aos","none");
    }
});