// index.php

// the landing page search box DOM element
var searchBox = document.querySelector('.header-search-input');

// if the element exists, add the search event listener to dynamically filter home page
if(searchBox != null) {
  searchBox.addEventListener('input', handleSearch);
}

function handleSearch () {
  // obtain the user-specified search word and make lowercase
  var searchWord = document.querySelector('.header-search-input').value;
  searchWord = searchWord.toLowerCase();
  // obtain all product posts
  var posts = document.querySelectorAll(".fraud-post, .fraud-post-faux");
  // iterate through all product posts
  for(var i = 0; i < posts.length; i++) {
    // obtain the post title and description and make lowercase
    var postTitle = posts[i].querySelector('.fraud-title').textContent;
    var postDescription = posts[i].querySelector('.fraud-description').textContent;
    postTitle = postTitle.toLowerCase();
    postDescription = postDescription.toLowerCase();
    // if the post's title or description does not contain the user-search word
    if((postTitle.indexOf(searchWord) < 0) && (postDescription.indexOf(searchWord) < 0))
    {
        // add the 'hidden' keyword to the element's class List as well as all descendents
        var c = posts[i].querySelectorAll('*');
        posts[i].classList.add('hidden');
        // add the dummy class to obtain hidden posts
        posts[i].classList.add('fraud-post-faux');
        // iterate through descendents and add 'hidden' class
        for (var i2 = 0; i2 < c.length; i2++) {
          c[i2].classList.add('hidden');
        }
        // remove original styling to completely hide from DOM
        posts[i].classList.remove('fraud-post');
    }
    else // the post's title/description contains the user-search word
    {
      // remove the 'hidden' keyword from class and class of all descendents
        var c = posts[i].querySelectorAll('*');
        posts[i].classList.remove('hidden');
        posts[i].classList.remove('fraud-post-faux');
        // iterate through descendents and remove 'hidden' class
        for (var i2 = 0; i2 < c.length; i2++) {
          c[i2].classList.remove('hidden');
        }
        // give the original styling class back to element to re-introduce to DOM
        posts[i].classList.add('fraud-post');
    }
  }
}

// the edit button on all product posts
var editPostButtons = document.querySelectorAll('.edit-fraud-post-btn');
// the edit modal
var editModal = document.querySelector('.editModal');
// validationCode for product
var validationCode;
// id of product to be edited
var editProductId;

// add event Listener to all product post's edit buttons
editPostButtons.forEach(function(elem) {
  // when edit button clicked...
  elem.addEventListener("click", function () {
    // show the validation code modal
    editModal.classList.remove('hidden');
    // obtain the validation code that is hidden in the class list of the edit button
    var tokens = this.classList[1].split(/[ -]+/);
    validationCode = tokens[1];
    // obtain the product id from the product post class List
    var classList = elem.parentElement.parentElement.parentElement.parentElement.classList;
    classList.forEach(function(elem2) {
      if(elem2.includes('product-id')) {
        editProductId = elem2;
      }
    })
    // split by a space character
    tokens = editProductId.split(/[ -]+/);
    editProductId = tokens[2];
  })
});

// validate/cancel button present in the validation code modal
var cancelButton = document.querySelector('.cancelButton');
var validateButton = document.querySelector('.validateButton');

// if cancel button is clicked, simply hide the validation modal again
if(cancelButton != null) {
  cancelButton.addEventListener("click", function () {
    editModal.classList.add('hidden');
  });
}

// if the validation button is clicked, validate the user input before editing access
if(validateButton != null) {
  // add event listenter to validate button
  validateButton.addEventListener("click", function () {
    // obtain user input
    var userInput = document.querySelector('input[class="validationInput"]').value;
    // is the user input matches the product's validation code
    if(userInput == validationCode) {
      // launch the edit page for that specific product
      var url = 'editFraud.php?productId='+editProductId;
      window.open(url,"_self");
    } else { // user input failed validation
      // remove the user input from the modal
      document.querySelector('input[class="validationInput"]').value = '';
      // hide the modal again
      editModal.classList.add('hidden');
    }
  });
}

