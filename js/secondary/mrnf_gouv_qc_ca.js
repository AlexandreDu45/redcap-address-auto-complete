STPH_addressAutoComplete.performSecondaryAction = function(id) {

    return new Promise((resolve, reject) => {

        var api = STPH_addressAutoComplete.configuration.api;
        var secondary_url = api.url_base + "/pes/rest/services/Territoire/Adresse_Geocodage/GeocodeServer/findAddressCandidates?magicKey=" + id + "&f=pjson&outFields=Name,ZIP,City,Num,Odonyme,Dir,Unite,SufNum";
        
        $.ajax({
            url: secondary_url,
            dataType: "json",
            success: function(data){                
                //  Adjust format here.
                const candidate = data.candidates[0];

                var response = {
                    street: candidate.attributes.Odonyme,
                    number: candidate.attributes.Num,
                    apt: candidate.attributes.Unite,
                    code: candidate.attributes.ZIP,
                    city: candidate.attributes.City,
                    country: 'Canada',
                    x: candidate.location.x,
                    y: candidate.location.y
                };

                resolve(response)
            },
            error: function (error) {
                reject(error)
            },
        })

    })
}