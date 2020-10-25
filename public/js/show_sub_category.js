
function getSubCategory(name) {
    fetch('/category/sub-category', {
        method: 'POST',
        headers: {'Content-type': 'application/x-www-form-urlencoded'},
        body: 'category=' + name
    }).then(function (response) {
        return response.json();
    })
        .then(function (myJson) {
            document.getElementById("subCategories").style.display = 'flex';
            var buttons_div = document.getElementById("subCategories");
            buttons_div.innerHTML = "";
            for (var i = 0;i < myJson.length; i++){
                var button = document.createElement("button");
                button.value = myJson[i]["name"];
                button.id = 'subCategoriesButtons';
                button.innerHTML = myJson[i]["name"];
                var subCat =  myJson[i]["name"];
                button.addEventListener('click', function (subCat) {
                    return function () {
                        getProducts(subCat);
                    }
                }(subCat));
                buttons_div.appendChild(button);
            }
        })
        .catch(function (e) {
            alert(e.message);
        })
}
function getProducts(name){
    window.location = "/product/all-products?subCat=" + name ;
}