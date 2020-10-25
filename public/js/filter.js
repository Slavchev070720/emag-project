function filter(page = 1) {
    var priceOrder = document.getElementById("priceFilter").value;
    var brand = document.getElementById("brandFilter").value;
    window.location = "/product/all-products?priceOrder=" + priceOrder + "&brand=" + brand + "&page=" + page;
}