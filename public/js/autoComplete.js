
function loadNames() {
    var text = document.getElementById("input-products").value;
    if (text.length > 1) {
        fetch('/product/search-product', {
            method: 'POST',
            headers: {'Content-type': 'application/x-www-form-urlencoded'},
            body: 'text=' + text
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (myJson) {
                var autoComplete = document.getElementById("autoComplete");
                autoComplete.innerHTML = "";
                autoComplete.style.display = "block";
                for(var i = 0; i < myJson.length; i++){
                    autoComplete.innerHTML += '<a href="/product/view-product?productId=' + myJson[i].id + '">' + myJson[i].name + '</a></br>';
                }
            })
            .catch(function (e) {
                alert(e.message);
            })
    }
    else{
        var autoComplete = document.getElementById("autoComplete");
        autoComplete.innerHTML = "";
        autoComplete.style.display = "none";
    }
}

function loadProducts() {
    var text = document.getElementById("input-products2").value;
    if (text.length > 1) {
        fetch('/product/search-product', {
            method: 'POST',
            headers: {'Content-type': 'application/x-www-form-urlencoded'},
            body: 'text=' + text
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (myJson) {
                var autoComplete = document.getElementById("autoComplete2");
                autoComplete.innerHTML = "";
                autoComplete.style.display = "block";
                for(var i = 0; i < myJson.length; i++){
                    autoComplete.innerHTML += '<a href="/user/view-edit-product?productId=' + myJson[i].id + '">' + myJson[i].name + '</a></br>';
                }
            })
            .catch(function (e) {
                alert(e.message);
            })
    }
    else{
        var autoComplete = document.getElementById("autoComplete2");
        autoComplete.innerHTML = "";
        autoComplete.style.display = "none";
    }
}
