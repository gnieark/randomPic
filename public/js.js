function submitKeyWord( keyword )
{
    let data = {'keyword': keyword };

    fetch("/",
    {
        method: "POST",
        body: JSON.stringify( data )
    })
    .then(function(res){ return res.json(); })
    .then(function(data){ 
        alert( data['message'] ); 
        document.querySelector("#keywords").value = "";
    })

}