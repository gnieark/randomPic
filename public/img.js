function loadImg(){
    fetch("/currentImgInfos",
    {
        method: "GET"
    })
    .then(function(res){ return res.json(); })
    .then(function(data){ 
        alert( data['message'] ); 
        //document.querySelector("#keywords").value = "";
    })

}