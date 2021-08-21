$("#shopping_bag_count").html(localStorage.getItem("bag_items").toString());


const setBagItem = () => {
    $("#shopping_bag_count").html(localStorage.getItem("bag_items")
        .toString());
}