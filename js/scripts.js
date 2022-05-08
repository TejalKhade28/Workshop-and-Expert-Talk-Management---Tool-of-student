var details = "";
function schedule_details(e){
    let str = e;
    details = str.split("|");
    alert("Now fill the GAP sheet.");
    //console.log(details);  
}

$(document).ready(function(){
    $('#RegisterBtn').click(function(e){
        e.preventDefault();

        var _self = $(this);
    
        var schedule_Id = _self.data('id');
        // if topic name = empty, then break;
        if (details != ""){
            document.getElementById("gap_schedule_id").value = details[0];
            for (let i = 2; i < details.length; i++){
                //console.log('gap'+i);
                //console.log(details[i]);
                document.getElementById('gap'+(i-1)).value = details[i];
            }
            //console.log("break");
            //document.getElementsByTagName('event_type').value = details[0];
            //document.getElementById("roll").value = details[0];
            //
            //sdocument.getElementById("gap_schedule_topic").value = details[1];
        
            //console.log(schedule_Id);
            //console.log("Hello");
            //console.log(topic);
            $('#register-modal').modal('show');
        } else {
            $('#register-modal').modal({show:false});
            alert("Please select schedule!!!");
        }
    });   
});