// obtain the product post's order button
var orderButtons = document.querySelectorAll('.orderButton');

// add event listener to all order buttons
if(orderButtons != null) {
  orderButtons.forEach(function(elem) {
    elem.addEventListener("click", function () {
      // if order button is clicked
      var classList = elem.parentElement.parentElement.classList;
      // obtain the product id from product post class list
      var productId;
      classList.forEach(function(elem2) {
        if(elem2.includes('product-id')) {
          productId = elem2;
        }
      })
      var tokens = productId.split(/[ -]+/);
      productId = tokens[2];
      // launch the order page for that specific product clicked on with id as URL arg
      var url = 'order.php?productId='+productId;
      window.open(url,"_self");
    })
  });
}

// the price display for all product posts
var fraudPrice = document.querySelectorAll('.fraud-price');

// format the price output
if(fraudPrice != null) {
  fraudPrice.forEach(function(elem) {
    var tokens = elem.textContent.split(" ");
    var price = parseFloat(tokens[1].substr(1));
    // Format is 2 decimal places, and comma-separated large values: e.g.: Price: $5,000.00
    elem.textContent = "Price: $" + price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2});
  });
}

// post.php

// obtain all the possible warehouses present on page
var warehouseContainers = document.querySelectorAll('.warehouse-container');
// running sum of warehouse entries
var totalProductQuantity = document.querySelector('.totalProductQuantity');
var sum = 0;

if(warehouseContainers != null) {

  // for all warehouses
  warehouseContainers.forEach(function(elem) {
    // obtain the checkbox element
    var checkbox = elem.querySelector('.product-warehouse-checkbox');
    // div containing quantity input
    var warehouseQuantitySection = elem.querySelector(".warehouse-quantity-faux, .warehouse-quantity");
    // quantity input itself
    var quantityInput = elem.querySelector('.warehouse-product-quantityInput');

    // add event listener to all checkboxes
    checkbox.addEventListener("change", function() {
      // if the checkbox checked, show the quantity input for that warehouse
      if(this.checked) {
        warehouseQuantitySection.classList.remove('hidden');
        warehouseQuantitySection.classList.remove('warehouse-quantity-faux');
        warehouseQuantitySection.classList.add('warehouse-quantity');
        quantityInput.value = 0;
      } else {
        //checkbox unchecked, hide quantity input, reduce sum, reset running total
        warehouseQuantitySection.classList.add('hidden');
        warehouseQuantitySection.classList.add('warehouse-quantity-faux');
        warehouseQuantitySection.classList.remove('warehouse-quantity');
        sum -= Number(quantityInput.value);
        totalProductQuantity.innerHTML = sum;
      }
    });

    // quantity input change event listener
    quantityInput.addEventListener("change", function() {
      // update the running sum and display in UI
      sum += Number(this.value);
      totalProductQuantity.innerHTML = sum;
    });
  });
}

//order

// check if we are in the order page
var orderPost = document.querySelector('.fraud-post-order');
// if we are obtain the product Id for product being ordered from class List of post
if(orderPost != null) {
  var classList = orderPost.classList;
  var productId;
  var productIdInput = document.querySelector('.product_id_input');
  classList.forEach(function(elem) {
    if(elem.includes('product-id')) {
      productId = elem;
    }
  })
  var tokens = productId.split(/[ -]+/);
  productId = tokens[2];
  // set the hidden product id fields value in order for data be passed in to review page
  productIdInput.value = productId;
}

// limit the quantity that can be ordered by the products total quantity
var quantityInput = document.querySelector('.orderQuantity');

if(quantityInput != null) {
  // obtain max possible quantity
  var tokens = document.querySelector('.fraud-quantity').textContent;
  var max = Number(tokens.split(' ')[1]);
  // add event listenter to quantity field
  quantityInput.addEventListener("change", function () {
    // automatically reset user input value if they go beyond bounds of [1-max]
    if(this.value < 1) {
      this.value = 1;
    }
    if(this.value > max) {
      this.value = max;
    }
  });
}
