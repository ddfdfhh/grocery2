function initFilePreviewEvent(container_id=null) {
  if(container_id){
        $("#"+container_id+" input[type=file]").each(function () {
            let el = this;
            if ($(el).attr("multiple")) {
                $(el).filer({
                    showThumbs: true,
                    addMore: true,
                
                    allowDuplicates: false,
                });
            } else {
            
                $(el).change(function () {
                    let f = this;
                    const file = f.files[0];
                
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            let y = event.target.result;
                            $(f)
                                .parent()
                                .append(
                                    `<img src='${y}'  class='img_rounded' style='width:100px;height:100px;margin:5px' />`
                                );
                        };
                        reader.readAsDataURL(file);
                    }
                });
            
        }});
    }
        else{
            $("input[type=file]").each(function () {
                let el = this;
                if ($(el).attr("multiple")) {
                    $(el).filer({
                        showThumbs: true,
                        addMore: true,
                    
                        allowDuplicates: false,
                    });
                } else {
                
                    $(el).change(function () {
                        let f = this;
                        const file = f.files[0];
                    
                        if (file) {
                            let reader = new FileReader();
                            reader.onload = function (event) {
                                let y = event.target.result;
                                $(f)
                                    .parent()
                                    .append(
                                        `<img src='${y}'  class='img_rounded' style='width:100px;height:100px;margin:5px' />`
                                    );
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                
            }});
        }
}
