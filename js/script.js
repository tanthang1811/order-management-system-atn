const showCustomerBtn = document.querySelector("#show-customers-btn");
const showProductBtn = document.querySelector("#show-products-btn");
const customerList = document.querySelector(".customer-list");
const productList = document.querySelector(".product-list");

const resetAppearance = () => {
    productList.classList.remove("appear");
    customerList.classList.remove("appear");
    productList.classList.remove("display-block");
    customerList.classList.remove("display-block");
}

showCustomerBtn.addEventListener('click', (e) => {
    resetAppearance();
    customerList.classList.add("display-block");
    setTimeout(() => {
        customerList.classList.add("appear");
    }, 200);
})

showProductBtn.addEventListener('click', (e) => {
    resetAppearance();
    productList.classList.add("display-block");
    setTimeout(() => {
        productList.classList.add("appear");
    }, 200);
})